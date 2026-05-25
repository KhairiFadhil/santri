-- SANTRI Belajar - Seed Data (jalankan setelah schema.sql)

USE santri_belajar;


-- POLI
INSERT INTO poli (code, name, sub, icon, is_open) VALUES
('UMU', 'Poli Umum',      'Pemeriksaan umum',  'Stethoscope', 1),
('GIG', 'Poli Gigi',      'Gigi & mulut',      'Tooth',       1),
('ANK', 'Poli Anak',      'Kesehatan anak',    'Baby',        1),
('JTG', 'Poli Jantung',   'Kardiologi',        'Heart',       1),
('MAT', 'Poli Mata',      'Oftalmologi',       'Eye',         1),
('SAR', 'Poli Saraf',     'Neurologi',         'Brain',       1),
('ORT', 'Poli Ortopedi',  'Tulang & sendi',    'Bone',        1),
('OBG', 'Poli Kebidanan', 'Kandungan',         'Heart',       1),
('FAR', 'Farmasi',        'Pengambilan obat',  'Pill',        1);


-- DOKTER (poli_id 1=UMU, 2=GIG, 3=ANK, 4=JTG, 5=MAT, 6=SAR, 7=ORT, 8=OBG, 9=FAR)
INSERT INTO doctors (poli_id, name, specialization, is_active) VALUES
(1, 'dr. Ayu Larasati',          'Dokter Umum',          1),
(1, 'dr. Bagas Pratama',         'Dokter Umum',          1),
(2, 'drg. Citra Halim, Sp.KG',   'Konservasi Gigi',      1),
(2, 'drg. Dimas Arif',           'Dokter Gigi Umum',     1),
(3, 'dr. Eka Wulandari, Sp.A',   'Spesialis Anak',       1),
(3, 'dr. Fikri Ramadhan, Sp.A',  'Spesialis Anak',       1),
(4, 'dr. Gilang Sutisna, Sp.JP', 'Kardiologi',           1),
(5, 'dr. Hana Pertiwi, Sp.M',    'Oftalmologi',          1),
(6, 'dr. Irfan Hakim, Sp.S',     'Neurologi',            1),
(7, 'dr. Joko Nugroho, Sp.OT',   'Ortopedi',             1),
(8, 'dr. Kirana Asih, Sp.OG',    'Obstetri & Ginekologi',1),
(9, 'Apt. Linda Permata',        'Apoteker',             1);


-- JADWAL MINGGUAN
INSERT INTO schedules (doctor_id, day_of_week, time_start, time_end, capacity) VALUES
(1, 'Senin', '08:00:00', '14:00:00', 30),
(1, 'Selasa','08:00:00', '14:00:00', 30),
(1, 'Rabu',  '08:00:00', '14:00:00', 30),
(1, 'Kamis', '08:00:00', '14:00:00', 30),
(1, 'Jumat', '08:00:00', '14:00:00', 30),

(2, 'Senin', '14:00:00', '20:00:00', 30),
(2, 'Selasa','14:00:00', '20:00:00', 30),
(2, 'Rabu',  '14:00:00', '20:00:00', 30),
(2, 'Kamis', '14:00:00', '20:00:00', 30),
(2, 'Jumat', '14:00:00', '20:00:00', 30),
(2, 'Sabtu', '14:00:00', '20:00:00', 30),

(3, 'Senin', '09:00:00', '13:00:00', 20),
(3, 'Rabu',  '09:00:00', '13:00:00', 20),
(3, 'Jumat', '09:00:00', '13:00:00', 20),

(4, 'Selasa','13:00:00', '18:00:00', 20),
(4, 'Kamis', '13:00:00', '18:00:00', 20),
(4, 'Sabtu', '13:00:00', '18:00:00', 20),

(5, 'Senin', '09:00:00', '14:00:00', 25),
(5, 'Selasa','09:00:00', '14:00:00', 25),
(5, 'Rabu',  '09:00:00', '14:00:00', 25),
(5, 'Kamis', '09:00:00', '14:00:00', 25),
(5, 'Jumat', '09:00:00', '14:00:00', 25),

(6, 'Senin', '15:00:00', '19:00:00', 20),
(6, 'Rabu',  '15:00:00', '19:00:00', 20),
(6, 'Jumat', '15:00:00', '19:00:00', 20),

(7, 'Selasa','10:00:00', '14:00:00', 15),
(7, 'Kamis', '10:00:00', '14:00:00', 15),

(8, 'Senin', '09:00:00', '13:00:00', 12),
(8, 'Rabu',  '09:00:00', '13:00:00', 12),

(9, 'Selasa','10:00:00', '15:00:00', 15),
(9, 'Kamis', '10:00:00', '15:00:00', 15),
(9, 'Sabtu', '10:00:00', '15:00:00', 15),

(10, 'Senin', '13:00:00', '18:00:00', 18),
(10, 'Selasa','13:00:00', '18:00:00', 18),
(10, 'Rabu',  '13:00:00', '18:00:00', 18),
(10, 'Kamis', '13:00:00', '18:00:00', 18),
(10, 'Jumat', '13:00:00', '18:00:00', 18),

(11, 'Senin', '08:00:00', '12:00:00', 20),
(11, 'Rabu',  '08:00:00', '12:00:00', 20),
(11, 'Jumat', '08:00:00', '12:00:00', 20),

(12, 'Senin', '07:00:00', '21:00:00', 100),
(12, 'Selasa','07:00:00', '21:00:00', 100),
(12, 'Rabu',  '07:00:00', '21:00:00', 100),
(12, 'Kamis', '07:00:00', '21:00:00', 100),
(12, 'Jumat', '07:00:00', '21:00:00', 100),
(12, 'Sabtu', '07:00:00', '21:00:00', 100),
(12, 'Minggu','07:00:00', '21:00:00', 100);


-- STAFF (default password: admin123)
INSERT INTO staff (name, email, password_hash, role, status) VALUES
('Faisal Rahman',  'admin@medicaria.id',   '$2y$12$GN.SsR6zDa.i9UEcbobShOSR5wfrJbtpK24HQ5n5SBu5F79mG9cWu', 'admin',   'online'),
('Bagas Hermawan', 'petugas@medicaria.id', '$2y$12$GN.SsR6zDa.i9UEcbobShOSR5wfrJbtpK24HQ5n5SBu5F79mG9cWu', 'petugas', 'online'),
('Diana Saputri',  'diana.s@medicaria.id', '$2y$12$GN.SsR6zDa.i9UEcbobShOSR5wfrJbtpK24HQ5n5SBu5F79mG9cWu', 'petugas', 'online'),
('Hesti Maulida',  'hesti.m@medicaria.id', '$2y$12$GN.SsR6zDa.i9UEcbobShOSR5wfrJbtpK24HQ5n5SBu5F79mG9cWu', 'petugas', 'break'),
('Imam Setiawan',  'imam.s@medicaria.id',  '$2y$12$GN.SsR6zDa.i9UEcbobShOSR5wfrJbtpK24HQ5n5SBu5F79mG9cWu', 'petugas', 'offline'),
('Joko Widodo',    'manajer@medicaria.id', '$2y$12$GN.SsR6zDa.i9UEcbobShOSR5wfrJbtpK24HQ5n5SBu5F79mG9cWu', 'manajer', 'offline');


-- USERS (default password: password123)
INSERT INTO users (nik, name, email, password_hash, phone, birth, gender, address, insurance_type, insurance_no) VALUES
('3201234567890001', 'Raihan Putra Wijaya', 'raihan@mail.com',  '$2y$12$sQQbY3t.PUiKWiwz/uWmn.5QsO59JPKp7B6KoqMM03W9YVNDoMqK6', '+62 812 3456 7890', '1994-08-14', 'Laki-laki', 'Jl. Cendana No. 27, Jakarta Pusat', 'BPJS', '0001 2345 6789'),
('3201234567890002', 'Sari Wulandari',      'sari@mail.com',    '$2y$12$sQQbY3t.PUiKWiwz/uWmn.5QsO59JPKp7B6KoqMM03W9YVNDoMqK6', '+62 813 1234 5678', '1992-03-10', 'Perempuan', 'Jl. Mawar No. 5, Jakarta Selatan',  'BPJS', '0001 2345 6712'),
('3174123456712233', 'Maya Anggraini',      'maya@mail.com',    '$2y$12$sQQbY3t.PUiKWiwz/uWmn.5QsO59JPKp7B6KoqMM03W9YVNDoMqK6', '+62 821 4567 8901', '1990-07-22', 'Perempuan', 'Jl. Kenanga No. 12, Tangerang',     'Umum', NULL),
('3674912345679120', 'Nadia Putri',         'nadia@mail.com',   '$2y$12$sQQbY3t.PUiKWiwz/uWmn.5QsO59JPKp7B6KoqMM03W9YVNDoMqK6', '+62 822 9876 5432', '2018-05-15', 'Perempuan', 'Jl. Anggrek No. 8, Bekasi',         'BPJS', '0001 2345 9120'),
('3201123456784419', 'Hendra Saputra',      'hendra@mail.com',  '$2y$12$sQQbY3t.PUiKWiwz/uWmn.5QsO59JPKp7B6KoqMM03W9YVNDoMqK6', '+62 813 4419 0001', '1985-11-30', 'Laki-laki', 'Jl. Melati No. 17, Jakarta Timur',  'BPJS', '0001 2345 4419');


-- QUEUES hari ini (campuran status untuk demo)
INSERT INTO queues (ticket_code, number, user_id, doctor_id, poli_id, schedule_date, schedule_time, complaint, status, insurance_type, registered_via, called_at, started_at, completed_at) VALUES
('UMU-025',25,5,1,1,CURDATE(),'08:00:00','Kontrol rutin','done','BPJS','online',     TIMESTAMP(CURDATE(),'08:00:00'), TIMESTAMP(CURDATE(),'08:05:00'), TIMESTAMP(CURDATE(),'08:14:00')),
('UMU-026',26,5,1,1,CURDATE(),'08:15:00','Demam ringan','progress','BPJS','online',  TIMESTAMP(CURDATE(),'08:15:00'), TIMESTAMP(CURDATE(),'08:18:00'), NULL),
('UMU-027',27,1,1,1,CURDATE(),'08:30:00','Sakit kepala','call','BPJS','online',      TIMESTAMP(CURDATE(),'08:30:00'), NULL, NULL),
('UMU-028',28,2,1,1,CURDATE(),'08:30:00','Pusing','wait','BPJS','online',            NULL, NULL, NULL),
('UMU-029',29,NULL,1,1,CURDATE(),'08:45:00','Walk-in','wait','BPJS','walkin',        NULL, NULL, NULL),

('GIG-014',14,3,3,2,CURDATE(),'08:45:00','Pembersihan karang','progress','Umum','online', TIMESTAMP(CURDATE(),'08:45:00'), TIMESTAMP(CURDATE(),'08:50:00'), NULL),
('GIG-015',15,3,3,2,CURDATE(),'09:00:00','Sakit gigi','wait','Umum','online',             NULL, NULL, NULL),
('GIG-016',16,NULL,3,2,CURDATE(),'09:15:00','Konsultasi','wait','Umum','walkin',          NULL, NULL, NULL),

('ANK-020',20,4,5,3,CURDATE(),'08:30:00','Demam','skip','BPJS','online',      TIMESTAMP(CURDATE(),'08:30:00'), NULL, NULL),
('ANK-021',21,4,5,3,CURDATE(),'09:00:00','Imunisasi','call','BPJS','online',  TIMESTAMP(CURDATE(),'09:00:00'), NULL, NULL),
('ANK-022',22,4,5,3,CURDATE(),'09:30:00','Kontrol','wait','BPJS','online',    NULL, NULL, NULL),

('JTG-008',8,5,7,4,CURDATE(),'10:00:00','Kontrol jantung','wait','Asuransi','online',     NULL, NULL, NULL),
('JTG-009',9,NULL,7,4,CURDATE(),'10:30:00','Konsultasi','wait','Asuransi','walkin',       NULL, NULL, NULL),

('FAR-061',61,1,12,9,CURDATE(),'09:00:00','Pengambilan obat','done','BPJS','online',  TIMESTAMP(CURDATE(),'09:00:00'), TIMESTAMP(CURDATE(),'09:05:00'), TIMESTAMP(CURDATE(),'09:12:00'));


-- RIWAYAT pasien Raihan (user_id=1)
INSERT INTO queues (ticket_code, number, user_id, doctor_id, poli_id, schedule_date, schedule_time, complaint, status, insurance_type, registered_via, completed_at, created_at) VALUES
('GIG-014',14,1,3,2,DATE_SUB(CURDATE(),INTERVAL 11 DAY),'10:00:00','Pembersihan karang gigi','done','BPJS','online',DATE_SUB(CURDATE(),INTERVAL 11 DAY),DATE_SUB(CURDATE(),INTERVAL 11 DAY)),
('UMU-027',27,1,1,1,DATE_SUB(CURDATE(),INTERVAL 58 DAY),'09:00:00','Kontrol tensi','done','BPJS','online',DATE_SUB(CURDATE(),INTERVAL 58 DAY),DATE_SUB(CURDATE(),INTERVAL 58 DAY)),
('JTG-008',8,1,7,4,DATE_SUB(CURDATE(),INTERVAL 70 DAY),'10:30:00','Konsultasi rutin','done','BPJS','online',DATE_SUB(CURDATE(),INTERVAL 70 DAY),DATE_SUB(CURDATE(),INTERVAL 70 DAY)),
('UMU-033',33,1,2,1,DATE_SUB(CURDATE(),INTERVAL 138 DAY),'15:00:00','Dibatalkan','cancel','BPJS','online',NULL,DATE_SUB(CURDATE(),INTERVAL 138 DAY));
