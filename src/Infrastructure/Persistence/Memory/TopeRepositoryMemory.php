<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Memory;

use App\Domain\Repository\Interfaces\TopeRepository;
use App\Domain\Entities\Periodo;
use App\Domain\Entities\Tope;

final class TopeRepositoryMemory implements TopeRepository
{
    /** @var Tope[] */
    private array $topes;

    public function __construct(array $topes)
    {
        $this->topes = $topes;
    }

    public function buscarPorPeriodo(Periodo $periodo): array
    {
        return array_filter(
            $this->topes,
            fn(Tope $t) => $t->getPeriodo()->equals($periodo)
        );
    }
}