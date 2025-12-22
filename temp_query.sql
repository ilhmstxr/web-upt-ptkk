SELECT pelatihan_id, tes_id, COUNT(*) FROM `data-jawaban-user` GROUP BY pelatihan_id, tes_id;
SELECT DISTINCT tes_id FROM `data-jawaban-user` WHERE pelatihan_id = 1;
SELECT DISTINCT pelatihan_id FROM `data-jawaban-user` WHERE tes_id = 5;
