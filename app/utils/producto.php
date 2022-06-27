<?php

require_once './utils/usuario.php';

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
