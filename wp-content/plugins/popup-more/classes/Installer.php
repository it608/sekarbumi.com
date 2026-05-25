<?php
namespace YpmPopup;
use \YpmShowReviewNotice;

class Installer
{
	public static function createTables($blogsId)
	{
		global $wpdb;
		$ycfContactFormFields = "CREATE TABLE IF NOT EXISTS ". $wpdb->prefix.$blogsId."ypm_contact_form_fields (
			`field_id` int(11) NOT NULL AUTO_INCREMENT,
			`form_id` int(11) NOT NULL,
			`fields_data` TEXT NOT NULL,
			PRIMARY KEY (field_id)
			)  ENGINE=InnoDB DEFAULT CHARSET=utf8; ";
		$wpdb->query($ycfContactFormFields);
		self::createSubscriptionTables($blogsId);
		self::createAiMessages($blogsId);
	}

	public static function createAiMessages($blogsId = '') {
		global $wpdb;
		$ypmContactFormFields = "CREATE TABLE IF NOT EXISTS ". $wpdb->prefix.$blogsId.YPM_AI_CHAT_MESSAGES." (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`ip_address` varchar(255) NOT NULL,
			`chat_id` int(11) NOT NULL,
			`user_id` int(11),
			`message` TEXT NOT NULL,
			`response` TEXT NOT NULL,
			`cDate` date NOT NULL,
			PRIMARY KEY (id)
			)  ENGINE=InnoDB DEFAULT CHARSET=utf8; ";
		$wpdb->query($ypmContactFormFields);
	}

	public static function createSubscriptionTables($blogsId = '') {
		global $wpdb;
		$ycfContactFormFields = "CREATE TABLE IF NOT EXISTS ". $wpdb->prefix.$blogsId.YPM_SUBSCRIPTION_FIELDS_TABLE_NAME." (
			`field_id` int(11) NOT NULL AUTO_INCREMENT,
			`form_id` int(11) NOT NULL,
			`fields_data` TEXT NOT NULL,
			PRIMARY KEY (field_id)
			)  ENGINE=InnoDB DEFAULT CHARSET=utf8; ";
		$wpdb->query($ycfContactFormFields);

		$subscribersSQL = "CREATE TABLE IF NOT EXISTS ". $wpdb->prefix.$blogsId.YPM_SUBSCRIBERS_TABLE_NAME.' (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`firstName` varchar(255),
					`lastName` varchar(255),
					`email` varchar(255),
					`formId` int(11),
					`popupId` int(11),
					`cDate` date,
					`status` varchar(255),
					`options` varchar(255),
					`unsubscribed` int(11) default 0,
					PRIMARY KEY (id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8; ';
		$wpdb->query($subscribersSQL);
	}

	public static function install()
	{
		$obj = new self();
		YpmShowReviewNotice::setInitialDates();
		$obj->createTables("");
		if(is_multisite()) {
			$sites = wp_get_sites();
			foreach($sites as $site) {
				$blogsId = $site['blog_id']."_";
				$obj->createTables($blogsId);
			}
		}
	}

	public static function uninstall()
	{
		$obj = new self();
		YpmShowReviewNotice::deleteInitialDates();
		$obj->uninstallTables("");
		if(is_multisite()) {
			$sites = wp_get_sites();
			foreach($sites as $site) {
				$blogsId = $site['blog_id']."_";
				$obj->uninstallTables($blogsId);
			}
		}
	}

	public function uninstallTables($blogsId)
	{
		global $wpdb;
		$tableNames = array(
			YPM_SUBSCRIPTION_FIELDS_TABLE_NAME,
			YPM_SUBSCRIBERS_TABLE_NAME,
			'ypm_contact_form_fields',
			YPM_AI_CHAT_MESSAGES
		);
		foreach ($tableNames as $tableName) {
			$ycfContactFormTable = $wpdb->prefix.$blogsId.$tableName;
			$prepareQuery = $wpdb->prepare(`DROP TABLE %i`, $ycfContactFormTable);
			$wpdb->query($prepareQuery);
		}
	}
}