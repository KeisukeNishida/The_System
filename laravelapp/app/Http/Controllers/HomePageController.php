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
}
