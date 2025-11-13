benerin 
1. rubah kode unik di pendaftaran pelatihan
UPDATE
  pendaftaran_pelatihan
SET
  nomor_registrasi = CONCAT('TEMP-', nomor_registrasi)
WHERE
  pelatihan_id = 2;

2. refractor
-- Mendefinisikan tabel virtual (CTE) untuk menyiapkan nomor registrasi baru
UPDATE
  pendaftaran_pelatihan pp
JOIN (
  SELECT
    pp.id,
    CONCAT(
      pp.pelatihan_id, '-', b.kode, '-',
      LPAD(
        ROW_NUMBER() OVER (
          PARTITION BY p.bidang_id
          ORDER BY pp.id ASC
        ),
        3, '0'
      )
    ) AS new_nomor_registrasi
  FROM
    pendaftaran_pelatihan pp
    JOIN peserta p ON pp.peserta_id = p.id
    JOIN bidang b ON p.bidang_id = b.id
  WHERE
    pp.pelatihan_id = 2 -- Sesuaikan jika perlu
) AS n ON pp.id = n.id
SET
  pp.nomor_registrasi = n.new_nomor_registrasi;

-- Lakukan UPDATE pada tabel utama dengan hasil dari CTE
UPDATE
  pendaftaran_pelatihan pp
JOIN
  NewRegNumbers n ON pp.id = n.id
SET
  pp.nomor_registrasi = n.new_nomor_registrasi;


3. cek konsistensi
SELECT
  b.nama_bidang,
  b.kode,
  COUNT(DISTINCT p.id) AS jumlah_peserta_valid,
  COUNT(pp.id) AS jumlah_nomor_registrasi
FROM
  bidang b
LEFT JOIN
  peserta p ON b.id = p.bidang_id
LEFT JOIN
  pendaftaran_pelatihan pp ON p.id = pp.peserta_id
GROUP BY
  b.id, b.nama_bidang, b.kode
ORDER BY
  b.nama_bidang; 

4. cek id bermasalah
