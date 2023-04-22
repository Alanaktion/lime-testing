<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class WebTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_page(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_dashboard_login_redirect(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_dashboard_with_auth(): void
    {
        $this->actingAs(User::factory()->withPersonalTeam()->create());
        $response = $this->get('/dashboard');
        $response->assertStatus(200);
    }
}
