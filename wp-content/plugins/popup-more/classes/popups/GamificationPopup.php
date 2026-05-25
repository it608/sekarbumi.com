<?php
namespace YpmPopup;
use \YpmAdminHelper;

class GamificationPopup extends Popup implements PopupViewInterface
{
	public $shortCodeName = 'ypm_gamification';

	public function __construct()
	{
		$this->includeJsFiles();
		add_filter('ypmDefaultOptions', array($this, 'defOptions'));
		add_filter('ypmRenderContent', array($this, 'content'), 2,2);
	}

	public function content($content, $type) {
		$popup = $type->getPopup();
		$typeName = $popup->getOptionValue('ypm-popup-type');

		if ($typeName == 'ypmgamification') {
			$content .= do_shortcode("[ypm_gamification id=".esc_attr($popup->getOptionValue('ypm-popup-sub-id'))."]");
		}
		return $content;
	}

	public function defOptions($options) {

		$options[] = array('name' => 'ypm-gamification-btn-width', 'type' => 'text', 'defaultValue' => '442px');
		$options[] = array('name' => 'ypm-gamification-btn-height', 'type' => 'text', 'defaultValue' => '68px');
		$options[] = array('name' => 'ypm-gamification-btn-border-radius', 'type' => 'text', 'defaultValue' => '5px');
		$options[] = array('name' => 'ypm-gamification-btn-border-width', 'type' => 'text', 'defaultValue' => '0px');
		$options[] = array('name' => 'ypm-gamification-btn-border-color', 'type' => 'text', 'defaultValue' => '#fab41c');
		$options[] = array('name' => 'ypm-gamification-btn-title', 'type' => 'text', 'defaultValue' => esc_attr__('Subscribe', 'popup_master'));
		$options[] = array('name' => 'ypm-gamification-btn-progress-title', 'type' => 'text', 'defaultValue' => esc_attr__('Please wait...', 'popup_master'));
		$options[] = array('name' => 'ypm-gamification-btn-bg-color', 'type' => 'text', 'defaultValue' => '#fab41c');
		$options[] = array('name' => 'ypm-gamification-btn-text-color', 'type' => 'text', 'defaultValue' => '#FFFFFF');

		$options[] = array('name' => 'ypm-gamification-text-placeholder', 'type' => 'text', 'defaultValue' => esc_attr__('Email', 'popup_master'));
		$options[] = array('name' => 'ypm-gamification-text-width', 'type' => 'text', 'defaultValue' => '442px');
		$options[] = array('name' => 'ypm-gamification-text-height', 'type' => 'text', 'defaultValue' => '68px');
		$options[] = array('name' => 'ypm-gamification-text-border-radius', 'type' => 'text', 'defaultValue' => '0px');
		$options[] = array('name' => 'ypm-gamification-text-border-width', 'type' => 'text', 'defaultValue' => '1px');
		$options[] = array('name' => 'ypm-gamification-text-border-color', 'type' => 'text', 'defaultValue' => '#CCCCCC');
		$options[] = array('name' => 'ypm-gamification-text-bg-color', 'type' => 'text', 'defaultValue' => '#FFFFFF');
		$options[] = array('name' => 'ypm-gamification-text-color', 'type' => 'text', 'defaultValue' => '#858383');
		$options[] = array('name' => 'ypm-gamification-text-placeholder-color', 'type' => 'text', 'defaultValue' => '#bbbab5');
		$options[] = array('name' => 'ypm-gamification-gift-image', 'type' => 'text', 'defaultValue' => YPM_GAMIFICATION_IMAGE_URL);

		$startScreen = $this->getStartHtmlContent();
		$playScreen = $this->getPlayScreen();
		$winScreen = $this->getWinScreen();
		$loseScreen = $this->getLoserText();

		$options[] = array('name' => 'ypm-gamification-start-text', 'type' => 'textMessage', 'defaultValue' =>  wp_kses($startScreen, YpmAdminHelper::getAllowedTags()));
		$options[] = array('name' => 'ypm-gamification-play-text', 'type' => 'textMessage', 'defaultValue' =>  wp_kses($playScreen, YpmAdminHelper::getAllowedTags()));
		$options[] = array('name' => 'ypm-gamification-lose-text', 'type' => 'textMessage', 'defaultValue' =>  wp_kses($loseScreen, YpmAdminHelper::getAllowedTags()));
		$options[] = array('name' => 'ypm-gamification-win-text', 'type' => 'textMessage', 'defaultValue' =>  wp_kses($winScreen, YpmAdminHelper::getAllowedTags()));

		$options[] = array('name' => 'ypm-gamification-error-message', 'type' => 'text', 'defaultValue' => esc_attr__('There was an error while trying to send your request. Please try again', 'popup_master').'.');
		$options[] = array('name' => 'ypm-gamification-invalid-message', 'type' => 'text', 'defaultValue' => esc_attr__('Please enter a valid email address', 'popup_master').'.');
		$options[] = array('name' => 'ypm-gamification-validation-message', 'type' => 'text', 'defaultValue' => esc_attr__('This field is required', 'popup_master').'.');
		$options[] = array('name' => 'ypm-gamification-gdpr-term', 'type' => 'text', 'defaultValue' => esc_attr__('* By giving your email address you agree with our terms and conditions', 'popup_master').'.');
		$options[] = array('name' => 'ypm-gamification-already-subscribed', 'type' => 'checkbox', 'defaultValue' => 'on');
		$options[] = array('name' => 'ypm-background-image-mode', 'type' => 'text', 'defaultValue' => 'cover');
		$options[] = array('name' => 'ypm-background-image', 'type' => 'text', 'defaultValue' => YPM_POPUP_IMAGES_URL."/gamification/gamification-default-bg.png");
		$options[] = array('name' => 'ypm-theme-close-image-url', 'type' => 'text', 'defaultValue' => YPM_POPUP_IMAGES_URL."/gamification/close-button.png");
		$options[] = array('name' => 'ypm-gamification-win-chance', 'type' => 'text', 'defaultValue' => "0");

		$changingOptions = array(
			'ypm-popup-theme' => array('name' => 'ypm-popup-theme', 'type' => 'text', 'defaultValue' => 'colorbox6'),
			'ypm-enable-bg-image' => array('name' => 'ypm-enable-bg-image', 'type' => 'checkbox', 'defaultValue' => 'on'),
			'ypm-background-image-mode' => array('name' => 'ypm-background-image-mode', 'type' => 'checkbox', 'defaultValue' => 'on'),
			'ypm-enable-close-delay' => array('name' => 'ypm-background-image-mode', 'type' => 'checkbox', 'defaultValue' => ''),
			'ypm-popup-dimensions-mode' => array('name' => 'ypm-popup-dimensions-mode', 'type' => 'text', 'defaultValue' => 'auto'),
			'ypm-theme-close-image-url' => array('name' => 'ypm-theme-close-image-url', 'type' => 'text', 'defaultValue' => YPM_POPUP_IMAGES_URL."/gamification/close-button.png")
		);
		$options = $this->changeDefaultOptionsByNames($options, $changingOptions);

		return $options;
	}
	private function getLoserText()
	{
		ob_start();
		?>
		<h3 class="ypm-gamification-loser-header" style="text-align: center;font-family: Segoe UI;font-size: 40px !important;color: #000000 !important;"><?php esc_attr_e('Oops!!!', 'popup_master'); ?></h3>
		<p class="ypm-gamification-loser-paragraph" style="text-align: center;font-size: 15px !important;font-family: Segoe UI;color: #8f8f8f !important; margin-bottom: 0;"><?php esc_attr_e('Not your lucky day! Next time you\'ll win!', 'popup_master'); ?></p>
		<div class="ypm-gamification-loser-img-wrapper" style="text-align: center;">
			<img src="<?php echo YPM_GAMIFICATION_LOSER_IMG_URL;?>" style="margin: 30px auto;">
		</div>
		<?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	private function getWinScreen()
	{
		ob_start();
		?>
		<h3 class="ypm-gamification-start-header" style="text-align: center;font-family: Segoe UI;font-size: 40px !important;color: #f1a528 !important;"><?php echo wp_kses('Congratulations!!!', YpmAdminHelper::getAllowedTags()); ?></h3>
		<p class="ypm-gamification-start-paragraph" style="text-align: center;font-size: 21px !important;font-family: Segoe UI;color: #8f8f8f !important; margin-bottom: 0;"><?php echo wp_kses('Your Discount Code Is: <span><b>HAPPYCUSTOMERCODE</b></span>', YpmAdminHelper::getAllowedTags()); ?></p>
		<?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	private function getStartHtmlContent()
	{
		ob_start();
		?>
		<h3 class="ypm-gamification-start-header" style="text-align: center;font-family: Segoe UI;font-size: 40px !important;color: #479dcb !important;"><?php esc_attr_e('Choose your gift', 'popup_master'); ?></h3>
		<p class="ypm-gamification-start-paragraph" style="text-align: center;font-size: 15px !important;font-family: Segoe UI;color: #000000 !important; margin-bottom: 0;"><?php esc_attr_e('Start the game to reveal your prize', 'popup_master'); ?></p>
		<?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	private function getPlayScreen()
	{
		ob_start();
		?>
		<h3 class="ypm-gamification-play-header" style="text-align: center;font-family: Segoe UI;font-size: 40px !important;color: #479dcb !important;"><?php esc_attr_e('Pick a gift to see what you\'ve won', 'popup_master'); ?></h3>
		<?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	private function includeJsFiles()
	{
		$jsUrl = YPM_POPUP_JS_URL.'/gamification/';
		$cssUrl = YPM_POPUP_CSS_URL.'/gamification/';

		if (is_admin()) {
			ScriptsManager::registerScript('GamificationBackend.js', array('dirUrl' => $jsUrl));
			ScriptsManager::localizeScript('GamificationBackend.js',
				'YPM_GAMIFICATION_ADMIN_PARAMS',
				array(
					'url' => admin_url('admin-ajax.php'),
					'nonce' => wp_create_nonce('ycfAjaxNonce'),
					'defaultImagename' => 'ypm-gift-icon-1.png',
					'chooseImage' => esc_attr__('Choose Image', 'popup_master'),
					'imgURL' => YPM_POPUP_IMAGE_URL . "/gamification/",

				)
			);
			ScriptsManager::enqueueScript('GamificationBackend.js');
		}
		ScriptsManager::registerStyle('gamification.css',  array('styleSrc' => $cssUrl));
		ScriptsManager::enqueueStyle('gamification.css');
	}

	public static function create($data, $obj = '')
	{
		$obj = new self();
		parent::create($data, $obj);
	}

	private function getContents()
	{
		$tags = YpmAdminHelper::getAllowedTags();
		$contents = '<div class="ypm-gamification-texts ypm-gamification-start-text">'.wp_kses($this->getOptionValue('ypm-gamification-start-text'), $tags).'</div>';
		$contents .= '<div class="ypm-gamification-texts ypm-gamification-play-text ypm-hide">'.wp_kses($this->getOptionValue('ypm-gamification-play-text'), $tags).'</div>';
		$contents .= '<div class="ypm-gamification-texts ypm-gamification-win-text ypm-hide">'.wp_kses($this->getOptionValue('ypm-gamification-win-text'), $tags).'</div>';
		$contents .= '<div class="ypm-gamification-texts ypm-gamification-lose-text ypm-hide">'.wp_kses($this->getOptionValue('ypm-gamification-lose-text'), $tags).'</div>';

		return $contents;
	}

	public function renderView($args, $content)
	{
		ob_start();
		?>
			<div class="ypm-gamification-content-wrapper ypm-gamification-content-wrapper-<?php echo esc_attr($this->getId())?>">
				<div class="ypm-gifts-content-wrapper">
					<?php echo wp_kses($this->getContents(), YpmAdminHelper::getAllowedTags())?>
					<?php echo wp_kses($this->renderForm(), YpmAdminHelper::getAllowedTags()); ?>
				</div>
				<?php echo wp_kses($this->getGifts(), YpmAdminHelper::getAllowedTags()); ?>
			</div>
		<?php
		$content .= ob_get_contents();
		ob_end_clean();

		return $content;
	}

	private function getGifts()
	{

		$popupId = $this->getId();
		$savedGift = $this->getOptionValue('ypm-gamification-gift-image');
		$placeholderColor = $this->getOptionValue('ypm-gamification-text-placeholder-color');

		$gifts = '<div class="ypm-gifts ypm-gifts-'.esc_attr($popupId).'">';
			$gifts .= '<img class="ypm-gift" width="72px" height="73px" src="'.esc_attr($savedGift).'">';
			$gifts .= '<img class="ypm-gift" width="72px" height="73px" src="'.esc_attr($savedGift).'">';
			$gifts .= '<img class="ypm-gift" width="72px" height="73px" src="'.esc_attr($savedGift).'">';
			$gifts .= '<img class="ypm-gift" width="72px" height="73px" src="'.esc_attr($savedGift).'">';
			$gifts .= '<img class="ypm-gift" width="72px" height="73px" src="'.esc_attr($savedGift).'">';
		$gifts .= '</div>';
		$gifts .= '<style type="text/css"> .ypm-gamification-content-wrapper {padding:0;}</style>';
		$gifts .= '<style type="text/css">';
			$gifts .= ' .js-gamification-text-inputs::-webkit-input-placeholder {color: '.esc_attr($placeholderColor).' !important;font-weight: lighter;}';
			$gifts .= '.js-gamification-text-inputs::-moz-placeholder {color: '.esc_attr($placeholderColor).' !important;font-weight: lighter;}';
			$gifts .= ' .js-gamification-text-inputs:-ms-input-placeholder {color: '.esc_attr($placeholderColor).' !important;font-weight: lighter;} /* ie */';
			$gifts .= ' .js-gamification-text-inputs:-moz-placeholder {color: '.esc_attr($placeholderColor).' !important;font-weight: lighter;}';
		$gifts .= '</style>';

		return $gifts;
	}

	private function getExpirationOptions()
	{
		$options = array(
			'ypm-popup-gamification-behavior',
			'ypm-popup-gamification-expiration-message',
			'ypm-popup-gamification-redirect-url',
			'ypm-popup-gamification-redirect-url-tab',
			'ypm-popup-gamification-enable-redirect',
			'ypm-popup-gamification-text-redirect-url',
			'ypm-gamification-win-chance',
			'ypm-gamification-already-subscribed',
			'ypm-hide-form',
		);

		$options = apply_filters('ypmContactOptions', $options);
		$keyValue = array();

		foreach ($options as $option) {
			$keyValue[$option] = $this->getOptionValue($option);
		}

		return $keyValue;
	}

	public function includeJs() {
		//gamificationFront.css
		$jsUrl = YPM_POPUP_JS_URL.'/gamification/';
		ScriptsManager::registerScript('Validate.js', array('dirUrl' => $jsUrl, 'dep' => array('jquery')));
		ScriptsManager::enqueueScript('Validate.js');
		ScriptsManager::registerScript('Gamification.js', array('dirUrl' => $jsUrl, 'dep' => array('jquery')));
		ScriptsManager::localizeScript('Gamification.js',
			'YPM_GAMIFICATION_PARAMS',
			array(
				'url' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('ycfAjaxNonce'),
				'defaultImagename' => 'ypm-gift-icon-1.png',
				'chooseImage' => esc_attr__('Choose Image', 'popup_master'),
				'imgURL' => YPM_POPUP_IMAGE_URL . "/gamification/",

			)
		);
		ScriptsManager::enqueueScript('Gamification.js');
	}

	public function includeCss() {
		//gamificationFront.css
		$args = array();
		$args['styleSrc'] = YPM_POPUP_CSS_URL.'/gamification/';
		ScriptsManager::registerStyle('gamificationFront.css', $args);
		ScriptsManager::enqueueStyle('gamificationFront.css');
	}

	public function renderForm()
	{
		$popupId = $this->getPopupId();

		require_once(YPM_POPUP_CLASSES . 'form/YcfBuilder.php');
		$form = $this->getSubscriptionFormObj();
		$formData = $form->getFormData();

		$validateObj = $form->createValidateObj($formData);

		$this->includeCss();
		$this->includeJs();
		$formData[0]['placeholder'] = $formData[0]['label'];
		unset($formData[0]['label']);

		$formBuilderObj = new YcfBuilder();
		$formBuilderObj->setFormId($popupId);
		$formBuilderObj->setFormElementsData($formData);
		$expirationOptions = $this->getExpirationOptions();

		$subscriptionForm = '<form 
			id="ycf-gamification-form"
			data-id="' . esc_attr($popupId) . '"
			class="ycf-gamification-form ycf-form-' . esc_attr($popupId) . '"
			action="admin-post.php"
			method="post"
			data-expiration-options=\'' . json_encode($expirationOptions) . '\'
			data-validate=\'' . json_encode($validateObj) . '\'
			>';
		$subscriptionForm .= $formBuilderObj->getFormFields();
		$subscriptionForm .= '</form>';
		$subscriptionForm .= $this->styles();

		return $subscriptionForm;
	}
	
	private function styles() {
		$submitStyles = array();
		$inputStyles = array();

		if ($this->getOptionValue('ypm-gamification-btn-width')) {
			$submitWidth = $this->getOptionValue('ypm-gamification-btn-width');
			$submitStyles['width'] = YpmAdminHelper::getCSSSafeSize($submitWidth).' !important';
		}
		if ($this->getOptionValue('ypm-gamification-btn-height')) {
			$submitHeight = $this->getOptionValue('ypm-gamification-btn-height');
			$submitStyles['height'] = YpmAdminHelper::getCSSSafeSize($submitHeight).' !important';
		}
		if ($this->getOptionValue('ypm-gamification-btn-bg-color')) {
			$submitStyles['background-color'] = $this->getOptionValue('ypm-gamification-btn-bg-color').' !important';
		}
		if ($this->getOptionValue('ypm-gamification-btn-text-color')) {
			$submitStyles['color'] = $this->getOptionValue('ypm-gamification-btn-text-color').' !important';
		}
		if ($this->getOptionValue('ypm-gamification-btn-border-radius')) {
			$borderRadius = YpmAdminHelper::getCSSSafeSize($this->getOptionValue('ypm-gamification-btn-border-radius'));
			$submitStyles['border-radius'] = $borderRadius.' !important';
		}
		if ($this->getOptionValue('ypm-gamification-btn-border-width')) {
			$submitStyles['border-width'] = $this->getOptionValue('ypm-gamification-btn-border-width').' !important';
		}
		if ($this->getOptionValue('ypm-gamification-btn-border-color')) {
			$submitStyles['border-color'] = $this->getOptionValue('ypm-gamification-btn-border-color').' !important';
		}

		$submitStyles['text-transform'] = 'none !important';
		$submitStyles['border-style'] = 'solid';
		$submitStyles['margin-bottom'] = '0px !important';
		$submitStyles['margin-top'] = '19px !important';
		$submitStyles['font-size'] = '25px !important';
		$submitStyles['padding'] = '0px !important';
		$submitStyles['font-family'] = 'Segoe UI !important';

		if ($this->getOptionValue('ypm-gamification-text-width'))  {
			$inputWidth = $this->getOptionValue('ypm-gamification-text-width');
			$inputStyles['width'] = YpmAdminHelper::getCSSSafeSize($inputWidth).' !important';
		}
		if ($this->getOptionValue('ypm-gamification-text-height')) {
			$inputHeight = $this->getOptionValue('ypm-gamification-text-height');
			$inputStyles['height'] = YpmAdminHelper::getCSSSafeSize($inputHeight).' !important';
		}
		if ($this->getOptionValue('ypm-gamification-text-border-width')) {
			$inputBorderWidth = $this->getOptionValue('ypm-gamification-text-border-width');
			$inputStyles['border-width'] = YpmAdminHelper::getCSSSafeSize($inputBorderWidth);
		}
		if ($this->getOptionValue('ypm-gamification-text-border-radius')) {
			$inputStyles['border-radius'] = $this->getOptionValue('ypm-gamification-text-border-radius');
		}
		if ($this->getOptionValue('ypm-gamification-text-border-color')) {
			$inputStyles['border-color'] = $this->getOptionValue('ypm-gamification-text-border-color');
		}
		if ($this->getOptionValue('ypm-gamification-text-bg-color')) {
			$inputStyles['background-color'] = $this->getOptionValue('ypm-gamification-text-bg-color');
		}
		if ($this->getOptionValue('ypm-gamification-text-color')) {
			$inputStyles['color'] = $this->getOptionValue('ypm-gamification-text-color');
		}

		$inputStyles['autocomplete'] = 'off';
		$inputStyles['margin-top'] = '0 !important';
		$inputStyles['margin-bottom'] = '0 !important';
		$inputStyles['border-style'] = 'solid !important';
		$inputStyles['text-indent'] = '13px';
		$inputStyles['border-color'] = '#f2f3e8';
		$style = "<style>#ycf-gamification-form input[type='email'] {";
		foreach ($inputStyles as $key => $value) {
			$style .= esc_attr($key).":".esc_attr($value).";";
		}
		$style .= "}";
		$style .= "#ycf-gamification-form .ycf-submit input {";
		foreach ($submitStyles as $key => $value) {
			$style .= esc_attr($key).":".esc_attr($value).";";
		}
		$style .= "}";
		$style .= "</style>";
		return $style;
	}

	public function getSubscriptionFormObj()
	{
		$popupId = $this->getPopupId();
		require_once YPM_POPUP_CLASSES . 'form/GamificationForm.php';
		$formObj = new GamificationForm($this);

		return $formObj;
	}
}