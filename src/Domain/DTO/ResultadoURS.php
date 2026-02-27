<?php
declare(strict_types=1);

namespace App\Domain\DTO;

final class ResultadoURS
{
    public function __construct(
        private int $asignado,
        private int $tope,
        private int $consumido,
        private int $ahorroMensual,
        private int $desvio,
        private bool $superaTope,
        private int $remanenteMensual,
        private int $ahorroAcumulado,
        private int $remanenteAcumulado
    ) {}

    public function getAsignado(): int { return $this->asignado; }
    public function getTope(): int { return $this->tope; }
    public function getConsumido(): int { return $this->consumido; }
    public function getAhorroMensual(): int { return $this->ahorroMensual; }
    public function getDesvio(): int { return $this->desvio; }
    public function getSuperaTope(): bool { return $this->superaTope; }
    public function getRemanenteMensual(): int { return $this->remanenteMensual; }
    public function getAhorroAcumulado(): int { return $this->ahorroAcumulado; }
    public function getRemanenteAcumulado(): int { return $this->remanenteAcumulado; }
}