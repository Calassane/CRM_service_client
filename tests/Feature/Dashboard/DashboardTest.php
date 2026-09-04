<?php

namespace Tests\Feature\Dashboard;

use App\Models\CustomerCall;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_agent_can_view_the_analytics_dashboard(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-09-04 12:00:00'));

        $agent = User::factory()->create(['name' => 'Awa Koné']);
        CustomerCall::factory()->create([
            'agent_id' => $agent,
            'started_at' => now()->subDay(),
        ]);

        $this->actingAs($agent)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('selectedPeriod', 30)
            ->assertViewHas('metrics', fn (array $metrics): bool => $metrics['total_calls'] === 1)
            ->assertSee('Tableau de bord')
            ->assertSee('Awa Koné')
            ->assertSee('daily-calls-chart', false)
            ->assertSee('dashboard-chart-data', false);
    }

    public function test_agent_can_select_the_dashboard_period(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-09-04 12:00:00'));

        $agent = User::factory()->create();
        CustomerCall::factory()->create([
            'agent_id' => $agent,
            'started_at' => now()->subDays(2),
        ]);
        CustomerCall::factory()->create([
            'agent_id' => $agent,
            'started_at' => now()->subDays(10),
        ]);

        $this->actingAs($agent)
            ->get(route('dashboard', ['period' => 7]))
            ->assertOk()
            ->assertViewHas('selectedPeriod', 7)
            ->assertViewHas('metrics', fn (array $metrics): bool => $metrics['total_calls'] === 1);
    }

    public function test_dashboard_rejects_an_unsupported_period(): void
    {
        $agent = User::factory()->create();

        $this->actingAs($agent)
            ->get(route('dashboard', ['period' => 365]))
            ->assertRedirect()
            ->assertSessionHasErrors('period');
    }
}
