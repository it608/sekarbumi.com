<?php
namespace YpmPopup;

use ypmFrontend\ProHelper;

class AgerestrictionPopup extends Popup implements PopupViewInterface {

	public $shortCodeName = 'ypm_agerestriction';

    public function __construct()
    {
        add_filter('ypmDefaultOptions', array($this, 'defOptions'));
	    $this->includeJsFiles();
    }

	private function includeJsFiles()
	{
		$jsUrl = YPM_POPUP_JS_URL.'/ageRestriction/';

		ScriptsManager::registerScript('AgeRestriction.js', array('dirUrl' => $jsUrl, 'dep' => array('jquery')));
		ScriptsManager::enqueueScript('AgeRestriction.js');
	}

    public function getOptions() {
		$options = parent::getOptions();
		$options['ypm-close-button'] = '';
		$options['ypm-esc-key'] = '';
		$options['ypm-overlay-click'] = '';
		$options['ypm-content-click-status'] = '';

    	return $options;
    }

	public static function create($data, $obj = '') {

		$obj = new self();
		if (ypm_is_free()) {
			$data['ypm-age-restriction-type'] = 'yesNo';
		}
		parent::create($data, $obj);
	}

    public function defOptions($options)
    {
	    $options[] = array('name' => 'ypm-restriction-accept-width', 'type' => 'text', 'defaultValue' => '100px');
	    $options[] = array('name' => 'ypm-restriction-accept-height', 'type' => 'text', 'defaultValue' => '100px');
	    $options[] = array('name' => 'ypm-restriction-accept-enable-dimension', 'type' => 'checkbox', 'defaultValue' => '');
        $options[] = array('name' => 'ypm-restriction-accept-label', 'type' => 'text', 'defaultValue' => 'Yes');
        $options[] = array('name' => 'ypm-restriction-accept-font-size', 'type' => 'text', 'defaultValue' => '20px');
        $options[] = array('name' => 'ypm-restriction-accept-padding', 'type' => 'text', 'defaultValue' => '5px');
        $options[] = array('name' => 'ypm-restriction-accept-bg-color', 'type' => 'text', 'defaultValue' => 'rgb(0, 128, 0)');
        $options[] = array('name' => 'ypm-restriction-accept-text-color', 'type' => 'text', 'defaultValue' => 'rgb(255,255,255)');
        $options[] = array('name' => 'ypm-restriction-accept-border-radius', 'type' => 'text', 'defaultValue' => '0px');
        $options[] = array('name' => 'ypm-restriction-accept-enable-hover', 'type' => 'checkbox', 'defaultValue' => '');
        $options[] = array('name' => 'ypm-restriction-accept-hover-bg-color', 'type' => 'text', 'defaultValue' => 'rgb(255,255,255)');
        $options[] = array('name' => 'ypm-restriction-accept-hover-text-color', 'type' => 'text', 'defaultValue' => 'rgb(0, 128, 0)');
	    $options[] = array('name' => 'ypm-restriction-deny-width', 'type' => 'text', 'defaultValue' => '100px');
	    $options[] = array('name' => 'ypm-restriction-deny-height', 'type' => 'text', 'defaultValue' => '100px');
	    $options[] = array('name' => 'ypm-restriction-deny-enable-dimension', 'type' => 'checkbox', 'defaultValue' => '');
        $options[] = array('name' => 'ypm-restriction-deny-label', 'type' => 'text', 'defaultValue' => 'No');
        $options[] = array('name' => 'ypm-restriction-deny-url', 'type' => 'text', 'defaultValue' => '');
        $options[] = array('name' => 'ypm-restriction-deny-font-size', 'type' => 'text', 'defaultValue' => '20px');
        $options[] = array('name' => 'ypm-restriction-deny-padding', 'type' => 'text', 'defaultValue' => '5px');
        $options[] = array('name' => 'ypm-restriction-deny-border-radius', 'type' => 'text', 'defaultValue' => '0px');
	    $options[] = array('name' => 'ypm-restriction-deny-enable-hover', 'type' => 'checkbox', 'defaultValue' => '');
	    $options[] = array('name' => 'ypm-restriction-deny-hover-bg-color', 'type' => 'text', 'defaultValue' => 'rgb(255,255,255)');
	    $options[] = array('name' => 'ypm-restriction-deny-hover-text-color', 'type' => 'text', 'defaultValue' => 'rgb(255, 0, 0)');
	    $options[] = array('name' => 'ypm-age-restriction-type', 'type' => 'text', 'defaultValue' => 'yesNo');
	    $options[] = array('name' => 'ypm-restriction-min-age', 'type' => 'text', 'defaultValue' => '18');
	    $options[] = array('name' => 'ypm-restriction-button-label', 'type' => 'text', 'defaultValue' => 'Confirm my age');
	    $options[] = array('name' => 'ypm-restriction-button-width', 'type' => 'text', 'defaultValue' => '200px');
	    $options[] = array('name' => 'ypm-restriction-button-height', 'type' => 'text', 'defaultValue' => '50px');
	    $options[] = array('name' => 'ypm-restriction-button-url', 'type' => 'text', 'defaultValue' => '');
	    $options[] = array('name' => 'ypm-restriction-button-font-size', 'type' => 'text', 'defaultValue' => '20px');
	    $options[] = array('name' => 'ypm-restriction-deny-bg-color', 'type' => 'text', 'defaultValue' => 'rgb(255, 0, 0)');
	    $options[] = array('name' => 'ypm-restriction-deny-text-color', 'type' => 'text', 'defaultValue' => 'rgb(255,255,255)');
	    $options[] = array('name' => 'ypm-restriction-button-padding', 'type' => 'text', 'defaultValue' => '8px');
	    $options[] = array('name' => 'ypm-restriction-button-border-radius', 'type' => 'text', 'defaultValue' => '0px');
	    $options[] = array('name' => 'ypm-restriction-button-bg-color', 'type' => 'text', 'defaultValue' => '#FF0000');
	    $options[] = array('name' => 'ypm-restriction-button-text-color', 'type' => 'text', 'defaultValue' => '#ffffff');
	    $options[] = array('name' => 'ypm-restriction-button-enable-hover', 'type' => 'text', 'defaultValue' => '');
	    $options[] = array('name' => 'ypm-restriction-button-hover-bg-color', 'type' => 'text', 'defaultValue' => '');
	    $options[] = array('name' => 'ypm-restriction-button-hover-text-color', 'type' => 'text', 'defaultValue' => '');

        return $options;
    }

    private function getButtons() {
	    $id = $this->getId();
    	$content = '<div class="ypm-buttons ypm-buttons-'.esc_attr($id).'">';

    	ob_start();
    	?>
		    <div class="ypm-no-button" data-id="<?php echo esc_attr($id);?>">
			    <button>
				    <?php echo esc_attr($this->getOptionValue('ypm-restriction-deny-label'));?>
			    </button>
		    </div>
		    <div class="ypm-yes-button" data-id="<?php echo esc_attr($id);?>">
			    <button>
				    <?php echo esc_attr($this->getOptionValue('ypm-restriction-accept-label'));?>
			    </button>
		    </div>
	    </div>
	    <style type="text/css">
		    .ypm-buttons {
			    display: flex;
			    justify-content: center;
			    align-items: center;
		    }
		    .ypm-buttons-<?php echo esc_attr($id); ?> .ypm-no-button button {
			    <?php if ($this->getOptionValue('ypm-restriction-deny-enable-dimension')): ?>
			    width: <?php echo esc_attr($this->getOptionValue('ypm-restriction-deny-width'));?>;
			    height: <?php echo esc_attr($this->getOptionValue('ypm-restriction-deny-height'));?>;
	            <?php endif; ?>
		    	font-size: <?php echo esc_attr($this->getOptionValue('ypm-restriction-deny-font-size'));?>;
		    	padding: <?php echo esc_attr($this->getOptionValue('ypm-restriction-deny-padding'));?>;
		    	background: <?php echo esc_attr($this->getOptionValue('ypm-restriction-deny-bg-color'));?> !important;
		    	color: <?php echo esc_attr($this->getOptionValue('ypm-restriction-deny-text-color'));?>;
			    border: none;
			    border-radius: <?php echo esc_attr($this->getOptionValue('ypm-restriction-deny-border-radius'));?>;
			    margin-right: 10px
		    }
		    .ypm-buttons-<?php echo esc_attr($id); ?> .ypm-yes-button button {
			    <?php if ($this->getOptionValue('ypm-restriction-accept-enable-dimension')): ?>
				    width: <?php echo esc_attr($this->getOptionValue('ypm-restriction-accept-width'));?>;
				    height: <?php echo esc_attr($this->getOptionValue('ypm-restriction-accept-height'));?>;
			    <?php endif; ?>
			    font-size: <?php echo esc_attr($this->getOptionValue('ypm-restriction-accept-font-size'));?>;
		    	padding: <?php echo esc_attr($this->getOptionValue('ypm-restriction-accept-padding'));?>;
		    	background: <?php echo esc_attr($this->getOptionValue('ypm-restriction-accept-bg-color'));?> !important;
		    	color: <?php echo esc_attr($this->getOptionValue('ypm-restriction-accept-text-color'));?>;
			    border: none;
			    border-radius: <?php echo esc_attr($this->getOptionValue('ypm-restriction-accept-border-radius'));?>;
			    margin-left: 10px;
		    }
		    <?php if ($this->getOptionValue('ypm-restriction-accept-enable-hover')): ?>
		    .ypm-buttons-<?php echo esc_attr($id); ?> .ypm-yes-button button:hover {
			    background: <?php echo esc_attr($this->getOptionValue('ypm-restriction-accept-hover-bg-color'));?> !important;
			    color: <?php echo esc_attr($this->getOptionValue('ypm-restriction-accept-hover-text-color'));?> !important;
		    }
		    <?php endif; ?>
		    <?php if ($this->getOptionValue('ypm-restriction-deny-enable-hover')): ?>
		    .ypm-buttons-<?php echo esc_attr($id); ?> .ypm-no-button button:hover {
			    background: <?php echo esc_attr($this->getOptionValue('ypm-restriction-deny-hover-bg-color'));?> !important;
			    color: <?php echo esc_attr($this->getOptionValue('ypm-restriction-deny-hover-text-color'));?> !important;
		    }
		    <?php endif; ?>
	    </style>
		<?php
		$content .= ob_get_contents();
		ob_end_clean();

		return $content;
    }

    public function renderView($args, $content) {
		$options = $this->getOptions();

		$this->setOptions($options);
		$type = $this->getOptionValue('ypm-age-restriction-type');
		$content = '<div class="ypm-restriction-options" data-options="'.esc_attr(json_encode($this->getOptions())).'">';
		if ($type === 'yesNo') {
			$content .= $this->getButtons();
		}
		if ($type === 'ageVerification') {
			$content .= \ypmFrontend\ProHelper::getRestrictionContent($this);
		}
	    $content .= "</div>";

		return $content;
	}
}