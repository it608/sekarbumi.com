<?php
namespace YpmPopup;

class WheelPopup extends Popup implements PopupViewInterface {
    public $shortCodeName = 'ypm_wheel';

    public function __construct() {
		if(is_admin()) {
			$this->includeAdminScripts();
		}
        add_filter('ypmDefaultOptions', array($this, 'defOptions'));
    }

    public function defOptions($options) {

        // $dataOptions = [
        //     1 => ['label' => 'Option 1', 'color' => '#ff0000', 'probability' => '20'],
        //     2 => ['label' => 'Option 2', 'color' => '#00ff00', 'probability' => '30'],
        //     3 => ['label' => 'Option 3', 'color' => '#0000ff', 'probability' => '50']
        // ];
		// $options[] = array('name' => 'ypm-wheeloptions[]', 'type' => 'array', 'defaultValue' => $dataOptions);

        return $options;
    }

    public static function create($data, $obj = '') {

		$obj = new self();
		if (ypm_is_free()) {
			$data['ypm-age-restriction-type'] = 'yesNo';
		}
		parent::create($data, $obj);
	}

    public function includeAdminScripts() {
        if (is_admin()) {
            wp_register_script('ypmWheelAdminJs', YPM_POPUP_JS_URL.'wheel/admin.js', array('jquery', 'jquery-ui-sortable'));
            $backLocalizeData = array(
                'proURL' => YPM_POPUP_PRO_URL
            );
            wp_localize_script('ypmWheelAdminJs', 'yrmWheelAdmin', $backLocalizeData);
            wp_enqueue_script('ypmWheelAdminJs');

            if (!ypm_is_free()) {
                wp_register_script('ypmWheelAdminPROJs', YPM_POPUP_JS_URL.'wheel/adminPro.js', array('jquery', 'jquery-ui-sortable'));
                wp_enqueue_script('ypmWheelAdminPROJs');
            }
        }
        wp_register_script('ypmWheelCoreJs', YPM_POPUP_JS_URL.'wheel/WheelCore.js', array('jquery', 'jquery-ui-sortable'));
		$backLocalizeData = array(
			'ajaxNonce' => wp_create_nonce('ycfAjaxNonce')
		);
		wp_localize_script('ypmWheelCoreJs', 'ycfBackendLocalization', $backLocalizeData);
        wp_enqueue_script('ypmWheelCoreJs');
    }

    public function includeJs() {
        $this->includeAdminScripts();
        $options = $this->getOptionValue('ypm-wheeloptions');
        $wheelSound = $this->getOptionValue('ypm-wheel-sound');

        $jsUrl = YPM_POPUP_JS_URL.'/wheel/';
		// ScriptsManager::registerScript('Validate.js', array('dirUrl' => $jsUrl, 'dep' => array('jquery')));
		// ScriptsManager::enqueueScript('Validate.js');
		ScriptsManager::registerScript('Wheel.js', array('dirUrl' => $jsUrl, 'dep' => array('jquery')));
		ScriptsManager::localizeScript('Wheel.js',
			'YPM_WHELL_PARAMS',
			array(
				'options' => json_encode(array_values($options)),
				'nonce' => wp_create_nonce('ycfAjaxNonce'),
				'numSegments' => count($options),
                'soundsURL' => YPM_POPUP_SOUNDS_URL,
                'spinSound' => $this->getOptionValue('ypm-wheel-sound-url'),
                'winSpinSoundStatus' => $this->getOptionValue('ypm-wheel-win-sound'),
                'winSpinSound' => $this->getOptionValue('ypm-wheel-win-sound-url'),
                'wheelSound' => $wheelSound
			)
		);
		ScriptsManager::enqueueScript('Wheel.js');
    }

    public function renderView($args, $content) {
        $this->includeJs();
        $id = uniqid();

        $arrowSize = $this->getOptionValue('ypm-wheel-arrow-size');

        ob_start();
        ?>
            <div class="ypm-wheel-container ypm-wheel-container-<?php echo esc_attr($id); ?>" data-id=<?php echo esc_attr($id); ?>>
            <div class="response-wrapper-<?php echo esc_attr($id); ?>"></div>
            <div class="ycd-div-wrapper ycd-div-wrapper-<?php echo esc_attr($id); ?>">
                   
                    <div id="ycd-wheel-pointer" class="ycd-wheel-pointer ycd-wheel-pointer-<?php echo esc_attr($id); ?>"></div>
                    <canvas id="<?php echo esc_attr($id); ?>" class="canvas" width="434" height="434">   
                        <p style="color: white;" align="center">Sorry, your browser doesn't support canvas. Please try another.</p>
                    </canvas>
                </div>
                <button id="ypm-add-btn" data-id=<?php echo esc_attr($id); ?> class="ypm-add-btn ypm-button-title"><?php esc_attr_e($this->getOptionValue('ypm-wheel-button-title'))?></button>
            </div>
            
            <style>
                .ycd-div-wrapper {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    margin-bottom: 10px;
                }
                .ypm-wheel-container-<?php echo esc_attr($id); ?> .ycd-wheel-pointer-<?php echo esc_attr($id); ?> {
                    width: 0;
                    height: 0;
                    border-left: <?php echo esc_attr($arrowSize) * 20; ?>px solid transparent;
                    border-right: <?php echo esc_attr($arrowSize) * 20; ?>px solid transparent;
                    border-bottom: <?php echo esc_attr($arrowSize) * 30; ?>px solid <?php esc_attr_e($this->getOptionValue('ypm-wheel-arrow-color'))?>;
                    transform: rotate(-180deg)
                }
                .ypm-wheel-container {
                    text-align: center;
                }
                .response-wrapper-<?php echo esc_attr($id); ?> {
                    margin-bottom: 40px;
                }
                .ypm-button-title {
                    line-height: 1;
                    text-decoration: none !important;
                    padding: <?php esc_attr_e($this->getOptionValue('ypm-wheel-button-padding')) ?>!important;
                    border-radius: <?php esc_attr_e($this->getOptionValue('ypm-wheel-button-border-radius')) ?>!important;
                    cursor: pointer;
                    width: <?php esc_attr_e($this->getOptionValue('ypm-wheel-button-width')); ?>;
                    height: <?php esc_attr_e($this->getOptionValue('ypm-wheel-button-height')); ?>;
                    font-size: <?php esc_attr_e($this->getOptionValue('ypm-wheel-button-font-size')); ?>;
                    color: <?php esc_attr_e($this->getOptionValue('ypm-wheel-button-color')); ?> !important;
                    background-color: <?php esc_attr_e($this->getOptionValue('ypm-wheel-button-bg-color')); ?> !important;
                }
                .ypm-wheel-container-<?php echo esc_attr($id); ?> .ypm-button-title:hover {
                    color: <?php esc_attr_e($this->getOptionValue('ypm-wheel-button-hover-color')); ?> !important;
                    background-color: <?php esc_attr_e($this->getOptionValue('ypm-wheel-button-hover-bg-color')); ?> !important;
                    border: 1px solid <?php esc_attr_e($this->getOptionValue('ypm-wheel-button-hover-color')); ?> !important;
                }
            </style>
            </div>
        <?php
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }
}