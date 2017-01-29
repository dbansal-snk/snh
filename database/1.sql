ALTER TABLE `hospital_hmssell`.`medicine` 
ADD COLUMN `retailer_name` VARCHAR(256) NULL AFTER `status`,
ADD COLUMN `medicine_expiry_date` VARCHAR(45) NULL AFTER `retailer_name`,
ADD COLUMN `quantity` INT(10) NULL AFTER `medicine_expiry_date`,
ADD COLUMN `packaging` INT(10) NULL AFTER `quantity`,
ADD COLUMN `batch` VARCHAR(256) NULL AFTER `packaging`,
ADD COLUMN `mrp` FLOAT NULL AFTER `batch`,
ADD COLUMN `discount` DECIMAL NULL AFTER `mrp`,
ADD COLUMN `vat` DECIMAL NULL AFTER `discount`,
ADD COLUMN `free_item` VARCHAR(45) NULL AFTER `Vat`;

