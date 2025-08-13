<?php

namespace App\Http\Controllers\Chart;

use App\Http\Controllers\Controller;
use App\Models\Blitzvideo\Reporta;
use App\Models\Blitzvideo\ReportaComentario;
use App\Charts\VideoReportCategoryChart;
use App\Charts\CommentReportCategoryChart;
use App\Charts\NotificationStatusChart;
use App\Models\Blitzvideo\Notifica;

class OtherChartController extends Controller
{
    public function ReportesContenidoPorCategoria(
        VideoReportCategoryChart $videoChart,
        CommentReportCategoryChart $commentChart
    ) {
        $videoReportColumns = [
            'contenido_inapropiado'    => 'Contenido Inapropiado',
            'spam'                     => 'Spam',
            'contenido_enganoso'       => 'Contenido Engañoso',
            'violacion_derechos_autor' => 'Violación Derechos Autor',
            'incitacion_al_odio'       => 'Incitación al Odio',
            'violencia_grafica'        => 'Violencia Gráfica',
            'otros'                    => 'Otros',
        ];

        $videoReportRawCounts = Reporta::whereNull('deleted_at')
            ->selectRaw(implode(',', array_map(function($col) {
                return "SUM(CASE WHEN {$col} = 1 THEN 1 ELSE 0 END) as {$col}_count";
            }, array_keys($videoReportColumns))))
            ->first();

        $videoReportLabels = [];
        $videoReportData = [];

        if ($videoReportRawCounts) {
            foreach ($videoReportColumns as $column => $label) {
                $count = $videoReportRawCounts->{"{$column}_count"};
                if ($count > 0) {
                    $videoReportLabels[] = $label;
                    $videoReportData[] = $count;
                }
            }
        }

        if (empty($videoReportLabels)) {
            $videoReportLabels = ['Sin Datos'];
            $videoReportData = [0];
        }

        $videoReportsChart = $videoChart->build($videoReportData, $videoReportLabels);

        $commentReportColumns = [
            'lenguaje_ofensivo' => 'Lenguaje Ofensivo',
            'spam'              => 'Spam',
            'contenido_enganoso'=> 'Contenido Engañoso',
            'incitacion_al_odio'=> 'Incitación al Odio',
            'acoso'             => 'Acoso',
            'contenido_sexual'  => 'Contenido Sexual',
            'otros'             => 'Otros',
        ];

        $commentReportRawCounts = ReportaComentario::whereNull('deleted_at')
            ->selectRaw(implode(',', array_map(function($col) {
                return "SUM(CASE WHEN {$col} = 1 THEN 1 ELSE 0 END) as {$col}_count";
            }, array_keys($commentReportColumns))))
            ->first();

        $commentReportLabels = [];
        $commentReportData = [];

        if ($commentReportRawCounts) {
            foreach ($commentReportColumns as $column => $label) {
                $count = $commentReportRawCounts->{"{$column}_count"};
                if ($count > 0) {
                    $commentReportLabels[] = $label;
                    $commentReportData[] = $count;
                }
            }
        }

        if (empty($commentReportLabels)) {
            $commentReportLabels = ['Sin Datos'];
            $commentReportData = [0];
        }

        $commentReportsChart = $commentChart->build($commentReportData, $commentReportLabels);

        return view('chart.chart-content-reports', compact('videoReportsChart', 'commentReportsChart'));
    }

    public function UsoNotificaciones(NotificationStatusChart $chart)
    {
        $readCount = Notifica::where('leido', true)
                             ->count();
        $unreadCount = Notifica::where('leido', false)
                               ->count();
        if ($readCount === 0 && $unreadCount === 0) {
            $readCount = 0;
            $unreadCount = 0;
        }
        $chart = $chart->build($readCount, $unreadCount);
        return view('chart.chart-notification-status', compact('chart'));
    }
}
