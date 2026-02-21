<?php
declare(strict_types=1);

namespace App\Domain\Entities;

final class Asignacion
{
    private int $secretariaId;
    private Periodo $periodo;
    private int $cantidad;
    private string $documento;

    public function __construct(
        int $secretariaId,
        Periodo $periodo,
        int $cantidad,
        string $documento
    ) {
        if ($secretariaId <= 0) {
            throw new \InvalidArgumentException("secretariaId inválido: $secretariaId");
        }
        if ($cantidad < 0) {
            throw new \InvalidArgumentException("cantidad inválida: $cantidad");
        }

        $documento = trim($documento);
        if ($documento === '') {
            throw new \InvalidArgumentException("documento no puede estar vacío");
        }

        $this->secretariaId = $secretariaId;
        $this->periodo = $periodo;
        $this->cantidad = $cantidad;
        $this->documento = $documento;
    }

    public function getSecretariaId(): int
    {
        return $this->secretariaId;
    }

    public function getPeriodo(): Periodo
    {
        return $this->periodo;
    }

    public function getCantidad(): int
    {
        return $this->cantidad;
    }

    public function getDocumento(): string
    {
        return $this->documento;
    }
}