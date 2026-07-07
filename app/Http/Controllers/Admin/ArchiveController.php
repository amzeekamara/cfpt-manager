<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seminar;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    public function index()
    {
        $pastSeminars = Seminar::where('status', 'completed')
            ->orderBy('end_date', 'desc')
            ->get();

        $archivedEditions = \App\Models\Edition::where('status', 'completed')
            ->withCount('seminars')
            ->orderBy('end_date', 'desc')
            ->get();

        return view('admin.archives', compact('pastSeminars', 'archivedEditions'));
    }
}
