<?php

const ADMIN = 'admin';
const BARTENDER = 'bartender';
const CERVECERO = 'cervecero';
const COCINERO = 'cocinero';
const MOZO = 'mozo';
const SOCIO = 'socio';

const ACTIVO = 'activo';
const SUSPENDIDO = 'suspendido';
const BAJA = 'baja';

function validarUsuario($tipo)
{
    if ($tipo === ADMIN || $tipo === BARTENDER || $tipo === CERVECERO || $tipo === COCINERO || $tipo === MOZO || $tipo === SOCIO) {
        return true;
    }
    return false;
}
