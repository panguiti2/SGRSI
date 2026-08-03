INSERT INTO DISPOSITIVO (id) VALUES
    ('44'),
    ('33'),
    ('22'),
    ('11');

INSERT INTO COMPONENTE (id, idDispositivo, marca, modelo, estado) VALUES
    ('55','44','ripcolor', 'RP-B0602','activo'),
    ('66', '33','ripcolor','GT-KB05','activo'),
    ('77', '22','viewsonic','BK-PT83','activo'),
    ('88', '11','hp','WZ-FA91','inactivo');

INSERT INTO TIPOCOMPONENTE (nombre, idComponente) VALUES
    ('mouse','55'),
    ('teclado','66'),
    ('monitor','77'),
    ('torre','88');

