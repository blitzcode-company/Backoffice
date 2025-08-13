<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use ArielMejiaDev\LarapexCharts\BarChart; // ¡Mantén esta importación!

class VideoRatingDistributionChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    /**
     * @param array $data Cantidad de videos por cada puntuación
     * @param array $labels Las puntuaciones (ej. ['1', '2', '3', '4', '5'])
     * @return \ArielMejiaDev\LarapexCharts\BarChart
     */
    public function build(array $data, array $labels): BarChart
    {
        return $this->chart->barChart()
            ->setTitle('Distribución de Puntuaciones de Videos (Emojis)') // ¡Título ajustado!
            ->setSubtitle('Cantidad de videos por promedio de la valoración del 1 al 5') // ¡Subtítulo ajustado!
            ->addData('Videos', $data)
            ->setXAxis($labels) // Las etiquetas serán '1', '2', '3', '4', '5'
            ->setColors(['#FFD700', '#FFA500', '#FF4500', '#4CAF50', '#008FFB']) // Colores para 1 a 5, aún relevantes
            ->setGrid(true);
    }
}