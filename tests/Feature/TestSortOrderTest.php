<?php

namespace Tests\Feature;

use App\Models\Test;
use App\Models\TestSuite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestSortOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_sort_order_can_be_updated_with_json()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $testSuite = TestSuite::factory()
            ->for($user->currentTeam)
            ->create();

        $test = Test::factory()->create(['test_suite_id' => $testSuite->id]);

        $data = [
            'sort_order' => 1.5,
        ];
        $response = $this->patchJson(route('tests.update', $test), $data);

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $test->id,
            'sort_order' => $data['sort_order'],
        ]);
    }

    public function test_sort_order_cannot_be_changed_without_update_permission()
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

        $data = [
            'sort_order' => 1.5,
        ];
        $response = $this->patchJson(route('tests.update', $test), $data);

        $response->assertStatus(403);
    }

    public function test_sort_order_cannot_be_changed_in_a_suite_owned_by_another_team()
    {
        $actingUser = User::factory()->withPersonalTeam()->create();
        $this->actingAs($actingUser);

        $otherUser = User::factory()->withPersonalTeam()->create();
        $testSuite = TestSuite::factory()
            ->for($otherUser->currentTeam)
            ->create();

        $test = Test::factory()->create(['test_suite_id' => $testSuite->id]);

        $data = [
            'sort_order' => 1.5,
        ];
        $response = $this->patchJson(route('tests.update', $test), $data);

        $response->assertStatus(403);
    }
}
