-- =============================================
-- SOURCE: Form Nilai Akhir Akselerasi Guru 1
-- NOTE: Pastikan nama pelatihan cocok. Kalau tidak ketemu, ganti pola LIKE di bawah.
SET @pelatihan_id := (SELECT id FROM pelatihan WHERE nama_pelatihan LIKE '%Akselerasi Guru 1%' LIMIT 1);
SET @updated := 0;
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 55.00,
    pp.nilai_post_test = 95.00,
    pp.nilai_praktek = 95.00,
    pp.rata_rata = 81.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'DIAN NUR RAHMAWATI, S.Kom';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 65.00,
    pp.nilai_post_test = 45.00,
    pp.nilai_praktek = 96.00,
    pp.rata_rata = 68.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'ODY NORANDA, S.ST';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 55.00,
    pp.nilai_post_test = 90.00,
    pp.nilai_praktek = 91.00,
    pp.rata_rata = 78.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'ANAS FAHRUZI, S.Pd';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 50.00,
    pp.nilai_post_test = 65.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 68.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'ARI HADI WAHONO, S.Kom';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 85.00,
    pp.nilai_praktek = 84.00,
    pp.rata_rata = 76.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'M.AGUS SALIM, S.Pd';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 95.00,
    pp.nilai_praktek = 82.00,
    pp.rata_rata = 79.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'IKA ROKHASARI LESTARI, S.Pd, S.Kom';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 45.00,
    pp.nilai_post_test = 50.00,
    pp.nilai_praktek = 89.00,
    pp.rata_rata = 61.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'MOCHAMAD SULTONI,S.ST';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 45.00,
    pp.nilai_post_test = 60.00,
    pp.nilai_praktek = 87.00,
    pp.rata_rata = 64.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'TIARA APRILIA YOGA ISWARI, S.Kom';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 40.00,
    pp.nilai_post_test = 95.00,
    pp.nilai_praktek = 83.00,
    pp.rata_rata = 72.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'KUSNUDIN, S. Pd';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 45.00,
    pp.nilai_post_test = 65.00,
    pp.nilai_praktek = 85.00,
    pp.rata_rata = 65.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'ARDIANSYAH, S.Pd';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 45.00,
    pp.nilai_post_test = 50.00,
    pp.nilai_praktek = 86.00,
    pp.rata_rata = 60.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'DENNY HENDRIFIKA SETYAWAN,S.Sn.,M.Pd';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 30.00,
    pp.nilai_post_test = 75.00,
    pp.nilai_praktek = 83.00,
    pp.rata_rata = 62.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'TRI SUDARMANTO, S.Kom';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 20.00,
    pp.nilai_post_test = 95.00,
    pp.nilai_praktek = 81.00,
    pp.rata_rata = 65.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'CHOIRUL MUHLIS, S.Kom';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 25.00,
    pp.nilai_post_test = 55.00,
    pp.nilai_praktek = 84.00,
    pp.rata_rata = 54.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'INDAYANTI, S.Kom';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 30.00,
    pp.nilai_post_test = 30.00,
    pp.nilai_praktek = 85.00,
    pp.rata_rata = 48.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'ANNI MIFTACHUL JANNAH,S. Pd';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 670.00,
    pp.nilai_post_test = 1050.00,
    pp.nilai_praktek = 1301.00,
    pp.rata_rata = 1007.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'Jumlah';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 44.67,
    pp.nilai_post_test = 70.00,
    pp.nilai_praktek = 86.73,
    pp.rata_rata = 67.13,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'Rata-Rata';

-- =============================================
-- SOURCE: Form Nilai Akhir MJC II
-- NOTE: Pastikan nama pelatihan cocok. Kalau tidak ketemu, ganti pola LIKE di bawah.
SET @pelatihan_id := (SELECT id FROM pelatihan WHERE nama_pelatihan LIKE '%MJC II%' LIMIT 1);
SET @updated := 0;
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 90.00,
    pp.nilai_post_test = 100.00,
    pp.nilai_praktek = 97.00,
    pp.rata_rata = 95.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'VIVI DWI KHOYIMATUL PUTRI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 90.00,
    pp.nilai_post_test = 95.00,
    pp.nilai_praktek = 93.00,
    pp.rata_rata = 92.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'SALSABILAH MALIK ASSYATIBIY';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 85.00,
    pp.nilai_post_test = 85.00,
    pp.nilai_praktek = 94.00,
    pp.rata_rata = 88.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'ELO DWI JUNEDY';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 70.00,
    pp.nilai_post_test = 75.00,
    pp.nilai_praktek = 97.00,
    pp.rata_rata = 80.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'DAFINZA MAULANA BAGUS PUTRA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 75.00,
    pp.nilai_post_test = 100.00,
    pp.nilai_praktek = 93.00,
    pp.rata_rata = 89.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'DANIL ANDADI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 55.00,
    pp.nilai_post_test = 85.00,
    pp.nilai_praktek = 96.00,
    pp.rata_rata = 78.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'SARAH FERLYZA FEBRIANTI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 65.00,
    pp.nilai_post_test = 80.00,
    pp.nilai_praktek = 95.00,
    pp.rata_rata = 80.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'ARLINA LAYYIN FIRMANDA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 95.00,
    pp.nilai_praktek = 93.00,
    pp.rata_rata = 82.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'DZAKY NAUFAL AQIL';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 65.00,
    pp.nilai_post_test = 90.00,
    pp.nilai_praktek = 92.00,
    pp.rata_rata = 82.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'MARIO TIARDI NURIZKI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 90.00,
    pp.nilai_post_test = 75.00,
    pp.nilai_praktek = 89.00,
    pp.rata_rata = 84.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'SILVI ROSIDATUL KOMARIAH';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 75.00,
    pp.nilai_post_test = 80.00,
    pp.nilai_praktek = 89.00,
    pp.rata_rata = 81.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'SATRIA YUGA PRATAMA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 70.00,
    pp.nilai_praktek = 91.00,
    pp.rata_rata = 73.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'ASTI ANANTA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 50.00,
    pp.nilai_post_test = 55.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 65.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'USMAN';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 60.00,
    pp.nilai_praktek = 80.00,
    pp.rata_rata = 66.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'RAYNA ROSALINDA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 50.00,
    pp.nilai_post_test = 40.00,
    pp.nilai_praktek = 76.00,
    pp.rata_rata = 55.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'NOR FITRIA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 1040.00,
    pp.nilai_post_test = 1185.00,
    pp.nilai_praktek = 1365.00,
    pp.rata_rata = 1196.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'Jumlah';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 69.33,
    pp.nilai_post_test = 79.00,
    pp.nilai_praktek = 91.00,
    pp.rata_rata = 79.78,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'Rata-Rata';

-- =============================================
-- SOURCE: Form Nilai Akhir MJC
-- NOTE: Pastikan nama pelatihan cocok. Kalau tidak ketemu, ganti pola LIKE di bawah.
SET @pelatihan_id := (SELECT id FROM pelatihan WHERE nama_pelatihan LIKE '%MJC%' LIMIT 1);
SET @updated := 0;
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 80.00,
    pp.nilai_post_test = 90.00,
    pp.nilai_praktek = 97.00,
    pp.rata_rata = 89.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'NAYSHA NABILA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 90.00,
    pp.nilai_praktek = 97.00,
    pp.rata_rata = 82.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'OLIVIA ANISA ZAHRA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 55.00,
    pp.nilai_post_test = 70.00,
    pp.nilai_praktek = 98.00,
    pp.rata_rata = 74.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'NASYWA DWI ARDANIS';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 100.00,
    pp.nilai_praktek = 93.00,
    pp.rata_rata = 84.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'MUHAMMAD GALANG JAYA WARDANA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 55.00,
    pp.nilai_post_test = 85.00,
    pp.nilai_praktek = 95.00,
    pp.rata_rata = 78.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'KHAMIM NUR AMRULLOH';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 75.00,
    pp.nilai_post_test = 85.00,
    pp.nilai_praktek = 91.00,
    pp.rata_rata = 83.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'MUHAMMAD RIFA''I';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 50.00,
    pp.nilai_post_test = 100.00,
    pp.nilai_praktek = 92.00,
    pp.rata_rata = 80.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'RADHITYA AJI PRATAMA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 45.00,
    pp.nilai_post_test = 100.00,
    pp.nilai_praktek = 91.00,
    pp.rata_rata = 78.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'DENY UMMAR KURNIAWAN';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 80.00,
    pp.nilai_praktek = 89.00,
    pp.rata_rata = 76.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'ALLIKA DWI PRASKA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 30.00,
    pp.nilai_post_test = 60.00,
    pp.nilai_praktek = 92.00,
    pp.rata_rata = 60.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'RIZKHA AURELIA AGUSTIN';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 45.00,
    pp.nilai_post_test = 65.00,
    pp.nilai_praktek = 88.00,
    pp.rata_rata = 66.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'TRISTAN DEALFAYET';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 55.00,
    pp.nilai_post_test = 55.00,
    pp.nilai_praktek = 86.00,
    pp.rata_rata = 65.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'RIDHO FERI VALENTINO';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 40.00,
    pp.nilai_post_test = 90.00,
    pp.nilai_praktek = 83.00,
    pp.rata_rata = 71.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'ZAKHI ABDUL HAMID';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 65.00,
    pp.nilai_post_test = 100.00,
    pp.nilai_praktek = 77.00,
    pp.rata_rata = 80.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'BAYU WAHID NUR EFENDY';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 55.00,
    pp.nilai_post_test = 85.00,
    pp.nilai_praktek = 74.00,
    pp.rata_rata = 71.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'GILBERTH HAYASI TARANTEIN';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 830.00,
    pp.nilai_post_test = 1255.00,
    pp.nilai_praktek = 1343.00,
    pp.rata_rata = 1142.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'Jumlah';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 55.33,
    pp.nilai_post_test = 83.67,
    pp.nilai_praktek = 89.53,
    pp.rata_rata = 76.18,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'Rata-Rata';

-- =============================================
-- SOURCE: Form Nilai Akhir MTU 2
-- NOTE: Pastikan nama pelatihan cocok. Kalau tidak ketemu, ganti pola LIKE di bawah.
SET @pelatihan_id := (SELECT id FROM pelatihan WHERE nama_pelatihan LIKE '%MTU 2%' LIMIT 1);
SET @updated := 0;
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 65.00,
    pp.nilai_post_test = 85.00,
    pp.nilai_praktek = 94.00,
    pp.rata_rata = 81.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'NAWIRA EKA NUGRAHA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 95.00,
    pp.nilai_post_test = 85.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 90.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'ELI HENDRIK PUJIONO';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 85.00,
    pp.nilai_post_test = 75.00,
    pp.nilai_praktek = 92.00,
    pp.rata_rata = 84.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'MUHAMMAD FAHRIZAL SABRI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 50.00,
    pp.nilai_post_test = 85.00,
    pp.nilai_praktek = 95.00,
    pp.rata_rata = 76.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'MUHAMMAD HILMIY ATMAJA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 65.00,
    pp.nilai_praktek = 95.00,
    pp.rata_rata = 73.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'MUHAMMAD ALIF FEBRIYAN';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 80.00,
    pp.nilai_post_test = 85.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 85.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'MUHAMMAD SABIQ NUR HIKAM';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 90.00,
    pp.nilai_post_test = 75.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 85.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'WILDAN MUJTABA FATKHULLAH';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 80.00,
    pp.nilai_post_test = 75.00,
    pp.nilai_praktek = 91.00,
    pp.rata_rata = 82.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'AHMAD ZAKI NAILIL FADLI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 80.00,
    pp.nilai_post_test = 80.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 83.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'PRABOWO BAGUS WICAKSONO';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 70.00,
    pp.nilai_post_test = 55.00,
    pp.nilai_praktek = 94.00,
    pp.rata_rata = 73.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'TOFA RIZAL ALFIAN';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 75.00,
    pp.nilai_post_test = 80.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 81.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'CHANDRA ADHITYA CAHYA PAMUNGKAS';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 70.00,
    pp.nilai_post_test = 70.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 76.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'JULIAN ALVINO SETIAWAN';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 65.00,
    pp.nilai_post_test = 70.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 75.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'AMIRUL MU''MININ';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 55.00,
    pp.nilai_post_test = 80.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 75.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'DZAKI IQBAL BHAKTIYAR';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 75.00,
    pp.nilai_post_test = 60.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 75.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'JUNIANTO PUTRA RAHARDI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 70.00,
    pp.nilai_post_test = 65.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 75.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'NUR ZAENAL ABIDIN';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 45.00,
    pp.nilai_post_test = 70.00,
    pp.nilai_praktek = 92.00,
    pp.rata_rata = 69.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'GALUH KURNIAWAN N.T';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 55.00,
    pp.nilai_post_test = 75.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 73.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'BUCHORI NASUTION';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 70.00,
    pp.nilai_post_test = 60.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 73.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'MOHAMMAD FASYA IBRIZI AL FATHIRI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 60.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 70.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'AINUL YAQIN';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 50.00,
    pp.nilai_post_test = 70.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 70.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'HAECHAL DANDY PURWA PUTRA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 35.00,
    pp.nilai_post_test = 75.00,
    pp.nilai_praktek = 91.00,
    pp.rata_rata = 67.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'KACUNG HENDRI PRATAMA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 40.00,
    pp.nilai_post_test = 75.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 68.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'DIMAS EMIRUDIN';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 50.00,
    pp.nilai_post_test = 60.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 66.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'BIMMA ARYA KUSUMA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 50.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 66.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'REZA NUR AZHAR';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 40.00,
    pp.nilai_post_test = 65.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 65.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'ALDI HENDRA SAPUTRA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 50.00,
    pp.nilai_post_test = 55.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 65.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'MUHAMAD SLAMET SUPRIYADI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 45.00,
    pp.nilai_post_test = 55.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 63.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'M. DANANG IMAM UTOMO';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 35.00,
    pp.nilai_post_test = 40.00,
    pp.nilai_praktek = 92.00,
    pp.rata_rata = 55.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'FARDAN ADIB MUDZAKKA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 35.00,
    pp.nilai_post_test = 50.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 58.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'RADITYA ZAKI WANDA FEBRYAN';

-- =============================================
-- SOURCE: Form Nilai Akhir MTU I
-- NOTE: Pastikan nama pelatihan cocok. Kalau tidak ketemu, ganti pola LIKE di bawah.
SET @pelatihan_id := (SELECT id FROM pelatihan WHERE nama_pelatihan LIKE '%MTU I%' LIMIT 1);
SET @updated := 0;
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 95.00,
    pp.nilai_post_test = 100.00,
    pp.nilai_praktek = 99.00,
    pp.rata_rata = 98.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'ACHMAD RAGIL PANGESTU';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 70.00,
    pp.nilai_post_test = 100.00,
    pp.nilai_praktek = 99.00,
    pp.rata_rata = 89.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'MUHAMMAD NUR HAMIDAR YUSUF';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 100.00,
    pp.nilai_praktek = 98.00,
    pp.rata_rata = 86.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'FANDI HARUN';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 65.00,
    pp.nilai_post_test = 95.00,
    pp.nilai_praktek = 97.00,
    pp.rata_rata = 85.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'SYARIFUDDIN HABIB AZURI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 50.00,
    pp.nilai_post_test = 90.00,
    pp.nilai_praktek = 98.00,
    pp.rata_rata = 79.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'AHMAD BAGUS NASYRAH';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 65.00,
    pp.nilai_post_test = 90.00,
    pp.nilai_praktek = 96.00,
    pp.rata_rata = 83.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'OKKY TRI ADITYA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 65.00,
    pp.nilai_post_test = 95.00,
    pp.nilai_praktek = 95.00,
    pp.rata_rata = 85.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'MOCHAMAD EKO RACHMADANI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 80.00,
    pp.nilai_post_test = 100.00,
    pp.nilai_praktek = 91.00,
    pp.rata_rata = 90.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'DIMAS NUR ABIDIN';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 80.00,
    pp.nilai_post_test = 100.00,
    pp.nilai_praktek = 91.00,
    pp.rata_rata = 90.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'FAREL FAHAMI ALFAREZA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 95.00,
    pp.nilai_praktek = 94.00,
    pp.rata_rata = 83.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'M.FRECHA EZY PUTRA P.';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 95.00,
    pp.nilai_praktek = 93.00,
    pp.rata_rata = 82.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'MUHAMMAD RIZQI ADITYA PRATAMA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 75.00,
    pp.nilai_post_test = 100.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 88.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'MOH.WAHYUDA SAPUTRA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 70.00,
    pp.nilai_post_test = 95.00,
    pp.nilai_praktek = 91.00,
    pp.rata_rata = 85.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'RASYID OKTAVIAN';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 65.00,
    pp.nilai_post_test = 95.00,
    pp.nilai_praktek = 89.00,
    pp.rata_rata = 83.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'FAIQ SIHABUDIN UBAIDILLAH';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 55.00,
    pp.nilai_post_test = 95.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 80.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'M.DIVAN ALFAY PUTRA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 55.00,
    pp.nilai_post_test = 95.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 80.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'NOVAL ADITRIA PUTRA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 95.00,
    pp.nilai_praktek = 87.00,
    pp.rata_rata = 80.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'JUNIAR RAFA ZAHWI ATTAYALLAH';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 45.00,
    pp.nilai_post_test = 90.00,
    pp.nilai_praktek = 89.00,
    pp.rata_rata = 74.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'MUHAMMAD AFRIZAL NASRUL ALFIANSYAH';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 35.00,
    pp.nilai_post_test = 90.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 71.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'NOVAN MAULANA SEPTIAN';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 80.00,
    pp.nilai_praktek = 88.00,
    pp.rata_rata = 76.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'FARLY DWI ANDIKA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 50.00,
    pp.nilai_post_test = 80.00,
    pp.nilai_praktek = 89.00,
    pp.rata_rata = 73.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'IQBAL FARIS TAQIYUDDIN JAHFAL';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 65.00,
    pp.nilai_post_test = 95.00,
    pp.nilai_praktek = 85.00,
    pp.rata_rata = 81.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'MUHAMMAD ZUHDI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 95.00,
    pp.nilai_praktek = 85.00,
    pp.rata_rata = 80.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'MOCH REISA ARMADA MUHIBBUDIN';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 45.00,
    pp.nilai_post_test = 90.00,
    pp.nilai_praktek = 84.00,
    pp.rata_rata = 73.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'ARGA FAWWAZ FIRJATULLAH';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 45.00,
    pp.nilai_post_test = 85.00,
    pp.nilai_praktek = 84.00,
    pp.rata_rata = 71.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'NAUVAL DWI ERIANTO';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 35.00,
    pp.nilai_post_test = 95.00,
    pp.nilai_praktek = 83.00,
    pp.rata_rata = 71.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'LANA PANJI ASMORO';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 55.00,
    pp.nilai_post_test = 90.00,
    pp.nilai_praktek = 80.00,
    pp.rata_rata = 75.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'AHMAD RAFI DWI SAPUTRO';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 40.00,
    pp.nilai_post_test = 95.00,
    pp.nilai_praktek = 80.00,
    pp.rata_rata = 71.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'FADHIL HIDAYAT';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 45.00,
    pp.nilai_post_test = 70.00,
    pp.nilai_praktek = 81.00,
    pp.rata_rata = 65.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'MUHAMMAD SURYA RAMADHANI SAPUTRA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 45.00,
    pp.nilai_post_test = 75.00,
    pp.nilai_praktek = 80.00,
    pp.rata_rata = 66.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'ADI RAHMAD FIRMANSYAH';

-- =============================================
-- SOURCE: Form Nilai Akhir aksel MJC siswa 1
-- NOTE: Pastikan nama pelatihan cocok. Kalau tidak ketemu, ganti pola LIKE di bawah.
SET @pelatihan_id := (SELECT id FROM pelatihan WHERE nama_pelatihan LIKE '%aksel MJC siswa 1%' LIMIT 1);
SET @updated := 0;
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 95.00,
    pp.nilai_post_test = 95.00,
    pp.nilai_praktek = 100.00,
    pp.rata_rata = 96.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'MAULANA AHMAD SYAMSUDDIN';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 100.00,
    pp.nilai_post_test = 100.00,
    pp.nilai_praktek = 97.00,
    pp.rata_rata = 99.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'ADEN PERMANA GUSTI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 90.00,
    pp.nilai_post_test = 85.00,
    pp.nilai_praktek = 100.00,
    pp.rata_rata = 91.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'CARENINA AGATHA JULIA WIBISONO';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 80.00,
    pp.nilai_post_test = 95.00,
    pp.nilai_praktek = 99.00,
    pp.rata_rata = 91.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'RESTU PANDHUSINATHYA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 70.00,
    pp.nilai_post_test = 85.00,
    pp.nilai_praktek = 100.00,
    pp.rata_rata = 85.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'RAHEL FEBRIANI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 75.00,
    pp.nilai_post_test = 90.00,
    pp.nilai_praktek = 97.00,
    pp.rata_rata = 87.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'AHMAD FERIZAL WILDAN SUSANTO';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 80.00,
    pp.nilai_praktek = 100.00,
    pp.rata_rata = 80.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'AILEEN GALUH PADANTYA WAHYUDI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 70.00,
    pp.nilai_post_test = 75.00,
    pp.nilai_praktek = 99.00,
    pp.rata_rata = 81.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'DJAGAD ANANTA WIKRAMA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 75.00,
    pp.nilai_post_test = 75.00,
    pp.nilai_praktek = 97.00,
    pp.rata_rata = 82.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'AFRIZALDI RAHMAT FAUZAN';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 70.00,
    pp.nilai_post_test = 80.00,
    pp.nilai_praktek = 97.00,
    pp.rata_rata = 82.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'NOVINDA AULYA PUTRI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 70.00,
    pp.nilai_post_test = 60.00,
    pp.nilai_praktek = 99.00,
    pp.rata_rata = 76.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'MUHAMMAD WAHYUDI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 65.00,
    pp.nilai_praktek = 99.00,
    pp.rata_rata = 74.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'NUR AIDA FITRI HAFIFAH';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 50.00,
    pp.nilai_post_test = 65.00,
    pp.nilai_praktek = 100.00,
    pp.rata_rata = 71.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'M. IQBAL FINDRA FASHA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 60.00,
    pp.nilai_praktek = 99.00,
    pp.rata_rata = 73.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'MUHAMMAD ABDUL WAFI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 35.00,
    pp.nilai_post_test = 95.00,
    pp.nilai_praktek = 97.00,
    pp.rata_rata = 75.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'SUBUH FARIEL ZIZTIARDI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 1060.00,
    pp.nilai_post_test = 1205.00,
    pp.nilai_praktek = 1480.00,
    pp.rata_rata = 1248.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'Jumlah';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 70.67,
    pp.nilai_post_test = 80.33,
    pp.nilai_praktek = 98.67,
    pp.rata_rata = 83.22,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'Rata-Rata';

-- =============================================
-- SOURCE: Form Nilai Akhir aksel reg guru 1
-- NOTE: Pastikan nama pelatihan cocok. Kalau tidak ketemu, ganti pola LIKE di bawah.
SET @pelatihan_id := (SELECT id FROM pelatihan WHERE nama_pelatihan LIKE '%aksel reg guru 1%' LIMIT 1);
SET @updated := 0;
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 72.00,
    pp.nilai_post_test = 96.00,
    pp.nilai_praktek = 92.00,
    pp.rata_rata = 86.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'OLIVIA FARA DEVANI,S.Pd';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 80.00,
    pp.nilai_post_test = 96.00,
    pp.nilai_praktek = 90.50,
    pp.rata_rata = 88.83,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'DINA WAHYU RAHMAYANTI,S.Pd';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 56.00,
    pp.nilai_post_test = 96.00,
    pp.nilai_praktek = 93.00,
    pp.rata_rata = 81.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'LATIFAH HAMDIYAH, S.Pd';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 72.00,
    pp.nilai_post_test = 92.00,
    pp.nilai_praktek = 91.00,
    pp.rata_rata = 85.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'ADE RINA MEGA TANTRI,S.Pd';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 76.00,
    pp.nilai_post_test = 96.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 87.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'DWI OKTAVIA WULANDARI, S.Pd';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 72.00,
    pp.nilai_post_test = 96.00,
    pp.nilai_praktek = 90.50,
    pp.rata_rata = 86.17,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'RADEN RORO FETTY NURHIDAYATI KUSUMA SRI CAHYANTI, S.Pd';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 96.00,
    pp.nilai_praktek = 91.00,
    pp.rata_rata = 82.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'RISANTIKA YANUARRISTI, S. Pd., Gr.';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 64.00,
    pp.nilai_post_test = 100.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 84.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'YENI PRIATNAWATI, S.Pd';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 68.00,
    pp.nilai_post_test = 92.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 83.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'ANIK YUNIARTI, S.Pd';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 92.00,
    pp.nilai_praktek = 91.00,
    pp.rata_rata = 81.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'NENOK MITASARI,S.Pd';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 52.00,
    pp.nilai_post_test = 96.00,
    pp.nilai_praktek = 91.00,
    pp.rata_rata = 79.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'NAILUL ZAKIYYATIL NGIRFANILLAH, S.Pd';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 52.00,
    pp.nilai_post_test = 96.00,
    pp.nilai_praktek = 90.50,
    pp.rata_rata = 79.50,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'HARINI INDAH SA''ADAH, S.Pd';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 56.00,
    pp.nilai_post_test = 92.00,
    pp.nilai_praktek = 90.50,
    pp.rata_rata = 79.50,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'HILDA DESI MAHANI, S. Pd';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 48.00,
    pp.nilai_post_test = 96.00,
    pp.nilai_praktek = 91.00,
    pp.rata_rata = 78.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'NOFIA DENDY RESTIANSARI, S.Pd';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 52.00,
    pp.nilai_post_test = 80.00,
    pp.nilai_praktek = 92.00,
    pp.rata_rata = 74.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'HINDUN WULANDARI S.Pd';

-- =============================================
-- SOURCE: Form Nilai Akhir aksel siswa 1
-- NOTE: Pastikan nama pelatihan cocok. Kalau tidak ketemu, ganti pola LIKE di bawah.
SET @pelatihan_id := (SELECT id FROM pelatihan WHERE nama_pelatihan LIKE '%aksel siswa 1%' LIMIT 1);
SET @updated := 0;
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 45.00,
    pp.nilai_post_test = 85.00,
    pp.nilai_praktek = 100.00,
    pp.rata_rata = 76.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'MIAZUL ILMI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 50.00,
    pp.nilai_post_test = 95.00,
    pp.nilai_praktek = 98.00,
    pp.rata_rata = 81.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'SITI ULYA AFIFAH';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 55.00,
    pp.nilai_post_test = 90.00,
    pp.nilai_praktek = 98.00,
    pp.rata_rata = 81.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'USWATUN CHASANAH';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 90.00,
    pp.nilai_praktek = 97.00,
    pp.rata_rata = 82.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'RENIA ANJANI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 55.00,
    pp.nilai_post_test = 85.00,
    pp.nilai_praktek = 98.00,
    pp.rata_rata = 79.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'SITI MUZAYYANATUZZAHRO';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 50.00,
    pp.nilai_post_test = 85.00,
    pp.nilai_praktek = 98.00,
    pp.rata_rata = 77.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'EFI AWINDA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 95.00,
    pp.nilai_praktek = 95.00,
    pp.rata_rata = 83.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'NUR IMAMAH';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 50.00,
    pp.nilai_post_test = 85.00,
    pp.nilai_praktek = 97.00,
    pp.rata_rata = 77.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'SULISTYA NURAINI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 40.00,
    pp.nilai_post_test = 90.00,
    pp.nilai_praktek = 97.00,
    pp.rata_rata = 75.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'IKE HAYATI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 40.00,
    pp.nilai_post_test = 90.00,
    pp.nilai_praktek = 97.00,
    pp.rata_rata = 75.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'SAFIRA GALUH NIRMALA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 50.00,
    pp.nilai_post_test = 95.00,
    pp.nilai_praktek = 95.00,
    pp.rata_rata = 80.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'VATHRATIA LINGKAR GRENADHA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 100.00,
    pp.nilai_praktek = 92.00,
    pp.rata_rata = 84.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'ARISTA RAHMADHANI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 50.00,
    pp.nilai_post_test = 80.00,
    pp.nilai_praktek = 95.00,
    pp.rata_rata = 75.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'LUCKYSYA INDAH SAHHIDA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 55.00,
    pp.nilai_post_test = 65.00,
    pp.nilai_praktek = 92.00,
    pp.rata_rata = 70.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'NOVIA JIHAN ILMINNISA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 65.00,
    pp.nilai_praktek = 84.00,
    pp.rata_rata = 69.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'TRIMEI TAURA VELISIA PUTRI';

-- =============================================
-- SOURCE: Form Nilai Akhir reg siswa 2
-- NOTE: Pastikan nama pelatihan cocok. Kalau tidak ketemu, ganti pola LIKE di bawah.
SET @pelatihan_id := (SELECT id FROM pelatihan WHERE nama_pelatihan LIKE '%reg siswa 2%' LIMIT 1);
SET @updated := 0;
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 85.00,
    pp.nilai_post_test = 85.00,
    pp.nilai_praktek = 92.00,
    pp.rata_rata = 87.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'ARUM MAWARNI DWI PUSPITA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 95.00,
    pp.nilai_post_test = 100.00,
    pp.nilai_praktek = 88.00,
    pp.rata_rata = 94.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'RIZKY FAJAR ADITIYA PUTRA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 85.00,
    pp.nilai_praktek = 92.00,
    pp.rata_rata = 79.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'SALSABILA NUR ROCHIM';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 75.00,
    pp.nilai_post_test = 90.00,
    pp.nilai_praktek = 89.00,
    pp.rata_rata = 84.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'AMELTA AULIA SUBARKO';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 75.00,
    pp.nilai_post_test = 80.00,
    pp.nilai_praktek = 89.00,
    pp.rata_rata = 81.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'DEWI SAFIRA FEBRIANA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 85.00,
    pp.nilai_post_test = 80.00,
    pp.nilai_praktek = 87.00,
    pp.rata_rata = 84.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'MASYIFA AZZAHRA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 70.00,
    pp.nilai_post_test = 90.00,
    pp.nilai_praktek = 87.00,
    pp.rata_rata = 82.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'ANNA ALTHAFUNNISA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 75.00,
    pp.nilai_post_test = 75.00,
    pp.nilai_praktek = 88.00,
    pp.rata_rata = 79.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'PRAJWALITA ZULFA FATIKA CHUSNA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 65.00,
    pp.nilai_post_test = 70.00,
    pp.nilai_praktek = 89.00,
    pp.rata_rata = 74.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'SILVIA RAMADANI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 70.00,
    pp.nilai_post_test = 70.00,
    pp.nilai_praktek = 88.00,
    pp.rata_rata = 76.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'ANGGREA REVALDA PRATIWI PUTRI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 65.00,
    pp.nilai_post_test = 65.00,
    pp.nilai_praktek = 89.00,
    pp.rata_rata = 73.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'RYNDI MEGA HERAWATI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 80.00,
    pp.nilai_praktek = 87.00,
    pp.rata_rata = 75.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'ALEXCA EVELINA AVRILLA PUTRI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 60.00,
    pp.nilai_praktek = 87.00,
    pp.rata_rata = 69.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'SABRINA WAFA AQILLAH';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 50.00,
    pp.nilai_post_test = 65.00,
    pp.nilai_praktek = 87.00,
    pp.rata_rata = 67.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'XTWOLITA ELFREDA ARDININGRUM';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 40.00,
    pp.nilai_post_test = 35.00,
    pp.nilai_praktek = 87.00,
    pp.rata_rata = 54.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'HALIMATUS PUTRI DEWI';

-- =============================================
-- SOURCE: Form Nilai Akhir reg siswa.1
-- NOTE: Pastikan nama pelatihan cocok. Kalau tidak ketemu, ganti pola LIKE di bawah.
SET @pelatihan_id := (SELECT id FROM pelatihan WHERE nama_pelatihan LIKE '%reg siswa.1%' LIMIT 1);
SET @updated := 0;
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 80.00,
    pp.nilai_post_test = 85.00,
    pp.nilai_praktek = 91.00,
    pp.rata_rata = 85.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'ARISTA RAHMADHANI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 75.00,
    pp.nilai_post_test = 90.00,
    pp.nilai_praktek = 91.00,
    pp.rata_rata = 85.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'SULISTYA NURAINI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 85.00,
    pp.nilai_post_test = 95.00,
    pp.nilai_praktek = 89.00,
    pp.rata_rata = 89.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'SITI MUZAYYANATUZZAHRO';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 90.00,
    pp.nilai_post_test = 100.00,
    pp.nilai_praktek = 87.00,
    pp.rata_rata = 92.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'ATA WILIANA PUTRI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 80.00,
    pp.nilai_post_test = 85.00,
    pp.nilai_praktek = 89.00,
    pp.rata_rata = 84.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'GHINA IRBAH ROSALBA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 55.00,
    pp.nilai_post_test = 100.00,
    pp.nilai_praktek = 90.00,
    pp.rata_rata = 81.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'LUSIANA KIRMAN';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 80.00,
    pp.nilai_praktek = 89.00,
    pp.rata_rata = 76.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'MELA PUTRI SETYANI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 70.00,
    pp.nilai_post_test = 50.00,
    pp.nilai_praktek = 91.00,
    pp.rata_rata = 70.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'LULU NAFIDATUL LAILA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 80.00,
    pp.nilai_post_test = 85.00,
    pp.nilai_praktek = 84.00,
    pp.rata_rata = 83.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'DESWYTA AYU ANANDA';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 70.00,
    pp.nilai_post_test = 80.00,
    pp.nilai_praktek = 85.00,
    pp.rata_rata = 78.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'ELYANA SEVI YANDRIANI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 60.00,
    pp.nilai_post_test = 65.00,
    pp.nilai_praktek = 88.00,
    pp.rata_rata = 71.00,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'MARSYANDA ANDRIYANI KAMILATUL KUTSIYAH';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 55.00,
    pp.nilai_post_test = 80.00,
    pp.nilai_praktek = 86.00,
    pp.rata_rata = 73.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'SEPTIA AYU RAHMADANI';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 45.00,
    pp.nilai_post_test = 55.00,
    pp.nilai_praktek = 84.00,
    pp.rata_rata = 61.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'DELIMATUS HASANAH';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 50.00,
    pp.nilai_post_test = 50.00,
    pp.nilai_praktek = 84.00,
    pp.rata_rata = 61.33,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'NURUD DINIYAH';
UPDATE pendaftaran_pelatihan pp
JOIN peserta ps ON ps.id = pp.peserta_id
SET pp.nilai_pre_test = 40.00,
    pp.nilai_post_test = 45.00,
    pp.nilai_praktek = 82.00,
    pp.rata_rata = 55.67,
    pp.updated_at = NOW()
WHERE pp.pelatihan_id = @pelatihan_id
  AND ps.nama = 'LAILATUL JAMILA';

-- =============================================
-- AGGREGATE: generate statistik_pelatihan for all pelatihan (batch = EXCEL_IMPORT_2025_12_16)
SET @batch := 'EXCEL_IMPORT_2025_12_16';
INSERT INTO statistik_pelatihan
(batch, pelatihan_id, pre_avg, post_avg, praktek_avg, rata_avg, created_at, updated_at)
SELECT
  @batch AS batch,
  pp.pelatihan_id,
  COALESCE(ROUND(AVG(NULLIF(pp.nilai_pre_test, 0)), 2), 0)  AS pre_avg,
  COALESCE(ROUND(AVG(NULLIF(pp.nilai_post_test, 0)), 2), 0) AS post_avg,
  COALESCE(ROUND(AVG(NULLIF(pp.nilai_praktek, 0)), 2), 0)   AS praktek_avg,
  COALESCE(ROUND(AVG(NULLIF(pp.rata_rata, 0)), 2), 0)       AS rata_avg,
  NOW(), NOW()
FROM pendaftaran_pelatihan pp
WHERE pp.pelatihan_id IS NOT NULL
GROUP BY pp.pelatihan_id
ON DUPLICATE KEY UPDATE
  pre_avg = VALUES(pre_avg),
  post_avg = VALUES(post_avg),
  praktek_avg = VALUES(praktek_avg),
  rata_avg = VALUES(rata_avg),
  updated_at = NOW();
