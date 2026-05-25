<?php
namespace YpmPopup;

class AdminPost
{
	public function __construct()
	{
		$this->init();
	}

	public function init()
	{
		add_action('admin_post_ypmSaveSettings', array($this, 'saveSettings'), 10, 1);
	}

	public function saveSettings()
	{
		if(isset($_POST)) {
			// Check for CSRF
			check_admin_referer('ypm_popup_settings');
		}
		$options = array(
			'ypm-hide-modules-menu',
			'ypm-hide-media-button'
		);
		foreach ($options as $option) {
			$current = '';
			if (!empty($_POST[$option])) {
				$current = sanitize_text_field($_POST[$option]);
			}
			update_option($option, $current);
		}
		$adminURL = admin_url('edit.php?post_type=' . YPM_POPUP_POST_TYPE);
		wp_redirect(add_query_arg(array(
			'page' => YPM_SETTINGS_PAGE,
			'saved' => 1
		), $adminURL));
	}
}

new AdminPost();