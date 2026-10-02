param(
    [int]$Port = 8082,
    [int]$DatabasePort = 3307,
    [string]$PhpPath = '',
    [string]$MySqlHome = 'C:\xampp\mysql',
    [switch]$DatabaseOnly
)

$ErrorActionPreference = 'Stop'
$project = Split-Path -Parent $PSScriptRoot
Set-Location -LiteralPath $project

if (-not $PhpPath) {
    $phpCommand = Get-Command php -ErrorAction SilentlyContinue
    if ($phpCommand) { $PhpPath = $phpCommand.Source }
    else {
        $phpCandidates = @(Get-ChildItem "$env:LOCALAPPDATA\Programs\PHP" -Filter php.exe -Recurse -ErrorAction SilentlyContinue)
        if ($phpCandidates.Count -gt 0) { $PhpPath = $phpCandidates[-1].FullName }
        elseif (Test-Path 'C:\xampp\php\php.exe') { $PhpPath = 'C:\xampp\php\php.exe' }
    }
}
if (-not $PhpPath -or -not (Test-Path -LiteralPath $PhpPath)) { throw 'PHP was not found. Install PHP 8.2+ or pass -PhpPath.' }

# Enable installed DLLs for this process only; no global php.ini changes.
$phpOptions = @()
foreach ($extension in @('intl', 'mbstring', 'mysqli')) {
    & $PhpPath -r "exit(extension_loaded('$extension') ? 0 : 1);"
    if ($LASTEXITCODE -ne 0) { $phpOptions += @('-d', "extension=$extension") }
}
& $PhpPath @phpOptions -r "exit(PHP_VERSION_ID >= 80200 && extension_loaded('intl') && extension_loaded('mbstring') && extension_loaded('mysqli') ? 0 : 1);"
if ($LASTEXITCODE -ne 0) { throw 'PHP 8.2+, intl, mbstring and mysqli are required.' }

if (-not (Test-Path 'vendor\autoload.php')) {
    throw 'Dependencies are missing. Run composer install in this folder, then start again.'
}
$server = Join-Path $MySqlHome 'bin\mysqld.exe'
$installer = Join-Path $MySqlHome 'bin\mysql_install_db.exe'
if (-not (Test-Path -LiteralPath $installer)) { throw 'XAMPP MariaDB was not found. Pass -MySqlHome or follow the manual database setup in README.md.' }

$local = Join-Path $project '.local'
$data = Join-Path $local 'mysql'
New-Item -ItemType Directory -Force -Path $local | Out-Null
if (-not (Test-Path (Join-Path $data 'mysql'))) {
    & $installer "--datadir=$data" "--port=$DatabasePort"
    if ($LASTEXITCODE -ne 0) { throw 'Database initialization failed. Check the terminal output.' }
}

function Test-LocalPort([int]$PortNumber) {
    $client = New-Object System.Net.Sockets.TcpClient
    try { $client.Connect('127.0.0.1', $PortNumber); return $true }
    catch { return $false }
    finally { $client.Dispose() }
}

if (-not (Test-LocalPort $DatabasePort)) {
    $dbArguments = @(
        '--no-defaults',
        ('--basedir="{0}"' -f $MySqlHome),
        ('--datadir="{0}"' -f $data),
        "--port=$DatabasePort", '--bind-address=127.0.0.1',
        ('--log-error="{0}"' -f (Join-Path $local 'mysql-error.log')),
        ('--pid-file="{0}"' -f (Join-Path $local 'mysql.pid')),
        '--character-set-server=utf8mb4', '--collation-server=utf8mb4_unicode_ci'
    )
    Start-Process -FilePath $server -ArgumentList $dbArguments -WindowStyle Hidden -PassThru | Out-Null
    for ($attempt = 0; $attempt -lt 30 -and -not (Test-LocalPort $DatabasePort); $attempt++) { Start-Sleep -Milliseconds 500 }
}
if (-not (Test-LocalPort $DatabasePort)) { throw 'Database did not start. See .local\mysql-error.log.' }

# Check database ownership before importing anything, including on an occupied port.
& $PhpPath @phpOptions 'tools/Initialize-Database.php' $DatabasePort $data
if ($LASTEXITCODE -ne 0) { throw 'Database preparation failed; existing data was not reset.' }

if (-not (Test-Path '.env')) {
    $environment = @"
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:$Port/'
database.default.hostname = 127.0.0.1
database.default.database = rally_supply_ta2
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = $DatabasePort
database.default.charset = utf8mb4
database.default.DBCollat = utf8mb4_unicode_ci
"@
    [IO.File]::WriteAllText((Join-Path $project '.env'), $environment, (New-Object Text.UTF8Encoding($false)))
}
$settings = Get-Content -LiteralPath '.env'
$configuredPort = $settings | Where-Object { $_ -match '^\s*database\.default\.port\s*=' } | Select-Object -First 1
$configuredDatabase = $settings | Where-Object { $_ -match '^\s*database\.default\.database\s*=' } | Select-Object -First 1
if ($configuredPort -notmatch "=\s*$DatabasePort\s*$" -or
    $configuredDatabase -notmatch '=\s*rally_supply_ta2\s*$') {
    throw 'The existing .env points to another database or port. Use the manual setup instructions for that configuration.'
}

Write-Host "TA2 database is available at 127.0.0.1:$DatabasePort."
if ($DatabaseOnly) { return }
if (Test-LocalPort $Port) { throw "Port $Port is already in use. If TA2 is running, open http://localhost:$Port/." }
Write-Host "Open http://localhost:$Port/ in your browser. Keep this terminal open."
Write-Host 'Press Ctrl+C to stop the website. Use Stop-Database.cmd to stop the database.'
# PHP -S avoids a child process that might lose the temporary mysqli setting.
& $PhpPath @phpOptions -S "127.0.0.1:$Port" -t public vendor/codeigniter4/framework/system/rewrite.php
