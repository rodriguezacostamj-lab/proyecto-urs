<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../Domain/Entities/Periodo.php';
require_once __DIR__ . '/../Domain/Entities/Anexo.php';
require_once __DIR__ . '/../Domain/Service/CalculadoraURS.php';
require_once __DIR__ . '/../Domain/DTO/ResultadoURS.php';

use App\Domain\Entities\Periodo;
use App\Domain\Entities\Anexo;
use App\Domain\Service\CalculadoraURS;




// Creamos periodo Enero 2026
$enero = new Periodo(2026, 1);

// Creamos datos de prueba (simulan tu anexo)
$anexos = [
    new Anexo('20-1','PEREZ','JUAN','SGP','CASA MILITAR','DCA',$enero,300),
    new Anexo('20-2','GOMEZ','ANA','SGP','ASUNTOS PRESIDENCIALES','DCA',$enero,200),
    new Anexo('20-3','LOPEZ','MAR','SGP','ASUNTOS PRESIDENCIALES','DEP X',$enero,150),
    new Anexo('20-4','SUAREZ','LUIS','SGP','GESTION INSTITUCIONAL','DCA',$enero,400),
];



$calc = new CalculadoraURS();

$topes = [
    'CASA MILITAR' => 400,
    'ASUNTOS PRESIDENCIALES' => 300,
    'GESTION INSTITUCIONAL' => 500,
];

$resultados = $calc->calcularSecretariaMes(
    $anexos,
    'SGP',
    $enero,
    $topes
);
echo "<pre>";
foreach ($resultados as $sub => $r) {
    echo $sub . "\n";
    print_r([
        'consumido' => $r->getConsumido(),
        'tope' => $r->getTope(),
        'ahorro' => $r->getAhorroMensual(),
        'desvio' => $r->getDesvio(),
        'superaTope' => $r->getSuperaTope()
    ]);
    echo "\n";
}
echo "</pre>";


$asignados = [
    '2026-1' => 700,
    '2026-2' => 2000,
    '2026-3' => 1500,
];

$topesPorPeriodo = [
    '2026-1' => [
        'CASA MILITAR' => 400,
        'ASUNTOS PRESIDENCIALES' => 300
    ],
    '2026-2' => [
        'CASA MILITAR' => 500,
        'ASUNTOS PRESIDENCIALES' => 400
    ],
    '2026-3' => [
        'CASA MILITAR' => 450,
        'ASUNTOS PRESIDENCIALES' => 350
    ],
];

$periodoConsulta = new Periodo(2026, 3);

$acumulado = $calc->calcularRemanenteAcumuladoHastaPeriodo(
    $periodoConsulta,
    $asignados,
    $topesPorPeriodo
);

echo $acumulado;