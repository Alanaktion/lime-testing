<?php

namespace App\Http\Controllers;

use App\Models\Run;
use App\Models\RunTest;
use App\Models\Test;
use Illuminate\Http\Request;

class RunTestController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified']);
        $this->authorizeResource(RunTest::class, 'test');
    }

    /**
     * Update a run test by run and test.
     */
    public function update(Run $run, Test $test, Request $request)
    {
        if ($test->test_suite_id != $run->test_suite_id) {
            abort(404);
        }
        if ($run->completed_at) {
            abort(400, 'Cannot modify a completed test run');
        }
        $data = $request->validate([
            'result' => 'sometimes|in:pass,fail,skip',
            'comment' => 'sometimes|nullable|string',
        ]);

        RunTest::unguard();
        try {
            $runTest = RunTest::updateOrCreate([
                'run_id' => $run->id,
                'test_id' => $test->id,
            ], $data);
        } finally {
            RunTest::reguard();
        }

        return $runTest;
    }
}
