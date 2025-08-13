<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use ArielMejiaDev\LarapexCharts\DonutChart;

class ChannelStreamStatusChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }
    public function build(int $activeStreamsCount, int $inactiveStreamsCount): DonutChart
    {
        return $this->chart->donutChart()
            ->setTitle('Canales con Streams Activos vs. Inactivos')
            ->setSubtitle('Proporción de canales por estado de su transmisión en vivo')
            ->addData([$activeStreamsCount, $inactiveStreamsCount])
            ->setLabels(['Streams Activos', 'Streams Inactivos o Sin Stream'])
            ->setColors(['#20C997', '#FF6384']);
    }
}
