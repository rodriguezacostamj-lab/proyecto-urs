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

    foreach($anexos as $a){

     if($a->getSecretaria() !== $secretaria){
        continue;
     }  
     if($a->getSubsecretaria() !== $subsecretaria){
        continue;   
     }

     $p = $a->getPeriodo();

     if($p->getAnio() !== $periodo->getAnio()){
        continue;
     }
     if($p->getMes() !== $periodo->getMes()){
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
 ):ResultadoUrs{
   
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

}