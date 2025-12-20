START TRANSACTION;

-- 1) Sinkronisasi data yang sudah ada di tabel
UPDATE kamars SET available_beds = total_beds WHERE available_beds = 0;

-- 2) Proses Data Asrama & Kamar (Tanpa perintah ALTER agar tidak error)

-- ===== MAWAR =====
INSERT INTO asramas (name, created_at, updated_at) 
VALUES ('Mawar', NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = VALUES(updated_at);

SET @asrama_id = (SELECT id FROM asramas WHERE name = 'Mawar' LIMIT 1);

INSERT INTO kamars (asrama_id, nomor_kamar, lantai, total_beds, available_beds, status, is_active, created_at, updated_at) VALUES
(@asrama_id, 1, NULL, 4, 4, 'Tersedia', 1, NOW(), NOW()),
(@asrama_id, 2, NULL, 0, 0, 'Perbaikan', 0, NOW(), NOW()),
(@asrama_id, 3, NULL, 0, 0, 'Perbaikan', 0, NOW(), NOW()),
(@asrama_id, 4, NULL, 4, 4, 'Tersedia', 1, NOW(), NOW()),
(@asrama_id, 5, NULL, 4, 4, 'Tersedia', 1, NOW(), NOW()),
(@asrama_id, 6, NULL, 4, 4, 'Tersedia', 1, NOW(), NOW()),
(@asrama_id, 7, NULL, 7, 7, 'Tersedia', 1, NOW(), NOW()),
(@asrama_id, 8, NULL, 9, 9, 'Tersedia', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    lantai = VALUES(lantai), total_beds = VALUES(total_beds), available_beds = VALUES(available_beds), 
    status = VALUES(status), is_active = VALUES(is_active), updated_at = VALUES(updated_at);

-- ===== MELATI BAWAH =====
INSERT INTO asramas (name, created_at, updated_at) 
VALUES ('Melati Bawah', NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = VALUES(updated_at);

SET @asrama_id = (SELECT id FROM asramas WHERE name = 'Melati Bawah' LIMIT 1);

INSERT INTO kamars (asrama_id, nomor_kamar, lantai, total_beds, available_beds, status, is_active, created_at, updated_at) VALUES
(@asrama_id, 1, 1, 0, 0, 'Perbaikan', 0, NOW(), NOW()),
(@asrama_id, 2, 1, 4, 4, 'Tersedia', 1, NOW(), NOW()),
(@asrama_id, 3, 1, 4, 4, 'Tersedia', 1, NOW(), NOW()),
(@asrama_id, 4, 1, 4, 4, 'Tersedia', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    lantai = VALUES(lantai), total_beds = VALUES(total_beds), available_beds = VALUES(available_beds), 
    status = VALUES(status), is_active = VALUES(is_active), updated_at = VALUES(updated_at);

-- ===== MELATI ATAS =====
INSERT INTO asramas (name, created_at, updated_at) 
VALUES ('Melati Atas', NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = VALUES(updated_at);

SET @asrama_id = (SELECT id FROM asramas WHERE name = 'Melati Atas' LIMIT 1);

INSERT INTO kamars (asrama_id, nomor_kamar, lantai, total_beds, available_beds, status, is_active, created_at, updated_at) VALUES
(@asrama_id, 5, 2, 6, 6, 'Tersedia', 1, NOW(), NOW()),
(@asrama_id, 6, 2, 4, 4, 'Tersedia', 1, NOW(), NOW()),
(@asrama_id, 7, 2, 0, 0, 'Perbaikan', 0, NOW(), NOW()),
(@asrama_id, 8, 2, 0, 0, 'Perbaikan', 0, NOW(), NOW()),
(@asrama_id, 9, 2, 0, 0, 'Rusak', 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    lantai = VALUES(lantai), total_beds = VALUES(total_beds), available_beds = VALUES(available_beds), 
    status = VALUES(status), is_active = VALUES(is_active), updated_at = VALUES(updated_at);

-- ===== TULIP BAWAH =====
INSERT INTO asramas (name, created_at, updated_at) 
VALUES ('Tulip Bawah', NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = VALUES(updated_at);

SET @asrama_id = (SELECT id FROM asramas WHERE name = 'Tulip Bawah' LIMIT 1);

INSERT INTO kamars (asrama_id, nomor_kamar, lantai, total_beds, available_beds, status, is_active, created_at, updated_at) VALUES
(@asrama_id, 1, 1, 4, 4, 'Tersedia', 1, NOW(), NOW()),
(@asrama_id, 2, 1, 4, 4, 'Tersedia', 1, NOW(), NOW()),
(@asrama_id, 3, 1, 4, 4, 'Tersedia', 1, NOW(), NOW()),
(@asrama_id, 4, 1, 4, 4, 'Tersedia', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    lantai = VALUES(lantai), total_beds = VALUES(total_beds), available_beds = VALUES(available_beds), 
    status = VALUES(status), is_active = VALUES(is_active), updated_at = VALUES(updated_at);

-- ===== TULIP ATAS =====
INSERT INTO asramas (name, created_at, updated_at) 
VALUES ('Tulip Atas', NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = VALUES(updated_at);

SET @asrama_id = (SELECT id FROM asramas WHERE name = 'Tulip Atas' LIMIT 1);

INSERT INTO kamars (asrama_id, nomor_kamar, lantai, total_beds, available_beds, status, is_active, created_at, updated_at) VALUES
(@asrama_id, 11, 2, 0, 0, 'Perbaikan', 0, NOW(), NOW()),
(@asrama_id, 12, 2, 0, 0, 'Perbaikan', 0, NOW(), NOW()),
(@asrama_id, 13, 2, 0, 0, 'Rusak', 0, NOW(), NOW()),
(@asrama_id, 14, 2, 4, 4, 'Tersedia', 1, NOW(), NOW()),
(@asrama_id, 15, 2, 0, 0, 'Rusak', 0, NOW(), NOW()),
(@asrama_id, 16, 2, 0, 0, 'Rusak', 0, NOW(), NOW()),
(@asrama_id, 21, 2, 4, 4, 'Tersedia', 1, NOW(), NOW()),
(@asrama_id, 22, 2, 3, 3, 'Tersedia', 1, NOW(), NOW()),
(@asrama_id, 23, 2, 3, 3, 'Tersedia', 1, NOW(), NOW()),
(@asrama_id, 28, 2, 4, 4, 'Tersedia', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    lantai = VALUES(lantai), total_beds = VALUES(total_beds), available_beds = VALUES(available_beds), 
    status = VALUES(status), is_active = VALUES(is_active), updated_at = VALUES(updated_at);

COMMIT;