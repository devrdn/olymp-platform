<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ContestController extends Controller
{
    public function create(): View
    {
        return view('contest::create');
    }
}
