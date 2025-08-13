<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use ArielMejiaDev\LarapexCharts\DonutChart;

class VideoAdAssociationChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(int $videosWithAdsCount, int $videosWithoutAdsCount): DonutChart
    {
        return $this->chart->donutChart()
            ->setTitle('Videos con Publicidad Asociada')
            ->setSubtitle('Proporción de videos con y sin campañas de publicidad')
            ->addData([$videosWithAdsCount, $videosWithoutAdsCount])
            ->setLabels(['Con Publicidad', 'Sin Publicidad'])
            ->setColors(['#008FFB', '#FF4500']);
    }
}