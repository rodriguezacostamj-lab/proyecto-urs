<?php
declare(strict_types=1);

namespace App\Domain\Entities;

final class Consumo{
    private string $cuil;
    private string $apellido;
    private string $nombre;
    private int $subsecretariaId;
    private int $dependenciaId;
    private Periodo $periodo;
    private int $cantidad;
    
    public function __construct(
            string $cuil,
            string $apellido,
            string $nombre,
            int $subsecretariaId,
            int $dependenciaId,
            Periodo $periodo,
            int $cantidad
    ) 
    
    {
        $cuil = trim($cuil);
        $apellido = trim($apellido);
        $nombre = trim($nombre);

        if ($cuil === '') {
            throw new \InvalidArgumentException("cuil no puede estar vacío");
        }
        if ($subsecretariaId <= 0) {
            throw new \InvalidArgumentException("subsecretariaId inválido: $subsecretariaId");
        }
        if ($dependenciaId <= 0) {
            throw new \InvalidArgumentException("dependenciaId inválido: $dependenciaId");
        }
        if ($cantidad < 0) {
            throw new \InvalidArgumentException("cantidad inválida: $cantidad");
        }
            
        $this->cuil = $cuil;
        $this->apellido = $apellido;
        $this->nombre = $nombre;
        $this->subsecretariaId = $subsecretariaId;
        $this->dependenciaId = $dependenciaId;
        $this->periodo = $periodo;
        $this->cantidad = $cantidad;
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
    public function getSubsecretariaId():int{
        return $this->subsecretariaId;
    }
    public function getDependenciaId():int {
        return $this->dependenciaId;
    }
    public function getPeriodo():Periodo{
        return $this->periodo;
    }
    public function getCantidad():int{
        return $this->cantidad;
    }
    
}
