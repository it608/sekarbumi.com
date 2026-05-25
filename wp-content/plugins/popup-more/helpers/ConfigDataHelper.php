<?php
namespace ypm;

class ConfigDataHelper
{
	public static $customPostType;

	public static function getPostTypeData($args = array())
	{
		$query = self::getQueryDataByArgs($args);

		$posts = array();
		foreach ($query->posts as $post) {
			$posts[$post->ID] = $post->post_title;
		}

		return $posts;
	}

	public static function getQueryDataByArgs($args = array())
	{
		$defaultArgs = array(
			'offset'           => '',
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_status'      => 'publish',
			'suppress_filters' => true,
			'post_type'        => 'post',
			'posts_per_page'   => 1000
		);
		$args = wp_parse_args($args, $defaultArgs);
		$query = new \WP_Query($args);

		return $query;
	}

	public static function getAllCustomPosts()
	{
		$args = array(
			'public' => true,
			'_builtin' => false
		);

		$allCustomPosts = get_post_types($args);

		if (isset($allCustomPosts[YPM_POPUP_POST_TYPE])) {
			unset($allCustomPosts[YPM_POPUP_POST_TYPE]);
		}

		return $allCustomPosts;
	}

	public static function addFilters()
	{
		self::addPostTypeToFilters();
	}

	private static function addPostTypeToFilters()
	{
		add_filter('ypmPopupTargetParams', array(__CLASS__, 'addPopupTargetParams'), 1, 1);
		add_filter('ypmPopupTargetData', array(__CLASS__, 'addPopupTargetData'), 1, 1);
		add_filter('ypmPopupTargetTypes', array(__CLASS__, 'addPopupTargetTypes'), 1, 1);
		add_filter('ypmPopupTargetAttrs', array(__CLASS__, 'addPopupTargetAttrs'), 1, 1);
		add_filter('ypmPopupPageTemplates', array(__CLASS__, 'addPopupPageTemplates'), 1, 1);
	}

	public static function addPopupTargetParams($targetParams)
	{
		$allCustomPostTypes = self::getAllCustomPosts();
		// for conditions, to exclude other post types, tags etc.
		if (isset($targetParams['select_role'])) {
			return $targetParams;
		}

		foreach ($allCustomPostTypes as $customPostType) {
			$targetParams[$customPostType] = array(
				$customPostType.'_all' => 'All '.ucfirst($customPostType).'s',
				$customPostType.'_archive' => 'Archives '.ucfirst($customPostType).'s',
				$customPostType.'_selected' => 'Select '.ucfirst($customPostType).'s',
				$customPostType.'_categories' => 'Select '.ucfirst($customPostType).' categories'
			);
		}

		return $targetParams;
	}

	public static function addPopupTargetData($targetData)
	{
		$allCustomPostTypes = self::getAllCustomPosts();

		foreach ($allCustomPostTypes as $customPostType) {
			$targetData[$customPostType.'_all'] = null;
			$targetData[$customPostType.'_selected'] = '';
			$targetData[$customPostType.'_categories'] = self::getCustomPostCategories($customPostType);
		}

		return $targetData;
	}

	public static function getCustomPostCategories($postTypeName)
	{
		$taxonomyObjects = get_object_taxonomies($postTypeName);
		if ($postTypeName == 'product') {
			$taxonomyObjects = array('product_cat');
		}
		$categories = self::getPostsAllCategories($postTypeName, $taxonomyObjects);

		return $categories;
	}

	public static function addPopupTargetTypes($targetTypes)
	{
		$allCustomPostTypes = self::getAllCustomPosts();

		foreach ($allCustomPostTypes as $customPostType) {
			$targetTypes[$customPostType.'_selected'] = 'select';
			$targetTypes[$customPostType.'_categories'] = 'select';
		}

		return $targetTypes;
	}

	public static function addPopupTargetAttrs($targetAttrs)
	{
		$allCustomPostTypes = self::getAllCustomPosts();

		foreach ($allCustomPostTypes as $customPostType) {
			$targetAttrs[$customPostType.'_selected']['htmlAttrs'] = array('class' => 'js-ypm-select2 js-select-ajax', 'data-select-class' => 'js-select-ajax', 'data-select-type' => 'ajax', 'data-value-param' => $customPostType, 'multiple' => 'multiple');
			$targetAttrs[$customPostType.'_selected']['infoAttrs'] = array('label' => esc_attr__('Select ', 'popup_master').esc_attr($customPostType));

			$targetAttrs[$customPostType.'_categories']['htmlAttrs'] = array('class' => 'js-ypm-select2 js-select-ajax', 'data-select-class' => 'js-select-ajax', 'isNotPostType' => true, 'data-value-param' => $customPostType, 'multiple' => 'multiple');
			$targetAttrs[$customPostType.'_categories']['infoAttrs'] = array('label' => esc_attr__('Select ', 'popup_master').esc_attr($customPostType).' categories');
		}

		return $targetAttrs;
	}

	public static function addPopupPageTemplates($templates)
	{
		$pageTemplates = self::getPageTemplates();

		$pageTemplates += $templates;

		return $pageTemplates;
	}

	public static function getAllCustomPostTypes()
	{
		$args = array(
			'public' => true,
			'_builtin' => false
		);

		$allCustomPosts = get_post_types($args);
		if (!empty($allCustomPosts[YPM_POPUP_POST_TYPE])) {
			unset($allCustomPosts[YPM_POPUP_POST_TYPE]);
		}

		return $allCustomPosts;
	}

	public static function getPostsAllCategories($postType = 'post', $taxonomies = array())
	{
		$cats = get_transient(YPM_TRANSIENT_POPUPS_ALL_CATEGORIES);
		if ($cats === false) {
			$cats =  get_terms(
				array(
					'taxonomy' => $taxonomies,
					'hide_empty' => false,
					'type'      => $postType,
					'orderby'   => 'name',
					'order'     => 'ASC'
				)
			);
			set_transient(YPM_TRANSIENT_POPUPS_ALL_CATEGORIES, $cats, YPM_TRANSIENT_TIMEOUT_WEEK);
		}

		$supportedTaxonomies = array('category');
		if (!empty($taxonomies)) {
			$supportedTaxonomies = $taxonomies;
		}

		$catsParams = array();
		foreach ($cats as $cat) {
			if (isset($cat->taxonomy)) {
				if (!in_array($cat->taxonomy, $supportedTaxonomies)) {
					continue;
				}
			}
			$id = $cat->term_id;
			$name = $cat->name;
			$catsParams[$id] = $name;
		}

		return $catsParams;
	}

	public static function getPageTypes()
	{
		$postTypes = array();

		$postTypes['is_home_page'] = esc_attr__('Home Page', 'popup_master');
		$postTypes['is_home'] = esc_attr__('Posts Page', 'popup_master');
		$postTypes['is_search'] = esc_attr__('Search Pages', 'popup_master');
		$postTypes['is_404'] = esc_attr__('404 Pages', 'popup_master');
		if (function_exists('is_shop')) {
			$postTypes['is_shop'] = esc_attr__('Shop Page', 'popup_master');
		}
		if (function_exists('is_archive')) {
			$postTypes['is_archive'] = esc_attr__('Archive Page', 'popup_master');
		}

		return $postTypes;
	}

	public static function getPageTemplates()
	{
		$pageTemplates = array(
			'page.php' => esc_attr__('Default Template', 'popup_master')
		);

		$templates = wp_get_theme()->get_page_templates();
		if (empty($templates)) {
			return $pageTemplates;
		}

		foreach ($templates as $key => $value) {
			$pageTemplates[$key] = $value;
		}

		return $pageTemplates;
	}

	public static function getAllTags()
	{
		$allTags = array();
		$tags = get_tags(array(
			'hide_empty' => false
		));

		foreach ($tags as $tag) {
			$allTags[$tag->slug] = $tag->name;
		}

		return $allTags;
	}

	public static function defaultData()
	{
		$data = array();

		$data['pxPercent'] = array(
			'px' => 'px',
			'%' => '%'
		);
		

		$data['popupInsertEventTypes'] = array(
			'inherit' => esc_attr__('Inherit', 'popup_master'),
			'onLoad' => esc_attr__('On load', 'popup_master'),
			'click' => esc_attr__('On click', 'popup_master'),
			'hover' => esc_attr__('On hover', 'popup_master')
		);

		$data['userRoles'] = self::getAllUserRoles();

		return $data;
	}

	public static function getAllUserRoles()
	{
		$rulesArray = array();
		if (!function_exists('get_editable_roles')){
			return $rulesArray;
		}

		$roles = get_editable_roles();
		foreach ($roles as $roleName => $roleInfo) {
			if ($roleName == 'administrator') {
				continue;
			}
			$rulesArray[$roleName] = $roleName;
		}

		return $rulesArray;
	}

	public static function getClickActionOptions()
	{
		$settings = array(
			'defaultClickClassName' => esc_attr__('Default', 'popup_master'),
			'clickActionCustomClass' => esc_attr__('Custom class', 'popup_master')
		);

		return $settings;
	}

	public static function getHoverActionOptions()
	{
		$settings = array(
			'defaultHoverClassName' => esc_attr__('Default', 'popup_master'),
			'hoverActionCustomClass' => esc_attr__('Custom class', 'popup_master')
		);

		return $settings;
	}

	public static function getCurrentDateTime()
	{
		return date('Y-m-d H:i', strtotime(' +1 day'));
	}

	public static function getDefaultTimezone()
	{
		$timezone = get_option('timezone_string');
		if (!$timezone) {
			$timezone = 'America/New_York';
		}

		return $timezone;
	}
}
