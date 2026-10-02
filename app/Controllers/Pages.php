<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function index(): string
    {
        return view('pages/home', ['title' => 'Your next game starts here']);
    }

    public function about(): string
    {
        return view('pages/about', ['title' => 'Built for the next point']);
    }
}
