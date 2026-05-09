<?php
// classes/ReporteBalance.php
// Clase que genera el balance entre entradas y salidas

require_once __DIR__ . '/Entradas.php';
require_once __DIR__ . '/Salidas.php';

class ReporteBalance {
    private $entradas;
    private $salidas;

    public function __construct() {
        $this->entradas = new Entradas();
        $this->salidas  = new Salidas();
    }

    public function getEntradas(): array {
        return $this->entradas->obtenerTodas();
    }

    public function getSalidas(): array {
        return $this->salidas->obtenerTodas();
    }

    public function getTotalEntradas(): float {
        return $this->entradas->totalEntradas();
    }

    public function getTotalSalidas(): float {
        return $this->salidas->totalSalidas();
    }

    // Balance = total entradas - total salidas
    public function getBalance(): float {
        return $this->getTotalEntradas() - $this->getTotalSalidas();
    }

    // Retorna porcentajes para el gráfico de pastel
    public function getPorcentajes(): array {
        $total = $this->getTotalEntradas() + $this->getTotalSalidas();
        if ($total == 0) return ['entradas' => 0, 'salidas' => 0];

        return [
            'entradas' => round(($this->getTotalEntradas() / $total) * 100, 1),
            'salidas'  => round(($this->getTotalSalidas()  / $total) * 100, 1),
        ];
    }
}
