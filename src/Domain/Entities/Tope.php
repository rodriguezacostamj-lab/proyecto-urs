<?php
declare(strict_types=1);

namespace App\Domain\Entities;

final class Tope
{
    public const NIVEL_SUBSECRETARIA = 'subsecretaria';
    public const NIVEL_DEPENDENCIA   = 'dependencia';

    private string $nivel;
    private int $entidadId;
    private Periodo $periodo;
    private int $monto;

    public function __construct(string $nivel, int $entidadId, Periodo $periodo, int $monto)
    {
        $nivel = strtolower(trim($nivel));
        if (!in_array($nivel, [self::NIVEL_SUBSECRETARIA, self::NIVEL_DEPENDENCIA], true)) {
            throw new \InvalidArgumentException("nivel inválido: $nivel");
        }
        if ($entidadId <= 0) {
            throw new \InvalidArgumentException("entidadId inválido: $entidadId");
        }
        if ($monto < 0) {
            throw new \InvalidArgumentException("monto inválido: $monto");
        }

        $this->nivel = $nivel;
        $this->entidadId = $entidadId;
        $this->periodo = $periodo;
        $this->monto = $monto;
    }

    public function getNivel(): string
    {
        return $this->nivel;
    }

    public function getEntidadId(): int
    {
        return $this->entidadId;
    }

    public function getPeriodo(): Periodo
    {
        return $this->periodo;
    }

    public function getMonto(): int
    {
        return $this->monto;
    }
}