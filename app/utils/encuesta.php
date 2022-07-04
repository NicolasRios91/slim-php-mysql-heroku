<?php

function validarPuntuacion($puntuacion)
{
    if ($puntuacion > 0 && $puntuacion < 11) {
        return true;
    }
    return false;
}
