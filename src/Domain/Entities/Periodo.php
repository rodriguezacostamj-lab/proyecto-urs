<?php
declare(strict_types=1);

namespace App\Domain\Entities;

final class Periodo
{
    private int $anio;
    private int $mes;

    public function __construct(int $anio, int $mes)
    {
        if ($anio < 2000 || $anio > 2100) {
            throw new \InvalidArgumentException("Año inválido: $anio");
        }
        if ($mes < 1 || $mes > 12) {
            throw new \InvalidArgumentException("Mes inválido: $mes");
        }

        $this->anio = $anio;
        $this->mes = $mes;
    }

    public function getAnio(): int {
        return $this->anio;
    }

    public function getMes(): int {
        return $this->mes;
    }

    public function getClave(): string {
        return sprintf('%04d-%02d', $this->anio, $this->mes);
    }

    public function equals(Periodo $otro): bool {
        return $this->anio === $otro->anio && $this->mes === $otro->mes;
    }

    public function compareTo(Periodo $otro): int {
        if ($this->anio !== $otro->anio) {
            return $this->anio <=> $otro->anio;
        }
        return $this->mes <=> $otro->mes;
    }

    public function key(): string {
        return sprintf('%04d-%02d', $this->anio, $this->mes);
    }
}
