<?php

const COCINA = 'cocina';
const BARRA = 'barra';
const PATIO = 'patio';
const CANDY_BAR = 'postres';

const COMIDA = 'comida';
const BEBIDA = 'bebida';

function validarProducto($sector, $tipo)
{
    if (
        ($sector === COCINA || $sector === BARRA || $sector === PATIO || $sector === CANDY_BAR) &&
        ($tipo === COMIDA || $tipo === BEBIDA)
    ) {
        return true;
    }
    return false;
}
