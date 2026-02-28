<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../autoload.php';

use App\Domain\Service\CalculadoraURS;
use App\Domain\Entities\Periodo;
use App\Domain\Entities\Anexo;
use App\Domain\Entities\Tope;
use App\Infrastructure\Persistence\Memory\AnexoRepositoryMemory;
use App\Infrastructure\Persistence\Memory\TopeRepositoryMemory;

// 1️⃣ Crear periodo
$enero = new Periodo(2026, 1);

// 2️⃣ Crear anexos de prueba
$anexos = [
    new Anexo(
        '20-12345678-9',
        'Perez',
        'Juan',
        'SGP',
        'SUB1',
        'DEP1',
        $enero,
        200
    ),
    new Anexo(
        '20-12345678-9',
        'Perez',
        'Juan',
        'SGP',
        'SUB1',
        'DEP1',
        $enero,
        300
    ),
    new Anexo(
        '20-87654321-0',
        'Gomez',
        'Ana',
        'SGP',
        'SUB2',
        'DEP2',
        $enero,
        150
    ),
];

// 3️⃣ Instanciar calculadora
$calc = new CalculadoraURS();

// 4️⃣ Ejecutar cálculo viejo
$resultados = $calc->calcularSecretariaMes(
    $anexos,
    'SGP',
    $enero,
    [
        'SUB1' => 1000,
        'SUB2' => 500
    ]
);

echo "<pre>";
foreach ($resultados as $sub => $r) {
    echo "Subsecretaria: $sub\n";
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



$topesObjetos = [
    new Tope(
        Tope::NIVEL_SUBSECRETARIA,
        1, // ID de SUB1
        $enero,
        1000
    ),
    new Tope(
        Tope::NIVEL_SUBSECRETARIA,
        2, // ID de SUB2
        $enero,
        500
    ),
];



// Repos
$anexoRepo = new AnexoRepositoryMemory($anexos);
$topeRepo = new TopeRepositoryMemory($topesObjetos);

// Calculadora nueva
$calcNuevo = new CalculadoraURS(null, $topeRepo, $anexoRepo);

// Buscamos consumo de SUB1 usando repos
$anexosDelPeriodo = $anexoRepo->buscarPorPeriodo($enero);

$consumidoRepo = 0;
foreach ($anexosDelPeriodo as $a) {
    if ($a->getSubsecretaria() === 'SUB1') {
        $consumidoRepo += $a->getCantidadUrs();
    }
}

echo "<pre>";
echo "Consumo SUB1 desde repo: $consumidoRepo\n";
echo "</pre>";