<?php

namespace Tests\Feature\Test;

use App\Models\Test;
use App\Models\TestSuite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateTestTest extends TestCase
{
    use RefreshDatabase;

    public function test_tests_can_be_created()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $testSuite = TestSuite::factory()
            ->for($user->currentTeam)
            ->create();

        $testData = Test::factory()->make()->toArray();
        $response = $this->post(route('test-suites.tests.store', $testSuite), $testData);

        $response->assertRedirect();
        $response->assertSessionHas('flash.banner');
    }

    public function test_tests_cannot_be_created_without_create_permissions()
    {
        /** @var User */
        $actingUser = User::factory()->create();
        $teamOwner = User::factory()->withPersonalTeam()->create();
        $teamOwner->currentTeam->users()->attach($actingUser, ['role' => 'tester']);

        $actingUser->switchTeam($teamOwner->currentTeam);
        $this->actingAs($actingUser);

        $testSuite = TestSuite::factory()
            ->for($teamOwner->currentTeam)
            ->create();

        $testData = Test::factory()->make()->toArray();
        $response = $this->post(route('test-suites.tests.store', $testSuite), $testData);

        $response->assertStatus(403);
    }

    public function test_tests_cannot_be_added_to_a_suite_owned_by_another_team()
    {
        $actingUser = User::factory()->withPersonalTeam()->create();
        $this->actingAs($actingUser);

        $otherUser = User::factory()->withPersonalTeam()->create();
        $testSuite = TestSuite::factory()
            ->for($otherUser->currentTeam)
            ->create();

        $testData = Test::factory()->make()->toArray();
        $response = $this->post(route('test-suites.tests.store', $testSuite), $testData);

        $response->assertStatus(403);
    }
}
