<?php
// classes/ReporteBalance.php

require_once 'Entradas.php';
require_once 'Salidas.php';

class ReporteBalance {
    private $entradas;
    private $salidas;

    public function __construct() {
        $this->entradas = new Entradas();
        $this->salidas  = new Salidas();
    }

    public function getEntradas() {
        return $this->entradas->obtenerTodas();
    }

    public function getSalidas() {
        return $this->salidas->obtenerTodas();
    }

    public function getTotalEntradas() {
        return $this->entradas->totalEntradas();
    }

    public function getTotalSalidas() {
        return $this->salidas->totalSalidas();
    }

    public function getBalance() {
        return $this->getTotalEntradas() - $this->getTotalSalidas();
    }

    public function getPorcentajes() {
        $total = $this->getTotalEntradas() + $this->getTotalSalidas();
        
        if ($total == 0) {
            return ['entradas' => 0, 'salidas' => 0];
        }
        
        return [
            'entradas' => round(($this->getTotalEntradas() / $total) * 100, 1),
            'salidas'  => round(($this->getTotalSalidas()  / $total) * 100, 1),
        ];
    }
}
?>