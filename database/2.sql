CREATE TABLE `hospital_hmssell`.`medicine_sale` (
  `id` INT(11) NOT NULL,
`patient_name` VARCHAR(255)  NULL,
  `medicine_id` INT(11) NOT NULL,
  `quantity` INT(5) NOT NULL,
  `amount` DECIMAL(10,4) NOT NULL,
  `time` VARCHAR(45) NOT NULL,
  `create_date` VARCHAR(45) NULL,
  PRIMARY KEY (`id`));

ALTER TABLE `hospital_hmssell`.`medicine_sale` 
CHANGE COLUMN `time` `sold_date` TIMESTAMP NOT NULL ,
CHANGE COLUMN `create_date` `create_date` TIMESTAMP NULL DEFAULT NULL ;

ALTER TABLE `hospital_hmssell`.`medicine_sale` 
CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT ;

