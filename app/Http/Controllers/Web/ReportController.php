<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class ReportController extends Controller
{
    public function index()
    {
        // Fetch the authenticated user's reports
        $reports = auth()->user()->reports;

        return view('reports.index', compact('reports'));
    }
}
