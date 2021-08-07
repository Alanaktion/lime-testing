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
        $testSuites = TestSuite::withCount('tests')
            ->orderBy('name')
            ->get();
        $archivedCount = TestSuite::onlyTrashed()->count();
        return Inertia::render('TestSuites/Index', [
            'testSuites' => $testSuites,
            'archivedCount' => $archivedCount,
        ]);
    }

    public function archived()
    {
        $testSuites = TestSuite::withCount('tests')
            ->onlyTrashed()
            ->orderBy('name')
            ->get();
        return Inertia::render('TestSuites/Archived', [
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

        return redirect()->route('test-suites.show', [$suite]);
    }

    public function update(Request $request, TestSuite $testSuite)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:4096',
        ]);

        $testSuite->fill([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        $testSuite->save();

        return redirect()->route('test-suites.show', [$testSuite])
            ->with('flash.banner', 'Test suite updated successfully.');
    }

    public function show(TestSuite $testSuite)
    {
        $tests = $testSuite->tests()
            ->with(['user:id,name'])
            ->orderBy('sort_order')
            ->get();
        return Inertia::render('TestSuites/Show', [
            'suite' => $testSuite,
            'tests' => $tests,
        ]);
    }

    public function destroy(TestSuite $testSuite)
    {
        $testSuite->delete();
        return redirect()->route('test-suites.index')
            ->with('flash.banner', 'Test suite archived.');
    }

    public function restore(TestSuite $testSuite)
    {
        $testSuite->restore();
        return redirect()->route('test-suites.index')
            ->with('flash.banner', 'Test suite restored.');
    }
}
