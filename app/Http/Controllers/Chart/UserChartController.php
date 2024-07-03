<?php

namespace App\Http\Controllers\Chart;

use App\Charts\ActiveInactiveUsersChart;
use App\Charts\MonthlyUsersChart;
use App\Charts\UserChannelChart;
use App\Http\Controllers\Controller;
use App\Models\Blitzvideo\User;

class UserChartController extends Controller
{
    public function UsuariosPremium(MonthlyUsersChart $chart)
    {

        $activePremiumUsersCount = User::where('premium', true)
            ->where('name', '!=', 'Invitado')
            ->whereNull('deleted_at')
            ->count();

        $activeNonPremiumUsersCount = User::where('premium', false)
            ->where('name', '!=', 'Invitado')
            ->whereNull('deleted_at')
            ->count();

        $totalActiveUsersCount = User::where('name', '!=', 'Invitado')
            ->whereNull('deleted_at')
            ->count();

        $chart = $chart->build($totalActiveUsersCount, $activePremiumUsersCount, $activeNonPremiumUsersCount);

        return view('chart.chart-premium-users', compact('chart'));
    }

    public function UsuarioActivoInactivo(ActiveInactiveUsersChart $chart)
    {
        $activeUsersCount = User::whereNull('deleted_at')->where('name', '!=', 'Invitado')->count();
        $inactiveUsersCount = User::onlyTrashed()->count();

        $chart = $chart->build($activeUsersCount, $inactiveUsersCount);

        return view('chart.chart-active-users', compact('chart'));
    }

    public function UsuarioConCanal(UserChannelChart $chart)
    {
        $totalUsers = User::where('name', '!=', 'Invitado')->count();
        $contentCreators = User::where('name', '!=', 'Invitado')->whereHas('canal')->count();
        $usersWithoutCanal = User::where('name', '!=', 'Invitado')->doesntHave('canal')->count();
        $premiumCreators = User::where('name', '!=', 'Invitado')->where('premium', true)->whereHas('canal')->count();
        $nonPremiumCreators = User::where('name', '!=', 'Invitado')->where('premium', false)->whereHas('canal')->count();
        $premiumWithoutCanal = User::where('name', '!=', 'Invitado')->where('premium', true)->doesntHave('canal')->count();
        $nonPremiumWithoutCanal = User::where('name', '!=', 'Invitado')->where('premium', false)->doesntHave('canal')->count();

        $chart = $chart->build($totalUsers, $contentCreators, $usersWithoutCanal, $premiumCreators, $nonPremiumCreators, $premiumWithoutCanal, $nonPremiumWithoutCanal);

        return view('chart.chart-user-channel', compact('chart'));
    }
}
