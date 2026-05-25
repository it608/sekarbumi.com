<?php
namespace YpmPopup;
require_once(dirname(__FILE__).'/Popup.php');
require_once(dirname(__FILE__).'/PopupViewInterface.php');

class FacebookPopup extends Popup implements PopupViewInterface
{

	public $shortCodeName = 'ypm_facebook';
	private $fbOptions;
	private $fbId;

	public function setFbOptions($fbOptions) {
		$this->fbOptions = $fbOptions;
	}

	public function getMenuLabelName() {

		return esc_attr__('Facebook', 'popup_master');
	}

	public function getFbOptions() {
		return $this->fbOptions;
	}

	public function setFbId($fbId) {
		$this->fbId = $fbId;
	}

	public function getFbId() {
		return $this->fbId;
	}

	public static function create($data, $obj = '') {
		$obj = new static();
		parent::create($data, $obj);
	}

	private function getFbScript() {

		$locale = $this->getSiteLocale();
		$script = '<div id="fb-root"></div>
			<script>(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/'.esc_attr($locale).'/sdk.js#xfbml=1&version=v2.10";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, \'script\', \'facebook-jssdk\'));</script>';

		return $script;
	}

	public function getFbLikeButtons() {

		$fbOptions = $this->getFbOptions();
		$fbId = (int)$this->getFbId();
		$href = '';
		$layout = 'standard';
		$action = 'like';
		$size = 'small';
		$shareStat = 'true';

		if(!empty($fbOptions['href'])) {
			$href = $fbOptions['href'];
		}
		if(!empty($fbOptions['layout'])) {
			$layout = $fbOptions['layout'];
		}
		if(!empty($fbOptions['fbAction'])) {
			$action = $fbOptions['fbAction'];
		}
		if(!empty($fbOptions['size'])) {
			$size = $fbOptions['size'];
		}
		if(!empty($fbOptions['shareStat'])) {
			$shareStat = false;
		}
		ob_start();
		?>
		<div class="fblike-button-wrapper fblike-button-wrapper-<?php echo esc_attr($fbId);?>">
			<div class="fb-like"
			     data-href="<?php echo esc_attr($href); ?>"
			     data-layout="<?php echo esc_attr($layout); ?>"
			     data-action="<?php echo esc_attr($action); ?>"
			     data-size="<?php echo esc_attr($size); ?>"
			     data-show-faces="true"
			     data-share="<?php echo esc_attr($shareStat); ?>">
			</div>
		</div>
		<?php
		$buttons = ob_get_contents();
		ob_end_clean();
		$buttons .= $this->buttonsStyle();
		return $buttons;
	}

	public function getFbLikeShareButton() {

		$fbOptions = $this->getFbOptions();
		$fbId = (int)$this->getFbId();
		$href = '';
		$layout = 'standard';
		$size = 'small';
		$alignment = 'center';

		if(!empty($fbOptions['href'])) {
			$href = $fbOptions['href'];
		}
		if(!empty($fbOptions['layout'])) {
			$layout = $fbOptions['layout'];
		}
		if(!empty($fbOptions['size'])) {
			$size = $fbOptions['size'];
		}
		if(!empty($fbOptions['alignment'])) {
			$alignment = $fbOptions['alignment'];
		}

		ob_start();
		?>
		<div style="text-align: <?php echo esc_attr($alignment); ?>;">
			<div class="fb-share-button"
			     data-href="<?php echo esc_url($href); ?>"
			     data-layout="<?php echo esc_attr($layout); ?>"
			     data-size="<?php echo esc_attr($size); ?>">
			</div>
		</div>
		<?php
		$buttons = ob_get_contents();
		ob_end_clean();

		return $buttons;
	}

	public function renderFbButtons() {

		$fbId = (int)$this->getFbId();
		$savedData = parent::getSavedData($fbId);

		$facebookType = $savedData['ypm-facebook-type'];
		$fbConfArray = array();
		$buttonContent = $this->getFbScript();

		if($facebookType == 'likeButton') {

			$fbConfArray['href'] = $savedData['ypm-facebook-url'];
			$fbConfArray['layout'] = $savedData['ypm-facebook-layout'];
			$fbConfArray['fbAction'] = $savedData['ypm-facebook-action'];
			$fbConfArray['size'] = $savedData['ypm-facebook-size'];
			$fbConfArray['alignment'] = $savedData['ypm-facebook-like-alignment'];
			$fbConfArray['shareStat'] = empty($savedData['ypm-facebook-share-button']);

			$this->setFbOptions($fbConfArray);

			$buttonContent .= $this->getFbLikeButtons();
		}
		else if($facebookType == 'shareButton') {
			$fbConfArray['href'] = $savedData['ypm-facebook-share-url'];
			$fbConfArray['layout'] = $savedData['ypm-facebook-share-layout'];
			$fbConfArray['size'] = $savedData['ypm-facebook-share-size'];
			$fbConfArray['alignment'] = $savedData['ypm-facebook-share-alignment'];

			$this->setFbOptions($fbConfArray);

			$buttonContent .= $this->getFbLikeShareButton();
		}

		return $buttonContent;
	}

	private function buttonsStyle() {

		$styles = '';
		$options = $this->getFbOptions();
		$buttonAlignment = $options['alignment'];
		$fbId = (int)$this->getFbId();

		$styles .= "<style>
			.fblike-button-wrapper-$fbId {
				text-align: $buttonAlignment;
			}
		</style>";

		return $styles;
	}

	public function renderView($args, $content)
	{
		$id = (int)$args['id'];
		$isPublished = Popup::isPostPublished($id);

		if(!$isPublished) {
			return false;
		}

		$fbObj = new FacebookPopup();
		$fbObj->setFbId($id);


		return $fbObj->renderFbButtons();
	}
}