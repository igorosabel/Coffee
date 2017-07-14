CREATE TABLE `person` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único de cada persona',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nombre de la persona',
  `num_coffee` int(11) NOT NULL COMMENT 'Número de veces que ha bajado al café',
  `num_pay` int(11) NOT NULL COMMENT 'Número de veces que ha pagado',
  `color` varchar(6) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Color para identificar a la persona',
  `created_at` datetime NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` datetime NOT NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `went` (
  `id_person` int(11) NOT NULL COMMENT 'Id de la persona que ha bajado al café',
  `id_coffee` int(11) NOT NULL COMMENT 'Id de la vez que se ha bajado al café',
  `pay` tinyint(1) NOT NULL COMMENT 'Indica si la persona ha pagado 1 o no 0',
  `created_at` datetime NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` datetime NOT NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id_person`,`id_coffee`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `coffee` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único de cada día que se ha bajado al café',
  `d` int(11) NOT NULL COMMENT 'Día que se ha bajado al café',
  `m` int(11) NOT NULL COMMENT 'Mes que se ha bajado al café',
  `y` int(11) NOT NULL COMMENT 'Año que se ha bajado al café',
  `special` tinyint(1) NOT NULL COMMENT 'Indica si es un día especial (viernes de pintxo)',
  `created_at` datetime NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` datetime NOT NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


