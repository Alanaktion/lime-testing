<?php

namespace App\Http\Controllers;

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
        /** @var \App\Models\User */
        $user = Auth::user();

        $activeRuns = $user->runs()
            ->with([
                'testSuite' => function ($query) {
                    $query
                        ->select('id', 'name')
                        ->withCount('tests');
                },
            ])
            ->withCount('runTests')
            ->whereNull('completed_at')
            ->get();

        /** @var \App\Models\Team */
        $team = $user->currentTeam;
        $testSuites = $team->testSuites()
            ->with('latestRun')
            ->orderBy('name')
            ->get();

        return Inertia::render('Dashboard', [
            'activeRuns' => $activeRuns,
            'suites' => $testSuites,
        ]);
    }
}
