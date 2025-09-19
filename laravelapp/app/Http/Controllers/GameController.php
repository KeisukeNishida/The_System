<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameController extends Controller
{
    // 追加: /homepage02 → HomePage/homepage02.blade.php
    public function game01()
    {
        return view('game.game01'); // resources/views/game/game01.blade.php
    }

    public function game02()
    {
        return view('game.game02'); // resources/views/game/game02.blade.php
    }

    public function game03()
    {
        return view('game.game03'); // resources/views/game/game02.blade.php
    }

    public function game04()
    {
        return view('game.game04'); // resources/views/game/game04.blade.php
    }

    public function game05()
    {
        return view('game.game05'); // resources/views/game/game05.blade.php
    }

    public function game06()
    {
        return view('game.game06'); // resources/views/game/game06.blade.php
    }

}
