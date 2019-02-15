/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

CREATE TABLE `went` (
  `id_person` INT(11) NOT NULL COMMENT 'Id de la persona que ha bajado al café',
  `id_coffee` INT(11) NOT NULL COMMENT 'Id de la vez que se ha bajado al café',
  `pay` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Indica si la persona ha pagado 1 o no 0',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id_person`,`id_coffee`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `coffee` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único de cada día que se ha bajado al café',
  `d` INT(11) NOT NULL DEFAULT '0' COMMENT 'Día que se ha bajado al café',
  `m` INT(11) NOT NULL DEFAULT '0' COMMENT 'Mes que se ha bajado al café',
  `y` INT(11) NOT NULL DEFAULT '0' COMMENT 'Año que se ha bajado al café',
  `id_person` INT(11) NOT NULL DEFAULT '0' COMMENT 'Id de la persona que ha pagado',
  `special` TINYINT(1) NOT NULL DEFAULT '' COMMENT 'Indica si es un día especial (viernes de pintxo)',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `person` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único de cada persona',
  `name` VARCHAR(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Nombre de la persona',
  `num_coffee` INT(11) NOT NULL DEFAULT '0' COMMENT 'Número de veces que ha bajado al café',
  `num_pay` INT(11) NOT NULL DEFAULT '0' COMMENT 'Número de veces que ha pagado',
  `num_special` INT(11) NOT NULL DEFAULT '0' COMMENT 'Número de viernes que ha bajado',
  `num_special_pay` INT(11) NOT NULL DEFAULT '0' COMMENT 'Número de viernes que ha pagado',
  `color` VARCHAR(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Color para identificar a la persona',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
