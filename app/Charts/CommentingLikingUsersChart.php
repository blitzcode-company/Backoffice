<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class CommentingLikingUsersChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }
    public function build(
        int $superActiveUsersCount,
        int $commentingLikingOnlyUsersCount,
        int $ratingOnlyUsersCount,
        int $passiveUsersCount
    ): \ArielMejiaDev\LarapexCharts\DonutChart {
        return $this->chart->donutChart()
            ->setTitle('Distribución de Usuarios por Nivel de Interacción')
            ->setLabels([
                'Súper Activos (Comentan, Dan Me Gusta, Puntúan)',
                'Activos (Comentan y/o Dan Me Gusta)',
                'Activos (Solo Puntúan Videos)',
                'Pasivos (Sin Interacción Registrada)',
            ])
            ->addData([
                $superActiveUsersCount,
                $commentingLikingOnlyUsersCount,
                $ratingOnlyUsersCount,
                $passiveUsersCount,
            ])
            ->setColors(['#00E396', '#775DD0', '#FEB019', '#FF4560']);
    }
}