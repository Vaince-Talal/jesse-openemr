-- Add temperature_celsius column to form_custom_vitals table
-- This script should be run for existing installations

-- Check if the column doesn't exist before adding it
SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE TABLE_SCHEMA = DATABASE() 
     AND TABLE_NAME = 'form_custom_vitals' 
     AND COLUMN_NAME = 'temperature_celsius') = 0,
    'ALTER TABLE form_custom_vitals ADD COLUMN temperature_celsius FLOAT(5,2) DEFAULT 0 AFTER mean_arterial_pressure',
    'SELECT "Column temperature_celsius already exists" as message'
));

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
