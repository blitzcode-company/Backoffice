<?php
namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class VideoStatusChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }
    public function build(int $activeVideosCount, int $blockedVideosCount): \ArielMejiaDev\LarapexCharts\DonutChart
    {
        return $this->chart->donutChart()
            ->setTitle('Estado de Videos: Activos vs. Bloqueados')
            ->setLabels(['Videos Activos', 'Videos Bloqueados'])
            ->addData([$activeVideosCount, $blockedVideosCount])
            ->setColors(['#28A745', '#DC3545']);
    }
}
