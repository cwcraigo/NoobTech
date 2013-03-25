SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


-- -----------------------------------------------------
-- Table `cwcraigo_intro2techdb`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cwcraigo_intro2techdb`.`user` ;

CREATE  TABLE IF NOT EXISTS `cwcraigo_intro2techdb`.`user` (
  `user_id` INT NOT NULL ,
  `email` VARCHAR(17) NOT NULL ,
  `user_name?` VARCHAR(45) NULL ,
  `password?` VARCHAR(255) NULL ,
  `created_by` INT NOT NULL ,
  `createion_date` DATE NOT NULL ,
  `last_updated_by` INT NULL ,
  `last_update_date` DATE NULL ,
  PRIMARY KEY (`user_id`) ,
  INDEX `user_created_by_idx` (`created_by` ASC) ,
  INDEX `user_last_updated_by_idx` (`last_updated_by` ASC) ,
  CONSTRAINT `user_created_by`
    FOREIGN KEY (`created_by` )
    REFERENCES `cwcraigo_intro2techdb`.`user` (`user_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `user_last_updated_by`
    FOREIGN KEY (`last_updated_by` )
    REFERENCES `cwcraigo_intro2techdb`.`user` (`user_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cwcraigo_intro2techdb`.`suggestion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cwcraigo_intro2techdb`.`suggestion` ;

CREATE  TABLE IF NOT EXISTS `cwcraigo_intro2techdb`.`suggestion` (
  `suggestion_id` INT NOT NULL ,
  `suggestion_title` VARCHAR(45) NOT NULL ,
  `suggestion_desc` VARCHAR(255) NOT NULL ,
  `active_flag` ENUM('Y','N') NOT NULL ,
  `created_by` INT NOT NULL ,
  `createion_date` DATE NOT NULL ,
  `last_updated_by` INT NULL ,
  `last_update_date` DATE NULL ,
  PRIMARY KEY (`suggestion_id`) ,
  INDEX `suggestion_user_created_by_idx` (`created_by` ASC) ,
  INDEX `suggestion_user_last_updated_by_idx` (`last_updated_by` ASC) ,
  CONSTRAINT `suggestion_user_created_by`
    FOREIGN KEY (`created_by` )
    REFERENCES `cwcraigo_intro2techdb`.`user` (`user_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `suggestion_user_last_updated_by`
    FOREIGN KEY (`last_updated_by` )
    REFERENCES `cwcraigo_intro2techdb`.`user` (`user_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cwcraigo_intro2techdb`.`video`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cwcraigo_intro2techdb`.`video` ;

CREATE  TABLE IF NOT EXISTS `cwcraigo_intro2techdb`.`video` (
  `video_id` INT NOT NULL ,
  `video_url` VARCHAR(60) NOT NULL ,
  `video_title` VARCHAR(45) NOT NULL ,
  `video_desc` VARCHAR(255) NOT NULL ,
  `video_length` VARCHAR(10) NOT NULL ,
  `active_flag` ENUM('Y','N') NOT NULL ,
  `created_by` INT NOT NULL ,
  `createion_date` DATE NOT NULL ,
  `last_updated_by` INT NULL ,
  `last_update_date` DATE NULL ,
  PRIMARY KEY (`video_id`) ,
  INDEX `video_user_idx` (`created_by` ASC) ,
  INDEX `video_user_last_updated_by_idx` (`last_updated_by` ASC) ,
  CONSTRAINT `video_user_created_by`
    FOREIGN KEY (`created_by` )
    REFERENCES `cwcraigo_intro2techdb`.`user` (`user_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `video_user_last_updated_by`
    FOREIGN KEY (`last_updated_by` )
    REFERENCES `cwcraigo_intro2techdb`.`user` (`user_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cwcraigo_intro2techdb`.`comment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cwcraigo_intro2techdb`.`comment` ;

CREATE  TABLE IF NOT EXISTS `cwcraigo_intro2techdb`.`comment` (
  `comment_id` INT NOT NULL ,
  `video_id` INT NOT NULL ,
  `comment_text` VARCHAR(255) NOT NULL ,
  `created_by` INT NOT NULL ,
  `createion_date` DATE NOT NULL ,
  `last_updated_by` INT NULL ,
  `last_update_date` DATE NULL ,
  PRIMARY KEY (`comment_id`) ,
  INDEX `comment_video_idx` (`video_id` ASC) ,
  INDEX `comment_created_by_idx` (`created_by` ASC) ,
  INDEX `comment_last_updated_by_idx` (`last_updated_by` ASC) ,
  CONSTRAINT `comment_video`
    FOREIGN KEY (`video_id` )
    REFERENCES `cwcraigo_intro2techdb`.`video` (`video_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `comment_created_by`
    FOREIGN KEY (`created_by` )
    REFERENCES `cwcraigo_intro2techdb`.`user` (`user_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `comment_last_updated_by`
    FOREIGN KEY (`last_updated_by` )
    REFERENCES `cwcraigo_intro2techdb`.`user` (`user_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cwcraigo_intro2techdb`.`tag`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cwcraigo_intro2techdb`.`tag` ;

CREATE  TABLE IF NOT EXISTS `cwcraigo_intro2techdb`.`tag` (
  `tag_id` INT NOT NULL ,
  `tag_text` VARCHAR(15) NOT NULL ,
  `active_flag` ENUM('Y','N') NOT NULL ,
  `created_by` INT NOT NULL ,
  `createion_date` DATE NOT NULL ,
  `last_updated_by` INT NULL ,
  `last_update_date` DATE NULL ,
  PRIMARY KEY (`tag_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cwcraigo_intro2techdb`.`video_tag`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cwcraigo_intro2techdb`.`video_tag` ;

CREATE  TABLE IF NOT EXISTS `cwcraigo_intro2techdb`.`video_tag` (
  `video_tag_id` INT NOT NULL ,
  `video_id` INT NOT NULL ,
  `tag_id` INT NOT NULL ,
  `created_by` INT NOT NULL ,
  `createion_date` DATE NOT NULL ,
  `last_updated_by` INT NULL ,
  `last_update_date` DATE NULL ,
  PRIMARY KEY (`video_tag_id`) ,
  INDEX `video_tag_video_idx` (`video_id` ASC) ,
  INDEX `video_tag_tag_idx` (`tag_id` ASC) ,
  INDEX `video_tag_created_by_idx` (`created_by` ASC) ,
  INDEX `video_tag_last_updated_by_idx` (`last_updated_by` ASC) ,
  CONSTRAINT `video_tag_video`
    FOREIGN KEY (`video_id` )
    REFERENCES `cwcraigo_intro2techdb`.`video` (`video_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `video_tag_tag`
    FOREIGN KEY (`tag_id` )
    REFERENCES `cwcraigo_intro2techdb`.`tag` (`tag_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `video_tag_created_by`
    FOREIGN KEY (`created_by` )
    REFERENCES `cwcraigo_intro2techdb`.`user` (`user_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `video_tag_last_updated_by`
    FOREIGN KEY (`last_updated_by` )
    REFERENCES `cwcraigo_intro2techdb`.`user` (`user_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
