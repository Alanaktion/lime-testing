<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified']);
    }

    public function index()
    {
        /** @var \App\Models\Team */
        $team = Auth::user()->currentTeam;
        $testSuites = $team->testSuites()
            ->with('latestRun')
            ->orderBy('name')
            ->get();
        return Inertia::render('Dashboard', [
            'suites' => $testSuites,
        ]);
    }
}
