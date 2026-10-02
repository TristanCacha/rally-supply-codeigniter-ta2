<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{
    public function index(): string
    {
        $model = new UserModel();

        $requestedSearch = $this->request->getGet('q');
        $search = is_string($requestedSearch) ? mb_substr(trim($requestedSearch), 0, 100) : '';

        $sortOptions = [
            'id_asc' => ['Original order', 'id', 'ASC'],
            'name_asc' => ['Name A–Z', 'full_name', 'ASC'],
            'name_desc' => ['Name Z–A', 'full_name', 'DESC'],
            'newest' => ['Newest added', 'created_at', 'DESC'],
            'oldest' => ['Oldest added', 'created_at', 'ASC'],
        ];
        $requestedSort = $this->request->getGet('sort');
        $sort = is_string($requestedSort) && isset($sortOptions[$requestedSort])
            ? $requestedSort
            : 'id_asc';

        $totalUsers = $model->countAllResults();
        if ($search !== '') {
            $model->groupStart()
                ->like('full_name', $search)
                ->orLike('username', $search)
                ->groupEnd();
        }

        $users = $model->orderBy($sortOptions[$sort][1], $sortOptions[$sort][2])->findAll();

        return view('accounts/users', [
            'title' => 'User accounts',
            'users' => $users,
            'totalUsers' => $totalUsers,
            'search' => $search,
            'sort' => $sort,
            'sortOptions' => $sortOptions,
        ]);
    }
}
