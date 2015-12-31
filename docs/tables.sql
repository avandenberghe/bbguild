# ************************************************************
# Sequel Pro SQL dump
# Version 4499
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: localhost (MySQL 5.5.42)
# Database: qi30_test01
# Generation Time: 2015-12-30 23:02:07 +0000
# ************************************************************

# Dump of table phpbb_bbdkp_adjustments
# ------------------------------------------------------------

CREATE TABLE `phpbb_bbdkp_adjustments` (
  `adjustment_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `adjustment_dkpid` smallint(4) unsigned NOT NULL DEFAULT '0',
  `adjustment_value` decimal(11,2) NOT NULL DEFAULT '0.00',
  `adjustment_date` int(11) unsigned NOT NULL DEFAULT '0',
  `adjustment_reason` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `adjustment_added_by` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `adjustment_updated_by` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `adjustment_group_key` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `adj_decay` decimal(11,2) NOT NULL DEFAULT '0.00',
  `can_decay` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `decay_time` decimal(11,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`adjustment_id`),
  KEY `member_id` (`member_id`,`adjustment_dkpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

# Dump of table phpbb_bbdkp_classes
# ------------------------------------------------------------

CREATE TABLE `phpbb_bbdkp_classes` (
  `c_index` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  `class_id` smallint(4) unsigned NOT NULL DEFAULT '0',
  `class_faction_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `class_min_level` smallint(4) unsigned NOT NULL DEFAULT '0',
  `class_max_level` smallint(4) unsigned NOT NULL DEFAULT '0',
  `class_armor_type` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `class_hide` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `imagename` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `colorcode` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`c_index`),
  UNIQUE KEY `bbclass` (`game_id`,`class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



# Dump of table phpbb_bbdkp_dkpsystem
# ------------------------------------------------------------

CREATE TABLE `phpbb_bbdkp_dkpsystem` (
  `dkpsys_id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `dkpsys_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `dkpsys_status` varchar(2) COLLATE utf8_bin NOT NULL DEFAULT 'Y',
  `dkpsys_addedby` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `dkpsys_updatedby` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `dkpsys_default` varchar(2) COLLATE utf8_bin NOT NULL DEFAULT 'N',
  `adj_decay` decimal(11,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`dkpsys_id`),
  UNIQUE KEY `dkpsys_name` (`dkpsys_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



# Dump of table phpbb_bbdkp_events
# ------------------------------------------------------------

CREATE TABLE `phpbb_bbdkp_events` (
  `event_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `event_dkpid` smallint(4) unsigned NOT NULL DEFAULT '0',
  `event_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `event_color` varchar(8) COLLATE utf8_bin NOT NULL DEFAULT '',
  `event_imagename` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `event_value` decimal(11,2) NOT NULL DEFAULT '0.00',
  `event_added_by` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `event_updated_by` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `event_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`event_id`),
  KEY `event_dkpid` (`event_dkpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



# Dump of table phpbb_bbdkp_factions
# ------------------------------------------------------------

CREATE TABLE `phpbb_bbdkp_factions` (
  `game_id` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `f_index` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `faction_id` smallint(4) unsigned NOT NULL DEFAULT '0',
  `faction_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `faction_hide` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`f_index`),
  UNIQUE KEY `bbdkp_factions` (`game_id`,`faction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



# Dump of table phpbb_bbdkp_gameroles
# ------------------------------------------------------------

CREATE TABLE `phpbb_bbdkp_gameroles` (
  `role_pkid` int(8) NOT NULL AUTO_INCREMENT,
  `game_id` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  `role_id` int(8) NOT NULL DEFAULT '0',
  `role_color` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `role_icon` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `role_cat_icon` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`role_pkid`),
  UNIQUE KEY `bbroles` (`game_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



# Dump of table phpbb_bbdkp_games
# ------------------------------------------------------------

CREATE TABLE `phpbb_bbdkp_games` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  `game_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `status` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '',
  `imagename` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '',
  `armory_enabled` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `bossbaseurl` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `zonebaseurl` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `apikey` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `apilocale` varchar(5) COLLATE utf8_bin NOT NULL DEFAULT '',
  `privkey` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `bbdkp_games` (`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



# Dump of table phpbb_bbdkp_language
# ------------------------------------------------------------

CREATE TABLE `phpbb_bbdkp_language` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  `attribute_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `language` char(2) COLLATE utf8_bin NOT NULL DEFAULT '',
  `attribute` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT '',
  `name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `name_short` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `bbdkp_language` (`game_id`,`attribute_id`,`language`,`attribute`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



# Dump of table phpbb_bbdkp_logs
# ------------------------------------------------------------

CREATE TABLE `phpbb_bbdkp_logs` (
  `log_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `log_date` int(11) unsigned NOT NULL DEFAULT '0',
  `log_type` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `log_action` text COLLATE utf8_bin NOT NULL,
  `log_ipaddress` varchar(45) COLLATE utf8_bin NOT NULL DEFAULT '',
  `log_sid` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
  `log_result` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `log_userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`log_id`),
  KEY `log_userid` (`log_userid`),
  KEY `log_type` (`log_type`),
  KEY `log_ipaddress` (`log_ipaddress`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



# Dump of table phpbb_bbdkp_member_ranks
# ------------------------------------------------------------

CREATE TABLE `phpbb_bbdkp_member_ranks` (
  `guild_id` smallint(4) unsigned NOT NULL DEFAULT '0',
  `rank_id` smallint(4) unsigned NOT NULL DEFAULT '0',
  `rank_name` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '',
  `rank_hide` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `rank_prefix` varchar(75) COLLATE utf8_bin NOT NULL DEFAULT '',
  `rank_suffix` varchar(75) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`rank_id`,`guild_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



# Dump of table phpbb_bbdkp_memberdkp
# ------------------------------------------------------------

CREATE TABLE `phpbb_bbdkp_memberdkp` (
  `member_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `member_dkpid` smallint(4) unsigned NOT NULL DEFAULT '0',
  `member_raid_value` decimal(11,2) NOT NULL DEFAULT '0.00',
  `member_time_bonus` decimal(11,2) NOT NULL DEFAULT '0.00',
  `member_zerosum_bonus` decimal(11,2) NOT NULL DEFAULT '0.00',
  `member_earned` decimal(11,2) NOT NULL DEFAULT '0.00',
  `member_raid_decay` decimal(11,2) NOT NULL DEFAULT '0.00',
  `member_spent` decimal(11,2) NOT NULL DEFAULT '0.00',
  `member_item_decay` decimal(11,2) NOT NULL DEFAULT '0.00',
  `member_adjustment` decimal(11,2) NOT NULL DEFAULT '0.00',
  `member_firstraid` int(11) unsigned NOT NULL DEFAULT '0',
  `member_lastraid` int(11) unsigned NOT NULL DEFAULT '0',
  `member_raidcount` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `adj_decay` decimal(11,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`member_dkpid`,`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



# Dump of table phpbb_bbdkp_memberguild
# ------------------------------------------------------------

CREATE TABLE `phpbb_bbdkp_memberguild` (
  `id` smallint(4) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `realm` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `region` varchar(3) COLLATE utf8_bin DEFAULT NULL,
  `roster` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `aion_legion_id` smallint(4) unsigned NOT NULL DEFAULT '0',
  `aion_server_id` smallint(4) unsigned NOT NULL DEFAULT '0',
  `level` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `members` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `achievementpoints` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `battlegroup` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `guildarmoryurl` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `emblemurl` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `game_id` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  `min_armory` mediumint(8) unsigned NOT NULL DEFAULT '90',
  `rec_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `guilddefault` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `armory_enabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `armoryresult` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `recruitforum` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `bbguild` (`name`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



# Dump of table phpbb_bbdkp_memberlist
# ------------------------------------------------------------

CREATE TABLE `phpbb_bbdkp_memberlist` (
  `game_id` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  `member_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `member_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `member_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `member_level` smallint(4) unsigned NOT NULL DEFAULT '0',
  `member_race_id` smallint(4) unsigned NOT NULL DEFAULT '0',
  `member_class_id` smallint(4) unsigned NOT NULL DEFAULT '0',
  `member_rank_id` smallint(4) unsigned NOT NULL DEFAULT '0',
  `member_comment` text COLLATE utf8_bin,
  `member_joindate` int(11) unsigned NOT NULL DEFAULT '0',
  `member_outdate` int(11) unsigned NOT NULL DEFAULT '0',
  `member_guild_id` smallint(4) unsigned NOT NULL DEFAULT '0',
  `member_gender_id` smallint(4) unsigned NOT NULL DEFAULT '0',
  `member_achiev` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `member_armory_url` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `member_portrait_url` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `phpbb_user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `member_title` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `member_role` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '',
  `member_region` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `member_realm` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `deactivate_reason` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `last_update` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`member_id`),
  UNIQUE KEY `member_name` (`member_guild_id`,`member_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



# Dump of table phpbb_bbdkp_news
# ------------------------------------------------------------

CREATE TABLE `phpbb_bbdkp_news` (
  `news_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `news_headline` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `news_message` text COLLATE utf8_bin NOT NULL,
  `news_date` int(11) unsigned NOT NULL DEFAULT '0',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `bbcode_bitfield` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '',
  `bbcode_uid` varchar(8) COLLATE utf8_bin NOT NULL DEFAULT '',
  `bbcode_options` varchar(8) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`news_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



# Dump of table phpbb_bbdkp_plugins
# ------------------------------------------------------------

CREATE TABLE `phpbb_bbdkp_plugins` (
  `name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `value` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `version` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '',
  `orginal_copyright` varchar(150) COLLATE utf8_bin NOT NULL DEFAULT '',
  `bbdkp_copyright` varchar(150) COLLATE utf8_bin NOT NULL DEFAULT '',
  `installdate` int(11) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



# Dump of table phpbb_bbdkp_races
# ------------------------------------------------------------

CREATE TABLE `phpbb_bbdkp_races` (
  `game_id` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  `race_id` smallint(4) unsigned NOT NULL DEFAULT '0',
  `race_faction_id` smallint(4) unsigned NOT NULL DEFAULT '0',
  `race_hide` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `image_female` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `image_male` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`game_id`,`race_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



# Dump of table phpbb_bbdkp_raid_detail
# ------------------------------------------------------------

CREATE TABLE `phpbb_bbdkp_raid_detail` (
  `raid_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `member_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `raid_value` decimal(11,2) NOT NULL DEFAULT '0.00',
  `time_bonus` decimal(11,2) NOT NULL DEFAULT '0.00',
  `zerosum_bonus` decimal(11,2) NOT NULL DEFAULT '0.00',
  `raid_decay` decimal(11,2) NOT NULL DEFAULT '0.00',
  `decay_time` decimal(11,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`raid_id`,`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



# Dump of table phpbb_bbdkp_raid_items
# ------------------------------------------------------------

CREATE TABLE `phpbb_bbdkp_raid_items` (
  `item_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `raid_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `item_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `member_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `item_date` int(11) unsigned NOT NULL DEFAULT '0',
  `item_added_by` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `item_updated_by` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `item_group_key` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `item_gameid` varchar(8) COLLATE utf8_bin DEFAULT NULL,
  `item_value` decimal(11,2) NOT NULL DEFAULT '0.00',
  `item_decay` decimal(11,2) NOT NULL DEFAULT '0.00',
  `item_zs` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `decay_time` decimal(11,2) NOT NULL DEFAULT '0.00',
  `wowhead_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_id`),
  KEY `raid_id` (`raid_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



# Dump of table phpbb_bbdkp_raids
# ------------------------------------------------------------

CREATE TABLE `phpbb_bbdkp_raids` (
  `raid_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `raid_note` text COLLATE utf8_bin,
  `raid_start` int(11) unsigned NOT NULL DEFAULT '0',
  `raid_end` int(11) unsigned NOT NULL DEFAULT '0',
  `raid_added_by` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `raid_updated_by` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`raid_id`),
  KEY `event_id` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



# Dump of table phpbb_bbdkp_recruit
# ------------------------------------------------------------

CREATE TABLE `phpbb_bbdkp_recruit` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `guild_id` smallint(4) unsigned NOT NULL DEFAULT '0',
  `role_id` int(8) NOT NULL DEFAULT '0',
  `class_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `level` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `positions` smallint(4) unsigned NOT NULL DEFAULT '0',
  `applicants` smallint(4) unsigned NOT NULL DEFAULT '0',
  `status` smallint(4) unsigned NOT NULL DEFAULT '0',
  `applytemplate_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `last_update` int(11) unsigned NOT NULL DEFAULT '0',
  `note` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



# Dump of table phpbb_bbdkp_welcomemsg
# ------------------------------------------------------------

CREATE TABLE `phpbb_bbdkp_welcomemsg` (
  `welcome_id` int(8) NOT NULL AUTO_INCREMENT,
  `welcome_title` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `welcome_msg` text COLLATE utf8_bin NOT NULL,
  `welcome_timestamp` int(11) unsigned NOT NULL DEFAULT '0',
  `bbcode_bitfield` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `bbcode_uid` varchar(8) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_id` int(8) NOT NULL DEFAULT '0',
  `bbcode_options` mediumint(8) unsigned NOT NULL DEFAULT '7',
  PRIMARY KEY (`welcome_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
