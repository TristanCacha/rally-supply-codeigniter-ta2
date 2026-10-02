param([int]$DatabasePort = 3307, [string]$MySqlHome = 'C:\xampp\mysql')
$ErrorActionPreference = 'Stop'
$project = Split-Path -Parent $PSScriptRoot
$data = Join-Path $project '.local\mysql'
$client = Join-Path $MySqlHome 'bin\mysql.exe'
$admin = Join-Path $MySqlHome 'bin\mysqladmin.exe'
if (-not (Test-Path -LiteralPath $data)) { Write-Host 'This copy has no local database.'; exit 0 }
$actual = & $client --host=127.0.0.1 "--port=$DatabasePort" --user=root --batch --skip-column-names --execute='SELECT @@datadir'
if ($LASTEXITCODE -ne 0) { throw 'Could not contact the database. It may already be stopped.' }
$actualPath = [IO.Path]::GetFullPath(($actual -join '').Replace('/', '\')).TrimEnd('\')
if ($actualPath -ine [IO.Path]::GetFullPath($data).TrimEnd('\')) { throw 'This port belongs to another database; it was not stopped.' }
& $admin --host=127.0.0.1 "--port=$DatabasePort" --user=root shutdown
if ($LASTEXITCODE -ne 0) { throw 'Database shutdown failed.' }
Write-Host 'TA2 database stopped. Its saved records are preserved.'
