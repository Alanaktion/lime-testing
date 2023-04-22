<?php

namespace App\Http\Controllers;

use App\Models\TestSuite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TestSuiteController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified']);
        $this->authorizeResource(TestSuite::class, 'test_suite');
    }

    public function index()
    {
        /** @var \App\Models\Team */
        $team = Auth::user()->currentTeam;
        $testSuites = $team->testSuites()
            ->with('latestRun')
            ->withCount('tests')
            ->orderBy('name')
            ->get();
        $archivedCount = $team->testSuites()
            ->onlyTrashed()
            ->count();

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
        $data = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:4096',
        ]);

        /** @var \App\Models\Team */
        $team = $request->user()->currentTeam;
        $suite = $team->testSuites()->create($data);

        return redirect()->route('test-suites.show', [$suite]);
    }

    public function update(Request $request, TestSuite $testSuite)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:4096',
        ]);

        $testSuite->fill($data);
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

        $archivedCount = $testSuite->tests()
            ->onlyTrashed()
            ->count();

        return Inertia::render('TestSuites/Show', [
            'suite' => $testSuite,
            'tests' => $tests,
            'archivedCount' => $archivedCount,
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
        $this->authorize('restore', $testSuite);
        $testSuite->restore();

        return redirect()->route('test-suites.index')
            ->with('flash.banner', 'Test suite restored.');
    }
}
