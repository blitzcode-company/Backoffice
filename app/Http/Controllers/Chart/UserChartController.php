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
        $contentCreators = User::where('name', '!=', 'Invitado')->whereHas('canales')->count();
        $usersWithoutCanal = User::where('name', '!=', 'Invitado')->doesntHave('canales')->count();
        $premiumCreators = User::where('name', '!=', 'Invitado')->where('premium', true)->whereHas('canales')->count();
        $nonPremiumCreators = User::where('name', '!=', 'Invitado')->where('premium', false)->whereHas('canales')->count();
        $premiumWithoutCanal = User::where('name', '!=', 'Invitado')->where('premium', true)->doesntHave('canales')->count();
        $nonPremiumWithoutCanal = User::where('name', '!=', 'Invitado')->where('premium', false)->doesntHave('canales')->count();

        $chart = $chart->build($totalUsers, $contentCreators, $usersWithoutCanal, $premiumCreators, $nonPremiumCreators, $premiumWithoutCanal, $nonPremiumWithoutCanal);

        return view('chart.chart-user-channel', compact('chart'));
    }
}
