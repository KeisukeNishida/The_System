<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomePageController extends Controller
{
    public function show()
    {
        // ここは **HomePage.home** （大文字小文字まで一致）
        return view('HomePage.home');
    }

    // 追加: /homepage02 → HomePage/homepage02.blade.php
    public function home02()
    {
        return view('HomePage.home02'); // resources/views/HomePage/homepage02.blade.php
    }

    public function home03()
    {
        return view('HomePage.home03'); // resources/views/HomePage/homepage03.blade.php
    }

    public function home04()
    {
        return view('HomePage.home04'); // resources/views/HomePage/homepage04.blade.php
    }

    public function home05()
    {
        return view('HomePage.home05'); // resources/views/HomePage/homepage05.blade.php
    }   
}
