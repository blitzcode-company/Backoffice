<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Models\Blitzvideo\Etiqueta;
use Illuminate\Support\Collection;

class VideoTagChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(Collection $etiquetas)
    {
        $labels = $etiquetas->pluck('nombre')->toArray();
        $data = $etiquetas->map(function ($etiqueta) {
            return $etiqueta->videos()->count();
        })->toArray();

        return $this->chart->barChart()
            ->setTitle('Videos por Etiqueta')
            ->setXAxis($labels)
            ->addData('Videos', $data);
    }

    public function container()
    {
        return $this->chart->container();
    }

    public function cdn()
    {
        return $this->chart->cdn();
    }

    public function script()
    {
        return $this->chart->script();
    }
}
