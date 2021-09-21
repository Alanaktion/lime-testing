<?php

namespace Tests\Feature\Run;

use App\Models\Run;
use App\Models\RunTest;
use App\Models\Test;
use App\Models\TestSuite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RunTestTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_run_tests_can_be_updated()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $testSuite = TestSuite::factory()
            ->for($user->currentTeam)
            ->hasTests(1)
            ->create();

        /** @var Run */
        $run = Run::forceCreate([
            'test_suite_id' => $testSuite->id,
            'user_id' => $user->id,
        ]);

        /** @var Test */
        $test = $testSuite->tests->first();

        $response = $this->patchJson(route('runs.run-test.update', [$run->id, $test->id]), [
            'run_id' => $run->id,
            'result' => RunTest::RESULT_PASS,
        ]);
        $response->assertJson([
            'run_id' => $run->id,
            'test_id' => $test->id,
            'result' => RunTest::RESULT_PASS,
        ]);
    }

    public function test_run_tests_can_have_comments()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $testSuite = TestSuite::factory()
            ->for($user->currentTeam)
            ->hasTests(1)
            ->create();

        /** @var Run */
        $run = Run::forceCreate([
            'test_suite_id' => $testSuite->id,
            'user_id' => $user->id,
        ]);

        /** @var Test */
        $test = $testSuite->tests->first();

        $comment = $this->faker->sentence;

        $response = $this->patchJson(route('runs.run-test.update', [$run->id, $test->id]), [
            'run_id' => $run->id,
            'comment' => $comment,
        ]);
        $response->assertJson([
            'run_id' => $run->id,
            'test_id' => $test->id,
            'comment' => $comment,
        ]);
    }

    public function test_run_tests_cannot_be_updated_on_completed_run()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $testSuite = TestSuite::factory()
            ->for($user->currentTeam)
            ->hasTests(1)
            ->create();

        /** @var Run */
        $run = Run::forceCreate([
            'test_suite_id' => $testSuite->id,
            'user_id' => $user->id,
            'completed_at' => now(),
        ]);

        /** @var Test */
        $test = $testSuite->tests->first();

        $response = $this->patchJson(route('runs.run-test.update', [$run->id, $test->id]), [
            'run_id' => $run->id,
            'result' => RunTest::RESULT_PASS,
        ]);
        $response->assertStatus(400);
    }

    // test run tests cannot be updated by another user
    public function test_run_tests_cannot_be_updated_by_another_user()
    {
        $otherUser = User::factory()->withPersonalTeam()->create();
        /** @var User */
        $actingUser = User::factory()->create();
        $this->actingAs($actingUser);

        $testSuite = TestSuite::factory()
            ->for($otherUser->currentTeam)
            ->hasTests(1)
            ->create();

        $run = Run::forceCreate([
            'test_suite_id' => $testSuite->id,
            'user_id' => $otherUser->id,
        ]);

        /** @var Test */
        $test = $testSuite->tests->first();

        $response = $this->patchJson(route('runs.run-test.update', [$run->id, $test->id]), [
            'run_id' => $run->id,
            'result' => RunTest::RESULT_PASS,
        ]);
        $response->assertStatus(403);
    }
}
