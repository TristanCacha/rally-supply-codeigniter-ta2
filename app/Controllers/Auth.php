<?php

namespace App\Controllers;

use App\Models\StaffCredentialModel;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function form(): string
    {
        $requestedNext = $this->request->getGet('next');
        $next = is_string($requestedNext) && in_array($requestedNext, ['pos/checkout', 'inventory', 'customers', 'users', 'sales', 'orders'], true)
            ? $requestedNext : 'inventory';
        return view('staff/login', ['title' => 'Staff sign in', 'error' => null, 'next' => $next]);
    }

    public function login()
    {
        $session = session();
        $requestedNext = $this->request->getPost('next');
        $next = is_string($requestedNext) && in_array($requestedNext, ['pos/checkout', 'inventory', 'customers', 'users', 'sales', 'orders'], true)
            ? $requestedNext : 'inventory';
        if ((int) $session->get('login_lock_until') > time()) {
            return view('staff/login', [
                'title' => 'Staff sign in',
                'error' => 'Too many attempts. Try again in five minutes.',
                'next' => $next,
            ]);
        }

        $submittedUsername = $this->request->getPost('username');
        $submittedPassword = $this->request->getPost('password');
        $username = is_string($submittedUsername) ? trim($submittedUsername) : '';
        $password = is_string($submittedPassword) ? $submittedPassword : '';
        $user = $username !== '' && mb_strlen($username) <= 50
            ? (new UserModel())->where('username', $username)->first()
            : null;
        $credentials = $user === null ? null : (new StaffCredentialModel())->find($user['id']);
        $valid = $credentials !== null
            && (int) $credentials['is_active'] === 1
            && password_verify($password, $credentials['password_hash']);

        if (!$valid) {
            $attempts = (int) $session->get('login_attempts') + 1;
            if ($attempts >= 5) {
                $session->set('login_lock_until', time() + 300);
                $session->remove('login_attempts');
            } else {
                $session->set('login_attempts', $attempts);
            }
            return view('staff/login', [
                'title' => 'Staff sign in',
                'error' => 'Username or password was not recognized.',
                'next' => $next,
            ]);
        }

        $session->regenerate(true);
        $session->remove(['login_attempts', 'login_lock_until']);
        $session->set(['staff_id' => (int) $user['id'], 'staff_name' => $user['full_name']]);
        return redirect()->to(site_url($next));
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('/'));
    }
}
