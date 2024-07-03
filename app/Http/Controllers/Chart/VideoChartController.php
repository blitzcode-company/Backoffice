<?php

namespace App\Http\Controllers\Chart;

use App\Charts\MostVisitedVideosChart;
use App\Charts\VideoTagChart;
use App\Http\Controllers\Controller;
use App\Models\Blitzvideo\Etiqueta;
use App\Models\Blitzvideo\Video;
use Carbon\Carbon;

class VideoChartController extends Controller
{
    public function videosPorEtiqueta(VideoTagChart $chart)
    {
        $etiquetas = Etiqueta::all();

        $chart = $chart->build($etiquetas);

        return view('chart.chart-videos-por-etiqueta', compact('chart'));
    }

    public function VideosMasVistadosElUltimoMes(MostVisitedVideosChart $chart)
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $videos = Video::select('videos.*')
        ->selectRaw('COUNT(visitas.id) as visitas_count')
        ->join('visitas', 'videos.id', '=', 'visitas.video_id')
        ->whereBetween('visitas.created_at', [$startDate, $endDate])
        ->groupBy('videos.id')
        ->orderByDesc('visitas_count')
        ->take(10)
        ->get();
    

        $labels = $videos->pluck('titulo')->toArray();
        $data = $videos->pluck('visitas_count')->toArray();

        $chart = $chart->build($labels, $data);

        return view('chart.chart-videos-mas-visitados', compact('chart'));
    }
}
