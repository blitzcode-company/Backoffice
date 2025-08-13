<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use ArielMejiaDev\LarapexCharts\HorizontalBar;
class TopCommentedVideosChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(array $videoTitles, array $commentsCount, int $limit): HorizontalBar
    {
        return $this->chart->horizontalBarChart()
            ->setTitle('Top ' . $limit . ' Videos con Más Comentarios')
            ->addData('Comentarios', $commentsCount)
            ->setXAxis($videoTitles)
            ->setColors(['#FF6384'])
            ->setGrid();
    }
}