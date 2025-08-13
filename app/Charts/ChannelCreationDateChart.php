<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use ArielMejiaDev\LarapexCharts\LineChart;

class ChannelCreationDateChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }
    public function build(array $months, array $data): LineChart
    {
        return $this->chart->lineChart()
            ->setTitle('Canales Creados por Mes')
            ->setSubtitle('Tendencia de creación de canales a lo largo del tiempo')
            ->addData('Canales Creados', $data)
            ->setXAxis($months)
            ->setColors(['#A78BFA']);
    }
}
