<?php
namespace  ypmFrontend;

use \WP_Query;
use YpmPopup\Popup;

class PopupLoader
{
	private static $instance;
	private $loadablePopups = array();
	private $loadablePopupsIds = array();

	private function __construct()
	{
		require_once(ABSPATH.'wp-admin/includes/plugin.php');
	}

	public static function instance() {

		if (!isset(self::$instance)) {
			self::$instance = new self;
		}

		return self::$instance;
	}


	public function setLoadablePopupsIds($loadablePopupsIds)
	{
		$this->loadablePopupsIds = $loadablePopupsIds;
	}

	public function getLoadablePopupsIds()
	{
		return $this->loadablePopupsIds;
	}

	public function addLoadablePopup($popupObj)
	{
		$this->loadablePopups[] = $popupObj;
	}

	public function setLoadablePopups($loadablePopups)
	{
		$this->loadablePopups = $loadablePopups;
	}

	public function getLoadablePopups()
	{
		return $this->loadablePopups;
	}

	private function getPostContentByPost($post) {

		$postId = $post->ID;
		$meta = get_post_meta( $postId, '_elementor_data', true );

		if ( ! empty( $meta ) ) {
			$content = \Elementor\Plugin::instance()->frontend->get_builder_content( $postId );
		} else {
			$content = $post->post_content;
		}

		return $content;
	}

	public function loadPopups()
	{
		$popupPosts = new WP_Query(
			array(
				'post_type'      => YPM_POPUP_POST_TYPE,
				'posts_per_page' => -1
			)
		);

		while ($popupPosts->have_posts()) {
			$popupPosts->next_post();
			$popupPost = $popupPosts->post;
			$popupId = $popupPost->ID;

			$popup = Popup::find($popupId);
			if (empty($popup) || !is_object($popup) || !$popup->allowToLoad()) {
				continue;
			}

			$content = $this->getPostContentByPost($popupPost);
			$popup->setContent($content);

			$this->addLoadablePopup($popup);
		}
		$this->doGroupFiltersPopups();
		$popups = $this->getLoadablePopups();

		$scriptsLoader = new ScriptsLoader();
		$scriptsLoader->setLoadablePopups($popups);
		$scriptsLoader->loadToFooter();
	}

	private function doGroupFiltersPopups()
	{
		$popups = $this->getLoadablePopups();
		$groupObj = new PopupGroupFilter();
		$groupObj->setPopups($popups);
		$popups = $groupObj->filter();

		$this->setLoadablePopups($popups);
	}
}