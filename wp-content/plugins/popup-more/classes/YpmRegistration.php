<?php
use YpmPopup\Popup;

class YpmRegistration
{

	private $popupTypeObj;

	public function __construct()
	{
		$this->createPostType();
		$this->menuAction();
		$this->registerCustomPostType();
	}

	public function createPostType()
	{
		global $YpmPostTypesInfo;
		$popupId = 0;
		$popupPostType = 'ypmHtml';

		if (!empty($_GET['post'])) {
			$popupId = (int)sanitize_text_field($_GET['post']);
		}

		if (!empty($_GET['post_type']) && $_GET['post_type'] != YPM_POPUP_POST_TYPE) {
			$popupPostType = sanitize_text_field($_GET['post_type']);
		}

		if (!empty($_GET['post'])) {
			$popupPostType = get_post_type(sanitize_text_field((int)$_GET['post']));
		}

		$popupPostTypes = $YpmPostTypesInfo['postTypes'];
		if ($popupPostType == YPM_POPUP_POST_TYPE || empty($popupPostTypes[$popupPostType])) {
			$popupPostType = 'ypmHtml';
		}

		$popupTypeObj = Popup::findByIdAndType($popupPostType, $popupId);
		$this->setPopupTypeObj($popupTypeObj);
	}

	public function setPopupTypeObj($popupTypeObj)
	{

		$this->popupTypeObj = $popupTypeObj;
	}

	public function getPopupTypeObj()
	{

		return $this->popupTypeObj;
	}

	private function createModuleLabels($moduleKey)
	{

		$labels = array(
			'name' => esc_html($moduleKey . ' module'),
			'singular_name' => esc_html($moduleKey),
			'all_items' => esc_html($moduleKey . ' module'),
			'add_new_item' => esc_html("Add New $moduleKey"),
			'edit_item' => esc_html("Edit $moduleKey"),
			'new_item' => esc_html("New $moduleKey"),
			'view_item' => esc_html("View $moduleKey"),
			'search_items' => esc_html("Search $moduleKey"),
			'not_found' => esc_html("No $moduleKey found"),
			'not_found_in_trash' => esc_html("No $moduleKey found in Trash"),
			'add_new' => esc_html("Add $moduleKey"),
		);

		return $labels;
	}

	public function registerCustomPostType()
	{

		global $YpmPostTypesInfo;
		$popupPostTypes = $YpmPostTypesInfo['postTypes'];

		$labels = array(
			'name' => _x('Popup More', 'post type general name', 'popup_master'),
			'singular_name' => _x('Popup More', 'post type singular name', 'popup_master'),
			'menu_name' => _x('Popup More', 'admin menu', 'popup_master'),
			'name_admin_bar' => _x('Popup More', 'add new on admin bar', 'popup_master'),
			'add_new' => _x('Add New', 'popup', 'popup_master'),
			'add_new_item' => esc_attr__('Add New Popup', 'popup_master'),
			'new_item' => esc_attr__('New Popup', 'popup_master'),
			'edit_item' => esc_attr__('Edit Popup', 'popup_master'),
			'view_item' => esc_attr__('View Popup', 'popup_master'),
			'all_items' => esc_attr__('All Popups', 'popup_master'),
			'search_items' => esc_attr__('Search Popups', 'popup_master'),
			'parent_item_colon' => esc_attr__('Parent Popups:', 'popup_master'),
			'not_found' => esc_attr__('No popups found.', 'popup_master'),
			'not_found_in_trash' => esc_attr__('No popups found in Trash.', 'popup_master')
		);

		$args = array(
			'labels' => $labels,
			'description' => esc_attr__('Description.', 'popup_master'),
			'public' => true, // false prevent other themes affect to hide editor
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'rewrite' => array('slug' => 'popup'),
			'capability_type' => 'post',
			'has_archive' => true,
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array()
		);

		register_post_type(YPM_POPUP_POST_TYPE, $args);

		$this->registerPopupTypePosts();

		add_filter('manage_' . YPM_POPUP_POST_TYPE . '_posts_columns', array($this, 'popupsStickyColumn'));
		add_action('manage_' . YPM_POPUP_POST_TYPE . '_posts_custom_column', array($this, 'popupTableColumnValues'), 10, 2);
		\YpmPopup\DataConfig::init();
	}

	private function registerPopupTypePosts()
	{

		global $YpmPostTypesInfo;
		$popupPostTypes = $YpmPostTypesInfo['postTypes'];
		$popupPostTypesLabels = $YpmPostTypesInfo['postTypesLabels'];
		unset($popupPostTypes[YPM_POPUP_POST_TYPE]);

		if (empty($popupPostTypes)) {
			return;
		}
		foreach ($popupPostTypes as $postType => $level) {

			if ($postType === YPM_IMAGE_POST_TYPE) {
				continue;
			}
			$labelName = ucfirst($postType);
			if (!empty($popupPostTypesLabels[$postType])) {
				$labelName = $popupPostTypesLabels[$postType];
			}

			$postTypeLabels = apply_filters($postType . 'ModuleLabels', $this->createModuleLabels($labelName), $postType);

			if ($level > YPM_POPUP_PKG && YPM_POPUP_PKG > YPM_POPUP_FREE) {
				continue;
			}
			if ($level > YPM_POPUP_PKG) {
				$postTypeLabels['all_items'] = $postTypeLabels['all_items'] . '<span style="color: red"> (Pro)</span>';
				$postTypeLabels['add_new_item'] = $postTypeLabels['add_new_item'] . '<span style="color: red"> (Pro)</span>';
				$postTypeLabels['name'] = $postTypeLabels['name'] . ' (Pro) ';
			}
			$postTypeArgs = array(
				'labels' => $postTypeLabels,
				'show_ui' => true,
				'show_in_nav_menus' => false,
				'show_in_menu' => 'edit.php?post_type=' . YPM_POPUP_POST_TYPE,
				'show_in_admin_bar' => false,
				'query_var' => false,
				'supports' => apply_filters($postType . 'Support', array('title')),
			);
			register_post_type($postType, apply_filters($postType . 'TypeArgs', $postTypeArgs));

			add_filter('manage_' . strtolower($postType) . '_posts_columns', array($this, 'popupModulesStickyColumns'),2,2);
			add_action('manage_' . strtolower($postType) . '_posts_custom_column', array($this, 'postTypeColumn'), 10, 2);
		}
	}

	public function popupTableColumnValues($column, $postId)
	{
		$popup = Popup::find($postId);

		if ($column == 'shortcode') {
			$shortCodeName = '[ypm_popup id="'.esc_attr($postId).'"]';
			echo YpmAdminHelper::copyClipboard($postId, $shortCodeName);
		}
		if ($column == 'ypmviews') {
			$count = (int)get_option('YpmPopupCount'.esc_attr($postId));
			ob_start();
			?>
				<div class='ypm-count-view-box'><?php echo esc_attr($count); ?></div>
                <?php if (!empty($count)): ?>
				    <input type="button" data-id="<?php echo esc_attr($postId); ?>" class="button ypm-reset-count-btn" value="<?php esc_attr_e('reset', 'popup_master')?>">
                <?php endif; ?>
			<?php
			$content = ob_get_contents();
			ob_end_clean();

			echo wp_kses($content, YpmAdminHelper::getAllowedTags());
		}
		if ($column == 'ypmtype') {
			global $YpmPostTypesInfo;
			$typeTitle = 'HTML';
			$type = $popup->getOptionValue('ypm-popup-type');
			if (!empty($YpmPostTypesInfo['postTypesLabels'][$type])) {
				$typeTitle = $YpmPostTypesInfo['postTypesLabels'][$type];
			}
			echo esc_attr($typeTitle);
		}
		if ($column == 'enable_disable') {
			$isActive = false;
			if (!empty($popup)) {
			    $isActive = $popup->getOptionValue('ypm-is-active', true);
			}
			$checked = isset($isActive) && $isActive ? 'checked' : '';
            ?>
	            <label class="ypm-switch">
	                <input type="checkbox" class="ypm-popup-enable" id="ypm-popup-enable-<?php echo esc_attr($postId); ?>" name="ypm-popup-enable" data-switch-id="<?php echo esc_attr($postId); ?>" <?php echo esc_attr($checked); ?>>
	                <span class="ypm-slider ypm-round"></span>
	            </label>
            <?php
        }
	}

	public function postTypeColumn($column, $postId)
	{
		if ($column == 'shortcode') {
			$postType = get_post_type($postId);
			$popupTypeObj = Popup::findByIdAndType($postType, $postId);
			$shortKey = $popupTypeObj->shortCodeName;
			$shortCodeName = '['.esc_attr($shortKey).' id="'.esc_attr($postId).'"]';
			echo YpmAdminHelper::copyClipboard($postId, $shortCodeName);
		}
	}

	public function stickyColumns(& $columns)
	{
		unset($columns['author']);
		unset($columns['date']);
		$columns = array_merge($columns,
			array('shortcode' => esc_attr__('Shortcode')));
	}

	public function popupsStickyColumn($columns)
	{
		$this->stickyColumns($columns);
		return array_merge( $columns,
			array('enable_disable' => esc_attr__('Enable/Disable')) ,
			array('ypmviews' => esc_attr__('Views')),
			array('ypmtype' => esc_attr__('Type'))
		);
	}

	public function popupModulesStickyColumns($columns)
	{
		$this->stickyColumns($columns);

		return $columns;
	}

	public function renderMetaboxes($metaboxes)
	{
		foreach ($metaboxes as $key => $metabox) {

			$postTypes = $metabox['support_post_type'];
			if ($postTypes == 'allTypes') {
				global $YpmDefaultsData;
				$postTypes = $YpmDefaultsData['postTypes'];
			}
			foreach ($postTypes as $type) {
				$position = 'advanced';
				if (!empty($metabox['possition'])) {
					$position = $metabox['possition'];
				}
				$priority = 'default';
				if (!empty($metabox['priority'])) {
					$priority = $metabox['priority'];
				}
 				add_meta_box($key, $metabox['label'], $metabox['callback'], $type, $position, $priority);
			}
		}
	}

	public function addMetaBoxes()
	{
		YpmPopup\Popup::getPopupIdTitleData();
		//add_meta_box('popup_master_dimension', esc_attr__('Popup dimensions', 'popup_master'), array($this, 'popupDimensionsOptions'), YPM_POPUP_POST_TYPE, 'advanced');
	}


	public function upgradeToProMetabox()
	{
		global $YpmDefaultsData;
		$postTypes = $YpmDefaultsData['postTypes'];
		add_meta_box('popup_master_upgrade', esc_attr__('Upgrade', 'popup_master'), array($this, 'upgradeToPro'), $postTypes, 'side');
	}



	public function popupDisplayRules($params)
	{
		$popupTypeObj = $this->getPopupTypeObj();
		if(file_exists(YPM_POPUP_GENERAL_METABOXES.'displayRules.php')) {
			require_once(YPM_POPUP_GENERAL_METABOXES.'displayRules.php');
		}
	}

	public function popupOpenEvents($params)
	{
		$popupTypeObj = $this->getPopupTypeObj();
		if(file_exists(YPM_POPUP_GENERAL_METABOXES.'openEvents.php')) {
			require_once(YPM_POPUP_GENERAL_METABOXES.'openEvents.php');
		}
	}

	public function popupDimensionsOptions($params)
	{
		$popupTypeObj = $this->getPopupTypeObj();
		if(file_exists(YPM_POPUP_VIEW.'dimensions.php')) {
			require_once(YPM_POPUP_VIEW.'dimensions.php');
		}
	}

	public function popupGeneralOptions()
	{
		$popupTypeObj = $this->getPopupTypeObj();
		if(file_exists(YPM_POPUP_GENERAL_METABOXES.'general.php')) {
			require_once(YPM_POPUP_GENERAL_METABOXES.'general.php');
		}
	}

	public function popupExitIntent()
	{
		$popupTypeObj = $this->getPopupTypeObj();
		if(file_exists(YPM_POPUP_VIEW.'exit.php')) {
			require_once(YPM_POPUP_VIEW.'exit.php');
		}
	}

	public function customFunctionality()
	{
		$popupTypeObj = $this->getPopupTypeObj();
		if(file_exists(YPM_POPUP_VIEW.'customFunctionality.php')) {
			require_once(YPM_POPUP_VIEW.'customFunctionality.php');
		}
	}

	public function popupConditions()
	{
		$popupTypeObj = $this->getPopupTypeObj();
		require_once(YPM_POPUP_GENERAL_METABOXES.'conditions.php');
	}

	public function upgradeToPro()
	{
		if(file_exists(YPM_POPUP_VIEW.'upgrade.php')) {
			require_once(YPM_POPUP_VIEW.'upgrade.php');
		}
	}

	public function featureRequest()
	{
		require_once(YPM_POPUP_GENERAL_METABOXES.'/helper/featureRequest.php');
	}

	public function support()
	{
		require_once(YPM_POPUP_VIEW.'support.php');
	}

	public function specialEvent()
	{
		$popupTypeObj = $this->getPopupTypeObj();
		include(YPM_POPUP_METABOXES.'specialEvents.php');
	}

	public function floatingButton()
	{
		$popupTypeObj = $this->getPopupTypeObj();
		include(YPM_POPUP_METABOXES.'floatingButton.php');
	}

	public function info()
	{
		$popupTypeObj = $this->getPopupTypeObj();
		if(file_exists(YPM_POPUP_VIEW.'info.php')) {
			require_once(YPM_POPUP_VIEW.'info.php');
		}
	}

	public function statistic()
	{
        $popupTypeObj = $this->getPopupTypeObj();
		include(YPM_POPUP_METABOXES."general/statistic.php");
	}

	public function popupCloseBehavior()
	{
		$popupTypeObj = $this->getPopupTypeObj();
		require_once(YPM_POPUP_GENERAL_METABOXES.'/closeBehavior.php');
	}

	public function popupGeneralSettings() {

		$popupTypeObj = $this->getPopupTypeObj();
		if(file_exists(YPM_POPUP_VIEW.'settings.php')) {
			require_once(YPM_POPUP_VIEW.'settings.php');
		}
	}

	public function popupFacebookOptions() {

		$popupTypeObj = $this->getPopupTypeObj();
		if(file_exists(YPM_POPUP_VIEW.'facebookOptions.php')) {
			require_once(YPM_POPUP_VIEW.'facebookOptions.php');
		}
	}

	public function popupAgeRestriction() {

		$popupTypeObj = $this->getPopupTypeObj();
		include_once(YPM_POPUP_VIEW."./ageRestriction/ageRestriction.php");
	}

	public function popupYoutubeOptions()
	{
		$popupTypeObj = $this->getPopupTypeObj();
		if(file_exists(YPM_POPUP_YOUTUBE_VIEW.'youtubeOptions.php')) {
			require_once(YPM_POPUP_YOUTUBE_VIEW.'youtubeOptions.php');
		}
	}

	public function popupIframeOptions()
	{
		$popupTypeObj = $this->getPopupTypeObj();
		if(file_exists(YPM_POPUP_VIEW.'iframe.php')) {
			require_once(YPM_POPUP_VIEW.'iframe.php');
		}
	}

	public function popupSocialOptions()
	{
		$popupTypeObj = $this->getPopupTypeObj();
		if(file_exists(YPM_POPUP_VIEW.'social.php')) {
			require_once(YPM_POPUP_VIEW.'social.php');
		}
	}

	public function popupCountdownOptions()
	{
		$popupTypeObj = $this->getPopupTypeObj();
		if(file_exists(YPM_POPUP_COUNTDOWN_VIEW.'countdown.php')) {
			require_once(YPM_POPUP_COUNTDOWN_VIEW.'countdown.php');
		}
	}

    public function gamification()
	{
		$popupTypeObj = $this->getPopupTypeObj();
		include(YPM_POPUP_GAMIFICATION_VIEW."/main.php");
	}
    
	public function wheel()
	{
		$popupTypeObj = $this->getPopupTypeObj();
		include(YPM_POPUP_WHEEL_VIEW."/main.php");
	}

	public function countdownExpiration()
	{
		$popupTypeObj = $this->getPopupTypeObj();
		if(file_exists(YPM_POPUP_COUNTDOWN_VIEW.'countdownExpiration.php')) {
			require_once(YPM_POPUP_COUNTDOWN_VIEW.'countdownExpiration.php');
		}
	}

	public function subscriptionFormFields()
	{
		$popupTypeObj = $this->getPopupTypeObj();
		if(file_exists(YPM_POPUP_VIEW.'subscription/subscriptionOptions.php')) {
			require_once(YPM_POPUP_VIEW.'subscription/subscriptionOptions.php');
		}
	}

	public function contactFormFields()
	{
		$popupTypeObj = $this->getPopupTypeObj();
		if(file_exists(YPM_POPUP_VIEW.'contact-form-type/contactFormOptions.php')) {
			require_once(YPM_POPUP_VIEW.'contact-form-type/contactFormOptions.php');
		}
	}

	public function aisettings() {
        $popupTypeObj = $this->getPopupTypeObj();
        require_once YPM_POPUP_VIEW."/chatGPT/settings.php";
    }

	public function aiMessages() {
        $popupTypeObj = $this->getPopupTypeObj();
        require_once YPM_POPUP_VIEW."/chatGPT/messages.php";
    }

	private function menuAction()
	{
		add_action("admin_menu", array($this, 'adminMenu'), 9, 9);
	}

	public function adminMenu()
	{
		add_submenu_page(
			'edit.php?post_type='.YPM_POPUP_POST_TYPE,
			__('Popup Types', 'popup_master'), // page title
			__('Popup Types', 'popup_master'), // menu title
			'manage_options',
			YPM_POPUP_POST_TYPE,
			array($this, 'typesPage')
		);
		add_submenu_page('edit.php?post_type='.YPM_POPUP_POST_TYPE, esc_attr__('Subscribers', 'popup_master'), esc_attr__('Subscribers', 'popup_master'), 'manage_options', YPM_SUBSCRIBERS_PAGE, array($this, 'subscribers'));
		add_submenu_page('edit.php?post_type='.YPM_POPUP_POST_TYPE, esc_attr__('Settings', 'popup_master'), esc_attr__('Settings', 'popup_master'), 'manage_options', YPM_SETTINGS_PAGE, array($this, 'settings'));
		$allowToShowMenu = get_option('ypm-hide-modules-menu');

		if (!empty($allowToShowMenu)) {
			return false;
		}
		add_menu_page(__('AI Chat module', 'popup_master'), esc_attr__('AI Chat module', 'popup_master'), 'manage_options', YPM_AICHAT_POST_TYPE, array($this, 'aiChatModule'), 'dashicons-chat', 12);
		add_menu_page(__('Facebook module', 'popup_master'), esc_attr__('Facebook module', 'popup_master'), 'manage_options', YPM_FACEBOOK_POST_TYPE, array($this, 'facebookModule'), 'dashicons-facebook', 13);
		add_menu_page(__('Youtube module', 'popup_master'), esc_attr__('Youtube module', 'popup_master'), 'manage_options', YPM_YOUTUBE_POST_TYPE, array($this, 'youtubeModule'), 'dashicons-video-alt3', 14);
		add_menu_page(__('Countdown module', 'popup_master'), esc_attr__('Countdown module', 'popup_master'), 'manage_options', YPM_COUNTDOWN_POST_TYPE, array($this, 'countdownModule'), 'dashicons-clock', 15);
	}

	public function typesPage()
	{
		if(file_exists(YPM_POPUP_VIEW.'popupTypes.php')) {
			require_once(YPM_POPUP_VIEW.'popupTypes.php');
		}
	}

	public function settings()
	{
		if(file_exists(YPM_POPUP_VIEW.'menuSettings.php')) {
			require_once(YPM_POPUP_VIEW.'menuSettings.php');
		}
	}

	public function subscribers()
	{
		if(file_exists(YPM_POPUP_VIEW.'subscribers.php')) {
			require_once(YPM_POPUPS.'/SubscriptionPopup.php');
			require_once(YPM_POPUP_CLASSES.'/dataTable/Subscribers.php');
			require_once(YPM_POPUP_VIEW.'subscribers.php');
		}
	}

	public function aiChatModule()
	{

	}
	
	public function facebookModule()
	{

	}

    public function countdownModule()
	{

	}
}