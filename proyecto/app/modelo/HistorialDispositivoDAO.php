<?php

/** Registro de cambios de inventario, dentro de la misma transacción que la operación. */
class HistorialDispositivoDAO
{
    public function __construct(private PDO $conexion) {}

    public function registrar(string $laboratorio, string $numero, string $operacion, string $actor, array $antes, array $despues): void
    {
        $consulta = $this->conexion->prepare(
            'INSERT INTO HISTORIAL_DISPOSITIVO (idLaboratorio,numeroDispositivo,operacion,cedulaActor,
            modificacionAnterior,modificacionNueva,estadoAnterior,estadoNuevo,fechaAnterior,fechaNueva)
            VALUES (:laboratorio,:numero,:operacion,:actor,:antes,:despues,:estadoAntes,:estadoDespues,:fechaAntes,:fechaDespues)'
        );
        $consulta->execute([
            'laboratorio' => $laboratorio, 'numero' => $numero, 'operacion' => $operacion,
            'actor' => $actor === '' ? null : $actor,
            'antes' => $antes['modificaciones'] ?? null, 'despues' => $despues['modificaciones'] ?? null,
            'estadoAntes' => isset($antes['estado']) ? (int) $antes['estado'] : null,
            'estadoDespues' => isset($despues['estado']) ? (int) $despues['estado'] : null,
            'fechaAntes' => $antes['ultimoCambio'] ?? null,
            'fechaDespues' => $despues['ultimoCambio'] ?? null
        ]);
    }

    public function listar(): array
    {
        return $this->conexion->query('SELECT id,idLaboratorio,numeroDispositivo,operacion,cedulaActor,
            fecha,modificacionAnterior,modificacionNueva,estadoAnterior,estadoNuevo,fechaAnterior,fechaNueva
            FROM HISTORIAL_DISPOSITIVO ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
    }
}
