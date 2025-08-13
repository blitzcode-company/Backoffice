<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use ArielMejiaDev\LarapexCharts\HorizontalBar;

class TopAdViewedVideosChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }
    public function build(array $videoTitles, array $adViewsCount, int $limit): HorizontalBar
    {
        return $this->chart->horizontalBarChart()
            ->setTitle('Top ' . $limit . ' Videos con Más Vistas de Publicidad')
            ->addData('Vistas de Publicidad', $adViewsCount)
            ->setXAxis($videoTitles)
            ->setColors(['#00E396']) // Un color verde para las barras
            ->setGrid();
    }
}