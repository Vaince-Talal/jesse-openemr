-- Test data generation for performance testing
-- Generate 100 entries for each form

-- Check current counts
SELECT 'Before - General Readings' as table_name, COUNT(*) as count FROM form_general_readings WHERE pid = 1;
SELECT 'Before - Custom Vitals' as table_name, COUNT(*) as count FROM form_custom_vitals WHERE pid = 1;

-- Generate General Readings entries (100 entries)
INSERT INTO form_general_readings (
    pid, user, groupname, authorized, activity, date,
    daily_fluid_intake, daily_protein_intake, shower, sponge_bath, walking,
    am_fasting_glucose, hs_fasting_glucose, energy, sleep_pattern, stress_level,
    pain, abdominal_pain, appetite, bowel_movements, fatigue, note
)
SELECT 
    1 as pid,
    'admin' as user,
    'Default' as groupname,
    1 as authorized,
    1 as activity,
    DATE_ADD('2021-01-01', INTERVAL (a.a + (10 * b.a)) DAY) as date,
    ROUND(RAND() * 3000, 2) as daily_fluid_intake,
    ROUND(RAND() * 200, 2) as daily_protein_intake,
    FLOOR(RAND() * 2) as shower,
    FLOOR(RAND() * 2) as sponge_bath,
    FLOOR(RAND() * 100) as walking,
    ROUND(RAND() * 200, 2) as am_fasting_glucose,
    ROUND(RAND() * 200, 2) as hs_fasting_glucose,
    FLOOR(RAND() * 10) as energy,
    FLOOR(RAND() * 10) as sleep_pattern,
    FLOOR(RAND() * 10) as stress_level,
    FLOOR(RAND() * 10) as pain,
    FLOOR(RAND() * 10) as abdominal_pain,
    FLOOR(RAND() * 10) as appetite,
    FLOOR(RAND() * 10) as bowel_movements,
    FLOOR(RAND() * 10) as fatigue,
    CONCAT('Test entry ', (a.a + (10 * b.a))) as note
FROM (SELECT 0 as a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a
CROSS JOIN (SELECT 0 as a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b;

-- Generate Custom Vitals entries (100 entries)
INSERT INTO form_custom_vitals (
    pid, user, groupname, authorized, activity, date,
    bps, bpd, pulse, respiration, oxygen_saturation, mean_arterial_pressure, note
)
SELECT 
    1 as pid,
    'admin' as user,
    'Default' as groupname,
    1 as authorized,
    1 as activity,
    DATE_ADD('2021-01-01', INTERVAL (a.a + (10 * b.a)) DAY) as date,
    CAST(FLOOR(RAND() * 250) + 50 AS CHAR) as bps,
    CAST(FLOOR(RAND() * 170) + 30 AS CHAR) as bpd,
    ROUND(RAND() * 270 + 30, 2) as pulse,
    ROUND(RAND() * 55 + 5, 2) as respiration,
    ROUND(RAND() * 50 + 50, 2) as oxygen_saturation,
    ROUND(RAND() * 130 + 20, 2) as mean_arterial_pressure,
    CONCAT('Test vitals entry ', (a.a + (10 * b.a))) as note
FROM (SELECT 0 as a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a
CROSS JOIN (SELECT 0 as a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b;

-- Check final counts
SELECT 'After - General Readings' as table_name, COUNT(*) as count FROM form_general_readings WHERE pid = 1;
SELECT 'After - Custom Vitals' as table_name, COUNT(*) as count FROM form_custom_vitals WHERE pid = 1;