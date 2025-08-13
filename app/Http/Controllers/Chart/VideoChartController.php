<?php
namespace App\Http\Controllers\Chart;

use App\Charts\MostVisitedVideosChart;
use App\Charts\TopCommentedVideosChart;
use App\Charts\VideoAccessChart;
use App\Charts\VideoRatingDistributionChart;
use App\Charts\VideoStatusChart;
use App\Charts\VideoTagChart;
use App\Charts\VideoAdAssociationChart;
use App\Charts\TopAdViewedVideosChart;

use App\Http\Controllers\Controller;
use App\Models\Blitzvideo\Comentario;
use App\Models\Blitzvideo\Etiqueta;
use App\Models\Blitzvideo\Puntua;
use App\Models\Blitzvideo\Video;
use App\Models\Blitzvideo\Visita;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
        $endDate   = Carbon::now()->endOfMonth();

        $videos = Video::select('videos.*')
            ->selectRaw('COUNT(visitas.id) as visitas_count')
            ->join('visitas', 'videos.id', '=', 'visitas.video_id')
            ->whereBetween('visitas.created_at', [$startDate, $endDate])
            ->groupBy('videos.id')
            ->orderByDesc('visitas_count')
            ->take(10)
            ->get();

        $labels = $videos->pluck('titulo')->toArray();
        $data   = $videos->pluck('visitas_count')->toArray();

        $chart = $chart->build($labels, $data);

        return view('chart.chart-videos-mas-visitados', compact('chart'));
    }
    public function VideosActivosBloqueados(VideoStatusChart $chart)
    {
        $activeVideosCount = Video::whereNull('deleted_at')
            ->where('bloqueado', false)
            ->count();

        $blockedVideosCount = Video::whereNull('deleted_at')
            ->where('bloqueado', true)
            ->count();
        $chart = $chart->build($activeVideosCount, $blockedVideosCount);
        return view('chart.chart-video-status', compact('chart'));
    }

    public function VideosPorNivelDeAcceso(VideoAccessChart $chart)
    {
        $publicVideosCount = Video::where('acceso', 'publico')
            ->whereNull('deleted_at')
            ->where('bloqueado', false)
            ->count();
        $privateVideosCount = Video::where('acceso', 'privado')
            ->whereNull('deleted_at')
            ->where('bloqueado', false)
            ->count();
        $unlistedVideosCount = Video::where('acceso', 'no listado')
            ->whereNull('deleted_at')
            ->where('bloqueado', false)
            ->count();
        $data   = [$publicVideosCount, $privateVideosCount, $unlistedVideosCount];
        $labels = ['Público', 'Privado', 'No Listado'];
        $chart  = $chart->build($data, $labels);
        return view('chart.chart-video-access', compact('chart'));
    }

    public function VideosConMasComentarios(TopCommentedVideosChart $chart)
    {
        $limit         = 10;
        $commentCounts = Comentario::select('video_id', DB::raw('count(*) as total_comentarios'))
            ->whereNull('deleted_at')
            ->where('bloqueado', false)
            ->groupBy('video_id')
            ->orderByDesc('total_comentarios')
            ->limit($limit)
            ->get();
        $videoIds = $commentCounts->pluck('video_id')->toArray();
        $videos   = Video::whereIn('id', $videoIds)
            ->whereNull('deleted_at')
            ->where('bloqueado', false)
            ->get()
            ->keyBy('id');
        $videoTitles   = [];
        $commentsCount = [];
        foreach ($commentCounts as $commentData) {
            $video = $videos->get($commentData->video_id);
            if ($video) {
                $videoTitles[]   = Str::limit($video->titulo, 30);
                $commentsCount[] = $commentData->total_comentarios;
            }
        }
        if (empty($videoTitles)) {
            $videoTitles   = ['Sin Datos'];
            $commentsCount = [0];
        }
        $chart = $chart->build($videoTitles, $commentsCount, $limit);
        return view('chart.chart-top-commented-videos', compact('chart'));
    }

    public function DistribucionPuntuacionesVideos(VideoRatingDistributionChart $chart)
    {
        $videoAverageRatings = Puntua::select('video_id', DB::raw('ROUND(AVG(valora)) as promedio_emojis'))
            ->whereHas('video', function ($query) {
                $query->whereNull('deleted_at')->where('bloqueado', false);
            })
            ->whereNull('deleted_at')
            ->groupBy('video_id')
            ->get();
        $counts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        foreach ($videoAverageRatings as $rating) {
            $roundedRating = (int) $rating->promedio_emojis;
            if (isset($counts[$roundedRating])) {
                $counts[$roundedRating]++;
            }
        }
        $data   = array_values($counts);
        $labels = ['1', '2', '3', '4', '5'];

        if (array_sum($data) === 0) {
            $data = [0, 0, 0, 0, 0];
        }
        $chart = $chart->build($data, $labels);
        return view('chart.chart-video-rating-distribution', compact('chart'));
    }

 public function PublicidadVideos(VideoAdAssociationChart $adChart, TopAdViewedVideosChart $topAdChart)
    {
        // 1. Datos para el gráfico de pastel (Videos con/sin publicidad)
        $totalVideos = Video::whereNull('deleted_at')->where('bloqueado', false)->count();

        // Contar videos que tienen al menos una publicidad asociada
        $videosWithAdsCount = Video::has('publicidad') // Esto sigue siendo válido para saber si tiene publicidad
                                    ->whereNull('deleted_at')
                                    ->where('bloqueado', false)
                                    ->count();

        $videosWithoutAdsCount = $totalVideos - $videosWithAdsCount;

        $videoAdChart = $adChart->build($videosWithAdsCount, $videosWithoutAdsCount);

        // 2. Datos para el gráfico de barras (Top N videos con más vistas de publicidad)
        $limit = 10;

        // Obtener los videos que tienen publicidad asociada, y que no estén eliminados/bloqueados
        $videosConPublicidadIds = Video::has('publicidad')
                                        ->whereNull('deleted_at')
                                        ->where('bloqueado', false)
                                        ->pluck('id'); // Obtener solo los IDs de estos videos

        // Si no hay videos con publicidad, salimos temprano para evitar consultas vacías
        if ($videosConPublicidadIds->isEmpty()) {
            $videoTitles = ['Sin Datos'];
            $adViewsCount = [0];
            $topAdViewsChart = $topAdChart->build($videoTitles, $adViewsCount, $limit);
            return view('chart.chart-video-ads', compact('videoAdChart', 'topAdViewsChart'));
        }

        // Contar las visitas para esos videos con publicidad
        $topAdViews = Visita::select('video_id', DB::raw('count(*) as total_vistas_publicidad'))
                            ->whereIn('video_id', $videosConPublicidadIds) // Solo visitas de videos con publicidad
                            ->groupBy('video_id')
                            ->orderByDesc('total_vistas_publicidad')
                            ->limit($limit)
                            ->get();

        $videoTitles = [];
        $adViewsCount = [];

        // Ahora, obtener los títulos de los videos y emparejarlos con las vistas
        $videosData = Video::whereIn('id', $topAdViews->pluck('video_id'))
                           ->get()
                           ->keyBy('id');

        foreach ($topAdViews as $adData) {
            $video = $videosData->get($adData->video_id);
            if ($video) {
                $videoTitles[] = Str::limit($video->titulo, 30);
                $adViewsCount[] = $adData->total_vistas_publicidad;
            }
        }

        // Si después de todo el procesamiento no hay datos, para evitar errores en la gráfica
        if (empty($videoTitles)) {
            $videoTitles = ['Sin Datos'];
            $adViewsCount = [0];
        }

        $topAdViewsChart = $topAdChart->build($videoTitles, $adViewsCount, $limit);

        return view('chart.chart-video-ads', compact('videoAdChart', 'topAdViewsChart'));
    }
}
