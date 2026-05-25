<?php
namespace YpmPopup;
require_once(dirname(__FILE__).'/Popup.php');

class SocialPopup extends Popup  implements PopupViewInterface {

	public $shortCodeName = 'ypm_social';

	public function __construct() {
		wp_register_script('socialjs', YPM_POPUP_JS_URL . '/jssocials.min.js', array('jquery'));
		wp_enqueue_script('socialjs');
		wp_register_script('ypmSocialJs', YPM_POPUP_JS_URL . '/Social.js', array('jquery'));
		$socialNetworks = array_keys($this->getSocialInfo());
		wp_localize_script('ypmSocialJs', 'YpmSocialData', array(
			'cssLink' => YPM_POPUP_CSS_URL.'socials',
			'socialNetworks' => $socialNetworks
		));
		wp_enqueue_script('ypmSocialJs');
		add_filter('ypmDefaultOptions', array($this, 'defOptions'));
		$this->extendDefaultData();
	}

	public function getMenuLabelName() {

		return __('Social', YPM_POPUP_TEXT_DOMAIN);
	}

	private function renderSocialStyles() {

		wp_register_style('ypmSocialMainStyles', YPM_POPUP_CSS_URL.'socials/jssocials.css');
		wp_enqueue_style('ypmSocialMainStyles');
		$id = $this->getPopupId();
		$theme = $this->getOptionValue('ypm-social-themes');
		wp_register_style('ypmSocialThemeStyles'.$id, YPM_POPUP_CSS_URL.'socials/jssocials-theme-'.$theme.'.css');
		wp_enqueue_style('ypmSocialThemeStyles'.$id);
		wp_register_style('ypmSocialFonts', YPM_POPUP_CSS_URL.'socials/font-awesome.min.css');
		wp_enqueue_style('ypmSocialFonts');
	}

	public function getSocialInfo() {

		$socialIno = array(
			'email' => array(
				'adminLabel' => __('E-mail', YPM_POPUP_TEXT_DOMAIN),
				'label' => __('E-mail', YPM_POPUP_TEXT_DOMAIN)
			),
			'twitter' => array(
				'adminLabel' => __('Tweet', YPM_POPUP_TEXT_DOMAIN),
				'label' => __('Twitter', YPM_POPUP_TEXT_DOMAIN)
			),
			'facebook' => array(
				'adminLabel' => __('Facebook', YPM_POPUP_TEXT_DOMAIN),
				'label' => __('Share', YPM_POPUP_TEXT_DOMAIN)
			),
			'googleplus' => array(
				'adminLabel' => __('Google+', YPM_POPUP_TEXT_DOMAIN),
				'label' => __('Share', YPM_POPUP_TEXT_DOMAIN)
			),
			'linkedin' => array(
				'adminLabel' => __('LinkedIn', YPM_POPUP_TEXT_DOMAIN),
				'label' => __('Share', YPM_POPUP_TEXT_DOMAIN)
			),
			'pinterest' => array(
				'adminLabel' => __('Pinterest', YPM_POPUP_TEXT_DOMAIN),
				'label' => __('Pin it', YPM_POPUP_TEXT_DOMAIN)
			),
			'stumbleupon' => array(
				'adminLabel' => __('Stumbleupon', YPM_POPUP_TEXT_DOMAIN),
				'label' => __('Share', YPM_POPUP_TEXT_DOMAIN)
			),
			'whatsapp' => array(
				'adminLabel' => __('Whatsapp', YPM_POPUP_TEXT_DOMAIN),
				'label' => __('whatsApp', YPM_POPUP_TEXT_DOMAIN)
			),
			'pocket' => array(
				'adminLabel' => __('Pocket', YPM_POPUP_TEXT_DOMAIN),
				'label' => __('Share', YPM_POPUP_TEXT_DOMAIN)
			),
			'whatsapp' => array(
				'adminLabel' => __('Whatsapp', YPM_POPUP_TEXT_DOMAIN),
				'label' => __('Share', YPM_POPUP_TEXT_DOMAIN)
			),
			'messenger' => array(
				'adminLabel' => __('Messenger', YPM_POPUP_TEXT_DOMAIN),
				'label' => __('Share', YPM_POPUP_TEXT_DOMAIN)
			),
			'vkontakte' => array(
				'adminLabel' => __('Vkontakte', YPM_POPUP_TEXT_DOMAIN),
				'label' => __('Share', YPM_POPUP_TEXT_DOMAIN)
			),
			'telegram' => array(
				'adminLabel' => __('Telegram', YPM_POPUP_TEXT_DOMAIN),
				'label' => __('Share', YPM_POPUP_TEXT_DOMAIN)
			),
			'line' => array(
				'adminLabel' => __('Line', YPM_POPUP_TEXT_DOMAIN),
				'label' => __('Share', YPM_POPUP_TEXT_DOMAIN)
			)
		);

		return $socialIno;
	}

	public function defOptions($options) {
		$socialNetworks = $this->getSocialInfo();

		foreach ($socialNetworks as $network => $networkData) {
			$options[] = array('name' => 'ypm-social-status-'.$network, 'type' => 'checkbox', 'defaultValue' => 'on');
			$options[] = array('name' => 'ypm-social-label-'.$network, 'type' => 'text', 'defaultValue' => $networkData['label']);
		}
		$options[] =  array('name' => 'ypm-popup-social-label', 'type' => 'checkbox', 'defaultValue' => 'on');
		$options[] =  array('name' => 'ypm-social-themes', 'type' => 'text', 'defaultValue' => 'classic');
		$options[] =  array('name' => 'ypm-social-font-size', 'type' => 'text', 'defaultValue' => '14');
		$options[] =  array('name' => 'ypm-social-share-count', 'type' => 'text', 'defaultValue' => 'true');

		return $options;
	}

	private function extendDefaultData() {

		global $YpmDefaults;

	}

	private function getActiveNetworks() {

		$allNetworks = array_keys($this->getSocialInfo());
		$activeNetworks = array();

		foreach($allNetworks as $network) {
			if(!$this->getOptionValue('ypm-social-status-'.$network)) {
				continue;
			}
			$networkLabel = $this->getOptionValue('ypm-social-label-'.$network);
			$activeNetworks[] = array(
				'share' => $network,
				'label' => $networkLabel
			);
		}

		return $activeNetworks;
	}

	public function renderView($args = array(), $content = array())
	{
		return $this->renderSocialButtons();
	}

	public function renderSocialButtons() {

		$this->renderSocialStyles();
		$shareCount = $this->getOptionValue('ypm-social-share-count');
		$shareText = $this->getOptionValue('ypm-social-share-text');
		$socialShareIn = $this->getOptionValue('ypm-social-share-in');
		$shareURL = $this->getOptionValue('ypm-social-share-URL');
		$socialLabel = $this->getOptionValue('ypm-popup-social-label');
		$socialLabel = (!empty($socialLabel)) ? 1: 0;
		$activeNetworks = $this->getActiveNetworks();
		$popupId = $this->getPopupId();
		$fontSize = $this->getOptionValue('ypm-social-font-size');
		$socialId = 'ypm-share-buttons-'.$popupId;
		ob_start();
		?>
		<div class="ypm-social-buttons"><div id="<?php echo $socialId; ?>"></div></div>
		<script type="text/javascript">

			jQuery(document).ready(function() {
				var ypmActiveNetworks = <?php echo json_encode($activeNetworks); ?>;
				var ypmSocialOptions = {
					shares: ypmActiveNetworks,
					text: '<?php echo $shareText; ?>',
					shareIn: '<?php echo $socialShareIn; ?>',
					url: '<?php echo $shareURL; ?>',
					showLabel: <?php echo $socialLabel; ?>,
					showCount: <?php echo ($shareCount == 'false') ? 0 : '"'.$shareCount.'"'; ?>
				};
				jQuery('#<?php echo $socialId; ?>').attr('data-social-conf', JSON.stringify(ypmSocialOptions)).jsSocials(ypmSocialOptions);

				var ypmSocialObj = new YpmSocial();
				ypmSocialObj.setId(<?php echo $this->getPopupId(); ?>);
				ypmSocialObj.preview();
			});
		</script>
		<style type="text/css">
			#<?php echo $socialId; ?> {
				font-size: <?php echo $fontSize; ?>px
			}
		</style>
		<?php
		$content = ob_get_contents();
		ob_get_clean();

		return $content;
	}

	private function currentSocial($network, $networkData)
	{
		ob_start();
		?>
		<div class="row">
			<div class="col-xs-6">
				<label class="control-label" for="<?php echo 'ypm-social-status-'.$network; ?>"><?php _e($networkData['adminLabel'], YPM_POPUP_TEXT_DOMAIN);?>:</label>
			</div>
			<div class="col-xs-6">
				<label class="ypm-switch">
					<input type="checkbox" id="<?php echo 'ypm-social-status-'.$network; ?>" data-network-name="<?php echo esc_attr($network); ?>" class="js-ypm-accordion ypm-social-network" name="<?php echo 'ypm-social-status-'.$network; ?>" <?php echo $this->getOptionValue('ypm-social-status-'.$network); ?>>
					<span class="ypm-slider ypm-round"></span>
				</label>
				<br>
			</div>
		</div>
		<div class="row ypm-margin-bottom-15">
			<div class="col-xs-6">
				<label class="control-label ypm-social-option-label" for="<?php echo 'ypm-social-label-'.$network; ?>"><?php _e('Label', YPM_POPUP_TEXT_DOMAIN);?>:</label>
			</div>
			<div class="col-xs-6">
				<input  class="form-control ypm-social-label" data-network-name="<?php echo esc_attr($network); ?>" id="<?php echo 'ypm-social-label-'.$network; ?>" type="text" name="<?php echo 'ypm-social-label-'.$network; ?>" value="<?php echo  $this->getOptionValue('ypm-social-label-'.$network); ?>">
			</div>
		</div>
		<?php
		$content = ob_get_contents();
		ob_get_clean();

		return $content;
	}

	public function renderSocials() {

		$networksInfo = $this->getSocialInfo();
		ob_start();
		$i = 1;
		foreach ($networksInfo as $network => $networkData):

			if ($i == 1) {
				?>
				<div class="row ypm-social-group-wrapper">
				<?php
			}
		?>
				<div class="col-md-4"><?php echo $this->currentSocial($network, $networkData); ?></div>
				<?php

				if ($i == '3' || end(array_keys($networksInfo)) == $network) {
					$i = 0;
				?>
					</div>
				<?php
				}
				$i++;
			?>
		<?php
		endforeach;
		$content = ob_get_contents();
		ob_get_clean();

		return $content;
	}

	public static function create($data, $obj = '') {

		$obj = new self();
		parent::create($data, $obj);
	}
}