<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Seminar;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch active seminars with their topics, departments, cohort, trainers and registrations
        $seminars = Seminar::with(['topics', 'departments', 'cohort', 'trainers', 'registrations'])
            ->where('status', 'active')
            ->orderBy('start_date', 'asc')
            ->get();

        return view('welcome', compact('seminars'));
    }
}
