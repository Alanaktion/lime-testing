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

    public function archived(TestSuite $testSuite)
    {
        $tests = $testSuite->tests()
            ->onlyTrashed()
            ->get();

        return Inertia::render('TestSuites/Tests/Archived', [
            'suite' => $testSuite,
            'tests' => $tests,
        ]);
    }

    public function store(Request $request, TestSuite $testSuite)
    {
        $this->authorize('view', $testSuite);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'priority' => 'required|string|in:optional,normal,high',
            'description' => 'nullable|string|max:4096',
            'steps' => 'nullable|string|max:4096',
        ]);
        $maxSortOrder = $testSuite->tests()->max('sort_order');
        $testSuite->tests()->create($data + [
            'sort_order' => $maxSortOrder + 1,
        ]);

        return redirect()->route('test-suites.show', $testSuite)
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
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'priority' => 'sometimes|string|in:optional,normal,high',
            'description' => 'sometimes|nullable|string|max:4096',
            'steps' => 'sometimes|nullable|string|max:4096',
            'sort_order' => 'sometimes|numeric',
        ]);
        $test->fill($data);
        $test->save();
        if ($request->expectsJson()) {
            return $test;
        }

        return redirect()->route('test-suites.show', $test->test_suite_id)
            ->with('flash.banner', 'Test updated successfully.');
    }

    public function destroy(Test $test)
    {
        $testSuite = $test->testSuite;
        $test->delete();

        return redirect()->route('test-suites.show', $testSuite)
            ->with('flash.banner', 'Test archived.');
    }

    public function restore(Test $test)
    {
        $this->authorize('restore', $test);
        $test->restore();

        return redirect()->route('test-suites.show', $test->test_suite_id)
            ->with('flash.banner', 'Test restored.');
    }
}
