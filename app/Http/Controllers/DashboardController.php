<?php

namespace App\Http\Controllers;

use App\Http\Requests\DashboardRequest;
use App\Services\Dashboard\DashboardAnalytics;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(
        DashboardRequest $request,
        DashboardAnalytics $dashboardAnalytics,
    ): View {
        $period = (int) ($request->validated('period') ?? 30);

        return view('dashboard', [
            ...$dashboardAnalytics->forPeriod($period),
            'periodOptions' => [
                ['value' => 7, 'label' => '7 derniers jours'],
                ['value' => 30, 'label' => '30 derniers jours'],
                ['value' => 90, 'label' => '90 derniers jours'],
            ],
        ]);
    }
}
