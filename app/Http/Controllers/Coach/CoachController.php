<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;

class CoachController extends Controller
{
    public function index() {
        return view('coach.dashboard');
    }
}