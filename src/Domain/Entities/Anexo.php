<?php

declare(strict_types=1);

namespace App\Domain\Entities;

final class Anexo{
    private string $cuil;
    private string $apellido;
    private string $nombre;
    private string $secretaria;
    private string $subsecretaria;
    private string $dependencia;
    private Periodo $periodo;
    private int $cantidadUrs;
    
    public function __construct(
            string $cuil,
            string $apellido,
            string $nombre,
            string $secretaria,
            string $subsecretaria,
            string $dependencia,
            Periodo $periodo,
            int $cantidadUrs
            
    ) {
        $cuil = trim($cuil);
        $apellido = trim($apellido);
        $nombre = trim($nombre);
        $secretaria = trim($secretaria);
        $subsecretaria = trim($subsecretaria);
        $dependencia = trim($dependencia);

        if ($cuil === '') {
            throw new \InvalidArgumentException("cuil no puede estar vacío");
        }
        if ($secretaria === '') {
            throw new \InvalidArgumentException("secretaria no puede estar vacío");
        }
        if ($subsecretaria === '') {
            throw new \InvalidArgumentException("subsecretaria no puede estar vacío");
        }
        if ($dependencia === '') {
            throw new \InvalidArgumentException("dependencia no puede estar vacío");
        }
        
        if ($cantidadUrs < 0) {
            throw new \InvalidArgumentException("cantidadUrs inválida: $cantidadUrs");
        }
        
        $this->cuil = $cuil;
        $this->apellido = $apellido;
        $this->nombre = $nombre;
        $this->secretaria = $secretaria;
        $this->subsecretaria = $subsecretaria;
        $this->dependencia = $dependencia;
        $this->periodo = $periodo;
        $this->cantidadUrs = $cantidadUrs;
    }
    
    public function getCuil():string{
        return $this->cuil;
    }
    public function getApellido():string{
        return $this->apellido;
    }
    public function getNombre():string{
        return $this->nombre;
    }
    public function getSecretaria():string{
        return $this->secretaria;
    }
    public function getSubsecretaria():string{
        return $this->subsecretaria;
    }
    public function getDependencia():string{
        return $this->dependencia;
    }
    public function getPeriodo():Periodo{
        return $this->periodo;
    }
    public function getNroMes():int {
        return $this->periodo->getMes();
    }
    public function getCantidadUrs():int{
        return $this->cantidadUrs;
    }
}