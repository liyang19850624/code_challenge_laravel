<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        return view('home.home');
    }
    
    public function aboutUs()
    {
        return view('home.about-us');
    }
}
