<?php
namespace App\Http\Controllers\Chart;
use App\Charts\TopFollowedChannelsChart;
use App\Charts\ChannelsByVideoCountChart;
use App\Charts\ChannelStreamStatusChart;
use App\Charts\ChannelCreationDateChart; 
use App\Http\Controllers\Controller;
use App\Models\Blitzvideo\Canal;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CanalChartController extends Controller
{
    public function TopCanalesSeguidos(TopFollowedChannelsChart $chart)
    {
        $limit       = 10;
        $topChannels = Canal::withCount(['suscriptores' => function ($query) {
            $query->whereNull('users.deleted_at');
        }])
            ->whereNull('deleted_at')
            ->orderByDesc('suscriptores_count')
            ->limit($limit)
            ->get();
        $channelNames     = [];
        $subscribersCount = [];
        foreach ($topChannels as $channel) {
            $channelNames[]     = Str::limit($channel->nombre, 30);
            $subscribersCount[] = $channel->suscriptores_count;
        }
        if (empty($channelNames)) {
            $channelNames     = ['Sin Datos'];
            $subscribersCount = [0];
        }
        $chart = $chart->build($channelNames, $subscribersCount, $limit);
        return view('chart.chart-top-followed-channels', compact('chart'));
    }

    public function CanalesPorCantidadVideos(ChannelsByVideoCountChart $chart)
    {
        $limit = 10;
        $topChannelsByVideos = Canal::withCount(['videos' => function ($query) {
            $query->whereNull('deleted_at')->where('bloqueado', false);
        }])
        ->whereNull('deleted_at')
        ->orderByDesc('videos_count')
        ->limit($limit)
        ->get();
        $channelNames = [];
        $videoCounts = [];
        foreach ($topChannelsByVideos as $channel) {
            $channelNames[] = Str::limit($channel->nombre, 30);
            $videoCounts[] = $channel->videos_count;
        }
        if (empty($channelNames)) {
            $channelNames = ['Sin Datos'];
            $videoCounts = [0];
        }
        $chart = $chart->build($channelNames, $videoCounts);
        return view('chart.chart-channels-by-video-count', compact('chart'));
    }

    public function CanalesStreamsActivosInactivos(ChannelStreamStatusChart $chart)
    {
        $activeStreamsCount = Canal::whereHas('streams', function ($query) {
                                        $query->where('activo', true)
                                              ->whereNull('deleted_at');
                                    })
                                    ->whereNull('deleted_at')
                                    ->count();

        $totalActiveCanales = Canal::whereNull('deleted_at')->count();
        $inactiveStreamsCount = $totalActiveCanales - $activeStreamsCount;

        $chart = $chart->build($activeStreamsCount, $inactiveStreamsCount);

        return view('chart.chart-channel-stream-status', compact('chart'));
    }

    public function CanalesPorAntiguedad(ChannelCreationDateChart $chart)
    {
        $months = [];
        $data = [];
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();

        for ($i = 0; $i < 12; $i++) {
            $month = $startDate->copy()->addMonths($i);
            $months[] = $month->isoFormat('MMM YY');

            $count = Canal::whereNull('deleted_at')
                         ->whereMonth('created_at', $month->month)
                         ->whereYear('created_at', $month->year)
                         ->count();
            $data[] = $count;
        }

        $chart = $chart->build($months, $data);

        return view('chart.chart-channel-creation-date', compact('chart'));
    }
}
