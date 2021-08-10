<?php

namespace Tests\Feature;

use App\Models\TestSuite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditTestSuiteTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_test_suites_can_be_edited()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $testSuite = TestSuite::factory()
            ->for($user->currentTeam)
            ->create();

        $response = $this->patch(route('test-suites.update', $testSuite), [
            'name' => $this->faker->name,
        ]);

        $response->assertRedirect();
        $path = parse_url($response->headers->get('Location'), PHP_URL_PATH);
        $this->assertMatchesRegularExpression('@^/test-suites/[\d]+$@', $path);
    }

    public function test_test_suites_cannot_be_edited_without_edit_permissions()
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

        $response = $this->patch(route('test-suites.update', $testSuite), [
            'name' => $this->faker->name,
        ]);
        $response->assertStatus(403);
    }

    public function test_test_suites_cannot_be_edited_by_another_team()
    {
        $actingUser = User::factory()->withPersonalTeam()->create();
        $this->actingAs($actingUser);

        $otherUser = User::factory()->withPersonalTeam()->create();
        $testSuite = TestSuite::factory()
            ->for($otherUser->currentTeam)
            ->create();

        $response = $this->patch(route('test-suites.update', $testSuite), [
            'name' => $this->faker->name,
        ]);
        $response->assertStatus(403);
    }
}
