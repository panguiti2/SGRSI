INSERT INTO LABORATORIO (idLaboratorio, nombre) VALUES
('LAB01', 'Laboratorio 1'),
('LAB02', 'Laboratorio 2'),
('LAB03', 'Laboratorio 3'),
('LAB04', 'Laboratorio 4'),
('LAB05', 'Laboratorio 5'),
('LAB06', 'Laboratorio 6');

INSERT INTO DISPOSITIVO (idLaboratorio, numeroDispositivo, modificaciones, estado) VALUES
('LAB01', 'PC010', 'Actualizado', TRUE),
('LAB01', 'PC011', 'Reparado', TRUE),
('LAB01', 'PC012', 'Actualizado', FALSE),

('LAB02', 'PC013', 'Reparado', TRUE),
('LAB02', 'PC014', 'Actualizado', TRUE),
('LAB02', 'PC015', 'Reparado', TRUE),

('LAB03', 'PC016', 'Actualizado', TRUE),
('LAB03', 'PC017', 'Reparado', FALSE),
('LAB03', 'PC018', 'Reparado', TRUE),

('LAB04', 'PC019', 'Actualizado', TRUE),
('LAB04', 'PC020', 'Reparado', TRUE),
('LAB04', 'PC021', 'Actualizado', FALSE),

('LAB05', 'PC022', 'Reparado', TRUE),
('LAB05', 'PC023', 'Actualizado', TRUE),
('LAB05', 'PC024', 'Reparado', TRUE),

('LAB06', 'PC025', 'Actualizado', TRUE),
('LAB06', 'PC026', 'Reparado', TRUE),
('LAB06', 'PC027', 'Actualizado', FALSE);
