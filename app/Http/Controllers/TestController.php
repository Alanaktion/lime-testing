<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\TestSuite;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TestController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified']);
    }

    public function store(Request $request, TestSuite $testSuite)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:4096',
        ]);
        $test = $testSuite->tests()->create($request->only([
            'name',
            'description',
        ]));
        return redirect()->route('tests.show', $test);
    }

    public function show(Test $test)
    {
        $test->load('testSuite');
        return Inertia::render('TestSuites/Tests/Show', [
            'test' => $test,
            'testSuite' => $test->testSuite,
        ]);
    }

    public function update(Request $request, Test $test)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:4096',
        ]);
        $test->fill($request->only([
            'name',
            'description',
        ]));
        $test->save();
        return redirect()->route('tests.show', $test);
    }

    public function destroy(Test $test)
    {
        $testSuite = $test->testSuite;
        $test->delete();
        return redirect()->route('test-suite.show', $testSuite);
    }
}
