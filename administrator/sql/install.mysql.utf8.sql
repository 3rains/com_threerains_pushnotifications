CREATE TABLE IF NOT EXISTS `#__threerains_pushnotifications_messages` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`text` TEXT NOT NULL ,
`title` VARCHAR(100)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__threerains_pushnotifications_sent_messages` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`date_of_sent` DATETIME NOT NULL ,
`text` TEXT NOT NULL ,
`title` VARCHAR(100)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__threerains_pushnotifications_devices` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`token` TEXT NOT NULL ,
`osid` INT NOT NULL ,
`label` VARCHAR(255)  NOT NULL ,
`language` INT NOT NULL ,
`app_version` INT NOT NULL ,
`model` VARCHAR(255)  NOT NULL ,
`udid` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__threerains_pushnotifications_os` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`name` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__threerains_pushnotifications_devices_lists` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`list_name` VARCHAR(255)  NOT NULL ,
`list_description` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__threerains_pushnotifications_subscriptions` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`device_id` INT NOT NULL ,
`list_id` INT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__threerains_pushnotifications_devices_log` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`device_id` INT NOT NULL ,
`timestamp` DATETIME NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__threerains_pushnotifications_languages` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`code` VARCHAR(255)  NOT NULL ,
`name` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__threerains_pushnotifications_scheduled_tasks` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`send_date` DATE NOT NULL ,
`send_hour` INT(11)  NOT NULL ,
`send_minute` INT(11)  NOT NULL ,
`segmentid` TEXT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__threerains_pushnotifications_segments` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`filter_os` TEXT NOT NULL ,
`filter_devices` TEXT NOT NULL ,
`filter_list` TEXT NOT NULL ,
`filter_language` TEXT NOT NULL ,
`filter_appunused` VARCHAR(255)  NOT NULL ,
`name` VARCHAR(50)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__threerains_pushnotifications_app_versions` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`version_code` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

INSERT INTO #__threerains_pushnotifications_devices_lists (list_name, list_description) VALUES
('iPhone', 'iPhone users'),
('iPod', 'iPod Touch users'),
('iPad', 'iPad users'),
('Android', 'Android smartphone users'),
('Android Tablet', 'Android Tablet users');

INSERT INTO #__threerains_pushnotifications_os (name, state) VALUES ('Android', 1), ('iOS', 1);


INSERT INTO #__threerains_pushnotifications_languages (name, code, state) VALUES
('Abkhazian', 'ab', 1),
('Afar', 'aa', 1),
('Afrikaans', 'af', 1),
('Albanian', 'sq', 1),
('Amharic', 'am', 1),
('Arabic', 'ar', 1),
('Aragonese', 'an', 1),
('Armenian', 'hy', 1),
('Assamese', 'as', 1),
('Aymara', 'ay', 1),
('Azerbaijani', 'az', 1),
('Bashkir', 'ba', 1),
('Basque', 'eu', 1),
('Bengali (Bangla)', 'bn', 1),
('Bhutani', 'dz', 1),
('Bihari', 'bh', 1),
('Bislama', 'bi', 1),
('Breton', 'br', 1),
('Bulgarian', 'bg', 1),
('Burmese', 'my', 1),
('Byelorussian (Belarusian)', 'be', 1),
('Cambodian', 'km', 1),
('Catalan', 'ca', 1),
('Chinese', 'zh', 1),
('Chinese (Simplified)', 'zh-Hans', 1),
('Chinese (Traditional)', 'zh-Hant', 1),
('Corsican', 'co', 1),
('Croatian', 'hr', 1),
('Czech', 'cs', 1),
('Danish', 'da', 1),
('Dutch', 'nl', 1),
('English', 'en', 1),
('Esperanto', 'eo', 1),
('Estonian', 'et', 1),
('Faeroese', 'fo', 1),
('Farsi', 'fa', 1),
('Fiji', 'fj', 1),
('Finnish', 'fi', 1),
('French', 'fr', 1),
('Frisian', 'fy', 1),
('Galician', 'gl', 1),
('Gaelic (Scottish)', 'gd', 1),
('Gaelic (Manx)', 'gv', 1),
('Georgian', 'ka', 1),
('German', 'de', 1),
('Greek', 'el', 1),
('Greenlandic', 'kl', 1),
('Guarani', 'gn', 1),
('Gujarati', 'gu', 1),
('Haitian Creole', 'ht', 1),
('Hausa', 'ha', 1),
('Hebrew', 'he, iw', 1),
('Hindi', 'hi', 1),
('Hungarian', 'hu', 1),
('Icelandic', 'is', 1),
('Ido', 'io', 1),
('Indonesian', 'id, in', 1),
('Interlingua', 'ia', 1),
('Interlingue', 'ie', 1),
('Inuktitut', 'iu', 1),
('Inupiak', 'ik', 1),
('Irish', 'ga', 1),
('Italian', 'it', 1),
('Japanese', 'ja', 1),
('Javanese', 'jv', 1),
('Kannada', 'kn', 1),
('Kashmiri', 'ks', 1),
('Kazakh', 'kk', 1),
('Kinyarwanda (Ruanda)', 'rw', 1),
('Kirghiz', 'ky', 1),
('Kirundi (Rundi)', 'rn', 1),
('Korean', 'ko', 1),
('Kurdish', 'ku', 1),
('Laothian', 'lo', 1),
('Latin', 'la', 1),
('Latvian (Lettish)', 'lv', 1),
('Limburgish ( Limburger)', 'li', 1),
('Lingala', 'ln', 1),
('Lithuanian', 'lt', 1),
('Macedonian', 'mk', 1),
('Malagasy', 'mg', 1),
('Malay', 'ms', 1),
('Malayalam', 'ml', 1),
('Maltese', 'mt', 1),
('Maori', 'mi', 1),
('Marathi', 'mr', 1),
('Moldavian', 'mo', 1),
('Mongolian', 'mn', 1),
('Nauru', 'na', 1),
('Nepali', 'ne', 1),
('Norwegian', 'no', 1),
('Occitan', 'oc', 1),
('Oriya', 'or', 1),
('Oromo (Afaan Oromo)', 'om', 1),
('Pashto (Pushto)', 'ps', 1),
('Polish', 'pl', 1),
('Portuguese', 'pt', 1),
('Punjabi', 'pa', 1),
('Quechua', 'qu', 1),
('Rhaeto-Romance', 'rm', 1),
('Romanian', 'ro', 1),
('Russian', 'ru', 1), 
('Samoan', 'sm', 1),
('Sangro', 'sg', 1),
('Sanskrit', 'sa', 1),
('Serbian', 'sr', 1),
('Serbo-Croatian', 'sh', 1),
('Sesotho', 'st', 1),
('Setswana', 'tn', 1),
('Shona', 'sn', 1),
('Sichuan Yi', 'ii', 1),
('Sindhi', 'sd', 1),
('Sinhalese', 'si', 1),
('Siswati', 'ss', 1),
('Slovak', 'sk', 1),
('Slovenian', 'sl', 1),
('Somali', 'so', 1),
('Spanish', 'es', 1),
('Sundanese', 'su', 1),
('Swahili (Kiswahili)', 'sw', 1),
('Swedish', 'sv', 1),
('Tagalog', 'tl', 1),
('Tajik', 'tg', 1),
('Tamil', 'ta', 1),
('Tatar', 'tt', 1),
('Telugu', 'te', 1),
('Thai', 'th', 1),
('Tibetan', 'bo', 1),
('Tigrinya', 'ti', 1),
('Tonga', 'to', 1),
('Tsonga', 'ts', 1),
('Turkish', 'tr', 1),
('Turkmen', 'tk', 1),
('Twi', 'tw', 1),
('Uighur', 'ug', 1),
('Ukrainian', 'uk', 1),
('Urdu', 'ur', 1),
('Uzbek', 'uz', 1),
('Vietnamese', 'vi', 1),
('Volap�k', 'vo', 1),
('Wallon', 'wa', 1),
('Welsh', 'cy', 1),
('Wolof', 'wo', 1),
('Xhosa', 'xh', 1),
('Yiddish', 'yi, ji', 1),
('Yoruba', 'yo', 1),
('Zulu', 'zu', 1);