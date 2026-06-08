-- Generado por Tecnopraxis — Diseñador de BD

CREATE TABLE `representantes` (
  `id_representante` INT NOT NULL AUTO_INCREMENT,
  `cedula` VARCHAR(15) NOT NULL DEFAULT 'V-12345678',
  `nombre` VARCHAR(50) NOT NULL,
  `apellidos` VARCHAR(50) NOT NULL,
  `telefono` VARCHAR(20) NOT NULL,
  `direccion` TEXT NOT NULL,
  PRIMARY KEY (`id_representante`),
  UNIQUE KEY `uq_representantes_cedula` (`cedula`)
);

CREATE TABLE `estudiantes` (
  `id_estudiante` INT NOT NULL AUTO_INCREMENT,
  `cedula_escolar` VARCHAR(20) NOT NULL,
  `nombres` VARCHAR(50) NOT NULL,
  `apellidos` VARCHAR(50) NOT NULL,
  `fecha_nacimiento` DATE NOT NULL,
  `genero` VARCHAR(10) NOT NULL,
  `id_representante` INT,
  `fecha_ingreso` DATE NOT NULL,
  `observaciones_medicas` TEXT NOT NULL,
  PRIMARY KEY (`id_estudiante`),
  UNIQUE KEY `uq_estudiantes_cedula_escolar` (`cedula_escolar`)
);

CREATE TABLE `grados_secciones` (
  `id_grados_seccion` INT NOT NULL AUTO_INCREMENT,
  `nivel` VARCHAR(20) NOT NULL DEFAULT 'Pre-escolar o Primaria',
  `grado` VARCHAR(20) NOT NULL,
  `seccion` VARCHAR(20) NOT NULL,
  `turno` VARCHAR(10) NOT NULL DEFAULT 'Mañana o Tarde',
  PRIMARY KEY (`id_grados_seccion`)
);

CREATE TABLE `nominas_estudiantes` (
  `id_nomina` INT NOT NULL AUTO_INCREMENT,
  `id_estudiante` INT NOT NULL,
  `id_grado_seccion` INT,
  `año_escolar` VARCHAR(9) NOT NULL DEFAULT '2025-2026',
  `fecha_registro` DATE NOT NULL,
  `estado` VARCHAR(15) NOT NULL,
  PRIMARY KEY (`id_nomina`)
);

ALTER TABLE `estudiantes` ADD CONSTRAINT `fk_rel_18` FOREIGN KEY (`id_representante`) REFERENCES `representantes` (`id_representante`) ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE `nominas_estudiantes` ADD CONSTRAINT `fk_rel_32` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`) ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE `nominas_estudiantes` ADD CONSTRAINT `fk_rel_33` FOREIGN KEY (`id_grado_seccion`) REFERENCES `grados_secciones` (`id_grados_seccion`) ON DELETE RESTRICT ON UPDATE CASCADE;