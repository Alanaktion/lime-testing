<?php

namespace Tests\Feature\Run;

use App\Models\Run;
use App\Models\TestSuite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class RunTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_runs_can_be_started(): void
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $testSuite = TestSuite::factory()
            ->for($user->currentTeam)
            ->hasTests(3)
            ->create();

        $response = $this->post(route('runs.store'), [
            'test_suite_id' => $testSuite->id,
        ]);

        $response->assertRedirect();

        $path = parse_url($response->headers->get('Location'), PHP_URL_PATH) ?? '';
        $this->assertMatchesRegularExpression('@^/runs/[\d]+$@', $path);
    }

    public function test_runs_can_be_started_without_write_permissions(): void
    {
        /** @var User */
        $actingUser = User::factory()->create();
        $teamOwner = User::factory()->withPersonalTeam()->create();
        $teamOwner->currentTeam->users()->attach($actingUser, ['role' => 'tester']);

        $actingUser->switchTeam($teamOwner->currentTeam);
        $this->actingAs($actingUser);

        $testSuite = TestSuite::factory()
            ->for($teamOwner->currentTeam)
            ->hasTests(3)
            ->create();

        $response = $this->post(route('runs.store'), [
            'test_suite_id' => $testSuite->id,
        ]);

        $response->assertRedirect();

        $path = parse_url($response->headers->get('Location'), PHP_URL_PATH) ?? '';
        $this->assertMatchesRegularExpression('@^/runs/[\d]+$@', $path);
    }

    public function test_runs_cannot_be_started_for_a_suite_owned_by_another_team(): void
    {
        $actingUser = User::factory()->withPersonalTeam()->create();
        $this->actingAs($actingUser);

        $otherUser = User::factory()->withPersonalTeam()->create();
        $testSuite = TestSuite::factory()
            ->for($otherUser->currentTeam)
            ->hasTests(3)
            ->create();

        $response = $this->post(route('runs.store'), [
            'test_suite_id' => $testSuite->id,
        ]);

        $response->assertStatus(403);
    }

    public function test_runs_can_be_finished(): void
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $testSuite = TestSuite::factory()
            ->for($user->currentTeam)
            ->hasTests(3)
            ->create();

        $run = Run::forceCreate([
            'test_suite_id' => $testSuite->id,
            'user_id' => $user->id,
        ]);

        $response = $this->patch(route('runs.update', $run));
        $response->assertRedirect(route('runs.index'));
    }

    public function test_runs_can_be_canceled(): void
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $testSuite = TestSuite::factory()
            ->for($user->currentTeam)
            ->hasTests(3)
            ->create();

        $run = Run::forceCreate([
            'test_suite_id' => $testSuite->id,
            'user_id' => $user->id,
        ]);

        $response = $this->delete(route('runs.destroy', $run));
        $response->assertRedirect(route('dashboard'));
    }

    public function test_runs_cannot_be_changed_by_others(): void
    {
        $otherUser = User::factory()->withPersonalTeam()->create();
        /** @var User */
        $actingUser = User::factory()->create();
        $this->actingAs($actingUser);

        $testSuite = TestSuite::factory()
            ->for($otherUser->currentTeam)
            ->hasTests(3)
            ->create();

        $run = Run::forceCreate([
            'test_suite_id' => $testSuite->id,
            'user_id' => $otherUser->id,
        ]);

        $response = $this->patch(route('runs.update', $run));
        $response->assertStatus(403);

        $response = $this->delete(route('runs.destroy', $run));
        $response->assertStatus(403);
    }

    public function test_runs_cannot_be_accessed_by_other_teams(): void
    {
        $actingUser = User::factory()->withPersonalTeam()->create();
        $this->actingAs($actingUser);

        $otherUser = User::factory()->withPersonalTeam()->create();
        $testSuite = TestSuite::factory()
            ->for($otherUser->currentTeam)
            ->hasTests(3)
            ->create();

        $run = Run::forceCreate([
            'test_suite_id' => $testSuite->id,
            'user_id' => $otherUser->id,
        ]);

        $response = $this->get(route('runs.show', $run));
        $response->assertStatus(403);
    }
}
