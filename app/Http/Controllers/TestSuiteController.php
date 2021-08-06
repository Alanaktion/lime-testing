<?php

namespace App\Http\Controllers;

use App\Models\TestSuite;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TestSuiteController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified']);
    }

    public function index()
    {
        $testSuites = TestSuite::withCount('tests')->get();
        return Inertia::render('TestSuites/Index', [
            'testSuites' => $testSuites,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:4096',
        ]);

        $team = $request->user()->currentTeam;
        $suite = $team->testSuites()->create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect('test-suites/' . $suite->id);
    }

    public function show(TestSuite $suite)
    {
        return Inertia::render('TestSuites/Show', [
            'suite' => $suite,
        ]);
    }
}
