<?php

namespace App\Http\Controllers;

use App\Models\Run;
use App\Models\TestSuite;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RunController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // TODO: implement pagination
        $runs = Run::with([
                'user:id,name',
                'testSuite:id,name',
            ])
            ->latest()
            ->get();
        return Inertia::render('Runs/Index', [
            'runs' => $runs,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        if ($testSuite->team_id != $request->user()->current_team_id) {
            abort(403);
        }
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
            'testSuite',
            'testSuite.tests' => function ($query) {
                $query->orderBy('sort_order');
            },
        ]);
        return Inertia::render('Runs/Show', [
            'run' => $run,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Run  $run
     * @return \Illuminate\Http\Response
     */
    public function edit(Run $run)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Run  $run
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Run $run)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Run  $run
     * @return \Illuminate\Http\Response
     */
    public function destroy(Run $run)
    {
        //
    }
}
