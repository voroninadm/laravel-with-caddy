<?php

namespace App\Http\Controllers\PersonalTesting;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class PersonalTestingController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('PersonalTesting/Index');
    }

}
