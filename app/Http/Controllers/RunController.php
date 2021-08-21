<?php

namespace App\Http\Controllers;

use App\Models\Run;
use App\Models\RunTest;
use App\Models\TestSuite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RunController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified']);
        $this->authorizeResource(Run::class, 'run');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $runs = Run::with([
                'user:id,name',
                'testSuite' => function ($query) {
                    $query->withTrashed()->select('id', 'name');
                },
            ])
            ->whereHas('testSuite', function ($query) {
                $query->where('team_id', Auth::user()->current_team_id);
            })
            ->latest()
            ->paginate();
        return Inertia::render('Runs/Index', [
            'runs' => $runs,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'test_suite_id' => 'required|integer|exists:test_suites,id',
        ]);
        $testSuite = TestSuite::find($request->input('test_suite_id'));
        $this->authorize('view', $testSuite);
        $run = Run::forceCreate([
            'test_suite_id' => $testSuite->id,
            'user_id' => $request->user()->id,
        ]);
        return redirect()->route('runs.show', $run);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Run  $run
     * @return \Illuminate\Http\Response
     */
    public function show(Run $run)
    {
        $run->load([
            'user:id,name',
            'testSuite' => function ($query) {
                $query->withTrashed();
            },
            'testSuite.tests' => function ($query) {
                $query->orderBy('sort_order');
            },
            'runTests',
        ]);
        return Inertia::render('Runs/Show', [
            'run' => $run,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * Currently always closes the run, but could do more in the future.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Run  $run
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Run $run)
    {
        if ($run->completed_at) {
            return redirect()->route('runs.show', $run);
        }

        // Set the result based on the test data
        $run->load('runTests:id,run_id,result');
        $run->result = RunTest::RESULT_PASS;
        foreach ($run->runTests as $runTest) {
            if ($runTest->result == RunTest::RESULT_FAIL && $runTest->required) {
                $run->result = RunTest::RESULT_FAIL;
                break;
            }
        }

        // Close the run
        $run->completed_at = now();
        $run->save();

        return redirect()->route('runs.index')
            ->with('flash.banner', 'Test run completed.');
    }

    public function destroy(Run $run)
    {
        if ($run->completed_at) {
            return redirect()->route('runs.show', $run);
        }

        $run->delete();
        return redirect()->route('dashboard')
            ->with('flash.banner', 'Test run canceled.');
    }
}
