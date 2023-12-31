-- MySQL Script generated by MySQL Workbench
-- Tue Dec 19 12:36:09 2023
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema default_schema
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema yeticave
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `yeticave` ;

-- -----------------------------------------------------
-- Schema yeticave
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `yeticave` DEFAULT CHARACTER SET utf8 ;
USE `yeticave` ;

-- -----------------------------------------------------
-- Table `yeticave`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `yeticave`.`users` ;

CREATE TABLE IF NOT EXISTS `yeticave`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `date_registration` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `email` VARCHAR(128) NOT NULL,
  `user_name` VARCHAR(128) NULL,
  `user_password` VARCHAR(255) NULL,
  `contacts` MEDIUMTEXT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) VISIBLE)
  ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `yeticave`.`categories`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `yeticave`.`categories` ;

CREATE TABLE IF NOT EXISTS `yeticave`.`categories` (
 `id` INT NOT NULL AUTO_INCREMENT,
 `name` VARCHAR(255) NULL DEFAULT NULL,
  `codename` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
  UNIQUE INDEX `codename_UNIQUE` (`codename` ASC) VISIBLE)
  ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `yeticave`.`lots`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `yeticave`.`lots` ;

CREATE TABLE IF NOT EXISTS `yeticave`.`lots` (
 `id` INT NOT NULL AUTO_INCREMENT,
 `date_creation` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
 `title` VARCHAR(255) NULL DEFAULT NULL,
  `lot_description` LONGTEXT NULL DEFAULT NULL,
  `img` VARCHAR(255) NULL DEFAULT NULL,
  `start_price` INT NOT NULL,
  `date_finish` DATE NULL DEFAULT NULL,
  `step` INT NULL DEFAULT NULL,
  `category_id` INT NULL DEFAULT NULL,
  `author_id` INT NULL DEFAULT NULL,
  `winner_id` INT NULL DEFAULT NULL,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `author_id_idx` (`author_id` ASC) VISIBLE,
  CONSTRAINT `category_id`
  FOREIGN KEY (`category_id`)
  REFERENCES `yeticave`.`categories` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
  CONSTRAINT `author_id`
  FOREIGN KEY (`author_id`)
  REFERENCES `yeticave`.`users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
  CONSTRAINT `winner_id`
  FOREIGN KEY (`winner_id`)
  REFERENCES `yeticave`.`users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION)
  ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `yeticave`.`bets`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `yeticave`.`bets` ;

CREATE TABLE IF NOT EXISTS `yeticave`.`bets` (
   `id` INT NOT NULL AUTO_INCREMENT,
   `date_bet` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
   `price_bet` INT NULL DEFAULT NULL,
   `user_id` INT NULL DEFAULT NULL,
   `lot_id` INT NULL DEFAULT NULL,
   UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `lot_id_idx` (`lot_id` ASC) VISIBLE,
  INDEX `user_id_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `lot_id`
  FOREIGN KEY (`lot_id`)
  REFERENCES `yeticave`.`lots` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
  CONSTRAINT `user_id`
  FOREIGN KEY (`user_id`)
  REFERENCES `yeticave`.`users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION)
  ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
