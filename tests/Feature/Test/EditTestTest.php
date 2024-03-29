<?php

namespace Tests\Feature\Test;

use App\Models\Test;
use App\Models\TestSuite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditTestTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_tests_can_be_edited(): void
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $testSuite = TestSuite::factory()
            ->for($user->currentTeam)
            ->create();

        $test = Test::factory()->create(['test_suite_id' => $testSuite->id]);

        $response = $this->patch(route('tests.update', $test), [
            'name' => $this->faker->sentence,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('flash.banner');
    }

    public function test_tests_cannot_be_edited_without_create_permissions(): void
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

        $test = Test::factory()->create(['test_suite_id' => $testSuite->id]);

        $response = $this->patch(route('tests.update', $test), [
            'name' => $this->faker->sentence,
        ]);

        $response->assertStatus(403);
    }

    public function test_tests_cannot_be_edited_in_a_suite_owned_by_another_team(): void
    {
        $actingUser = User::factory()->withPersonalTeam()->create();
        $this->actingAs($actingUser);

        $otherUser = User::factory()->withPersonalTeam()->create();
        $testSuite = TestSuite::factory()
            ->for($otherUser->currentTeam)
            ->create();

        $test = Test::factory()->create(['test_suite_id' => $testSuite->id]);

        $response = $this->patch(route('tests.update', $test), [
            'name' => $this->faker->sentence,
        ]);

        $response->assertStatus(403);
    }
}
