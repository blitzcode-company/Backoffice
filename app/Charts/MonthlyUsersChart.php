<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class MonthlyUsersChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build($totalActiveUsersCount, $activePremiumUsersCount, $activeNonPremiumUsersCount)
    {
        return $this->chart->barChart()
            ->setTitle('Estadísticas de Usuarios')
            ->setSubtitle('Usuarios activos por categoría')
            ->addData('Total de Usuarios', [$totalActiveUsersCount])
            ->addData('Usuarios Premium', [$activePremiumUsersCount])
            ->addData('Usuarios No Premium', [$activeNonPremiumUsersCount])
            ->setXAxis(['Usuarios']);
    }
}
