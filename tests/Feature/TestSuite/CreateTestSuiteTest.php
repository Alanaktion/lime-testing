<?php

namespace Tests\Feature\TestSuite;

use App\Models\TestSuite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateTestSuiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_test_suites_can_be_created()
    {
        $this->actingAs(User::factory()->withPersonalTeam()->create());

        $testSuiteData = TestSuite::factory()->make()->toArray();
        $response = $this->post(route('test-suites.store'), $testSuiteData);

        $response->assertRedirect();
        $path = parse_url($response->headers->get('Location'), PHP_URL_PATH) ?? '';
        $this->assertMatchesRegularExpression('@^/test-suites/[\d]+$@', $path);
    }

    public function test_test_suites_cannot_be_created_without_create_permissions()
    {
        /** @var User */
        $actingUser = User::factory()->create();
        $teamOwner = User::factory()->withPersonalTeam()->create();
        $teamOwner->currentTeam->users()->attach($actingUser, ['role' => 'tester']);

        $actingUser->switchTeam($teamOwner->currentTeam);
        $this->actingAs($actingUser);

        $testSuiteData = TestSuite::factory()->make()->toArray();
        $response = $this->post(route('test-suites.store'), $testSuiteData);

        $response->assertStatus(403);
    }
}
