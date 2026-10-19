<?php

/** Error previsto que puede comunicarse al cliente sin detalles internos. */
class ErrorHttp extends RuntimeException
{
    public function __construct(string $mensaje, int $estado = 422)
    {
        parent::__construct($mensaje, $estado);
    }
}
