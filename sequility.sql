/*

Source Database       : sequility

Target Server Type    : MYSQL
Target Server Version : 50163
File Encoding         : 65001

Date: 2012-10-24 09:46:29
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `amg_albums`
-- ----------------------------
DROP TABLE IF EXISTS `amg_albums`;
CREATE TABLE `amg_albums` (
  `album_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `domain_owner` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `parent` int(11) NOT NULL,
  `gallery_id` int(11) NOT NULL DEFAULT '1',
  `pictures` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `sortorder` int(11) NOT NULL DEFAULT '0',
  `likes` int(11) NOT NULL DEFAULT '0',
  `date_released` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `first_paid_page` int(11) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  PRIMARY KEY (`album_id`),
  KEY `domain_owner` (`domain_owner`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=115 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of amg_albums
-- ----------------------------
INSERT INTO `amg_albums` VALUES ('1', '0', '0', 'Ad Campaign Generic', '0', '1', '1', 'ipod_nano_5.jpg', '0', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '');
INSERT INTO `amg_albums` VALUES ('3', '0', '0', 'Merchants', '0', '1', '0', 'sony_vaio_3.jpg', '0', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '');
INSERT INTO `amg_albums` VALUES ('4', '0', '0', 'Stores', '0', '1', '0', 't_people.png', '0', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '');

-- ----------------------------
-- Table structure for `amg_albums_access`
-- ----------------------------
DROP TABLE IF EXISTS `amg_albums_access`;
CREATE TABLE `amg_albums_access` (
  `user_id` int(11) NOT NULL,
  `album_id` int(11) NOT NULL,
  `admin` int(11) NOT NULL DEFAULT '0',
  `add` int(11) NOT NULL DEFAULT '0',
  `change` int(11) NOT NULL DEFAULT '0',
  `read` int(11) NOT NULL DEFAULT '0' COMMENT '0: no; 1;all;-1:logged in only',
  UNIQUE KEY `album_user_id` (`album_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of amg_albums_access
-- ----------------------------

-- ----------------------------
-- Table structure for `amg_albums_tokens`
-- ----------------------------
DROP TABLE IF EXISTS `amg_albums_tokens`;
CREATE TABLE `amg_albums_tokens` (
  `album_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_expiration` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `album_token` (`album_id`,`token`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of amg_albums_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for `amg_albums_user`
-- ----------------------------
DROP TABLE IF EXISTS `amg_albums_user`;
CREATE TABLE `amg_albums_user` (
  `user_id` int(11) NOT NULL,
  `album_id` int(11) NOT NULL AUTO_INCREMENT,
  `gallery_id` int(11) NOT NULL DEFAULT '1',
  `paid_amount` int(11) NOT NULL DEFAULT '0',
  `date_end_subscription` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `album_user_id` (`album_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of amg_albums_user
-- ----------------------------

-- ----------------------------
-- Table structure for `amg_categories`
-- ----------------------------
DROP TABLE IF EXISTS `amg_categories`;
CREATE TABLE `amg_categories` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `albums` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `level` int(11) NOT NULL DEFAULT '0',
  `parent` int(11) NOT NULL,
  `domain_owner` int(11) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of amg_categories
-- ----------------------------
INSERT INTO `amg_categories` VALUES ('6', 'All', '0', '', '0', '0', '0');

-- ----------------------------
-- Table structure for `amg_categories_album`
-- ----------------------------
DROP TABLE IF EXISTS `amg_categories_album`;
CREATE TABLE `amg_categories_album` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) unsigned NOT NULL,
  `album_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_id` (`category_id`,`album_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1028563 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of amg_categories_album
-- ----------------------------

-- ----------------------------
-- Table structure for `amg_gallery`
-- ----------------------------
DROP TABLE IF EXISTS `amg_gallery`;
CREATE TABLE `amg_gallery` (
  `gallery_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `albums` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `sortorder` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`gallery_id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of amg_gallery
-- ----------------------------
INSERT INTO `amg_gallery` VALUES ('1', 'Admin', '0', 'admin_silhouette.jpg', '0', '6');
INSERT INTO `amg_gallery` VALUES ('2', 'Publish', '0', 'admin_silhouette.jpg', '1', '1');
INSERT INTO `amg_gallery` VALUES ('3', 'My Albums', '0', 'admin_silhouette.jpg', '2', '1');
INSERT INTO `amg_gallery` VALUES ('12', 'News', '0', 'admin_silhouette.jpg', '3', '3');

-- ----------------------------
-- Table structure for `amg_pictures`
-- ----------------------------
DROP TABLE IF EXISTS `amg_pictures`;
CREATE TABLE `amg_pictures` (
  `picture_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text CHARACTER SET latin1 NOT NULL,
  `image` varchar(255) NOT NULL,
  `width_large` int(11) NOT NULL,
  `height_large` int(11) NOT NULL,
  `zip` char(16) NOT NULL,
  `category_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `album_id` int(11) NOT NULL,
  `sortorder` int(11) NOT NULL DEFAULT '0',
  `date_added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_released` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`picture_id`)
) ENGINE=MyISAM AUTO_INCREMENT=779 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of amg_pictures
-- ----------------------------

-- ----------------------------
-- Table structure for `amg_sequence`
-- ----------------------------
DROP TABLE IF EXISTS `amg_sequence`;
CREATE TABLE `amg_sequence` (
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of amg_sequence
-- ----------------------------
INSERT INTO `amg_sequence` VALUES ('31');

-- ----------------------------
-- Table structure for `amg_setting`
-- ----------------------------
DROP TABLE IF EXISTS `amg_setting`;
CREATE TABLE `amg_setting` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `notes` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `flag` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`setting_id`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of amg_setting
-- ----------------------------
INSERT INTO `amg_setting` VALUES ('1', 'Error Log File Name', 'config_error_filename', 'error.txt');
INSERT INTO `amg_setting` VALUES ('2', 'Log the Errors', 'config_error_log', '1');
INSERT INTO `amg_setting` VALUES ('3', 'Display The Errors', 'config_error_display', '0');
INSERT INTO `amg_setting` VALUES ('4', 'Enable Seo Urls', 'config_seo_url', '1');
INSERT INTO `amg_setting` VALUES ('5', 'Website Logo Image', 'config_logo', 'black-logo.png');
INSERT INTO `amg_setting` VALUES ('6', 'Template Folder Name <br> Note: Ending slash needed', 'config_template', 'default/');
INSERT INTO `amg_setting` VALUES ('7', 'Admin Email', 'config_email', 'tyisreally@gmail.com');
INSERT INTO `amg_setting` VALUES ('8', 'Memory limit (In MB)', 'config_memory_limit', '128');
INSERT INTO `amg_setting` VALUES ('9', 'Host Name ', 'config_site_url', 'http://www.sequility.com');
INSERT INTO `amg_setting` VALUES ('10', 'Website Title', 'config_site_title', 'Sequility');
INSERT INTO `amg_setting` VALUES ('11', 'Description', 'config_site_description', 'Comics');
INSERT INTO `amg_setting` VALUES ('12', 'Favicon', 'config_icon', 'favicon.ico');
INSERT INTO `amg_setting` VALUES ('13', 'Albums Per Page (In px)', 'albums_per_page', '8');
INSERT INTO `amg_setting` VALUES ('14', 'Pictures Per Page (In px)', 'pictures_per_page', '8');
INSERT INTO `amg_setting` VALUES ('15', 'Picture Thumbnail Width(In px)', 'config_thumb_width', '220');
INSERT INTO `amg_setting` VALUES ('16', 'Picture Thumbnail Height(In px)', 'config_thumb_height', '190');
INSERT INTO `amg_setting` VALUES ('17', 'Large Picture Width (In px)', 'config_large_width', '960');
INSERT INTO `amg_setting` VALUES ('18', 'Large Picture Height (In px)', 'config_large_height', '1358');
INSERT INTO `amg_setting` VALUES ('19', 'Gallery Cover Thumbnail  Width (In px)', 'gallery_thumb_width', '220');
INSERT INTO `amg_setting` VALUES ('20', 'Gallery Cover Thumbnail  Height(In px)', 'gallery_thumb_height', '190');
INSERT INTO `amg_setting` VALUES ('21', 'Album Cover Thumb Width(In px)', 'album_thumb_width', '220');
INSERT INTO `amg_setting` VALUES ('22', 'Album Cover Thumb Height(In px)', 'album_thumb_height', '190');
INSERT INTO `amg_setting` VALUES ('27', 'Local Ajax Server', 'config_dynamic_ajax', '/app/controller/ajax.php');
INSERT INTO `amg_setting` VALUES ('23', 'Twitter consumer_key', 'consumer_key', '');
INSERT INTO `amg_setting` VALUES ('24', 'Twitter consumer_secret', 'consumer_secret', '');
INSERT INTO `amg_setting` VALUES ('25', 'Twitter accessToken', 'accessToken', '');
INSERT INTO `amg_setting` VALUES ('26', 'Twitter accessTokenSecret', 'accessTokenSecret', '');
INSERT INTO `amg_setting` VALUES ('29', 'paypal email', 'config_paypal_email', '');
INSERT INTO `amg_setting` VALUES ('28', 'Paypal Flow URL', 'pp_flow_pay', 'https://www.sandbox.paypal.com/webapps/adaptivepayment/flow/pay');
INSERT INTO `amg_setting` VALUES ('30', 'Author Default Zip', 'default_zip', '');
INSERT INTO `amg_setting` VALUES ('35', 'Welcome Image', 'config_welcome_image', '1colorlogo.png');
INSERT INTO `amg_setting` VALUES ('31', 'Facebook app id', 'fb_app_id', '');
INSERT INTO `amg_setting` VALUES ('32', 'Facebook app secret', 'fb_app_secret', '');
INSERT INTO `amg_setting` VALUES ('33', 'Help File name for visitors<br/>(xml files only)', 'visitors_help_filename', 'visitors_help.xml');
INSERT INTO `amg_setting` VALUES ('34', 'Help Filename for authors<br/>(xml files only)', 'authors_help_filename', 'authors_help.xml');
INSERT INTO `amg_setting` VALUES ('36', 'Call to Action', 'config_call_to_action', 'Start your own &lt;b&gt;Comic&lt;/b&gt;');
INSERT INTO `amg_setting` VALUES ('37', 'Google Analytics Code', 'config_google_analytics_code', '0');
INSERT INTO `amg_setting` VALUES ('38', 'About File name<br/>(xml files only)', 'about_filename', 'about.xml');
INSERT INTO `amg_setting` VALUES ('39', 'News File Name<br/>(xml files only)', 'news_filename', 'news.xml');
INSERT INTO `amg_setting` VALUES ('40', 'Categorize Albums', 'config_show_cats', '0');
INSERT INTO `amg_setting` VALUES ('41', 'Show Left Menu', 'config_show_left_menu', '1');
INSERT INTO `amg_setting` VALUES ('42', 'Sign In Message', 'config_sign_in_msg', 'Sign in with <b>Facebook</b>\r\nSign in with <b>Facebook</b>\r\nSign in with <b>Facebook</b>');
INSERT INTO `amg_setting` VALUES ('43', 'Compression Level', 'config_compression', '4');
INSERT INTO `amg_setting` VALUES ('44', 'Image Quality', 'config_image_quality', '90');

-- ----------------------------
-- Table structure for `amg_setting_author`
-- ----------------------------
DROP TABLE IF EXISTS `amg_setting_author`;
CREATE TABLE `amg_setting_author` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `notes` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `flag` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`setting_id`),
  UNIQUE KEY `user_flag` (`user_id`,`flag`)
) ENGINE=MyISAM AUTO_INCREMENT=197 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of amg_setting_author
-- ----------------------------
INSERT INTO `amg_setting_author` VALUES ('5', '0', 'Website Logo Image', 'config_logo', 't_textility.png');
INSERT INTO `amg_setting_author` VALUES ('116', '0', 'Help File Name for authors<br/>(xml files only)', 'authors_help_filename', 'authors_help.xml');
INSERT INTO `amg_setting_author` VALUES ('117', '0', 'Help File Name for visitors<br/>(xml files only)', 'visitors_help_filename', 'visitors_help.xml');
INSERT INTO `amg_setting_author` VALUES ('9', '0', 'Host Name', 'config_site_url', 'http://');
INSERT INTO `amg_setting_author` VALUES ('10', '0', 'Website Title', 'config_site_title', 'Sequility');
INSERT INTO `amg_setting_author` VALUES ('11', '0', 'Description', 'config_site_description', 'Comics');
INSERT INTO `amg_setting_author` VALUES ('12', '0', 'Favicon', 'config_icon', 'favicon.ico');
INSERT INTO `amg_setting_author` VALUES ('35', '0', 'Top Masthead Color', 'config_masthead_color', 'black');
INSERT INTO `amg_setting_author` VALUES ('36', '0', 'Background Image', 'config_background_image', 'bg.png');
INSERT INTO `amg_setting_author` VALUES ('37', '0', 'Welcome Image', 'config_welcome_image', 'tex_welcome.png');
INSERT INTO `amg_setting_author` VALUES ('38', '0', 'Call to Action', 'config_call_to_action', 'Start your own <b>Comic</b>');
INSERT INTO `amg_setting_author` VALUES ('120', '0', 'Header Expansion Button Visible', 'header_expand_btn', '0');
INSERT INTO `amg_setting_author` VALUES ('114', '0', 'Google Analytics Code', 'config_google_analytics_code', '0');
INSERT INTO `amg_setting_author` VALUES ('108', '0', 'Facebook app id', 'fb_app_id', '');
INSERT INTO `amg_setting_author` VALUES ('109', '0', 'Facebook app secret', 'fb_app_secret', '');
INSERT INTO `amg_setting_author` VALUES ('137', '0', 'Template Folder Name <br> Note: Ending slash needed', 'config_template', 'default/');
INSERT INTO `amg_setting_author` VALUES ('140', '0', 'About File Name<br/>(xml files only)', 'about_filename', 'about.xml');
INSERT INTO `amg_setting_author` VALUES ('143', '0', 'News File Name<br/>(xml files only)', 'news_filename', 'news.xml');
INSERT INTO `amg_setting_author` VALUES ('147', '0', 'Categorize Albums', 'config_show_cats', '0');
INSERT INTO `amg_setting_author` VALUES ('188', '0', 'Show Left Menu', 'config_show_left_menu', '0');
INSERT INTO `amg_setting_author` VALUES ('191', '0', 'Sign In Message', 'config_sign_in_msg', 'Sign in with <b>Facebook</b>');
INSERT INTO `amg_setting_author` VALUES ('194', '0', 'Image Quality', 'config_image_quality', '90');

-- ----------------------------
-- Table structure for `amg_user`
-- ----------------------------
DROP TABLE IF EXISTS `amg_user`;
CREATE TABLE `amg_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `emb_user_id` int(11) unsigned NOT NULL,
  `username` varchar(63) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'email address',
  `password` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ip` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `nickname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `store_id` int(11) NOT NULL,
  `twitter_access_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `zip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `admin_level` int(11) NOT NULL,
  `fb_id` bigint(22) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of amg_user
-- ----------------------------
INSERT INTO `amg_user` VALUES ('1', '1', 'tyisreally@gmail.com', '26cae7718c32180a7a0f8e19d6d40a59', '', '1', '', 'Ty Underwood', '0', '', '', '1', '3734184918524');
