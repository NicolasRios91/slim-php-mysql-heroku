<?php

const ESPERANDO_PEDIDO = 'esperando';
const CLIENTE_COMIENDO = 'comiendo';
const CLIENTE_PAGANDO = 'pagando';
const CERRADA = 'cerrada';
const LIBRE = 'libre';

function validarMesa($estado)
{
    if ($estado === ESPERANDO_PEDIDO || $estado === CLIENTE_COMIENDO || $estado === CLIENTE_PAGANDO || $estado === CERRADA || $estado === LIBRE) {
        return true;
    }
    return false;
}
