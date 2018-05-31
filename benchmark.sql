CREATE SCHEMA `benchmark`;

CREATE TABLE `benchmark`.`products` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(500) NULL,
  `sku` VARCHAR(45) NULL,
  `price` FLOAT NULL,
  PRIMARY KEY (`id`));

INSERT INTO `benchmark`.`products` (`name`, `sku`, `price`)
VALUES ('Adult', 'ABC123', '15.00'),
 ('Child', 'DEF456', '10.00'),
 ('Senior', 'GHI789', '12.50');
