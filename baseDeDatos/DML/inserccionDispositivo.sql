INSERT INTO LABORATORIO (idLab, nombre) VALUES
('TALL01', 'Taller 1'),
('TALL02', 'Taller 2'),
('TALL03', 'Taller 3'),
('LAB01', 'Laboratorio 1'),
('LAB02', 'Laboratorio 2'),
('LAB03', 'Laboratorio 3'),
('LAB04', 'Laboratorio 4'),
('LAB05', 'Laboratorio 5'),
('LAB06', 'Laboratorio 6');

INSERT INTO DISPOSITIVO (idLab, numeroDispositivo, Modificaciones, estado) VALUES
('TALL01', 'PC001', 2, TRUE),
('TALL01', 'PC002', 1, TRUE),
('TALL01', 'PC003', 0, FALSE),

('TALL02', 'PC004', 3, TRUE),
('TALL02', 'PC005', 1, TRUE),
('TALL02', 'PC006', 2, FALSE),

('TALL03', 'PC007', 0, TRUE),
('TALL03', 'PC008', 4, TRUE),
('TALL03', 'PC009', 1, TRUE),

('LAB01', 'PC010', 2, TRUE),
('LAB01', 'PC011', 0, TRUE),
('LAB01', 'PC012', 3, FALSE),

('LAB02', 'PC013', 1, TRUE),
('LAB02', 'PC014', 2, TRUE),
('LAB02', 'PC015', 0, TRUE),

('LAB03', 'PC016', 3, TRUE),
('LAB03', 'PC017', 1, FALSE),
('LAB03', 'PC018', 2, TRUE),

('LAB04', 'PC019', 0, TRUE),
('LAB04', 'PC020', 2, TRUE),
('LAB04', 'PC021', 1, FALSE),

('LAB05', 'PC022', 4, TRUE),
('LAB05', 'PC023', 1, TRUE),
('LAB05', 'PC024', 0, TRUE),

('LAB06', 'PC025', 2, TRUE),
('LAB06', 'PC026', 3, TRUE),
('LAB06', 'PC027', 0, FALSE);