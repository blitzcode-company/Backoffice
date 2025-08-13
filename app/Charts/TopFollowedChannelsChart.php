<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use ArielMejiaDev\LarapexCharts\HorizontalBar;

class TopFollowedChannelsChart
{
    protected $chart;
    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }
    public function build(array $channelNames, array $subscribersCount, int $limit): HorizontalBar
    {
        return $this->chart->horizontalBarChart()
            ->setTitle('Top ' . $limit . ' Canales Más Seguidos')
            ->addData('Suscriptores', $subscribersCount)
            ->setXAxis($channelNames)
            ->setColors(['#FF9800'])
            ->setGrid();
    }
}