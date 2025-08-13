<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class VideoAccessChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }
    public function build(array $data, array $labels): \ArielMejiaDev\LarapexCharts\DonutChart
    {
        return $this->chart->donutChart()
            ->setTitle('Videos por Nivel de Acceso')
            ->setLabels($labels)
            ->addData($data)
            ->setColors(['#28A745', '#FFC107', '#6C757D']);
    }
}