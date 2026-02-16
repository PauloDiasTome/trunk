SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

CREATE TABLE IF NOT EXISTS `banco`.`block_list` (
  `id_block_list` INT(11) NOT NULL AUTO_INCREMENT,
  `id_user` INT(11) NOT NULL,
  `id_contact` INT(11) NOT NULL,
  `creation` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id_block_list`),
  INDEX `fk_block_list_user1_idx` (`id_user` ASC),
  INDEX `fk_block_list_contact1_idx` (`id_contact` ASC) ,
  CONSTRAINT `fk_block_list_user1`
    FOREIGN KEY (`id_user`)
    REFERENCES `banco`.`user` (`id_user`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`bot` (
  `id_bot` INT(11) NOT NULL AUTO_INCREMENT,
  `id_bot_question` INT(11) NOT NULL,
  `id_channel` INT(11) NOT NULL,
  `creation` INT(11) NOT NULL,
  `name` TEXT NOT NULL,
  PRIMARY KEY (`id_bot`),
  INDEX `fk_bot_channel1_idx` (`id_channel` ASC) ,
  INDEX `fk_bot_bot_question1_idx` (`id_bot_question` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`bot_failed` (
  `id_bot_failed` INT(11) NOT NULL AUTO_INCREMENT,
  `id_bot` INT(11) NULL DEFAULT NULL,
  `id_bot_question` INT(11) NOT NULL,
  `creation` INT(11) NOT NULL,
  `type` SMALLINT(1) NOT NULL,
  `content` TEXT NOT NULL,
  PRIMARY KEY (`id_bot_failed`),
  INDEX `fk_bot_failed_bot_question1_idx` (`id_bot_question` ASC) ,
  INDEX `fk_bot_failed_bot1_idx` (`id_bot` ASC) ,
  CONSTRAINT `fk_bot_failed_bot_question1`
    FOREIGN KEY (`id_bot_question`)
    REFERENCES `banco`.`bot_question` (`id_bot_question`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`bot_question` (
  `id_bot_question` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `question` TEXT NOT NULL,
  `reply` SMALLINT(1) NOT NULL,
  `end` SMALLINT(1) NOT NULL,
  PRIMARY KEY (`id_bot_question`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`bot_question_type` (
  `id_bot_question_type` INT(11) NOT NULL AUTO_INCREMENT,
  `id_bot_question` INT(11) NOT NULL,
  `creation` INT(11) NOT NULL,
  `type` SMALLINT(1) NOT NULL,
  `json` TEXT NOT NULL,
  PRIMARY KEY (`id_bot_question_type`),
  INDEX `fk_bot_question_type_bot_question1_idx` (`id_bot_question` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`bot_success` (
  `id_bot_success` INT(11) NOT NULL AUTO_INCREMENT,
  `id_bot` INT(11) NULL DEFAULT NULL,
  `id_bot_question` INT(11) NOT NULL,
  `creation` INT(11) NOT NULL,
  `type` SMALLINT(1) NOT NULL,
  `content` TEXT NOT NULL,
  PRIMARY KEY (`id_bot_success`),
  INDEX `fk_bot_success_bot_question1_idx` (`id_bot_question` ASC) ,
  INDEX `fk_bot_success_bot1_idx` (`id_bot` ASC) ,
  CONSTRAINT `fk_bot_success_bot_question1`
    FOREIGN KEY (`id_bot_question`)
    REFERENCES `banco`.`bot_question` (`id_bot_question`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`broadcast` (
  `id_broadcast` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `token` TEXT NOT NULL,
  `name` TEXT NOT NULL,
  `count` SMALLINT(3) NOT NULL,
  `participants` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id_broadcast`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`broadcast_participants` (
  `id_broadcast_participant` INT(11) NOT NULL AUTO_INCREMENT,
  `broadcast` TEXT NOT NULL,
  `key_remote_id` TEXT NOT NULL,
  PRIMARY KEY (`id_broadcast_participant`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`broadcast_schedule` (
  `id_broadcast_schedule` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `id_channel` INT(11) NOT NULL,
  `title` TEXT NOT NULL,
  `token` TEXT NOT NULL,
  `schedule` INT(11) NOT NULL,
  `media_type` SMALLINT(1) NOT NULL,
  `data` TEXT NULL DEFAULT NULL,
  `media_url` TEXT NULL DEFAULT NULL,
  `media_caption` TEXT NULL DEFAULT NULL,
  `media_size` INT(11) NULL DEFAULT NULL,
  `media_duration` INT(11) NULL DEFAULT NULL,
  `thumb_image` BLOB NULL DEFAULT NULL,
  `status` SMALLINT(1) NOT NULL,
  `start` INT(11) NULL DEFAULT NULL,
  `finished` INT(11) NULL DEFAULT NULL,
  `expire` INT(11) NOT NULL,
  `participants` LONGTEXT NOT NULL,
  `count` INT(11) NOT NULL,
  `groups` TEXT NULL DEFAULT NULL,
  `message_send` INT(11) NULL DEFAULT NULL,
  `message_receipt` INT(11) NULL DEFAULT NULL,
  `message_read` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id_broadcast_schedule`),
  INDEX `fk_broadcast_schedule_channel1_idx` (`id_channel` ASC) ,
  CONSTRAINT `fk_broadcast_schedule_channel1`
    FOREIGN KEY (`id_channel`)
    REFERENCES `banco`.`channel` (`id_channel`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`broadcast_send` (
  `id_broadcast_send` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `schedule` INT(11) NOT NULL,
  `id_channel` INT(11) NOT NULL,
  `token` TEXT NOT NULL,
  `key_id` TEXT NOT NULL,
  `key_remote_id` TEXT NOT NULL,
  `data` TEXT NULL DEFAULT NULL,
  `media_type` SMALLINT(1) NOT NULL,
  `media_caption` INT(11) NULL DEFAULT NULL,
  `media_duration` INT(11) NULL DEFAULT NULL,
  `media_title` TEXT NULL DEFAULT NULL,
  `media_size` INT(11) NULL DEFAULT NULL,
  `media_url` TEXT NULL DEFAULT NULL,
  `longitude` TEXT NULL DEFAULT NULL,
  `latitude` TEXT NULL DEFAULT NULL,
  `thumb_image` BLOB NULL DEFAULT NULL,
  `status` SMALLINT(1) NOT NULL,
  `send_timestamp` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id_broadcast_send`),
  INDEX `fk_broadcast_send_channel1_idx` (`id_channel` ASC) ,
  CONSTRAINT `fk_broadcast_send_channel1`
    FOREIGN KEY (`id_channel`)
    REFERENCES `banco`.`channel` (`id_channel`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`channel` (
  `id_channel` INT(11) NOT NULL AUTO_INCREMENT,
  `name` TEXT NOT NULL,
  `id_user_group` INT(11) NULL DEFAULT NULL,
  `id_work_time` INT(11) NULL DEFAULT NULL,
  `type` SMALLINT(1) NOT NULL,
  `id` TEXT NOT NULL,
  `pw` TEXT NULL DEFAULT NULL,
  `status` SMALLINT(1) NOT NULL DEFAULT '1',
  `t` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id_channel`),
  INDEX `fk_channel_user_group1_idx` (`id_user_group` ASC) ,
  INDEX `fk_work_time_idx` (`id_work_time` ASC) ,
  CONSTRAINT `fk_work_time`
    FOREIGN KEY (`id_work_time`)
    REFERENCES `banco`.`work_time` (`id_work_time`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`channel_event` (
  `id_channel_event` INT(11) NOT NULL,
  `id_channel` INT(11) NOT NULL,
  `id_bot` INT(11) NULL DEFAULT NULL,
  `creation` INT(11) NOT NULL,
  `type` SMALLINT(1) NOT NULL,
  `tag` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id_channel_event`),
  INDEX `fk_channel_event_channel1_idx` (`id_channel` ASC) ,
  INDEX `fk_channel_event_bot1_idx` (`id_bot` ASC) ,
  CONSTRAINT `fk_channel_event_channel1`
    FOREIGN KEY (`id_channel`)
    REFERENCES `banco`.`channel` (`id_channel`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`channel_location` (
  `id_channel_location` INT(11) NOT NULL AUTO_INCREMENT,
  `id_channel` INT(11) NOT NULL,
  `creation` INT(11) NOT NULL,
  `display_name` TEXT NOT NULL,
  `longitude` TEXT NOT NULL,
  `latitude` TEXT NOT NULL,
  `contact` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id_channel_location`),
  INDEX `fk_channel_location_channel1_idx` (`id_channel` ASC) ,
  CONSTRAINT `fk_channel_location_channel1`
    FOREIGN KEY (`id_channel`)
    REFERENCES `banco`.`channel` (`id_channel`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`chat_form` (
  `id_chat_form` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `title` TEXT NOT NULL,
  `color` INT(11) NOT NULL,
  PRIMARY KEY (`id_chat_form`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`chat_form_fields` (
  `id_chat_form_fields` INT(11) NOT NULL,
  `id_chat_form` INT(11) NOT NULL,
  `creation` INT(11) NOT NULL,
  `name` TEXT NOT NULL,
  `type` SMALLINT(1) NOT NULL,
  PRIMARY KEY (`id_chat_form_fields`),
  INDEX `fk_chat_form_fields_chat_form1_idx` (`id_chat_form` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`chat_list` (
  `id_chat_list` INT(11) NOT NULL AUTO_INCREMENT,
  `id_channel` INT(11) NOT NULL,
  `id_label` INT(11) NULL DEFAULT NULL,
  `id_contact` INT(11) NOT NULL,
  `key_remote_id` TEXT NULL DEFAULT NULL,
  `creation` INT(11) NOT NULL,
  `is_chat` SMALLINT(1) NOT NULL DEFAULT '1',
  `is_broadcast` SMALLINT(1) NULL DEFAULT '1',
  `is_group` SMALLINT(1) NULL DEFAULT '1',
  `is_private` SMALLINT(1) NULL DEFAULT '1',
  `is_wait` SMALLINT(1) NULL DEFAULT '1',
  `ignore` SMALLINT(1) NULL DEFAULT '1',
  `is_close` SMALLINT(1) NULL DEFAULT '1',
  `spam` SMALLINT(1) NULL DEFAULT '1',
  `deleted` SMALLINT(1) NULL DEFAULT '1',
  `user_notify_spam` INT(11) NULL DEFAULT NULL,
  `user_ignore` TEXT NULL DEFAULT NULL,
  `user_deleted` INT(11) NULL DEFAULT NULL,
  `short_timestamp` INT(11) NULL DEFAULT NULL,
  `message_no_read` INT(11) NULL DEFAULT '0',
  `last_message_table_id` INT(11) NULL DEFAULT NULL,
  `last_welcome_timestamp` INT(11) NULL DEFAULT NULL,
  `last_office_hours_end` INT(11) NULL DEFAULT NULL,
  `labels` TEXT NULL DEFAULT NULL,
  `last_attendence` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id_chat_list`),
  INDEX `fk_chat_list_channel1_idx` (`id_channel` ASC) ,
  INDEX `fk_chat_list_contact1_idx` (`id_contact` ASC) ,
  INDEX `fk_chat_list_labels1_idx` (`id_label` ASC) ,
  CONSTRAINT `fk_chat_list_channel1`
    FOREIGN KEY (`id_channel`)
    REFERENCES `banco`.`channel` (`id_channel`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_chat_list_labels1`
    FOREIGN KEY (`id_label`)
    REFERENCES `banco`.`label` (`id_label`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`chat_list_label` (
  `id_chat_list_label` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `id_chat_list` INT(11) NOT NULL,
  `id_label` INT(11) NOT NULL,
  PRIMARY KEY (`id_chat_list_label`),
  INDEX `fk_chat_list_label_chat_list1_idx` (`id_chat_list` ASC) ,
  INDEX `fk_chat_list_label_label1_idx` (`id_label` ASC) ,
  CONSTRAINT `fk_chat_list_label_chat_list1`
    FOREIGN KEY (`id_chat_list`)
    REFERENCES `banco`.`chat_list` (`id_chat_list`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_chat_list_label_label1`
    FOREIGN KEY (`id_label`)
    REFERENCES `banco`.`label` (`id_label`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`chat_list_log` (
  `id_chat_list_log` INT(11) NOT NULL AUTO_INCREMENT,
  `id_chat_list` INT(11) NOT NULL,
  `creation` INT(11) NOT NULL,
  `key_remote_id` TEXT NOT NULL,
  `start` INT(11) NOT NULL,
  `end` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id_chat_list_log`),
  INDEX `fk_chat_list_log_chat_list1_idx` (`id_chat_list` ASC) ,
  CONSTRAINT `fk_chat_list_log_chat_list1`
    FOREIGN KEY (`id_chat_list`)
    REFERENCES `banco`.`chat_list` (`id_chat_list`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`chatbot` (
  `id_chatbot` INT(11) NOT NULL AUTO_INCREMENT,
  `id_user_group` INT(11) NOT NULL,
  `option` TEXT NOT NULL,
  `description` TEXT NOT NULL,
  PRIMARY KEY (`id_chatbot`),
  INDEX `fk_chatbot_user_group1_idx` (`id_user_group` ASC) ,
  CONSTRAINT `fk_chatbot_user_group1`
    FOREIGN KEY (`id_user_group`)
    REFERENCES `banco`.`user_group` (`id_user_group`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`city` (
  `id_city` INT(11) NOT NULL AUTO_INCREMENT,
  `id_region` INT(11) NOT NULL,
  `city` TEXT NOT NULL,
  PRIMARY KEY (`id_city`),
  INDEX `fk_city_region1_idx` (`id_region` ASC) ,
  CONSTRAINT `fk_city_region1`
    FOREIGN KEY (`id_region`)
    REFERENCES `banco`.`region` (`id_region`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`company` (
  `id_company` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NULL DEFAULT NULL,
  `name` VARCHAR(100) NULL DEFAULT NULL,
  `cnpj` VARCHAR(18) NULL DEFAULT NULL,
  `profile_picture` INT(1) NULL DEFAULT '1',
  `contact_number` INT(1) NULL DEFAULT '1',
  PRIMARY KEY (`id_company`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1
COMMENT = 'tabela de informações e configurações da empresa';

CREATE TABLE IF NOT EXISTS `banco`.`config` (
  `id_config` INT(11) NOT NULL AUTO_INCREMENT,
  `id_channel` INT(11) NOT NULL,
  `timezone` TEXT CHARACTER SET 'utf8' NOT NULL,
  `welcome` TEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
  `attendance_enable` SMALLINT(1) NOT NULL DEFAULT '2',
  `office_hours_end` TEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
  `chatbot_enable` SMALLINT(1) NOT NULL DEFAULT '2',
  `transfer_message` TEXT NULL DEFAULT NULL,
  `automatic_transfer` SMALLINT(1) NOT NULL DEFAULT '2',
  `automatic_transfer_minute` SMALLINT(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_config`),
  INDEX `fk_config_channel1_idx` (`id_channel` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8mb4;

CREATE TABLE IF NOT EXISTS `banco`.`contact` (
  `id_contact` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `id_channel` INT(11) NULL DEFAULT NULL,
  `key_remote_id` TEXT NOT NULL,
  `id_contact_tag` INT(11) NULL DEFAULT NULL,
  `user_key_remote_id` TEXT NULL DEFAULT NULL,
  `id_user_group` INT(11) NULL DEFAULT NULL,
  `full_name` TEXT NULL DEFAULT NULL,
  `deleted` SMALLINT(1) NOT NULL DEFAULT '1',
  `spam` SMALLINT(1) NOT NULL DEFAULT '1',
  `sex` SMALLINT(1) NULL DEFAULT '1',
  `email` TEXT NULL DEFAULT NULL,
  `note` TEXT NULL DEFAULT NULL,
  `verify` SMALLINT(1) NULL DEFAULT '1',
  `exist` SMALLINT(1) NULL DEFAULT '2',
  `is_private` SMALLINT(1) NULL DEFAULT '1',
  `is_group` SMALLINT(1) NULL DEFAULT NULL,
  `presence` TEXT NULL DEFAULT NULL,
  `timestamp` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id_contact`),
  INDEX `fk_contact_contact_tag1_idx` (`id_contact_tag` ASC) ,
  INDEX `fk_contact_channel1_idx` (`id_channel` ASC) ,
  INDEX `fk_contact_user_group1_idx` (`id_user_group` ASC) ,
  CONSTRAINT `fk_contact_channel1`
    FOREIGN KEY (`id_channel`)
    REFERENCES `banco`.`channel` (`id_channel`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_contact_user_group1`
    FOREIGN KEY (`id_user_group`)
    REFERENCES `banco`.`user_group` (`id_user_group`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`contact_group` (
  `id_contact_group` INT(11) NOT NULL AUTO_INCREMENT,
  `id_group_contact` INT(11) NOT NULL,
  `id_contact` INT(11) NOT NULL,
  `creation` INT(11) NOT NULL,
  PRIMARY KEY (`id_contact_group`),
  INDEX `fk_contact_group_contact1_idx` (`id_contact` ASC) ,
  INDEX `fk_contact_group_group_contact1_idx` (`id_group_contact` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`contact_interests` (
  `id_contact_interest` INT(11) NOT NULL,
  `creation` INT(11) NOT NULL,
  `id_interest` INT(11) NOT NULL,
  `id_contact` INT(11) NOT NULL,
  PRIMARY KEY (`id_contact_interest`),
  INDEX `fk_contact_interests_interests1_idx` (`id_interest` ASC) ,
  INDEX `fk_contact_interests_contact1_idx` (`id_contact` ASC) ,
  CONSTRAINT `fk_contact_interests_interests1`
    FOREIGN KEY (`id_interest`)
    REFERENCES `banco`.`interests` (`id_interest`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`contact_note` (
  `id_contact_note` INT(11) NOT NULL AUTO_INCREMENT,
  `id_contact` INT(11) NOT NULL,
  `creation` SMALLINT(1) NOT NULL,
  `text` TEXT NOT NULL,
  PRIMARY KEY (`id_contact_note`),
  INDEX `fk_note_contact1_idx` (`id_contact` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`contact_tag` (
  `id_contact_tag` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `id_tag` INT(11) NOT NULL,
  `id_contact` INT(11) NOT NULL,
  PRIMARY KEY (`id_contact_tag`),
  INDEX `fk_contact_tag_tag1_idx` (`id_tag` ASC) ,
  INDEX `fk_contact_tag_contact1_idx` (`id_contact` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`country` (
  `id_country` INT(11) NOT NULL AUTO_INCREMENT,
  `country` TEXT NOT NULL,
  PRIMARY KEY (`id_country`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`group_contact` (
  `id_group_contact` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `name` TEXT NOT NULL,
  `tags` TEXT NULL DEFAULT NULL,
  `opt_in` TEXT NULL DEFAULT NULL,
  `participants` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id_group_contact`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`group_participants` (
  `id_group_participant` INT(11) NOT NULL AUTO_INCREMENT,
  `key_id` TEXT NULL DEFAULT NULL,
  `id` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id_group_participant`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`interests` (
  `id_interest` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `name` TEXT NOT NULL,
  `deleted` SMALLINT(1) NOT NULL,
  PRIMARY KEY (`id_interest`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`label` (
  `id_label` INT(11) NOT NULL AUTO_INCREMENT,
  `name` TEXT NOT NULL,
  `color` TEXT NOT NULL,
  PRIMARY KEY (`id_label`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`messages` (
  `id_message` INT(11) NOT NULL AUTO_INCREMENT,
  `id_chat_list` INT(11) NOT NULL,
  `creation` INT(11) NOT NULL,
  `key_id` TEXT CHARACTER SET 'utf8' NOT NULL,
  `key_from_me` SMALLINT(1) NULL DEFAULT NULL,
  `key_remote_id` TEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
  `need_push` SMALLINT(1) NULL DEFAULT NULL,
  `data` TEXT NULL DEFAULT NULL,
  `status` SMALLINT(1) NULL DEFAULT NULL,
  `media_type` SMALLINT(2) NULL DEFAULT NULL,
  `media_url` TEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
  `media_mime_type` TEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
  `media_size` INT(11) NULL DEFAULT NULL,
  `media_name` TEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
  `media_caption` TEXT NULL DEFAULT NULL,
  `media_hash` TEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
  `media_duration` INT(11) NULL DEFAULT NULL,
  `latitude` TEXT NULL DEFAULT NULL,
  `longitude` TEXT NULL DEFAULT NULL,
  `thumb_image` LONGBLOB NULL DEFAULT NULL,
  `send_timestamp` INT(11) NULL DEFAULT NULL,
  `receipt_server_timestamp` INT(11) NULL DEFAULT NULL,
  `read_device_timestamp` INT(11) NULL DEFAULT NULL,
  `played_device_timestamp` INT(11) NULL DEFAULT NULL,
  `quoted_row_id` TEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
  `participant` TEXT NULL DEFAULT NULL,
  `file_name` TEXT NULL DEFAULT NULL,
  `title` TEXT NULL DEFAULT NULL,
  `page_count` INT(11) NULL DEFAULT '0',
  PRIMARY KEY (`id_message`),
  INDEX `fk_messages_chat_list1_idx` (`id_chat_list` ASC) ,
  CONSTRAINT `fk_messages_chat_list1`
    FOREIGN KEY (`id_chat_list`)
    REFERENCES `banco`.`chat_list` (`id_chat_list`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8mb4;

CREATE TABLE IF NOT EXISTS `banco`.`permission` (
  `id_permission` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `name` VARCHAR(100) NULL DEFAULT 'Administrador',
  `permission` SMALLINT(1) NULL DEFAULT '1',
  `usergroup` SMALLINT(1) NULL DEFAULT '1',
  `contact` SMALLINT(1) NULL DEFAULT '1',
  `label` SMALLINT(1) NULL DEFAULT '1',
  `replies` SMALLINT(1) NULL DEFAULT '1',
  `user` SMALLINT(1) NULL DEFAULT '1',
  `messenger` SMALLINT(1) NULL DEFAULT '1',
  `usercall` SMALLINT(1) NULL DEFAULT '1',
  `broadcast` SMALLINT(1) NULL DEFAULT '1',
  `report` SMALLINT(1) NULL DEFAULT '1',
  `config` SMALLINT(1) NULL DEFAULT '1',
  `adm` SMALLINT(1) NULL DEFAULT '1',
  `report_call` SMALLINT(1) NULL DEFAULT '1',
  `kanban` SMALLINT(1) NULL DEFAULT '1',
  `work_time` SMALLINT(1) NULL DEFAULT '1',
  `ip_list` LONGTEXT NULL DEFAULT NULL,
  `shortlink` SMALLINT(1) NULL DEFAULT NULL,
  `calendar` SMALLINT(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id_permission`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`push` (
  `id_push` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NULL DEFAULT NULL,
  `key_id` TEXT NULL DEFAULT NULL,
  `key_remote_id` TEXT NULL DEFAULT NULL,
  `title` TEXT NULL DEFAULT NULL,
  `message` TEXT NULL DEFAULT NULL,
  `status` SMALLINT(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id_push`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`push_schedule` (
  `id_push_schedule` INT(11) NOT NULL,
  `id_user` INT(11) NOT NULL,
  `creation` INT(11) NOT NULL,
  `title` TEXT NOT NULL,
  `message` TEXT NOT NULL,
  `schedule` INT(11) NOT NULL,
  `status` SMALLINT(1) NOT NULL,
  PRIMARY KEY (`id_push_schedule`),
  INDEX `fk_push_schedule_user1_idx` (`id_user` ASC) ,
  CONSTRAINT `fk_push_schedule_user1`
    FOREIGN KEY (`id_user`)
    REFERENCES `banco`.`user` (`id_user`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`quick_replies` (
  `id_quick_replies` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` VARCHAR(45) NOT NULL,
  `tag` TEXT NOT NULL,
  `key_remote_id` TEXT NULL DEFAULT NULL,
  `title` TEXT NOT NULL,
  `content` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id_quick_replies`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`region` (
  `id_region` INT(11) NOT NULL AUTO_INCREMENT,
  `id_country` INT(11) NOT NULL,
  `region` TEXT NOT NULL,
  PRIMARY KEY (`id_region`),
  INDEX `fk_region_country1_idx` (`id_country` ASC) ,
  CONSTRAINT `fk_region_country1`
    FOREIGN KEY (`id_country`)
    REFERENCES `banco`.`country` (`id_country`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`schedule` (
  `id_schedule` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `schedule` INT(11) NOT NULL,
  `title` TEXT NOT NULL,
  `text` TEXT NOT NULL,
  `status` SMALLINT(1) NOT NULL,
  PRIMARY KEY (`id_schedule`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`score` (
  `id_score` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `key_id` TEXT NOT NULL,
  `liked` INT(11) NULL DEFAULT '1',
  `not_liked` INT(11) NULL DEFAULT '1',
  PRIMARY KEY (`id_score`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`short_link` (
  `id_short_link` INT(11) NOT NULL AUTO_INCREMENT,
  `id_user` INT(11) NOT NULL,
  `creation` INT(11) NOT NULL,
  `message` TEXT NOT NULL,
  `name` TEXT NOT NULL,
  `phone` TEXT NOT NULL,
  `link` TEXT NOT NULL,
  `media_url` TEXT NULL DEFAULT NULL,
  `title` TEXT NULL DEFAULT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `status` SMALLINT(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_short_link`),
  INDEX `fk_short_link_user1_idx` (`id_user` ASC) ,
  CONSTRAINT `fk_short_link_user1`
    FOREIGN KEY (`id_user`)
    REFERENCES `banco`.`user` (`id_user`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`short_link_log` (
  `id_short_link_log` INT(11) NOT NULL AUTO_INCREMENT,
  `id_short_link` INT(11) NOT NULL,
  `creation` INT(11) NOT NULL,
  `country` TEXT NOT NULL,
  `region` TEXT NOT NULL,
  `city` TEXT NOT NULL,
  `agent` TEXT NOT NULL,
  `browser_version` TEXT NOT NULL,
  `is_mobile` SMALLINT(1) NOT NULL,
  `device_version` TEXT NOT NULL,
  `ip` TEXT NOT NULL,
  `hostname` TEXT NOT NULL,
  `timezone` TEXT NOT NULL,
  `org` TEXT NOT NULL,
  `is_facebook` SMALLINT(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id_short_link_log`),
  INDEX `fk_short_link_log_short_link1_idx` (`id_short_link` ASC) ,
  CONSTRAINT `fk_short_link_log_short_link1`
    FOREIGN KEY (`id_short_link`)
    REFERENCES `banco`.`short_link` (`id_short_link`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`ticket` (
  `id_ticket` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `id_contact` INT(11) NOT NULL,
  `id_ticket_type` INT(11) NOT NULL,
  `id_user` INT(11) NOT NULL,
  `id_ticket_status` INT(11) NOT NULL,
  `comment` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id_ticket`),
  INDEX `fk_ticket_ticket_type1_idx` (`id_ticket_type` ASC) ,
  INDEX `fk_ticket_contact1_idx` (`id_contact` ASC) ,
  INDEX `fk_ticket_user1_idx` (`id_user` ASC) ,
  INDEX `fk_ticket_ticket_status1_idx` (`id_ticket_status` ASC) ,
  CONSTRAINT `fk_ticket_contact1`
    FOREIGN KEY (`id_contact`)
    REFERENCES `banco`.`contact` (`id_contact`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_ticket_status1`
    FOREIGN KEY (`id_ticket_status`)
    REFERENCES `banco`.`ticket_status` (`id_ticket_status`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_ticket_type1`
    FOREIGN KEY (`id_ticket_type`)
    REFERENCES `banco`.`ticket_type` (`id_ticket_type`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_user1`
    FOREIGN KEY (`id_user`)
    REFERENCES `banco`.`user` (`id_user`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`ticket_log` (
  `id_ticket_log` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NULL DEFAULT NULL,
  `id_ticket` INT(11) NOT NULL,
  `id_user` INT(11) NOT NULL,
  `id_ticket_type` INT(11) NOT NULL,
  `id_ticket_status` INT(11) NOT NULL,
  `comment` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id_ticket_log`),
  INDEX `fk_ticket_log_ticket1_idx` (`id_ticket` ASC) ,
  INDEX `fk_ticket_log_ticket_type1_idx` (`id_ticket_type` ASC) ,
  INDEX `fk_ticket_log_user1_idx` (`id_user` ASC) ,
  INDEX `fk_ticket_log_ticket_status1_idx` (`id_ticket_status` ASC) ,
  CONSTRAINT `fk_ticket_log_ticket1`
    FOREIGN KEY (`id_ticket`)
    REFERENCES `banco`.`ticket` (`id_ticket`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_log_ticket_status1`
    FOREIGN KEY (`id_ticket_status`)
    REFERENCES `banco`.`ticket_status` (`id_ticket_status`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_log_ticket_type1`
    FOREIGN KEY (`id_ticket_type`)
    REFERENCES `banco`.`ticket_type` (`id_ticket_type`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_log_user1`
    FOREIGN KEY (`id_user`)
    REFERENCES `banco`.`user` (`id_user`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`ticket_status` (
  `id_ticket_status` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `name` TEXT NOT NULL,
  `is_open` SMALLINT(1) NOT NULL,
  `color` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id_ticket_status`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`ticket_type` (
  `id_ticket_type` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `name` TEXT NOT NULL,
  `status` SMALLINT(1) NOT NULL DEFAULT '1',
  `color` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id_ticket_type`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`user` (
  `id_user` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` VARCHAR(45) NOT NULL,
  `id_permission` INT(11) NOT NULL,
  `id_user_call` INT(11) NULL DEFAULT NULL,
  `id_work_time` INT(11) NULL DEFAULT NULL,
  `id_user_group` INT(11) NULL DEFAULT NULL,
  `key_remote_id` TEXT NOT NULL,
  `name` TEXT NOT NULL,
  `last_name` TEXT NOT NULL,
  `email` TEXT NOT NULL,
  `password` TEXT NOT NULL,
  `profile_picture` TEXT NULL DEFAULT NULL,
  `visible` SMALLINT(1) NOT NULL,
  `status` SMALLINT(1) NOT NULL,
  `2fa` SMALLINT(1) NULL DEFAULT '0',
  `phone` TEXT NULL DEFAULT NULL,
  `visible_widget` SMALLINT(1) NULL DEFAULT '2',
  PRIMARY KEY (`id_user`),
  INDEX `fk_user_user_calls1_idx` (`id_user_call` ASC) ,
  INDEX `fk_user_work_time1_idx` (`id_work_time` ASC) ,
  INDEX `fk_user_user_group1_idx` (`id_user_group` ASC) ,
  INDEX `fk_user_permission1_idx` (`id_permission` ASC) ,
  CONSTRAINT `fk_user_permission`
    FOREIGN KEY (`id_permission`)
    REFERENCES `banco`.`permission` (`id_permission`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_user_calls`
    FOREIGN KEY (`id_user_call`)
    REFERENCES `banco`.`user_calls` (`id_user_call`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_user_group`
    FOREIGN KEY (`id_user_group`)
    REFERENCES `banco`.`user_group` (`id_user_group`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_work_time`
    FOREIGN KEY (`id_work_time`)
    REFERENCES `banco`.`work_time` (`id_work_time`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`user_2fa` (
  `id_user_2fa` INT(11) NOT NULL AUTO_INCREMENT,
  `code` TEXT NULL DEFAULT NULL,
  `retry` INT(11) NULL DEFAULT '1',
  `user_key_remote_id` TEXT NULL DEFAULT NULL,
  `creation` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `expire` DATETIME NULL DEFAULT NULL,
  `email_send` TINYINT(4) NULL DEFAULT '0',
  `wa_send` TINYINT(4) NULL DEFAULT '0',
  `sms_send` TINYINT(4) NULL DEFAULT '0',
  `is_validated` TINYINT(4) NULL DEFAULT '0',
  `is_expired` TINYINT(4) NULL DEFAULT '0',
  PRIMARY KEY (`id_user_2fa`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`user_calls` (
  `id_user_call` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `name` TEXT NOT NULL,
  `limit` INT(11) NOT NULL,
  PRIMARY KEY (`id_user_call`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`user_group` (
  `id_user_group` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `name` TEXT NOT NULL,
  PRIMARY KEY (`id_user_group`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`user_log` (
  `id_user_log` INT(11) NOT NULL AUTO_INCREMENT,
  `id_user` INT(11) NOT NULL,
  `creation` INT(11) NOT NULL,
  `text` TEXT NOT NULL,
  `system` TEXT NULL DEFAULT NULL,
  `agent` TEXT NULL DEFAULT NULL,
  `version` TEXT NULL DEFAULT NULL,
  `ip` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id_user_log`),
  INDEX `fk_user_log_user1_idx` (`id_user` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`user_reset_password` (
  `id_user_reset_password` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NULL DEFAULT NULL,
  `expire` INT(11) NULL DEFAULT NULL,
  `token` TEXT NOT NULL,
  `origin_key_remote_id` TEXT NOT NULL,
  `key_remote_id` TEXT NOT NULL,
  `status` SMALLINT(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_user_reset_password`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`user_timestamp` (
  `id_user_timestamp` INT(11) NOT NULL AUTO_INCREMENT,
  `id_user` INT(11) NOT NULL,
  `short_timestamp` INT(11) NULL DEFAULT NULL,
  `start` INT(11) NOT NULL,
  `end` INT(11) NULL DEFAULT NULL,
  `timediff` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id_user_timestamp`),
  INDEX `fk_user_timestamp_user1_idx` (`id_user` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`user_token` (
  `id_user_token` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` VARCHAR(45) NULL DEFAULT NULL,
  `id_user` INT(11) NOT NULL,
  `token` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id_user_token`),
  INDEX `fk_user_token_user1_idx` (`id_user` ASC) ,
  CONSTRAINT `fk_user_token_user1`
    FOREIGN KEY (`id_user`)
    REFERENCES `banco`.`user` (`id_user`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`visitor` (
  `id_visitor` INT(11) NOT NULL AUTO_INCREMENT,
  `id_contact` INT(11) NOT NULL,
  `creation` INT(11) NOT NULL,
  `ip` TEXT NOT NULL,
  `hostname` TEXT NOT NULL,
  `city` TEXT NOT NULL,
  `region` TEXT NOT NULL,
  `country` TEXT NOT NULL,
  `org` TEXT NOT NULL,
  `timezone` TEXT NOT NULL,
  `page` TEXT NOT NULL,
  `t` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id_visitor`),
  INDEX `fk_visitor_contact1_idx` (`id_contact` ASC) ,
  CONSTRAINT `fk_visitor_contact1`
    FOREIGN KEY (`id_contact`)
    REFERENCES `banco`.`contact` (`id_contact`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`wait_list` (
  `id_wait_list` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `key_remote_id` TEXT NOT NULL,
  `id_user_group` INT(11) NULL DEFAULT NULL,
  `user_key_remote_id` TEXT NULL DEFAULT NULL,
  `timestamp_send_user` INT(11) NULL DEFAULT NULL,
  `status` SMALLINT(1) NOT NULL,
  PRIMARY KEY (`id_wait_list`),
  INDEX `fk_wait_list_user_group1_idx` (`id_user_group` ASC) ,
  CONSTRAINT `fk_wait_list_user_group1`
    FOREIGN KEY (`id_user_group`)
    REFERENCES `banco`.`user_group` (`id_user_group`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`work_time` (
  `id_work_time` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `name` TEXT NOT NULL,
  PRIMARY KEY (`id_work_time`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `banco`.`work_time_week` (
  `id_work_time_week` INT(11) NOT NULL AUTO_INCREMENT,
  `id_work_time` INT(11) NOT NULL,
  `week` SMALLINT(1) NOT NULL,
  `start` TIME NOT NULL,
  `end` TIME NOT NULL,
  PRIMARY KEY (`id_work_time_week`),
  INDEX `fk_work_time_week_work_time1_idx` (`id_work_time` ASC) ,
  CONSTRAINT `fk_work_time_week_work_time1`
    FOREIGN KEY (`id_work_time`)
    REFERENCES `banco`.`work_time` (`id_work_time`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

INSERT INTO `banco`.`permission`(`creation`,`name`) VALUES(UNIX_TIMESTAMP(),"Administrador");
INSERT INTO `banco`.`user_group`(`creation`,`name`) VALUES(UNIX_TIMESTAMP(),"Comercial");
INSERT INTO `banco`.`user_calls`(`creation`,`name`,`limit`) VALUES(UNIX_TIMESTAMP(),"Comercial",10);

INSERT INTO `banco`.`work_time`(`creation`,`name`) VALUES(UNIX_TIMESTAMP(),"Horário comercial");
INSERT INTO `banco`.`work_time_week`(`id_work_time`,`week`,`start`,`end`) VALUES((select max(`banco`.work_time.id_work_time) from `banco`.work_time),1,'08:00:00','18:00:00');
INSERT INTO `banco`.`work_time_week`(`id_work_time`,`week`,`start`,`end`) VALUES((select max(`banco`.work_time.id_work_time) from `banco`.work_time),2,'08:00:00','18:00:00');
INSERT INTO `banco`.`work_time_week`(`id_work_time`,`week`,`start`,`end`) VALUES((select max(`banco`.work_time.id_work_time) from `banco`.work_time),3,'08:00:00','18:00:00');
INSERT INTO `banco`.`work_time_week`(`id_work_time`,`week`,`start`,`end`) VALUES((select max(`banco`.work_time.id_work_time) from `banco`.work_time),4,'08:00:00','18:00:00');
INSERT INTO `banco`.`work_time_week`(`id_work_time`,`week`,`start`,`end`) VALUES((select max(`banco`.work_time.id_work_time) from `banco`.work_time),5,'08:00:00','18:00:00');
INSERT INTO `banco`.`work_time_week`(`id_work_time`,`week`,`start`,`end`) VALUES((select max(`banco`.work_time.id_work_time) from `banco`.work_time),6,'08:00:00','18:00:00');

INSERT INTO `banco`.`user`(
    `creation`,
    `id_permission`,
    `id_user_call`,
    `id_work_time`,
    `id_user_group`,
    `key_remote_id`,
    `name`,
    `last_name`,
    `email`,
    `password`,
    `visible`,
    `status`
  ) 

  VALUES(
    UNIX_TIMESTAMP(),
    (select max(`banco`.permission.id_permission) from `banco`.permission),
    (select max(`banco`.user_calls.id_user_call) from `banco`.user_calls),
    (select max(`banco`.work_time.id_work_time) from `banco`.work_time),
    (select max(`banco`.user_group.id_user_group) from `banco`.user_group),
    '{KEY_REMOTE_ID}',
    '{NAME}',
    '{LAST_NAME}',
    '{EMAIL}',
    '{PASSWORD}',
    2,
    1
  );

INSERT INTO `banco`.`channel`(`name`,`id`,`type`) VALUES('{NAME}','{KEY_REMOTE_ID}',1); 
INSERT INTO `banco`.`channel`(`name`,`id`,`type`) VALUES('{NAME}','kanban-{KEY_REMOTE_ID}',5);
INSERT INTO `banco`.`channel`(`name`,`id`,`type`) VALUES('{NAME}','qrcode-{KEY_REMOTE_ID}',20);
INSERT INTO `banco`.`user_token`(`creation`,`id_user`,`token`) VALUES(UNIX_TIMESTAMP(),(select max(`banco`.user.id_user) from `banco`.user),'{KEY_REMOTE_ID}'); 
INSERT INTO `banco`.`config`(`id_channel`,`timezone`,`welcome`,`attendance_enable`) VALUES((select max(`banco`.channel.id_channel) from `banco`.channel),'-00:00','',1); 

INSERT INTO `banco`.`contact`(
    `creation`,
    `id_channel`,
    `key_remote_id`,
    `full_name`,
    `deleted`,
    `spam`,
    `sex`,
    `verify`,
    `exist`,
    `is_private`,
    `is_group`,
    `presence`,
    `timestamp`
  ) 

  VALUES(
    UNIX_TIMESTAMP(),
    ( select `banco`.channel.id_channel from `banco`.channel where `banco`.channel.id = '{KEY_REMOTE_ID}'),
    '{KEY_REMOTE_ID}',
    '{LAST_NAME}',
    1,
    1,
    1,
    2,
    1,
    2,
    1,
    'unavailable',
    UNIX_TIMESTAMP()
  );

INSERT INTO `talkall_admin`.`channel`(`id_company`,`creation`,`id`,`type`,`status`,`executed`)
VALUES({ID_COMPANY},UNIX_TIMESTAMP(),'{KEY_REMOTE_ID}',1,1,1); 

INSERT INTO `talkall_admin`.`channel`(`id_company`,`creation`,`id`,`type`,`status`,`executed`)
VALUES({ID_COMPANY},UNIX_TIMESTAMP(),'kanban-{KEY_REMOTE_ID}',5,1,1); 

INSERT INTO `talkall_admin`.`channel`(`id_company`,`creation`,`id`,`type`,`status`,`executed`)
VALUES({ID_COMPANY},UNIX_TIMESTAMP(),'qrcode-{KEY_REMOTE_ID}',20,1,1); 

INSERT INTO `talkall_admin`.`company_module`(`id_company`,`creation`,`type`)
VALUES({ID_COMPANY},UNIX_TIMESTAMP(),'TALKALL_MODULE_ATTENDANCE');

ALTER TABLE `banco`.`chat_list` 
DROP FOREIGN KEY `fk_chat_list_labels1`;

ALTER TABLE `banco`.`chatbot` 
DROP FOREIGN KEY `fk_chatbot_user_group1`;

ALTER TABLE `banco`.`bot_failed` 
DROP COLUMN `type`,
DROP COLUMN `id_bot`,
CHANGE COLUMN `content` `content` TEXT NULL DEFAULT NULL ,
DROP INDEX `fk_bot_failed_bot1_idx`;

ALTER TABLE `banco`.`bot_question` 
DROP COLUMN `end`,
DROP COLUMN `reply`,
ADD COLUMN `name` TEXT NOT NULL AFTER `creation`,
ADD COLUMN `input_type` TEXT NOT NULL AFTER `question`,
ADD COLUMN `input_size` INT(11) NOT NULL AFTER `input_type`,
ADD COLUMN `param_name` TEXT NOT NULL AFTER `input_size`,
ADD COLUMN `callback` TEXT NOT NULL AFTER `param_name`;

CREATE TABLE IF NOT EXISTS `banco`.`bot_step` (
  `id_bot_step` INT(11) NOT NULL AUTO_INCREMENT,
  `id_bot_question` INT(11) NOT NULL,
  `creation` INT(11) NOT NULL,
  `key_remote_id` VARCHAR(100) NOT NULL,
  `param_value` TEXT NOT NULL,
  `status` SMALLINT(1) NOT NULL,
  PRIMARY KEY (`id_bot_step`),
  INDEX `fk_bot_step_bot_question1_idx` (`id_bot_question` ASC) ,
  CONSTRAINT `fk_bot_step_bot_question1`
    FOREIGN KEY (`id_bot_question`)
    REFERENCES `banco`.`bot_question` (`id_bot_question`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;

ALTER TABLE `banco`.`bot_success` 
DROP COLUMN `type`,
DROP COLUMN `id_bot`,
CHANGE COLUMN `content` `content` TEXT NULL DEFAULT NULL ,
DROP INDEX `fk_bot_success_bot1_idx`;

ALTER TABLE `banco`.`broadcast_participants` 
DROP COLUMN `broadcast`,
ADD COLUMN `key_id` VARCHAR(100) NULL DEFAULT NULL AFTER `key_remote_id`,
ADD COLUMN `type` SMALLINT(1) NULL DEFAULT NULL AFTER `key_id`;

ALTER TABLE `banco`.`broadcast_schedule` 
ADD COLUMN `is_wa_status` SMALLINT(1) NULL DEFAULT '2' AFTER `message_read`,
ADD COLUMN `is_wa_broadcast` SMALLINT(1) NULL DEFAULT '2' AFTER `is_wa_status`;

ALTER TABLE `banco`.`broadcast_send` 
CHANGE COLUMN `media_caption` `media_caption` TEXT NULL DEFAULT NULL ;

CREATE TABLE IF NOT EXISTS `banco`.`broadcast_sms_schedule` (
  `id_broadcast_sms_schedule` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `title` TEXT NOT NULL,
  `token` TEXT NOT NULL,
  `schedule` INT(11) NOT NULL,
  `data` VARCHAR(140) NOT NULL,
  `groups` TEXT NULL DEFAULT NULL,
  `participants` LONGTEXT NOT NULL,
  `count` INT(11) NOT NULL,
  `status` SMALLINT(1) NOT NULL,
  PRIMARY KEY (`id_broadcast_sms_schedule`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`product` (
  `id_product` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `code` TEXT NOT NULL,
  `title` TEXT NOT NULL,
  `short_description` TEXT NOT NULL,
  `media_url` TEXT NOT NULL,
  `thumbnail` LONGBLOB NOT NULL,
  `status` SMALLINT(1) NOT NULL,
  `currency` VARCHAR(10) NULL DEFAULT NULL,
  `price` VARCHAR(10) NULL DEFAULT NULL,
  `wa_product_id` TEXT NULL DEFAULT NULL,
  `url` TEXT NULL DEFAULT NULL,
  `is_approved` SMALLINT(1) NULL DEFAULT '2',
  `is_rejected` SMALLINT(1) NULL DEFAULT '1',
  `is_visible` SMALLINT(1) NULL DEFAULT '1',
  PRIMARY KEY (`id_product`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`product_picture` (
  `id_product_picture` INT(11) NOT NULL AUTO_INCREMENT,
  `id_product` INT(11) NOT NULL,
  `creation` INT(11) NOT NULL,
  `order` SMALLINT(1) NOT NULL,
  `media_url` TEXT NOT NULL,
  `media_caption` TEXT NOT NULL,
  `media_size` INT(11) NOT NULL,
  `media_mime_type` TEXT NOT NULL,
  `thumbnail` LONGBLOB NULL DEFAULT NULL,
  PRIMARY KEY (`id_product_picture`),
  INDEX `fk_product_picture_product1_idx` (`id_product` ASC) ,
  CONSTRAINT `fk_product_picture_product1`
    FOREIGN KEY (`id_product`)
    REFERENCES `banco`.`product` (`id_product`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`product_statistic` (
  `id_product_statistic` INT(11) NOT NULL,
  `id_product` INT(11) NOT NULL,
  `creation` INT(11) NOT NULL,
  `key_remote_id` TEXT NOT NULL,
  PRIMARY KEY (`id_product_statistic`),
  INDEX `fk_product_statistic_product1_idx` (`id_product` ASC) ,
  CONSTRAINT `fk_product_statistic_product1`
    FOREIGN KEY (`id_product`)
    REFERENCES `banco`.`product` (`id_product`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`catalog_category` (
  `id_catalog_category` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `name` TEXT NOT NULL,
  PRIMARY KEY (`id_catalog_category`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`catalog` (
  `id_catalog` INT(11) NOT NULL AUTO_INCREMENT,
  `id_channel` INT(11) NOT NULL,
  `id_catalog_category` INT(11) NOT NULL,
  `name` TEXT NOT NULL,
  `creation` INT(11) NOT NULL,
  `mon` SMALLINT(1) NOT NULL,
  `tue` SMALLINT(1) NOT NULL,
  `wed` SMALLINT(1) NOT NULL,
  `thu` SMALLINT(1) NOT NULL,
  `fri` SMALLINT(1) NOT NULL,
  `sat` SMALLINT(1) NOT NULL,
  `sun` SMALLINT(1) NOT NULL,
  `status` SMALLINT(1) NOT NULL,
  PRIMARY KEY (`id_catalog`),
  INDEX `fk_catalog_catalog_category1_idx` (`id_catalog_category` ASC) ,
  INDEX `fk_catalog_channel1_idx` (`id_channel` ASC) ,
  CONSTRAINT `fk_catalog_catalog_category1`
    FOREIGN KEY (`id_catalog_category`)
    REFERENCES `banco`.`catalog_category` (`id_catalog_category`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_catalog_channel1`
    FOREIGN KEY (`id_channel`)
    REFERENCES `banco`.`channel` (`id_channel`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`catalog_product` (
  `id_catalog_product` INT(11) NOT NULL,
  `id_product` INT(11) NOT NULL,
  `id_catalog` INT(11) NOT NULL,
  `creation` INT(11) NOT NULL,
  PRIMARY KEY (`id_catalog_product`),
  INDEX `fk_catalog_product_product1_idx` (`id_product` ASC) ,
  INDEX `fk_catalog_product_catalog1_idx` (`id_catalog` ASC) ,
  CONSTRAINT `fk_catalog_product_catalog1`
    FOREIGN KEY (`id_catalog`)
    REFERENCES `banco`.`catalog` (`id_catalog`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_catalog_product_product1`
    FOREIGN KEY (`id_product`)
    REFERENCES `banco`.`product` (`id_product`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

ALTER TABLE `banco`.`channel` 
ADD COLUMN `quality` SMALLINT(2) NULL DEFAULT '10' AFTER `t`,
ADD COLUMN `credit_template` FLOAT(11) NULL DEFAULT '0' AFTER `quality`,
ADD COLUMN `pricing_template` FLOAT(11) NULL DEFAULT '0.05486' AFTER `credit_template`,
ADD COLUMN `is_broadcast` SMALLINT(1) NULL DEFAULT '2' AFTER `pricing_template`;

ALTER TABLE `banco`.`chat_list` 
DROP COLUMN `id_label`,
ADD COLUMN `last_timestamp_client` INT(11) NULL DEFAULT NULL AFTER `last_attendence`,
CHANGE COLUMN `key_remote_id` `key_remote_id` VARCHAR(100) NULL DEFAULT NULL ,
ADD INDEX `idx_chat_list_id_contact` (`id_contact` ASC) ,
ADD INDEX `idx_chat_list_is_wait` (`is_wait` ASC) ,
ADD INDEX `idx_chat_list_is_close` (`is_close` ASC) ,
ADD INDEX `idx_chat_list_key_remote_id` (`key_remote_id` ASC) ,
DROP INDEX `fk_chat_list_labels1_idx` ;

ALTER TABLE `banco`.`chat_list_label` 
ADD INDEX `idx_chat_list_label` (`id_chat_list` ASC);

ALTER TABLE `banco`.`chat_list_log` 
ADD COLUMN `protocol` TEXT NULL DEFAULT NULL AFTER `end`,
CHANGE COLUMN `key_remote_id` `key_remote_id` VARCHAR(100) NOT NULL ,
ADD INDEX `idx_chat_list_log_end` (`end` ASC) ,
ADD INDEX `idx_chat_list_log_key_remote_id` (`key_remote_id` ASC);

ALTER TABLE `banco`.`chatbot` 
ADD COLUMN `text` TEXT NULL DEFAULT NULL AFTER `id_user_group`,
ADD COLUMN `id_submenu` INT(11) NULL DEFAULT NULL AFTER `description`,
ADD COLUMN `is_menu` SMALLINT(1) NOT NULL DEFAULT '2' AFTER `id_submenu`,
ADD COLUMN `is_primary` SMALLINT(1) NULL DEFAULT '1' AFTER `is_menu`,
ADD COLUMN `webhook` TEXT NULL DEFAULT NULL AFTER `is_primary`,
CHANGE COLUMN `id_user_group` `id_user_group` INT(11) NULL DEFAULT NULL ,
CHANGE COLUMN `option` `option` TEXT CHARACTER SET 'utf8mb4' NOT NULL ,
CHANGE COLUMN `description` `description` TEXT CHARACTER SET 'utf8mb4' NOT NULL ;

ALTER TABLE `banco`.`config` 
ADD COLUMN `interval_broadcast` SMALLINT(2) NULL DEFAULT '10' AFTER `automatic_transfer_minute`,
ADD COLUMN `webhook` TEXT NULL DEFAULT NULL AFTER `interval_broadcast`,
ADD COLUMN `attendance_message_close` TEXT NULL DEFAULT NULL AFTER `webhook`,
ADD COLUMN `message_close_enabled` SMALLINT(1) NULL DEFAULT '2' AFTER `attendance_message_close`,
ADD COLUMN `template_welcome` TEXT NULL DEFAULT NULL AFTER `message_close_enabled`,
ADD COLUMN `template_attendance_message_close` TEXT NULL DEFAULT NULL AFTER `template_welcome`,
ADD COLUMN `template_question_evaluate_service` TEXT NULL DEFAULT NULL AFTER `template_attendance_message_close`,
ADD COLUMN `template_evaluate_service` TEXT NULL DEFAULT NULL AFTER `template_question_evaluate_service`,
ADD COLUMN `namespace` TEXT NULL DEFAULT NULL AFTER `template_evaluate_service`,
ADD COLUMN `template_namespace` TEXT NULL DEFAULT NULL AFTER `namespace`,
ADD COLUMN `enable_protocol` SMALLINT(1) NULL DEFAULT '2' AFTER `template_namespace`,
ADD COLUMN `message_start_attendance` TEXT NULL DEFAULT NULL AFTER `enable_protocol`,
ADD COLUMN `enabled_lgpd_question` SMALLINT(1) NULL DEFAULT '2' AFTER `message_start_attendance`,
ADD COLUMN `template_name_lgpd` TEXT NULL DEFAULT NULL AFTER `enabled_lgpd_question`,
ADD COLUMN `lgpd_question_text` TEXT NULL DEFAULT NULL AFTER `template_name_lgpd`,
ADD COLUMN `template_lgpd_question_no` TEXT NULL DEFAULT NULL AFTER `lgpd_question_text`,
ADD COLUMN `lgpd_not_confirm_message` TEXT NULL DEFAULT NULL AFTER `template_lgpd_question_no`,
ADD COLUMN `order_user_group` INT(11) NULL DEFAULT NULL AFTER `lgpd_not_confirm_message`,
ADD COLUMN `id_order_status` INT(11) NULL DEFAULT NULL AFTER `order_user_group`,
CHANGE COLUMN `welcome` `welcome` TEXT NULL DEFAULT NULL ,
CHANGE COLUMN `office_hours_end` `office_hours_end` TEXT NULL DEFAULT NULL ;

ALTER TABLE `banco`.`contact` 
CHARACTER SET = utf8mb4 , COLLATE = utf8mb4_general_ci ,
ADD COLUMN `id_last_menu_selected` INT(11) NULL DEFAULT NULL AFTER `id_channel`,
ADD COLUMN `crm_profile` TEXT CHARACTER SET 'utf8' NULL DEFAULT NULL AFTER `timestamp`,
ADD COLUMN `crm_timestamp` INT(11) NULL DEFAULT NULL AFTER `crm_profile`,
ADD COLUMN `is_imported` SMALLINT(1) NULL DEFAULT '1' AFTER `crm_timestamp`,
CHANGE COLUMN `key_remote_id` `key_remote_id` VARCHAR(100) CHARACTER SET 'utf8' NOT NULL ,
CHANGE COLUMN `presence` `presence` VARCHAR(12) CHARACTER SET 'utf8' NULL DEFAULT NULL ,
ADD INDEX `idx_contact_key_remote_id` (`key_remote_id` ASC) ,
ADD INDEX `idx_contact_presence` (`presence` ASC) ;

ALTER TABLE `banco`.`contact_tag` 
ADD COLUMN `id_label` TEXT NULL DEFAULT NULL AFTER `id_contact`,
CHANGE COLUMN `creation` `creation` INT(11) NULL DEFAULT NULL ,
CHANGE COLUMN `id_tag` `id_tag` INT(11) NULL DEFAULT NULL ,
CHANGE COLUMN `id_contact` `id_contact` VARCHAR(100) NULL DEFAULT NULL ;

CREATE TABLE IF NOT EXISTS `banco`.`evaluate_service` (
  `id_evaluate_service` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `user_key_remote_id` TEXT NOT NULL,
  `key_remote_id` TEXT NOT NULL,
  `status` SMALLINT(1) NOT NULL,
  `evaluation` SMALLINT(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id_evaluate_service`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`faq` (
  `id_faq` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `key_remote_id` TEXT NOT NULL,
  `title` TEXT NOT NULL,
  `content` TEXT NOT NULL,
  `status` SMALLINT(1) NOT NULL,
  PRIMARY KEY (`id_faq`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`faq_statistic` (
  `id_faq_statistic` INT(11) NOT NULL AUTO_INCREMENT,
  `id_faq` INT(11) NOT NULL,
  `creation` INT(11) NOT NULL,
  `key_remote_id` TEXT NOT NULL,
  `type` SMALLINT(1) NOT NULL,
  `point` INT(11) NOT NULL,
  PRIMARY KEY (`id_faq_statistic`),
  INDEX `fk_faq_statistic_faq1_idx` (`id_faq` ASC) ,
  CONSTRAINT `fk_faq_statistic_faq1`
    FOREIGN KEY (`id_faq`)
    REFERENCES `banco`.`faq` (`id_faq`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`faq_tag` (
  `id_faq_tag` INT(11) NOT NULL AUTO_INCREMENT,
  `id_faq` INT(11) NOT NULL,
  `tag` TEXT NOT NULL,
  PRIMARY KEY (`id_faq_tag`),
  INDEX `fk_faq_tag_faq1_idx` (`id_faq` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;

ALTER TABLE `banco`.`group_participants` 
DROP COLUMN `id`,
DROP COLUMN `key_id`,
ADD COLUMN `id_group` INT(11) NOT NULL AFTER `id_group_participant`,
ADD COLUMN `creation` INT(11) NOT NULL AFTER `id_group`,
ADD COLUMN `key_remote_id` TEXT NOT NULL AFTER `creation`,
ADD INDEX `fk_group_participants_group1_idx` (`id_group` ASC) ;

CREATE TABLE IF NOT EXISTS `banco`.`groups` (
  `id_group` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `key_remote_id` TEXT NOT NULL,
  `participants` TEXT NULL DEFAULT NULL,
  `name` TEXT NOT NULL,
  `STATUS` SMALLINT(1) NULL DEFAULT '1',
  PRIMARY KEY (`id_group`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`job_delete_chat` (
  `id_job_delete_chat` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `key_remote_id` TEXT NOT NULL,
  PRIMARY KEY (`id_job_delete_chat`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`job_revoke_msg` (
  `id_job_revoke_msg` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `key_id` TEXT NOT NULL,
  `key_remote_id` TEXT NOT NULL,
  PRIMARY KEY (`id_job_revoke_msg`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`lgpd_confirm` (
  `id_lgpd_confirm` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `key_remote_id` VARCHAR(100) NOT NULL,
  `status` SMALLINT(1) NOT NULL,
  PRIMARY KEY (`id_lgpd_confirm`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`message_ack` (
  `id_message_ack` INT(11) NOT NULL AUTO_INCREMENT,
  `id_message` INT(11) NOT NULL,
  `creation` INT(11) NULL DEFAULT NULL,
  `token` TEXT NOT NULL,
  PRIMARY KEY (`id_message_ack`),
  INDEX `fk_message_ack_messages1_idx` (`id_message` ASC) ,
  CONSTRAINT `fk_message_ack_messages1`
    FOREIGN KEY (`id_message`)
    REFERENCES `banco`.`messages` (`id_message`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`message_key` (
  `id_message_key` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `key_id` VARCHAR(100) NOT NULL,
  `key_remote_id` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id_message_key`),
  INDEX `idx_message_key_key_id` (`key_id` ASC) ,
  INDEX `idx_message_key_key_remote_id` (`key_remote_id` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`message_quoted` (
  `id_message_quoted` INT(11) NOT NULL AUTO_INCREMENT,
  `id_message` INT(11) NOT NULL,
  `creation` INT(11) NOT NULL,
  `key_id` TEXT NOT NULL,
  PRIMARY KEY (`id_message_quoted`),
  INDEX `fk_message_quoted_messages1_idx` (`id_message` ASC) ,
  CONSTRAINT `fk_message_quoted_messages1`
    FOREIGN KEY (`id_message`)
    REFERENCES `banco`.`messages` (`id_message`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;

ALTER TABLE `banco`.`messages` 
ADD COLUMN `wa_key_id` VARCHAR(100) NULL DEFAULT NULL AFTER `key_id`,
ADD COLUMN `forwarded` SMALLINT(1) NULL DEFAULT '1' AFTER `page_count`,
ADD COLUMN `name` VARCHAR(255) NULL DEFAULT NULL AFTER `forwarded`,
ADD COLUMN `namespace` VARCHAR(255) NULL DEFAULT NULL AFTER `name`,
ADD COLUMN `language` VARCHAR(8) NULL DEFAULT NULL AFTER `namespace`,
ADD COLUMN `policy` VARCHAR(100) NULL DEFAULT NULL AFTER `language`,
ADD COLUMN `json_return` TEXT NULL DEFAULT NULL AFTER `policy`,
CHANGE COLUMN `key_id` `key_id` VARCHAR(100) CHARACTER SET 'utf8' NOT NULL ,
CHANGE COLUMN `key_remote_id` `key_remote_id` VARCHAR(100) CHARACTER SET 'utf8' NULL DEFAULT NULL ,
CHANGE COLUMN `media_type` `media_type` SMALLINT(2) NULL DEFAULT NULL,
CHANGE COLUMN `quoted_row_id` `quoted_row_id` VARCHAR(40) CHARACTER SET 'utf8' NULL DEFAULT NULL ,
ADD INDEX `idx_messages_status` (`status` ASC) ,
ADD INDEX `idx_messages_key_id` (`key_id` ASC) ,
ADD INDEX `idx_messages_media_type` (`media_type` ASC) ,
ADD INDEX `idx_messages_key_from_me` (`key_from_me` ASC) ,
ADD INDEX `idx_quoted_row_id` (`quoted_row_id` ASC) ,
ADD INDEX `idx_messages_key_remote_id` (`key_remote_id` ASC) ,
ADD INDEX `idx_messages_quoted_row_id` (`quoted_row_id` ASC) ;

CREATE TABLE IF NOT EXISTS `banco`.`order_status` (
  `id_order_status` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `name` TEXT CHARACTER SET 'latin1' NOT NULL,
  `color` TEXT CHARACTER SET 'latin1' NOT NULL,
  `message` TEXT NULL DEFAULT NULL,
  `is_close` SMALLINT(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id_order_status`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;

CREATE TABLE IF NOT EXISTS `banco`.`payment_methods` (
  `id_payment_method` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `name` TEXT NOT NULL,
  `status` SMALLINT(1) NOT NULL,
  PRIMARY KEY (`id_payment_method`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`messages_order` (
  `id_messages_order` INT(11) NOT NULL AUTO_INCREMENT,
  `id_order_status` INT(11) NULL DEFAULT NULL,
  `id_payment_method` INT(11) NULL DEFAULT NULL,
  `creation` INT(11) NOT NULL,
  `token` TEXT NOT NULL,
  `order_id` TEXT NOT NULL,
  `seller_jid` TEXT NOT NULL,
  `key_id` VARCHAR(100) NOT NULL,
  `order_title` TEXT NULL DEFAULT NULL,
  `item_count` INT(11) NOT NULL,
  `message` TEXT NULL DEFAULT NULL,
  `subtotal` TEXT NULL DEFAULT NULL,
  `total` TEXT NULL DEFAULT NULL,
  `postal` TEXT NULL DEFAULT NULL,
  `address` TEXT NULL DEFAULT NULL,
  `number` TEXT NULL DEFAULT NULL,
  `district` TEXT NULL DEFAULT NULL,
  `city` TEXT NULL DEFAULT NULL,
  `complement` TEXT NULL DEFAULT NULL,
  `distance` TEXT NULL DEFAULT NULL,
  `distance_time` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id_messages_order`),
  INDEX `fk_messages_order_order_status1_idx` (`id_order_status` ASC) ,
  INDEX `fk_messages_order_payment_methods1_idx` (`id_payment_method` ASC) ,
  CONSTRAINT `fk_messages_order_order_status1`
    FOREIGN KEY (`id_order_status`)
    REFERENCES `banco`.`order_status` (`id_order_status`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_messages_order_payment_methods1`
    FOREIGN KEY (`id_payment_method`)
    REFERENCES `banco`.`payment_methods` (`id_payment_method`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`messages_order_product` (
  `id_messages_order_product` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `code` TEXT NOT NULL,
  `id_messages_order` INT(11) NOT NULL,
  `id` TEXT NOT NULL,
  `quantity` TEXT NOT NULL,
  `name` TEXT NOT NULL,
  `currency` TEXT NOT NULL,
  `price` TEXT NOT NULL,
  PRIMARY KEY (`id_messages_order_product`),
  INDEX `fk_messages_order_product_messages_order1_idx` (`id_messages_order` ASC) ,
  CONSTRAINT `fk_messages_order_product_messages_order1`
    FOREIGN KEY (`id_messages_order`)
    REFERENCES `banco`.`messages_order` (`id_messages_order`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;


ALTER TABLE `banco`.`permission` 
ADD COLUMN `block_list` SMALLINT(1) NULL DEFAULT '2' AFTER `calendar`,
ADD COLUMN `ticket` SMALLINT(1) NULL DEFAULT '1' AFTER `block_list`,
ADD COLUMN `intranet` SMALLINT(1) NULL DEFAULT '2' AFTER `ticket`,
ADD COLUMN `invoice` SMALLINT(1) NULL DEFAULT '2' AFTER `intranet`,
ADD COLUMN `myinvoice` SMALLINT(1) NULL DEFAULT '2' AFTER `invoice`,
ADD COLUMN `dashboard` SMALLINT(1) NULL DEFAULT '2' AFTER `myinvoice`,
ADD COLUMN `group_contact` SMALLINT(1) NULL DEFAULT '1' AFTER `dashboard`,
ADD COLUMN `broadcast_sms` SMALLINT(1) NULL DEFAULT '1' AFTER `group_contact`,
ADD COLUMN `chatbot` SMALLINT(1) NULL DEFAULT '1' AFTER `broadcast_sms`,
ADD COLUMN `block_access_work_time` SMALLINT(1) NULL DEFAULT NULL AFTER `chatbot`,
ADD COLUMN `evaluate_report` SMALLINT(1) NULL DEFAULT '2' AFTER `block_access_work_time`,
ADD COLUMN `product` SMALLINT(1) NULL DEFAULT '1' AFTER `evaluate_report`,
ADD COLUMN `faq` SMALLINT(1) NULL DEFAULT '1' AFTER `product`;

ALTER TABLE `banco`.`quick_replies` 
CHANGE COLUMN `content` `content` TEXT CHARACTER SET 'utf8mb4' NULL DEFAULT NULL ;

CREATE TABLE IF NOT EXISTS `banco`.`tamplate_params` (
  `id_template_parameter` INT(11) NOT NULL AUTO_INCREMENT,
  `label` VARCHAR(100) NULL DEFAULT NULL,
  `number` INT(2) NULL DEFAULT NULL,
  `creation` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id_template_parameter`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`template` (
  `id_template` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `name` VARCHAR(255) NULL DEFAULT NULL,
  `name_to_request` VARCHAR(255) NULL DEFAULT NULL,
  `namespace` VARCHAR(255) NULL DEFAULT NULL,
  `text_body` TEXT NULL DEFAULT NULL,
  `text_footer` TEXT NULL DEFAULT NULL,
  `language` VARCHAR(8) NULL DEFAULT 'pt_BR',
  `category` VARCHAR(100) NOT NULL DEFAULT 'ISSUE_RESOLUTION',
  `rejected_reason` TEXT NULL DEFAULT NULL,
  `status` INT(11) NULL DEFAULT '1',
  PRIMARY KEY (`id_template`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`template_type` (
  `id_template_type` INT(11) NOT NULL,
  `category` VARCHAR(120) NULL DEFAULT NULL,
  `pt_BR` VARCHAR(120) NULL DEFAULT NULL,
  `pt_PT` VARCHAR(120) NULL DEFAULT NULL,
  `es_AR` VARCHAR(120) NULL DEFAULT NULL,
  `es_ES` VARCHAR(120) NULL DEFAULT NULL,
  `es_MX` VARCHAR(120) NULL DEFAULT NULL,
  `es` VARCHAR(120) NULL DEFAULT NULL,
  `creation` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id_template_type`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

ALTER TABLE `banco`.`ticket` 
ADD COLUMN `timestamp_close` INT(11) NULL DEFAULT NULL AFTER `comment`;

CREATE TABLE IF NOT EXISTS `banco`.`ticket_sla` (
  `id_ticket_sla` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `name` TEXT NOT NULL,
  `time_sla` TEXT NOT NULL,
  `color` TEXT NOT NULL,
  PRIMARY KEY (`id_ticket_sla`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`ticket_task_list` (
  `id_ticket_task_list` INT(11) NOT NULL AUTO_INCREMENT,
  `id_ticket` INT(11) NOT NULL,
  `creation` INT(11) NOT NULL,
  `key_remote_id` TEXT NOT NULL,
  `timestamp_start` INT(11) NULL DEFAULT NULL,
  `timestamp_end` INT(11) NULL DEFAULT NULL,
  `timestamp_diff` INT(11) NULL DEFAULT NULL,
  `status` SMALLINT(1) NOT NULL,
  PRIMARY KEY (`id_ticket_task_list`),
  INDEX `fk_ticket_task_list_ticket1_idx` (`id_ticket` ASC) ,
  CONSTRAINT `fk_ticket_task_list_ticket1`
    FOREIGN KEY (`id_ticket`)
    REFERENCES `banco`.`ticket` (`id_ticket`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

ALTER TABLE `banco`.`ticket_type` 
ADD COLUMN `id_user_group` INT(11) NULL DEFAULT NULL AFTER `id_ticket_type`,
ADD COLUMN `id_ticket_sla` INT(11) NULL DEFAULT NULL AFTER `id_user_group`,
ADD INDEX `fk_ticket_type_ticket_sla1_idx` (`id_ticket_sla` ASC) ,
ADD INDEX `fk_ticket_type_user_group1_idx` (`id_user_group` ASC) ;

CREATE TABLE IF NOT EXISTS `banco`.`ticket_wait_list` (
  `id_ticket_wait_list` INT(11) NOT NULL AUTO_INCREMENT,
  `id_ticket` INT(11) NOT NULL,
  `creation` INT(11) NOT NULL,
  `key_remote_id` TEXT NULL DEFAULT NULL,
  `id_user_group` INT(11) NULL DEFAULT NULL,
  `user_key_remote_id` TEXT NULL DEFAULT NULL,
  `timestamp_send_user` INT(11) NULL DEFAULT NULL,
  `status` SMALLINT(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_ticket_wait_list`),
  INDEX `fk_ticket_wait_list_user_group1_idx` (`id_user_group` ASC) ,
  INDEX `fk_ticket_wait_list_ticket1_idx` (`id_ticket` ASC) ,
  CONSTRAINT `fk_ticket_wait_list_ticket1`
    FOREIGN KEY (`id_ticket`)
    REFERENCES `banco`.`ticket` (`id_ticket`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_wait_list_user_group1`
    FOREIGN KEY (`id_user_group`)
    REFERENCES `banco`.`user_group` (`id_user_group`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

ALTER TABLE `banco`.`user` 
ADD COLUMN `notification_alert_url` TEXT NULL DEFAULT NULL AFTER `visible_widget`,
CHANGE COLUMN `key_remote_id` `key_remote_id` VARCHAR(100) NULL DEFAULT NULL ,
ADD INDEX `idx_user_key_remote_id` (`key_remote_id` ASC) ;

ALTER TABLE `banco`.`user_group` 
ADD COLUMN `status` SMALLINT(1) NULL DEFAULT '1' AFTER `name`;

ALTER TABLE `banco`.`user_timestamp` 
ADD COLUMN `ip` VARCHAR(15) NULL DEFAULT '' AFTER `timediff`;

ALTER TABLE `banco`.`wait_list` 
ADD COLUMN `push_notification` SMALLINT(1) NULL DEFAULT '1' AFTER `status`,
CHANGE COLUMN `key_remote_id` `key_remote_id` VARCHAR(100) NOT NULL ,
ADD INDEX `idx_wait_list_key_remote_id` (`key_remote_id` ASC);

ALTER TABLE `banco`.`channel` add column button_text TEXT;
ALTER TABLE `banco`.`channel` add column button_color varchar(25);
ALTER TABLE `banco`.`channel` add column title TEXT;
ALTER TABLE `banco`.`channel` add column subtitle TEXT;

DROP TABLE IF EXISTS `banco`.`bot_question_type`;

ALTER TABLE `banco`.`chatbot` 
ADD CONSTRAINT `fk_chatbot_user_group1`
  FOREIGN KEY (`id_user_group`)
  REFERENCES `banco`.`user_group` (`id_user_group`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `banco`.`group_participants` 
ADD CONSTRAINT `fk_group_participants_group1`
  FOREIGN KEY (`id_group`)
  REFERENCES `banco`.`groups` (`id_group`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `banco`.`ticket_type` 
ADD CONSTRAINT `fk_ticket_type_ticket_sla1`
  FOREIGN KEY (`id_ticket_sla`)
  REFERENCES `banco`.`ticket_sla` (`id_ticket_sla`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_ticket_type_user_group1`
  FOREIGN KEY (`id_user_group`)
  REFERENCES `banco`.`user_group` (`id_user_group`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `banco`.`messages` CHANGE COLUMN `media_type` `media_type` SMALLINT(2) NULL DEFAULT NULL COMMENT '';

ALTER TABLE `banco`.`permission` CHANGE COLUMN `product` `product` SMALLINT(1) NULL DEFAULT '1' AFTER `evaluate_report`, CHANGE COLUMN `faq` `faq` SMALLINT(1) NULL DEFAULT '1' AFTER `product`;

ALTER TABLE `banco`.`product` CHANGE COLUMN `status` `status` SMALLINT(1) NOT NULL COMMENT '' ;

ALTER TABLE `banco`.`quick_replies` CHANGE COLUMN `creation` `creation` VARCHAR(45) NOT NULL ;

ALTER TABLE `banco`.`messages` CHANGE COLUMN `media_caption` `media_caption` TEXT CHARACTER SET "utf8mb4" COLLATE "utf8mb4_general_ci" NULL DEFAULT NULL ;

ALTER TABLE `banco`.`messages` CHANGE COLUMN `data` `data` TEXT CHARACTER SET "utf8mb4" COLLATE "utf8mb4_general_ci" NULL DEFAULT NULL ;

ALTER TABLE `banco`.`chatbot` CHANGE COLUMN `description` `description` TEXT CHARACTER SET "utf8mb4" COLLATE "utf8mb4_general_ci" NOT NULL;

ALTER TABLE `banco`.`config` CHANGE COLUMN `office_hours_end` `office_hours_end` TEXT CHARACTER SET "utf8mb4" COLLATE "utf8mb4_general_ci" NULL DEFAULT NULL ;

ALTER TABLE `banco`.`config` 
CHANGE COLUMN `office_hours_end` `office_hours_end` TEXT CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_general_ci' NULL DEFAULT NULL ,
CHANGE COLUMN `transfer_message` `transfer_message` TEXT CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_general_ci' NULL DEFAULT NULL ,
CHANGE COLUMN `attendance_message_close` `attendance_message_close` TEXT CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_general_ci' NULL DEFAULT NULL ,
CHANGE COLUMN `template_welcome` `template_welcome` TEXT CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_general_ci' NULL DEFAULT NULL ,
CHANGE COLUMN `template_attendance_message_close` `template_attendance_message_close` TEXT CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_general_ci' NULL DEFAULT NULL ,
CHANGE COLUMN `template_question_evaluate_service` `template_question_evaluate_service` TEXT CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_general_ci' NULL DEFAULT NULL ,
CHANGE COLUMN `template_evaluate_service` `template_evaluate_service` TEXT CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_general_ci' NULL DEFAULT NULL ,
CHANGE COLUMN `namespace` `namespace` TEXT CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_general_ci' NULL DEFAULT NULL ,
CHANGE COLUMN `template_namespace` `template_namespace` TEXT CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_general_ci' NULL DEFAULT NULL ,
CHANGE COLUMN `message_start_attendance` `message_start_attendance` TEXT CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_general_ci' NULL DEFAULT NULL ,
CHANGE COLUMN `template_name_lgpd` `template_name_lgpd` TEXT CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_general_ci' NULL DEFAULT NULL ,
CHANGE COLUMN `lgpd_question_text` `lgpd_question_text` TEXT CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_general_ci' NULL DEFAULT NULL ,
CHANGE COLUMN `template_lgpd_question_no` `template_lgpd_question_no` TEXT CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_general_ci' NULL DEFAULT NULL ,
CHANGE COLUMN `lgpd_not_confirm_message` `lgpd_not_confirm_message` TEXT CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_general_ci' NULL DEFAULT NULL ;

ALTER TABLE `banco`.`contact` CHANGE COLUMN `full_name` `full_name` TEXT CHARACTER SET "utf8mb4" COLLATE "utf8mb4_general_ci" NULL DEFAULT NULL ;

ALTER TABLE `banco`.`job_delete_chat` ADD COLUMN `owner` VARCHAR(5) NULL AFTER `key_remote_id`;

UPDATE `banco`.`permission` SET `shortlink` = '1', `calendar` = '1', `block_list` = '1', `intranet` = '1', `invoice` = '1', `myinvoice` = '1', `dashboard` = '1', `block_access_work_time` = '1', `evaluate_report` = '1' WHERE (`id_permission` = '1');

ALTER TABLE `banco`.`messages` CHANGE COLUMN `media_type` `media_type` SMALLINT(2) NULL DEFAULT NULL;

ALTER TABLE `banco`.`permission` CHANGE COLUMN `adm` `adm` SMALLINT(1) NULL DEFAULT '1';

ALTER TABLE `banco`.`product` CHANGE COLUMN `status` `status` SMALLINT(1) NOT NULL ;

alter table `banco`.`channel` add column `whatsapp_business_messaging` TEXT;

ALTER SCHEMA `banco`  DEFAULT CHARACTER SET utf8mb4  DEFAULT COLLATE utf8mb4_bin ;

ALTER TABLE `banco`.`ticket` 
DROP FOREIGN KEY `fk_ticket_contact1`;

CREATE TABLE IF NOT EXISTS `banco`.`bot_log` (
  `id_bot_log` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `key_remote_id` VARCHAR(100) NOT NULL,
  `start` INT(11) NOT NULL,
  `end` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id_bot_log`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_bin;

CREATE TABLE IF NOT EXISTS `banco`.`broadcast_approval` (
  `id_approval` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `token_broadcast_schedule` TEXT NOT NULL,
  `submitted_by_user` INT(11) NOT NULL,
  `message` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id_approval`),
  INDEX `submitted_by_user_idx` (`submitted_by_user` ASC) ,
  CONSTRAINT `submitted_by_user`
    FOREIGN KEY (`submitted_by_user`)
    REFERENCES `banco`.`user` (`id_user`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`broadcast_approval_contact` (
  `id_broadcast_approval_contact` INT(11) NOT NULL AUTO_INCREMENT,
  `id_approval` INT(11) NOT NULL,
  `token_approval` TEXT NOT NULL,
  `email` TEXT NULL DEFAULT NULL,
  `key_remote_id` TEXT NULL DEFAULT NULL,
  `ip` VARCHAR(100) NOT NULL,
  `user_agent` VARCHAR(100) NOT NULL,
  `status_email` SMALLINT(1) NOT NULL,
  `status_approval` SMALLINT(1) NOT NULL,
  `message` TEXT NULL DEFAULT NULL,
  `email_id` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id_broadcast_approval_contact`),
  INDEX `id_approval` (`id_approval` ASC) ,
  CONSTRAINT `broadcast_approval_contact_ibfk_1`
    FOREIGN KEY (`id_approval`)
    REFERENCES `banco`.`broadcast_approval` (`id_approval`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_bin;

CREATE TABLE IF NOT EXISTS `banco`.`broadcast_approval_log` (
  `id_broadcast_approval_log` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `id_approval` INT(11) NOT NULL,
  `key_remote_id` TEXT NULL DEFAULT NULL,
  `id_broadcast_approval_contact` INT(11) NULL DEFAULT NULL,
  `status` INT(11) NOT NULL,
  `message` TEXT NULL DEFAULT NULL,
  `email_id` TEXT NULL DEFAULT NULL,
  `ip` VARCHAR(100) NULL DEFAULT NULL,
  `user_agent` VARCHAR(100) NULL DEFAULT NULL,
  `token_schedule` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id_broadcast_approval_log`),
  INDEX `id_approval_user_idx` (`id_broadcast_approval_contact` ASC) ,
  INDEX `id_approval_idx` (`id_approval` ASC) ,
  CONSTRAINT `fk_id_approval`
    FOREIGN KEY (`id_approval`)
    REFERENCES `banco`.`broadcast_approval` (`id_approval`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_id_broadcast_approval_user`
    FOREIGN KEY (`id_broadcast_approval_contact`)
    REFERENCES `banco`.`broadcast_approval_contact` (`id_broadcast_approval_contact`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_bin;

CREATE TABLE IF NOT EXISTS `banco`.`broadcast_list` (
  `id_broadcast_list` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `key_id` TEXT NOT NULL,
  `token` TEXT NOT NULL,
  `channel` TEXT NOT NULL,
  `broadcast` TEXT NULL DEFAULT NULL,
  `participants` TEXT NULL DEFAULT NULL,
  `status` SMALLINT(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id_broadcast_list`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_bin;

ALTER TABLE `banco`.`broadcast_schedule` 
ADD COLUMN `submitted_approval` SMALLINT(1) NOT NULL,
ADD COLUMN `status_approval` SMALLINT(1) NOT NULL,
ADD COLUMN `is_fb_publication` SMALLINT(1) NULL DEFAULT '2' AFTER `is_wa_broadcast`,
ADD COLUMN `is_waba_broadcast` SMALLINT(1) NULL DEFAULT '2' AFTER `is_fb_publication`,
ADD COLUMN `is_Ig_publication` SMALLINT(1) NULL DEFAULT '2' AFTER `is_waba_broadcast`,
ADD COLUMN `id_template` INT(11) NULL DEFAULT NULL AFTER `is_Ig_publication`,
ADD COLUMN `json_return_error` TEXT NULL DEFAULT NULL AFTER `id_template`,
ADD COLUMN `id_approval` INT(11) NULL DEFAULT NULL AFTER `json_return_error`,
ADD COLUMN `media_name` TEXT NULL DEFAULT NULL AFTER `id_approval`,
CHANGE COLUMN `data` `data` TEXT CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci' NULL DEFAULT NULL ,
CHANGE COLUMN `thumb_image` `thumb_image` LONGBLOB NULL DEFAULT NULL ,
CHANGE COLUMN `status` `status` SMALLINT(1) NOT NULL,
ADD INDEX `id_approval_idx` (`id_approval` ASC) ,
ADD INDEX `id_approval_idx1` (`id_approval` ASC) ;

ALTER TABLE `banco`.`broadcast_send` 
CHARACTER SET = utf8mb4 , COLLATE = utf8mb4_bin ,
ADD COLUMN `media_name` MEDIUMTEXT NULL DEFAULT NULL AFTER `media_duration`,
CHANGE COLUMN `token` `token` MEDIUMTEXT NOT NULL ,
CHANGE COLUMN `key_id` `key_id` MEDIUMTEXT NULL DEFAULT NULL ,
CHANGE COLUMN `key_remote_id` `key_remote_id` MEDIUMTEXT NULL DEFAULT NULL ,
CHANGE COLUMN `data` `data` MEDIUMTEXT NULL DEFAULT NULL ,
CHANGE COLUMN `media_caption` `media_caption` MEDIUMTEXT NULL DEFAULT NULL ,
CHANGE COLUMN `media_title` `media_title` MEDIUMTEXT NULL DEFAULT NULL ,
CHANGE COLUMN `media_url` `media_url` MEDIUMTEXT NULL DEFAULT NULL ,
CHANGE COLUMN `longitude` `longitude` MEDIUMTEXT NULL DEFAULT NULL ,
CHANGE COLUMN `latitude` `latitude` MEDIUMTEXT NULL DEFAULT NULL ,
CHANGE COLUMN `thumb_image` `thumb_image` LONGBLOB NULL DEFAULT NULL ;

ALTER TABLE `banco`.`channel` 
ADD COLUMN `pricing_template_br` FLOAT(11) NULL DEFAULT NULL AFTER `subtitle`,
ADD COLUMN `position` TEXT NULL DEFAULT NULL AFTER `pricing_template_br`,
ADD COLUMN `credit_conversation` FLOAT(11) NULL DEFAULT '0' AFTER `position`,
ADD COLUMN `business_initiated_price` FLOAT(11) NULL DEFAULT NULL AFTER `credit_conversation`,
ADD COLUMN `user_initiated_price` FLOAT(11) NULL DEFAULT NULL AFTER `business_initiated_price`,
ADD COLUMN `referral_conversion_price` FLOAT(11) NULL DEFAULT NULL AFTER `user_initiated_price`,
ADD COLUMN `minimum_credit` FLOAT(11) NULL DEFAULT '100' AFTER `referral_conversion_price`,
ADD COLUMN `database_clear` VARCHAR(10) NULL DEFAULT NULL AFTER `minimum_credit`,
CHANGE COLUMN `name` `name` TEXT NULL DEFAULT NULL ;

ALTER TABLE `banco`.`channel_event` 
CHANGE COLUMN `type` `type` SMALLINT(1) NOT NULL;

ALTER TABLE `banco`.`chat_form_fields` 
CHANGE COLUMN `type` `type` SMALLINT(1) NOT NULL;

ALTER TABLE `banco`.`chat_list` 
ADD COLUMN `last_conversation_id` TEXT NULL DEFAULT NULL AFTER `key_remote_id`,
ADD COLUMN `fixed_timestamp` INT(11) NULL DEFAULT NULL AFTER `last_timestamp_client`,
ADD COLUMN `id_last_message_read` INT(11) NULL DEFAULT NULL AFTER `fixed_timestamp`,
ADD COLUMN `id_last_message_send` INT(11) NULL DEFAULT NULL AFTER `id_last_message_read`,
ADD COLUMN `id_last_message_no_read` INT(11) NULL DEFAULT NULL AFTER `id_last_message_send`,
ADD COLUMN `id_message_no_read` INT(11) NULL DEFAULT NULL AFTER `id_last_message_no_read`,
ADD COLUMN `is_bot` SMALLINT(1) NULL DEFAULT '2' AFTER `id_message_no_read`,
ADD COLUMN `hidden_msg` SMALLINT(1) NULL DEFAULT '1' AFTER `is_bot`;

ALTER TABLE `banco`.`chat_list_log` 
ADD COLUMN `id_user_group` INT(11) NULL DEFAULT NULL AFTER `protocol`,
ADD INDEX `idx_chat_list_log__end` (`end` ASC) ,
ADD INDEX `idx_chat_list_log_start` (`start` ASC) ;

ALTER TABLE `banco`.`chatbot` 
ADD COLUMN `media_type` SMALLINT(1) NULL DEFAULT '1' AFTER `webhook`,
ADD COLUMN `media_url` TEXT NULL DEFAULT NULL AFTER `media_type`,
ADD COLUMN `media_caption` TEXT NULL DEFAULT NULL AFTER `media_url`,
ADD COLUMN `vcard` TEXT NULL DEFAULT NULL AFTER `media_caption`,
ADD COLUMN `is_end` SMALLINT(1) NULL DEFAULT '2' AFTER `vcard`,
CHANGE COLUMN `text` `text` TEXT CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci' NULL DEFAULT NULL ,
CHANGE COLUMN `description` `description` TEXT CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci' NOT NULL ;

ALTER TABLE `banco`.`company` 
DROP COLUMN `contact_number`,
DROP COLUMN `profile_picture`,
DROP COLUMN `name`,
ADD COLUMN `corporate_name` VARCHAR(100) NULL DEFAULT NULL AFTER `creation`,
ADD COLUMN `fantasy_name` VARCHAR(100) NULL DEFAULT NULL AFTER `corporate_name`;

ALTER TABLE `banco`.`config` 
COLLATE = utf8mb4_bin ,
ADD COLUMN `welcome_message` TEXT NULL DEFAULT NULL AFTER `id_order_status`,
ADD COLUMN `address` TEXT NULL DEFAULT NULL AFTER `welcome_message`,
ADD COLUMN `email` TEXT NULL DEFAULT NULL AFTER `address`,
ADD COLUMN `social_media` TEXT NULL DEFAULT NULL AFTER `email`,
ADD COLUMN `picture` TEXT NULL DEFAULT NULL AFTER `social_media`,
ADD COLUMN `company_description` TEXT NULL DEFAULT NULL AFTER `picture`,
ADD COLUMN `is_broadcast` SMALLINT(1) NULL DEFAULT '2' AFTER `company_description`,
ADD COLUMN `automatic_message` TEXT NULL DEFAULT NULL AFTER `is_broadcast`,
ADD COLUMN `opt_out_key` TEXT NULL DEFAULT NULL AFTER `automatic_message`,
ADD COLUMN `opt_out_message` TEXT NULL DEFAULT NULL AFTER `opt_out_key`,
ADD COLUMN `return_to_channel_message` TEXT NULL DEFAULT NULL AFTER `opt_out_message`,
ADD COLUMN `template_evaluate_service_components` TEXT NULL DEFAULT NULL AFTER `return_to_channel_message`,
ADD COLUMN `template_question_evaluate_service_components` TEXT NULL DEFAULT NULL AFTER `template_evaluate_service_components`,
ADD COLUMN `template_question_evaluate_service_no` TEXT NULL DEFAULT NULL AFTER `template_question_evaluate_service_components`,
ADD COLUMN `template_question_evaluate_service_yes` TEXT NULL DEFAULT NULL AFTER `template_question_evaluate_service_no`,
CHANGE COLUMN `welcome` `welcome` TEXT CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci' NULL DEFAULT NULL ,
CHANGE COLUMN `office_hours_end` `office_hours_end` TEXT CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci' NULL DEFAULT NULL ,
CHANGE COLUMN `lgpd_question_text` `lgpd_question_text` TEXT CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci' NULL DEFAULT NULL ;

ALTER TABLE `banco`.`contact` 
COLLATE = utf8mb4_bin ,
ADD COLUMN `broadcast_timestamp` INT(11) NULL DEFAULT NULL AFTER `is_imported`,
ADD COLUMN `broadcast_create` SMALLINT(1) NULL DEFAULT '1' AFTER `broadcast_timestamp`;

CREATE TABLE IF NOT EXISTS `banco`.`conversation_billable` (
  `id_conversation_billable` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NULL DEFAULT NULL,
  `key_remote_id` VARCHAR(100) NULL DEFAULT NULL,
  `conversation_id` VARCHAR(100) NOT NULL,
  `expiration_timestamp` INT(11) NULL DEFAULT NULL,
  `origin_type` TEXT NULL DEFAULT NULL,
  `model` TEXT NULL DEFAULT NULL,
  `billable` TINYINT(4) NULL DEFAULT NULL,
  `category` TEXT NULL DEFAULT NULL,
  `current_price` FLOAT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id_conversation_billable`),
  UNIQUE INDEX `conversation_id_UNIQUE` (`conversation_id` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_bin;

ALTER TABLE `banco`.`evaluate_service` 
CHANGE COLUMN `evaluation` `evaluation` TEXT NULL DEFAULT NULL ;

ALTER TABLE `banco`.`group_contact` 
ADD COLUMN `key_id` TEXT NULL DEFAULT NULL AFTER `creation`,
ADD COLUMN `gid` TEXT NULL DEFAULT NULL AFTER `key_id`,
ADD COLUMN `is_wa_group` SMALLINT(1) NULL DEFAULT '2' AFTER `participants`,
ADD COLUMN `url_wa_group` TEXT NULL DEFAULT NULL AFTER `is_wa_group`,
ADD COLUMN `description` TEXT NULL DEFAULT NULL AFTER `url_wa_group`,
ADD COLUMN `subject` TEXT NULL DEFAULT NULL AFTER `description`,
ADD COLUMN `send_message` SMALLINT(1) NULL DEFAULT '2' AFTER `subject`,
ADD COLUMN `id_channel` INT(11) NULL DEFAULT NULL AFTER `send_message`,
ADD COLUMN `profile` TEXT NULL DEFAULT NULL AFTER `id_channel`;

CREATE TABLE IF NOT EXISTS `banco`.`json_pending` (
  `id_json_pending` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `key_remote_id` VARCHAR(100) CHARACTER SET 'latin1' NULL DEFAULT NULL,
  `key_id` VARCHAR(50) CHARACTER SET 'latin1' NOT NULL,
  `json` TEXT NOT NULL,
  PRIMARY KEY (`id_json_pending`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_bin;

CREATE TABLE IF NOT EXISTS `banco`.`media` (
  `id_media` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `token` VARCHAR(100) NULL DEFAULT NULL,
  `media_type` SMALLINT(1) NULL DEFAULT NULL,
  `media_size` INT(11) NULL DEFAULT NULL,
  `media_duration` INT(11) NULL DEFAULT NULL,
  `media_caption` VARCHAR(100) NULL DEFAULT NULL,
  `status` SMALLINT(1) NULL DEFAULT NULL,
  `server` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id_media`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `banco`.`media_temp` (
  `id_media_temp` INT(11) NOT NULL AUTO_INCREMENT,
  `id_channel` INT(11) NOT NULL,
  `id_media` INT(11) NOT NULL,
  `token` VARCHAR(100) NOT NULL,
  `ip` TEXT NOT NULL,
  PRIMARY KEY (`id_media_temp`),
  INDEX `fk_media_temp_media1_idx` (`id_media` ASC) ,
  INDEX `fk_media_temp_channel1_idx` (`id_channel` ASC) ,
  CONSTRAINT `fk_media_temp_channel1`
    FOREIGN KEY (`id_channel`)
    REFERENCES `banco`.`channel` (`id_channel`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_media_temp_media1`
    FOREIGN KEY (`id_media`)
    REFERENCES `banco`.`media` (`id_media`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

ALTER TABLE `banco`.`messages` 
COLLATE = utf8mb4_bin ,
DROP COLUMN `wa_key_id`,
ADD COLUMN `ta_key_id` VARCHAR(200) CHARACTER SET 'utf8mb4' NULL DEFAULT NULL AFTER `key_id`,
ADD COLUMN `id_chat_bot` SMALLINT(11) NULL DEFAULT NULL AFTER `json_return`,
ADD COLUMN `starred` SMALLINT(1) NULL DEFAULT NULL AFTER `id_chat_bot`,
ADD COLUMN `components` TEXT CHARACTER SET 'utf8mb4' NULL DEFAULT NULL AFTER `starred`,
ADD COLUMN `deleted` SMALLINT(1) NULL DEFAULT '1' AFTER `components`,
ADD COLUMN `media_key` TEXT NULL DEFAULT NULL AFTER `deleted`,
ADD COLUMN `component` TEXT NULL DEFAULT NULL AFTER `media_key`,
ADD COLUMN `visible` SMALLINT(1) NULL DEFAULT '1' AFTER `component`,
ADD COLUMN `conversation_id` TEXT NULL DEFAULT NULL AFTER `visible`,
ADD COLUMN `conversation_expiration_timestamp` INT(11) NULL DEFAULT NULL AFTER `conversation_id`,
ADD COLUMN `conversation_origin_type` TEXT NULL DEFAULT NULL AFTER `conversation_expiration_timestamp`,
ADD COLUMN `pricing_model` TEXT NULL DEFAULT NULL AFTER `conversation_origin_type`,
ADD COLUMN `pricing_billable` TINYINT(1) NULL DEFAULT NULL AFTER `pricing_model`,
ADD COLUMN `pricing_category` TEXT NULL DEFAULT NULL AFTER `pricing_billable`,
CHANGE COLUMN `key_id` `key_id` VARCHAR(200) CHARACTER SET 'utf8mb4' NOT NULL ,
CHANGE COLUMN `media_name` `media_name` TEXT CHARACTER SET 'utf8mb4' NULL DEFAULT NULL ,
CHANGE COLUMN `quoted_row_id` `quoted_row_id` VARCHAR(200) CHARACTER SET 'utf8' NULL DEFAULT NULL ,
ADD INDEX `idx_messages_creation` (`creation` ASC) ,
DROP INDEX `idx_messages_key_from_me` ,
ADD INDEX `idx_messages_key_from_me` (`key_from_me` ASC, `status` ASC) ,
DROP INDEX `idx_messages_status` ,
ADD INDEX `idx_messages_status` (`status` ASC, `key_from_me` ASC) ,
ADD INDEX `idx_messages_deleted` (`deleted` ASC) ,
ADD INDEX `idx_messages_ta_key_id` (`ta_key_id` ASC) ,
DROP INDEX `idx_quoted_row_id` ;

ALTER TABLE `banco`.`order_status` 
COLLATE = utf8mb4_bin ;

ALTER TABLE `banco`.`permission` 
DROP COLUMN `broadcast`,
ADD COLUMN `persona` SMALLINT(1) NULL DEFAULT '1' AFTER `contact`,
ADD COLUMN `publication_whatsapp_waba` SMALLINT(1) NULL DEFAULT '1' AFTER `messenger`,
ADD COLUMN `publication_whatsapp` SMALLINT(1) NULL DEFAULT '1' AFTER `publication_whatsapp_waba`,
ADD COLUMN `publication_facebook` SMALLINT(1) NULL DEFAULT '1' AFTER `publication_whatsapp`,
ADD COLUMN `publication_instagram` SMALLINT(1) NULL DEFAULT '1' AFTER `publication_facebook`,
ADD COLUMN `conversation_billable` SMALLINT(1) NULL DEFAULT '1' AFTER `block_access_work_time`,
CHANGE COLUMN `dashboard` `dashboard` SMALLINT(1) NULL DEFAULT '2' AFTER `name`,
CHANGE COLUMN `block_list` `block_list` SMALLINT(1) NULL DEFAULT '2' AFTER `label`,
CHANGE COLUMN `user` `user` SMALLINT(1) NULL DEFAULT '1' AFTER `block_list`,
CHANGE COLUMN `usergroup` `usergroup` SMALLINT(1) NULL DEFAULT '1' AFTER `replies`,
CHANGE COLUMN `permission` `permission` SMALLINT(1) NULL DEFAULT '1' AFTER `usergroup`,
CHANGE COLUMN `usercall` `usercall` SMALLINT(1) NULL DEFAULT '1' AFTER `permission`,
CHANGE COLUMN `ticket` `ticket` SMALLINT(1) NULL DEFAULT '1' AFTER `publication_instagram`,
CHANGE COLUMN `kanban` `kanban` SMALLINT(1) NULL DEFAULT '1' AFTER `ticket`,
CHANGE COLUMN `evaluate_report` `evaluate_report` SMALLINT(1) NULL DEFAULT '2' AFTER `report`,
CHANGE COLUMN `adm` `adm` SMALLINT(1) NULL DEFAULT '1' AFTER `evaluate_report`,
CHANGE COLUMN `chatbot` `chatbot` SMALLINT(1) NULL DEFAULT '1' AFTER `config`,
CHANGE COLUMN `product` `product` SMALLINT(1) NULL DEFAULT '1' AFTER `myinvoice`,
CHANGE COLUMN `faq` `faq` SMALLINT(1) NULL DEFAULT '1' AFTER `product`,
CHANGE COLUMN `block_access_work_time` `block_access_work_time` SMALLINT(1) NULL DEFAULT NULL AFTER `group_contact`,
CHANGE COLUMN `broadcast_sms` `broadcast_sms` SMALLINT(1) NULL DEFAULT '2' ;

ALTER TABLE `banco`.`product` 
ADD COLUMN `is_appealed` SMALLINT(1) NULL DEFAULT NULL AFTER `is_visible`;

ALTER TABLE `banco`.`quick_replies` 
CHANGE COLUMN `tag` `tag` TEXT CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_german2_ci' NOT NULL ,
CHANGE COLUMN `content` `content` TEXT CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci' NULL DEFAULT NULL ;

CREATE TABLE IF NOT EXISTS `banco`.`receipt_user` (
  `id_broadcast_participant` INT(11) NOT NULL AUTO_INCREMENT,
  `key_id` VARCHAR(100) NOT NULL,
  `key_remote_id` VARCHAR(100) NOT NULL,
  `receipt_device_timestamp` INT(11) NULL DEFAULT NULL,
  `played_device_timestamp` INT(11) NULL DEFAULT NULL,
  `read_device_timestamp` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id_broadcast_participant`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

ALTER TABLE `banco`.`template` 
CHANGE COLUMN `text_body` `text_body` TEXT CHARACTER SET 'utf8mb4' NULL DEFAULT NULL ;

ALTER TABLE `banco`.`template_type` 
CHANGE COLUMN `id_template_type` `id_template_type` INT(11) NOT NULL AUTO_INCREMENT ;

ALTER TABLE `banco`.`ticket` 
ADD COLUMN `id_subtype` INT(11) NULL DEFAULT NULL AFTER `timestamp_close`,
ADD COLUMN `id_company` INT(11) NULL DEFAULT NULL AFTER `id_subtype`,
CHANGE COLUMN `id_contact` `id_contact` INT(11) NULL DEFAULT NULL ;

ALTER TABLE `banco`.`ticket_status` 
ADD COLUMN `status` SMALLINT(1) NULL DEFAULT NULL AFTER `color`,
ADD COLUMN `ticket_statuscol` VARCHAR(45) NULL DEFAULT '1' AFTER `status`;

ALTER TABLE `banco`.`ticket_type` 
ADD COLUMN `id_subtype` INT(11) NULL DEFAULT NULL AFTER `color`,
ADD COLUMN `is_primary` INT(11) NULL DEFAULT '1' AFTER `id_subtype`;

ALTER TABLE `banco`.`user` 
ADD COLUMN `login_retry` INT(11) NULL DEFAULT '0',
ADD COLUMN `language` TEXT NULL,
ADD COLUMN `attendance_available` SMALLINT(11) NULL DEFAULT NULL;

ALTER TABLE `banco`.`user_2fa` 
DROP COLUMN `is_validated`,
ADD COLUMN `is_add_2fa` TINYINT(4) NULL DEFAULT '2' AFTER `sms_send`,
ADD COLUMN `resend_code` INT(11) NULL DEFAULT '0' AFTER `is_expired`;

ALTER TABLE `banco`.`wait_list` 
ADD COLUMN `account_key_remote_id` VARCHAR(200) NULL DEFAULT NULL AFTER `push_notification`,
ADD COLUMN `type` VARCHAR(45) NULL DEFAULT NULL AFTER `account_key_remote_id`,
ADD COLUMN `t` INT(11) NULL DEFAULT NULL AFTER `type`,
CHANGE COLUMN `key_remote_id` `key_remote_id` VARCHAR(200) NOT NULL ,
ADD INDEX `idx_wait_list_type` (`type` ASC) ,
ADD INDEX `idx_wait_list_creation` (`creation` ASC) ,
ADD INDEX `idx_wait_list_account_key_remote_id` (`account_key_remote_id` ASC) ,
ADD INDEX `idx_wait_list_status` (`status` ASC) ;

CREATE TABLE IF NOT EXISTS `banco`.`webhook` (
  `id_webhook` INT(11) NOT NULL AUTO_INCREMENT,
  `creation` INT(11) NOT NULL,
  `channel` VARCHAR(200) NOT NULL,
  `webhook` TEXT NOT NULL,
  `json` TEXT NOT NULL,
  `status` SMALLINT(1) NOT NULL,
  PRIMARY KEY (`id_webhook`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_bin;

ALTER TABLE `banco`.`broadcast_schedule` 
ADD CONSTRAINT `id_approval`
  FOREIGN KEY (`id_approval`)
  REFERENCES `banco`.`broadcast_approval` (`id_approval`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `banco`.`ticket` 
ADD CONSTRAINT `fk_ticket_contact1`
  FOREIGN KEY (`id_contact`)
  REFERENCES `banco`.`contact` (`id_contact`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

INSERT INTO `banco`.`template_type` (`category`, `pt_BR`) VALUES ('ACCOUNT UPDATE', 'ATUALIZAÇÃO DA CONTA');
INSERT INTO `banco`.`template_type` (`category`, `pt_BR`) VALUES ('PAYMENT UPDATE', 'ATUALIZAÇÃO DE PAGAMENTO');
INSERT INTO `banco`.`template_type` (`category`, `pt_BR`) VALUES ('PERSONAL FINANCE UPDATE', 'ATUALIZAÇÃO DE FINANÇAS PESSOAIS');
INSERT INTO `banco`.`template_type` (`category`, `pt_BR`) VALUES ('SHIPPING UPDATE', 'ATUALIZAÇÃO DE REMESSA');
INSERT INTO `banco`.`template_type` (`category`, `pt_BR`) VALUES ('RESERVATION UPDATE', 'ATUALIZAÇÃO DE RESERVA');
INSERT INTO `banco`.`template_type` (`category`, `pt_BR`) VALUES ('ISSUE RESOLUTION', 'RESOLUÇÃO DE PROBLEMAS');
INSERT INTO `banco`.`template_type` (`category`, `pt_BR`) VALUES ('APPOINTMENT UPDATE', 'ATUALIZAÇÃO DE COMPROMISSO');
INSERT INTO `banco`.`template_type` (`category`, `pt_BR`) VALUES ('TRANSPORTATION UPDATE', 'ATUALIZAÇÃO DE TRANSPORTE');
INSERT INTO `banco`.`template_type` (`category`, `pt_BR`) VALUES ('TICKET UPDATE', 'ATUALIZAÇÃO DE TICKET');
INSERT INTO `banco`.`template_type` (`category`, `pt_BR`) VALUES ('ALERT UPDATE', 'ATUALIZAÇÃO DE ALERTA');
