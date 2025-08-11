<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class SiteController extends Controller
{
    public function index(): Response
    {
        $dailyInfo = Cache::get('dashboard_report');
        return Inertia::render('Dashboard', ['dailyInfo' => $dailyInfo ?? null]);
    }
}
