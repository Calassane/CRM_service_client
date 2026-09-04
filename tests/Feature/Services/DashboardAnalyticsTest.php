<?php

namespace Tests\Feature\Services;

use App\Enums\CallReason;
use App\Enums\CallStatus;
use App\Models\CustomerCall;
use App\Models\User;
use App\Services\Dashboard\DashboardAnalytics;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_builds_dashboard_metrics_charts_and_agent_ranking(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-09-04 12:00:00'));

        $firstAgent = User::factory()->create(['name' => 'Awa Koné']);
        $secondAgent = User::factory()->create(['name' => 'Moussa Diallo']);

        $this->createCall($firstAgent, '2026-09-04 09:00:00', 120, CallReason::Reservation, CallStatus::Resolved);
        $this->createCall($firstAgent, '2026-08-28 10:00:00', 180, CallReason::Payment, CallStatus::Pending);
        $this->createCall($secondAgent, '2026-09-03 14:00:00', 300, CallReason::Reservation, CallStatus::Resolved);
        $this->createCall($secondAgent, '2026-07-01 14:00:00', 900, CallReason::Complaint, CallStatus::Escalated);

        $analytics = app(DashboardAnalytics::class)->forPeriod(30);

        $this->assertSame(30, $analytics['selectedPeriod']);
        $this->assertSame([
            'total_calls' => 3,
            'weekly_calls' => 2,
            'average_duration' => '3 min 20 s',
            'resolution_rate' => 66.7,
        ], $analytics['metrics']);

        $dailyIndex = array_search('2026-09-04', $analytics['charts']['daily']['dates'], true);
        $this->assertNotFalse($dailyIndex);
        $this->assertSame(1, $analytics['charts']['daily']['values'][$dailyIndex]);
        $this->assertSame(3, array_sum($analytics['charts']['weekly']['values']));
        $this->assertSame([2, 0, 0, 1, 0], $analytics['charts']['reasons']['values']);
        $this->assertSame([2, 1, 0], $analytics['charts']['statuses']['values']);

        $this->assertSame([
            ['name' => 'Awa Koné', 'calls' => 2, 'average_duration' => '2 min 30 s'],
            ['name' => 'Moussa Diallo', 'calls' => 1, 'average_duration' => '5 min 00 s'],
        ], $analytics['agentRanking']);
    }

    private function createCall(
        User $agent,
        string $startedAt,
        int $duration,
        CallReason $reason,
        CallStatus $status,
    ): CustomerCall {
        return CustomerCall::factory()->create([
            'agent_id' => $agent,
            'started_at' => $startedAt,
            'duration_seconds' => $duration,
            'reason' => $reason,
            'status' => $status,
        ]);
    }
}
