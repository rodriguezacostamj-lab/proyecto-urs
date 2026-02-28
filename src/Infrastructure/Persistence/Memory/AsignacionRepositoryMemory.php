<?php
namespace Infrastructure\Persistence\Memory;

use Domain\Repository\Interfaces\AsignacionRepository;
use Domain\Entities\Periodo;
use Domain\Entities\Asignacion;

class AsignacionRepositoryMemory implements AsignacionRepository
{
    /** @var Asignacion[] */
    private array $asignaciones;

    public function __construct(array $asignaciones)
    {
        $this->asignaciones = $asignaciones;
    }

    public function buscarPorPeriodo(Periodo $periodo): ?Asignacion
    {
        foreach ($this->asignaciones as $a) {
            if ($a->getPeriodo()->equals($periodo)) {
                return $a;
            }
        }
        return null;
    }

    public function listarPeriodosHasta(Periodo $periodo): array
    {
        $res = [];
        foreach ($this->asignaciones as $a) {
            $p = $a->getPeriodo();

            if ($p->getAnio() === $periodo->getAnio() && $p->compareTo($periodo) <= 0) {
                $res[$p->key()] = $p; // evita duplicados
            }
        }
        ksort($res);
        return array_values($res);
    }
}