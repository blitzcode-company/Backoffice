<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class ActiveInactiveUsersChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build($activeCount, $inactiveCount): \ArielMejiaDev\LarapexCharts\DonutChart
    {
        return $this->chart->donutChart()
            ->setTitle('Activos vs Inactivos')
            ->setLabels(['Usuarios Activos', 'Usuarios Inactivos'])
            ->addData([$activeCount, $inactiveCount])
            ->setColors(['#00E396', '#FF4560']);
    }
}
