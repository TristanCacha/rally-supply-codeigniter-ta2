<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Pages::index');
$routes->get('about', 'Pages::about');
$routes->get('shop', 'Catalog::index');
$routes->get('login', 'Auth::form');
$routes->post('login', 'Auth::login');
$routes->post('logout', 'Auth::logout');
$routes->get('customers', 'Customers::index', ['filter' => 'staff']);
$routes->get('users', 'Users::index', ['filter' => 'staff']);
$routes->get('inventory', 'Inventory::index', ['filter' => 'staff']);
$routes->post('inventory/update', 'Inventory::update', ['filter' => 'staff']);
$routes->get('cart', 'Cart::index');
$routes->post('cart/add', 'Cart::add');
$routes->post('cart/update', 'Cart::update');
$routes->post('cart/remove', 'Cart::remove');
$routes->get('checkout', 'Orders::checkout');
$routes->post('checkout', 'Orders::store');
$routes->get('order/(:segment)', 'Orders::confirmation/$1');
$routes->get('orders', 'Orders::index', ['filter' => 'staff']);
$routes->post('orders/(:num)/status', 'Orders::updateStatus/$1', ['filter' => 'staff']);
$routes->get('pos/checkout', 'Sales::checkout', ['filter' => 'staff']);
$routes->post('pos/checkout', 'Sales::store', ['filter' => 'staff']);
$routes->get('sales', 'Sales::index', ['filter' => 'staff']);
$routes->get('sales/(:num)', 'Sales::receipt/$1', ['filter' => 'staff']);
