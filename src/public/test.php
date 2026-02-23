<?php
declare(strict_types=1);

require_once __DIR__ . '/../Domain/Entities/Periodo.php';
require_once __DIR__ . '/../Domain/Entities/Anexo.php';
require_once __DIR__ . '/../Domain/Service/CalculadoraURS.php';

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
    new Anexo('20-4','SUAREZ','LUIS','VP','CASA MILITAR','DCA',$enero,400),
];

$calc = new CalculadoraURS();

// Queremos sumar CASA MILITAR dentro de SGP en Enero 2026
$total = $calc->sumarConsumoSubsecretariaMes(
    $anexos,
    'SGP',
    'ASUNTOS PRESIDENCIALES',
    $enero
);


echo "<pre>";
echo "Total SGP - ASUNTOS PRESIDENCIALES - Enero 2026: $total";
echo "</pre>";