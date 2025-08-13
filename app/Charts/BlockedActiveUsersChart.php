<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class BlockedActiveUsersChart
{
    protected $chart;
    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }
    public function build(int $blockedUsersCount, int $activeUsersCount): \ArielMejiaDev\LarapexCharts\DonutChart
    {
        return $this->chart->donutChart()
            ->setTitle('Distribución de Usuarios: Bloqueados vs. Activos')
            ->setLabels(['Usuarios Bloqueados', 'Usuarios Activos'])
            ->addData([$blockedUsersCount, $activeUsersCount])
            ->setColors(['#DC3545', '#28A745']);
    }
}