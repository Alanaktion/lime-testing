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
        $this->authorizeResource(Test::class, 'test');
    }

    public function create(TestSuite $testSuite)
    {
        $this->authorize('view', $testSuite);
        return Inertia::render('TestSuites/Tests/Show', [
            'suite' => $testSuite,
        ]);
    }

    public function store(Request $request, TestSuite $testSuite)
    {
        $this->authorize('view', $testSuite);
        $request->validate([
            'name' => 'required|string|max:255',
            'priority' => 'required|string|in:optional,normal,high',
            'description' => 'nullable|string|max:4096',
            'steps' => 'nullable|string|max:4096',
        ]);
        $maxSortOrder = $testSuite->tests()->max('sort_order');
        $test = $testSuite->tests()->create($request->only([
            'name',
            'priority',
            'description',
            'steps',
        ]) + [
            'sort_order' => $maxSortOrder + 1,
        ]);
        return redirect()->route('tests.show', $test)
            ->with('flash.banner', 'Test created successfully.');
    }

    public function show(Test $test)
    {
        $test->load('testSuite');
        return Inertia::render('TestSuites/Tests/Show', [
            'test' => $test,
            'suite' => $test->testSuite,
        ]);
    }

    public function update(Request $request, Test $test)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'priority' => 'required|string|in:optional,normal,high',
            'description' => 'nullable|string|max:4096',
            'steps' => 'nullable|string|max:4096',
        ]);
        $test->fill($request->only([
            'name',
            'priority',
            'description',
            'steps',
        ]));
        $test->save();
        return redirect()->route('tests.show', $test)
            ->with('flash.banner', 'Test updated successfully.');
    }

    public function destroy(Test $test)
    {
        $testSuite = $test->testSuite;
        $test->delete();
        return redirect()->route('test-suite.show', $testSuite);
    }
}
