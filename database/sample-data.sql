-- Fictional teaching records preserved from the TFA1 account rosters.
-- Explicit dates satisfy the required schema without extra timestamp columns.
-- Import once into the new tables; this file is not a reset script.
USE rally_supply_ta2;

INSERT INTO customers (full_name, email, phone, created_at) VALUES
('Mika Reyes', 'mika.reyes@example.test', '+63 917 204 1180', '2026-10-01 09:00:00'),
('Andre Santos', 'andre.santos@example.test', '+63 918 315 2291', '2026-10-01 09:15:00'),
('Bianca Cruz', 'bianca.cruz@example.test', '+63 919 426 3302', '2026-10-01 09:30:00'),
('Paolo Mendoza', 'paolo.mendoza@example.test', '+63 920 537 4413', '2026-10-01 09:45:00'),
('Toni Garcia', 'toni.garcia@example.test', '+63 921 648 5524', '2026-10-01 10:00:00');

INSERT INTO users (username, full_name, created_at) VALUES
('courtadmin', 'Alex Rivera', '2026-10-01 08:00:00'),
('rallycashier', 'Jamie Lim', '2026-10-01 08:10:00'),
('paddletech', 'Morgan Lee', '2026-10-01 08:20:00'),
('servecrew', 'Sam Dela Cruz', '2026-10-01 08:30:00'),
('matchdesk', 'Taylor Navarro', '2026-10-01 08:40:00');
