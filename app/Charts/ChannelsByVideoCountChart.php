<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use ArielMejiaDev\LarapexCharts\BarChart;

class ChannelsByVideoCountChart
{
    protected $chart;
    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }
    public function build(array $channelNames, array $videoCounts): BarChart
    {
        return $this->chart->barChart()
            ->setTitle('Canales por Cantidad de Videos Subidos')
            ->setSubtitle('Número de videos publicados por cada canal activo')
            ->addData('Videos', $videoCounts)
            ->setXAxis($channelNames)
            ->setColors(['#4BC0C0'])
            ->setGrid(true);
    }
}