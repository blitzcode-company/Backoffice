<?php
namespace App\Http\Controllers\Chart;

use App\Charts\ActiveInactiveUsersChart;
use App\Charts\BlockedActiveUsersChart;
use App\Charts\CommentingLikingUsersChart;
use App\Charts\MonthlyRegistrationsChart;
use App\Charts\MonthlyUsersChart;
use App\Charts\UserChannelChart;
use App\Http\Controllers\Controller;
use App\Models\Blitzvideo\Comentario;
use App\Models\Blitzvideo\MeGusta;
use App\Models\Blitzvideo\Puntua;
use App\Models\Blitzvideo\User;
use Carbon\Carbon;

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
        $activeUsersCount   = User::whereNull('deleted_at')->where('name', '!=', 'Invitado')->count();
        $inactiveUsersCount = User::onlyTrashed()->count();

        $chart = $chart->build($activeUsersCount, $inactiveUsersCount);

        return view('chart.chart-active-users', compact('chart'));
    }

    public function UsuarioConCanal(UserChannelChart $chart)
    {
        $totalUsers             = User::where('name', '!=', 'Invitado')->count();
        $contentCreators        = User::where('name', '!=', 'Invitado')->whereHas('canales')->count();
        $usersWithoutCanal      = User::where('name', '!=', 'Invitado')->doesntHave('canales')->count();
        $premiumCreators        = User::where('name', '!=', 'Invitado')->where('premium', true)->whereHas('canales')->count();
        $nonPremiumCreators     = User::where('name', '!=', 'Invitado')->where('premium', false)->whereHas('canales')->count();
        $premiumWithoutCanal    = User::where('name', '!=', 'Invitado')->where('premium', true)->doesntHave('canales')->count();
        $nonPremiumWithoutCanal = User::where('name', '!=', 'Invitado')->where('premium', false)->doesntHave('canales')->count();

        $chart = $chart->build($totalUsers, $contentCreators, $usersWithoutCanal, $premiumCreators, $nonPremiumCreators, $premiumWithoutCanal, $nonPremiumWithoutCanal);

        return view('chart.chart-user-channel', compact('chart'));
    }

    public function UsuariosInteraccionGeneral(CommentingLikingUsersChart $chart)
    {
        $allActiveUserIds = User::where('name', '!=', 'Invitado')
            ->whereNull('deleted_at')
            ->pluck('id');
        $totalActiveUsers  = $allActiveUserIds->count();
        $commentingUserIds = Comentario::whereIn('usuario_id', $allActiveUserIds)
            ->pluck('usuario_id')
            ->unique();
        $likingUserIds = MeGusta::whereIn('usuario_id', $allActiveUserIds)
            ->pluck('usuario_id')
            ->unique();
        $ratingUserIds = Puntua::whereIn('user_id', $allActiveUserIds)
            ->pluck('user_id')
            ->unique();
        $commentingLikingInteractionIds = $commentingUserIds->merge($likingUserIds)->unique();
        $superActiveUsersIds            = $commentingLikingInteractionIds->intersect($ratingUserIds);
        $superActiveUsersCount          = $superActiveUsersIds->count();
        $commentingLikingOnlyUsersIds   = $commentingLikingInteractionIds->diff($ratingUserIds);
        $commentingLikingOnlyUsersCount = $commentingLikingOnlyUsersIds->count();
        $ratingOnlyUsersIds             = $ratingUserIds->diff($commentingLikingInteractionIds);
        $ratingOnlyUsersCount           = $ratingOnlyUsersIds->count();
        $passiveUsersCount              = $totalActiveUsers - ($superActiveUsersCount + $commentingLikingOnlyUsersCount + $ratingOnlyUsersCount);
        $passiveUsersCount              = max(0, $passiveUsersCount);
        $chart                          = $chart->build(
            $superActiveUsersCount,
            $commentingLikingOnlyUsersCount,
            $ratingOnlyUsersCount,
            $passiveUsersCount
        );
        return view('chart.chart-commenting-liking-users', compact('chart'));
    }

    public function UsuariosRegistradosPorMes(MonthlyRegistrationsChart $chart)
    {
        $months    = [];
        $data      = [];
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        for ($i = 0; $i < 12; $i++) {
            $month    = $startDate->copy()->addMonths($i);
            $months[] = $month->isoFormat('MMM YY');
            $count    = User::where('name', '!=', 'Invitado')
                ->whereNull('deleted_at')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
            $data[] = $count;
        }

        $chart = $chart->build($months, $data);

        return view('chart.chart-monthly-registrations', compact('chart'));
    }
    public function UsuariosBloqueadosActivos(BlockedActiveUsersChart $chart)
    {
        $blockedUsersCount = User::where('bloqueado', true)
            ->whereNull('deleted_at')
            ->count();
        $activeUsersCount = User::where('bloqueado', false)
            ->where('name', '!=', 'Invitado')
            ->whereNull('deleted_at')
            ->count();
        $chart = $chart->build($blockedUsersCount, $activeUsersCount);
        return view('chart.chart-blocked-active-users', compact('chart'));
    }
}
