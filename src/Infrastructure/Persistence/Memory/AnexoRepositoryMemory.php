<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Memory;

use App\Domain\Repository\Interfaces\AnexoRepository;
use App\Domain\Entities\Periodo;
use App\Domain\Entities\Anexo;

final class AnexoRepositoryMemory implements AnexoRepository
{
    /** @var Anexo[] */
    private array $anexos;

    public function __construct(array $anexos)
    {
        $this->anexos = $anexos;
    }

    public function buscarPorPeriodo(Periodo $periodo): array
    {
        return array_filter(
            $this->anexos,
            fn(Anexo $a) => $a->getPeriodo()->equals($periodo)
        );
    }
}