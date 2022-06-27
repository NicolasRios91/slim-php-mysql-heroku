<?php

const SOCIO = 'socio';
const EMPLEADO = 'empleado';
const MOZO = 'mozo';

const COCINA = 'cocina';
const BARRA = 'barra';
const PATIO = 'patio';
const CANDY_BAR = 'postres';
const ADMINISTRACION = 'administracion';
const SALON = 'salon';

const ACTIVO = 'activo';
const SUSPENDIDO = 'suspendido';
const BAJA = 'baja';

function validarUsuario($tipo)
{
    if ($tipo === SOCIO || $tipo === EMPLEADO) {
        return true;
    }
    return false;
}

function validarSector($sector)
{
    if ($sector === COCINA || $sector === BARRA || $sector === PATIO || $sector === CANDY_BAR || $sector === SALON || $sector === ADMINISTRACION) {
        return true;
    }
    return false;
}
