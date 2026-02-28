<?php
namespace App\Domain\Repository\Interfaces;

use App\Domain\Entities\Periodo;
use App\Domain\Entities\Tope;

interface TopeRepository
{
    /** @return Tope[] */
    public function buscarPorPeriodo(Periodo $periodo): array;
}