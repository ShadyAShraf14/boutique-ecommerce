<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // ممكن برضه مؤقتًا ترجع نفس backend home
        return view('Backend.pages.home');
    }
}
