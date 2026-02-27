<?php

namespace App\Domain\Service;

use App\Domain\Entities\Anexo;
use App\Domain\Entities\Periodo;
use App\Domain\DTO\ResultadoURS;


final class CalculadoraURS
{
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
}
