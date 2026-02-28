<?php
namespace App\Domain\Repository\Interfaces;

use App\Domain\Entities\Periodo;
use App\Domain\Entities\Anexo;

interface AnexoRepository
{
    /** @return Anexo[] */
    public function buscarPorPeriodo(Periodo $periodo): array;
}