<?php

namespace App\Filters;

use App\Models\StaffCredentialModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class StaffOnly implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $staffId = session()->get('staff_id');
        if (!is_numeric($staffId) || (int) $staffId < 1) {
            $path = $request->getUri()->getRoutePath();
            $next = in_array($path, ['pos/checkout', 'inventory', 'customers', 'users', 'sales', 'orders'], true) ? $path : 'inventory';
            return redirect()->to(site_url('login') . '?next=' . rawurlencode($next));
        }

        $credential = (new StaffCredentialModel())->find((int) $staffId);
        if ($credential === null || (int) $credential['is_active'] !== 1) {
            session()->remove(['staff_id', 'staff_name']);
            return redirect()->to(site_url('login'));
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $response->setHeader('Cache-Control', 'no-store, private');
        return null;
    }
}
