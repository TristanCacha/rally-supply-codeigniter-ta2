<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

/** Creates the first local staff login or resets a named staff password. */
class StaffPassword extends BaseCommand
{
    protected $group = 'Rally Supply';
    protected $name = 'staff:password';
    protected $description = 'Create the first staff login, or reset a named staff password.';
    protected $usage = 'staff:password [username]';

    public function run(array $params)
    {
        $db = Database::connect();
        $username = $params[0] ?? null;
        if ($username === null) {
            if ($db->table('staff_credentials')->countAllResults() > 0) {
                CLI::write('A staff login already exists. Use staff:password USERNAME to reset one.');
                return 0;
            }
            $user = $db->table('users')->orderBy('id', 'ASC')->get(1)->getRowArray();
        } else {
            $user = $db->table('users')->where('username', $username)->get()->getRowArray();
        }
        if ($user === null) {
            CLI::error('No matching user account exists.');
            return 1;
        }

        $password = bin2hex(random_bytes(12));
        $record = [
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $existing = $db->table('staff_credentials')->where('user_id', $user['id'])->countAllResults() > 0;
        if ($existing) {
            $db->table('staff_credentials')->where('user_id', $user['id'])->update($record);
        } else {
            $db->table('staff_credentials')->insert(['user_id' => $user['id']] + $record);
        }

        $folder = ROOTPATH . '.local';
        if (!is_dir($folder)) {
            mkdir($folder, 0700, true);
        }
        $firstUser = $db->table('users')->orderBy('id', 'ASC')->get(1)->getRowArray();
        $fileName = (int) $firstUser['id'] === (int) $user['id']
            ? 'staff-login.txt'
            : 'staff-login-' . preg_replace('/[^a-zA-Z0-9_-]/', '-', $user['username']) . '.txt';
        $file = $folder . DIRECTORY_SEPARATOR . $fileName;
        file_put_contents($file, "Rally Supply local staff login\nUsername: {$user['username']}\nPassword: $password\n");
        CLI::write("Staff login saved to $file. Keep this file private.");
        return 0;
    }
}
