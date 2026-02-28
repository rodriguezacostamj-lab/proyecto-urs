<?php
namespace App\Domain\Repository\Interfaces;

use App\Domain\Entities\Periodo;
use App\Domain\Entities\Asignacion;

interface AsignacionRepository
{
    public function buscarPorPeriodo(Periodo $periodo): ?Asignacion;

    /** @return Periodo[] */
    public function listarPeriodosHasta(Periodo $periodo): array;
}