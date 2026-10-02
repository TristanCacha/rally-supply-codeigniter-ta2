<?php

namespace App\Controllers;

use App\Models\CustomerModel;

class Customers extends BaseController
{
    public function index(): string
    {
        $model = new CustomerModel();

        $requestedSearch = $this->request->getGet('q');
        $search = is_string($requestedSearch) ? mb_substr(trim($requestedSearch), 0, 100) : '';

        // Only known database columns can be used for sorting.
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

        $totalCustomers = $model->countAllResults();
        if ($search !== '') {
            $model->like('full_name', $search);
        }

        // The Model still owns retrieval; Query Builder adds optional filtering.
        $customers = $model->orderBy($sortOptions[$sort][1], $sortOptions[$sort][2])->findAll();

        return view('accounts/customers', [
            'title' => 'Customer accounts',
            'customers' => $customers,
            'totalCustomers' => $totalCustomers,
            'search' => $search,
            'sort' => $sort,
            'sortOptions' => $sortOptions,
        ]);
    }
}
