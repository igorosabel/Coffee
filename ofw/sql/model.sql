/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

CREATE TABLE `entry` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id única de cada entrada',
  `id_user` INT(11) NOT NULL COMMENT 'Id del usuario dueño de la entrada',
  `title` VARCHAR(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Título de la entrada',
  `entry` TEXT NOT NULL DEFAULT '' COMMENT 'Cuerpo de la entrada',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `user` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id único de cada usuario',
  `name` VARCHAR(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Nombre de usuario',
  `pass` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Contraseña cifrada del usuario',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `entry_tag` (
  `id_entry` INT(11) NOT NULL COMMENT 'Id de la entrada',
  `id_tag` INT(11) NOT NULL COMMENT 'Id de la tag',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  PRIMARY KEY (`id_entry`,`id_tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `tag` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id única de cada tag',
  `id_user` INT(11) NOT NULL COMMENT 'Id del usuario dueño de la tag',
  `tag` VARCHAR(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Texto de la tag',
  `created_at` DATETIME NOT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` DATETIME NULL COMMENT 'Fecha de última modificación del registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `entry`
  ADD KEY `fk_entry_user_idx` (`id_user`),
  ADD CONSTRAINT `fk_entry_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `entry_tag`
  ADD KEY `fk_entry_tag_entry_idx` (`id_entry`),
  ADD KEY `fk_entry_tag_tag_idx` (`id_tag`),
  ADD CONSTRAINT `fk_entry_tag_entry` FOREIGN KEY (`id_entry`) REFERENCES `entry` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_entry_tag_tag` FOREIGN KEY (`id_tag`) REFERENCES `tag` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


ALTER TABLE `tag`
  ADD KEY `fk_tag_user_idx` (`id_user`),
  ADD CONSTRAINT `fk_tag_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
