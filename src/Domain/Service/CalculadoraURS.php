<?php

namespace App\Domain\Service;

use App\Domain\Entities\Anexo;
use App\Domain\Entities\Periodo;
use App\Domain\DTO\ResultadoURS;
use App\Domain\Repository\Interfaces\AsignacionRepository;
use App\Domain\Repository\Interfaces\TopeRepository;
use App\Domain\Repository\Interfaces\AnexoRepository;


final class CalculadoraURS {

    private ?AsignacionRepository $asignacionRepo = null;
    private ?TopeRepository $topeRepo = null;
    private ?AnexoRepository $anexoRepo = null;
    
    public function __construct(
    ?AsignacionRepository $asignacionRepo = null,
    ?TopeRepository $topeRepo = null,
    ?AnexoRepository $anexoRepo = null
) {
    $this->asignacionRepo = $asignacionRepo;
    $this->topeRepo = $topeRepo;
    $this->anexoRepo = $anexoRepo;
}

public function calcularSubsecretariaMesDesdeRepositorio(
    string $secretaria,
    string $subsecretaria,
    Periodo $periodo
): ResultadoURS {

    if (!$this->anexoRepo || !$this->topeRepo) {
        throw new \LogicException("Repositorios no configurados");
    }

    $anexos = $this->anexoRepo->buscarPorPeriodo($periodo);

    $consumido = 0;

    foreach ($anexos as $a) {

        if ($a->getSecretaria() !== $secretaria) {
            continue;
        }

        if ($a->getSubsecretaria() !== $subsecretaria) {
            continue;
        }

        $consumido += $a->getCantidadUrs();
    }

    $topes = $this->topeRepo->buscarPorPeriodo($periodo);

    $tope = 0;

    foreach ($topes as $t) {
        if ($t->getSecretaria() === $secretaria
            && $t->getSubsecretaria() === $subsecretaria) {

            $tope = $t->getCantidad();
            break;
        }
    }

    $ahorro = $tope - $consumido;
    $superaTope = $consumido > $tope;
    $desvio = $superaTope ? $consumido - $tope : 0;

    return new ResultadoURS(
        0,
        $tope,
        $consumido,
        $ahorro,
        $desvio,
        $superaTope,
        0,
        0,
        0
    );
}

    public function sumarConsumoSubsecretariaMes(
            array $anexos,
            string $secretaria,
            string $subsecretaria,
            Periodo $periodo
    ): int {
        $total = 0;

        foreach ($anexos as $a) {

         if ($a->getSecretaria() !== $secretaria) {
            continue;
         }
         if ($a->getSubsecretaria() !== $subsecretaria) {
            continue;
         }

         $p = $a->getPeriodo();

         if ($p->getAnio() !== $periodo->getAnio()) {
            continue;
         }
         if ($p->getMes() !== $periodo->getMes()) {
            continue;
         }

         $total += $a->getCantidadUrs();
      }
      return $total;
   }

   public function calcularSubsecretariaMes(
      array $anexos,
      string $secretaria,
      string $subsecretaria,
      Periodo $periodo,
      int $tope
   ): ResultadoUrs {

      $consumido = $this->sumarConsumoSubsecretariaMes(
         $anexos,
         $secretaria,
         $subsecretaria,
         $periodo
      );

      $ahorro = $tope - $consumido;

      $superaTope = $consumido > $tope;

      $desvio = $superaTope ? $consumido - $tope : 0;

      return new ResultadoURS(
         0,
         $tope,
         $consumido,
         $ahorro,
         $desvio,
         $superaTope,
         0,
         0,
         0
      );
   }
   /**
    * @param Anexo[] $anexos
    * @return array<string, int>  // subsecretaria => consumido
    */
   public function agruparConsumoPorSubsecretariaMes(
      array $anexos,
      string $secretaria,
      Periodo $periodo
   ): array {
      $totales = [];

      foreach ($anexos as $a) {

         if ($a->getSecretaria() !== $secretaria) {
            continue;
         }

         $p = $a->getPeriodo();
         if ($p->getAnio() !== $periodo->getAnio()) {
            continue;
         }
         if ($p->getMes() !== $periodo->getMes()) {
            continue;
         }

         $sub = $a->getSubsecretaria();

         if (!isset($totales[$sub])) {
            $totales[$sub] = 0;
         }

         $totales[$sub] += $a->getCantidadUrs();
      }

      return $totales;
   }

   public function calcularSecretariaMes(
    array $anexos,
    string $secretaria,
    Periodo $periodo,
    array $topesPorSub
): array {

    $consumos = $this->agruparConsumoPorSubsecretariaMes(
        $anexos,
        $secretaria,
        $periodo
    );

    $resultados = [];

    foreach ($consumos as $sub => $consumido) {

        $tope = $topesPorSub[$sub] ?? 0;

        $ahorro = $tope - $consumido;
        $superaTope = $consumido > $tope;
        $desvio = $superaTope ? $consumido - $tope : 0;

        $resultados[$sub] = new ResultadoURS(
            0, $tope, $consumido, $ahorro, $desvio, $superaTope, 0, 0, 0
        );
    }

    return $resultados;
}

   public function calcularRemanenteMensualSecretaria(
            int $asignado,
            array $topesPorSub
    ): int {

        $sumaTopes = 0;

        foreach ($topesPorSub as $tope) {
            $sumaTopes += $tope;
        }

        if ($sumaTopes > $asignado) {
            throw new \DomainException(
                            "La suma de topes ($sumaTopes) supera el asignado ($asignado)"
                    );
        }

        return $asignado - $sumaTopes;
    }

    public function calcularRemanenteAcumuladoAnual(
            array $asignadosPorPeriodo,
            array $topesPorPeriodo
    ): int {

        $acumulado = 0;

        foreach ($asignadosPorPeriodo as $periodoKey => $asignado) {

            $topesMes = $topesPorPeriodo[$periodoKey] ?? [];

            $remanente = $this->calcularRemanenteMensualSecretaria(
                    $asignado,
                    $topesMes
            );

            $acumulado += $remanente;
        }

        return $acumulado;
    }

    public function calcularRemanenteAcumuladoHastaPeriodoDesdeRepositorio(
            string $secretaria,
            Periodo $periodoObjetivo
    ): int {

        if (!$this->asignacionRepo || !$this->topeRepo) {
            throw new \LogicException("Repositorios no configurados");
        }

        $periodos = $this->asignacionRepo->listarPeriodosHasta($periodoObjetivo);

        $acumulado = 0;

        foreach ($periodos as $periodo) {

            $asignacion = $this->asignacionRepo
                    ->buscarPorPeriodo($periodo, $secretaria);

            if (!$asignacion) {
                continue;
            }

            $topes = $this->topeRepo->buscarPorPeriodo($periodo);

            $sumaTopes = 0;

            foreach ($topes as $t) {
                if ($t->getSecretaria() === $secretaria) {
                    $sumaTopes += $t->getCantidad();
                }
            }

            if ($sumaTopes > $asignacion->getCantidad()) {
                throw new \DomainException("Topes superan asignado");
            }

            $acumulado += $asignacion->getCantidad() - $sumaTopes;
        }

        return $acumulado;
    }
}
