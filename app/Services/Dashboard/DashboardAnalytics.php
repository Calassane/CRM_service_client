<?php

namespace App\Services\Dashboard;

use App\Enums\CallReason;
use App\Enums\CallStatus;
use App\Models\CustomerCall;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;

class DashboardAnalytics
{
    /**
     * @return array<string, mixed>
     */
    public function forPeriod(int $days): array
    {
        $end = CarbonImmutable::today()->endOfDay();
        $start = CarbonImmutable::today()->subDays($days - 1)->startOfDay();
        $query = CustomerCall::query()->whereBetween('started_at', [$start, $end]);

        $totalCalls = (clone $query)->count();
        $averageDuration = (int) round((float) (clone $query)->avg('duration_seconds'));
        $resolvedCalls = (clone $query)->where('status', CallStatus::Resolved->value)->count();
        $daily = $this->dailyVolume($start, $end);

        return [
            'selectedPeriod' => $days,
            'periodLabel' => sprintf('Du %s au %s', $start->format('d/m/Y'), $end->format('d/m/Y')),
            'metrics' => [
                'total_calls' => $totalCalls,
                'weekly_calls' => CustomerCall::query()
                    ->whereBetween('started_at', [CarbonImmutable::now()->startOfWeek(), $end])
                    ->count(),
                'average_duration' => $this->durationLabel($averageDuration),
                'resolution_rate' => $totalCalls > 0
                    ? round(($resolvedCalls / $totalCalls) * 100, 1)
                    : 0,
            ],
            'charts' => [
                'daily' => $daily,
                'weekly' => $this->weeklyVolume($daily),
                'reasons' => $this->reasonDistribution($query),
                'statuses' => $this->statusDistribution($query),
            ],
            'agentRanking' => $this->agentRanking($start, $end),
        ];
    }

    /**
     * @return array{labels: list<string>, values: list<int>, dates: list<string>}
     */
    private function dailyVolume(CarbonImmutable $start, CarbonImmutable $end): array
    {
        $counts = CustomerCall::query()
            ->whereBetween('started_at', [$start, $end])
            ->selectRaw('DATE(started_at) as call_date, COUNT(*) as total')
            ->groupBy('call_date')
            ->pluck('total', 'call_date');

        $labels = [];
        $values = [];
        $dates = [];

        for ($date = $start->startOfDay(); $date->lte($end); $date = $date->addDay()) {
            $dateKey = $date->format('Y-m-d');
            $dates[] = $dateKey;
            $labels[] = $date->format('d/m');
            $values[] = (int) $counts->get($dateKey, 0);
        }

        return compact('labels', 'values', 'dates');
    }

    /**
     * @param  array{labels: list<string>, values: list<int>, dates: list<string>}  $daily
     * @return array{labels: list<string>, values: list<int>}
     */
    private function weeklyVolume(array $daily): array
    {
        $weeks = [];

        foreach ($daily['dates'] as $index => $date) {
            $weekStart = CarbonImmutable::parse($date)->startOfWeek()->format('Y-m-d');
            $weeks[$weekStart] = ($weeks[$weekStart] ?? 0) + $daily['values'][$index];
        }

        return [
            'labels' => array_map(
                fn (string $date): string => 'Sem. '.$this->shortDate($date),
                array_keys($weeks),
            ),
            'values' => array_values($weeks),
        ];
    }

    /**
     * @param  Builder<CustomerCall>  $query
     * @return array{labels: list<string>, values: list<int>}
     */
    private function reasonDistribution(Builder $query): array
    {
        $counts = (clone $query)
            ->selectRaw('reason, COUNT(*) as total')
            ->groupBy('reason')
            ->pluck('total', 'reason');

        return [
            'labels' => array_map(fn (CallReason $reason): string => $reason->label(), CallReason::cases()),
            'values' => array_map(fn (CallReason $reason): int => (int) $counts->get($reason->value, 0), CallReason::cases()),
        ];
    }

    /**
     * @param  Builder<CustomerCall>  $query
     * @return array{labels: list<string>, values: list<int>}
     */
    private function statusDistribution(Builder $query): array
    {
        $counts = (clone $query)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'labels' => array_map(fn (CallStatus $status): string => $status->label(), CallStatus::cases()),
            'values' => array_map(fn (CallStatus $status): int => (int) $counts->get($status->value, 0), CallStatus::cases()),
        ];
    }

    /**
     * @return list<array{name: string, calls: int, average_duration: string}>
     */
    private function agentRanking(CarbonImmutable $start, CarbonImmutable $end): array
    {
        $period = fn (Builder $query): Builder => $query->whereBetween('started_at', [$start, $end]);

        return User::query()
            ->select(['id', 'name'])
            ->whereHas('handledCalls', $period)
            ->withCount(['handledCalls as calls_count' => $period])
            ->withAvg(['handledCalls as average_duration_seconds' => $period], 'duration_seconds')
            ->orderByDesc('calls_count')
            ->orderBy('name')
            ->get()
            ->map(fn (User $agent): array => [
                'name' => $agent->name,
                'calls' => (int) $agent->calls_count,
                'average_duration' => $this->durationLabel((int) round((float) $agent->average_duration_seconds)),
            ])
            ->all();
    }

    private function durationLabel(int $seconds): string
    {
        $minutes = intdiv($seconds, 60);
        $remainingSeconds = $seconds % 60;

        return $minutes > 0
            ? sprintf('%d min %02d s', $minutes, $remainingSeconds)
            : sprintf('%d s', $remainingSeconds);
    }

    private function shortDate(string $date): string
    {
        return CarbonImmutable::parse($date)->format('d/m');
    }
}
