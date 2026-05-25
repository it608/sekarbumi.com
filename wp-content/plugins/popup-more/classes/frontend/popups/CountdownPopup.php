<?php
namespace YpmPopup;
use \YpmAdminHelper;

class CountdownPopup extends Popup implements PopupViewInterface
{
    public $shortCodeName = 'ypm_countdown';

    public function __construct()
    {
        $this->extendDefaults();
	    $this->includeJsFiles();
	    add_filter('ypmDefaultOptions', array($this, 'defOptions'));
    }

    public function defOptions($options) {

	    $options[] = array('name' => 'ypm-type', 'type' => 'text', 'defaultValue' => 'circle');
	    $options[] = array('name' => 'ypm-countdown-date-type', 'type' => 'text', 'defaultValue' => 'dueDate');
	    $options[] = array('name' => 'ypm-date-time-picker', 'type' => 'text', 'defaultValue' => date('Y-m-d H:i', strtotime(' +1 day')));
	    $options[] = array('name' => 'ypm-date-progress-start-date', 'type' => 'text', 'defaultValue' => date('Y-m-d H:i'));
	    $options[] = array('name' => 'ypm-circle-time-zone', 'type' => 'text', 'defaultValue' => YpmAdminHelper::getDefaultTimezone());
	    $options[] = array('name' => 'ypm-circle-animation', 'type' => 'text', 'defaultValue' => 'smooth');
	    $options[] = array('name' => 'ypm-circle-alignment', 'type' => 'text', 'defaultValue' => 'center');
	    $options[] = array('name' => 'ypm-countdown-width', 'type' => 'text', 'defaultValue' => '500');
	    $options[] = array('name' => 'ypm-dimension-measure', 'type' => 'text', 'defaultValue' => 'px');
	    $options[] = array('name' => 'ypm-countdown-background-circle', 'type' => 'checkbox', 'defaultValue' => 'on');
	    $options[] = array('name' => 'ypm-countdown-months', 'type' => 'checkbox', 'defaultValue' => '');
	    $options[] = array('name' => 'ypm-countdown-months-text', 'type' => 'text', 'defaultValue' => __('Months', YPM_POPUP_TEXT_DOMAIN));
	    $options[] = array('name' => 'ypm-countdown-years', 'type' => 'checkbox', 'defaultValue' => '');
	    $options[] = array('name' => 'ypm-countdown-years-text', 'type' => 'text', 'defaultValue' => __('Years', YPM_POPUP_TEXT_DOMAIN));
	    $options[] = array('name' => 'ypm-countdown-days', 'type' => 'checkbox', 'defaultValue' => 'on');
	    $options[] = array('name' => 'ypm-countdown-days-text', 'type' => 'text', 'defaultValue' => __('DAYS', YPM_POPUP_TEXT_DOMAIN));
	    $options[] = array('name' => 'ypm-countdown-hours', 'type' => 'checkbox', 'defaultValue' => 'on');
	    $options[] = array('name' => 'ypm-countdown-hours-text', 'type' => 'text', 'defaultValue' => __('HOURS', YPM_POPUP_TEXT_DOMAIN));
	    $options[] = array('name' => 'ypm-countdown-minutes', 'type' => 'checkbox', 'defaultValue' => 'on');
	    $options[] = array('name' => 'ypm-countdown-minutes-text', 'type' => 'text', 'defaultValue' => __('MINUTES', YPM_POPUP_TEXT_DOMAIN));
	    $options[] = array('name' => 'ypm-countdown-seconds', 'type' => 'checkbox', 'defaultValue' => 'on');
	    $options[] = array('name' => 'ypm-countdown-seconds-text', 'type' => 'text', 'defaultValue' => __('SECONDS', YPM_POPUP_TEXT_DOMAIN));
	    $options[] = array('name' => 'ypm-countdown-direction', 'type' => 'text', 'defaultValue' => __('Clockwise', YPM_POPUP_TEXT_DOMAIN));
	    $options[] = array(
		    'name' => 'ypm-countdown-expire-behavior',
		    'type' => 'text',
		    'defaultValue' => __('hideCountdown', YPM_POPUP_TEXT_DOMAIN),
		    'ver' => YPM_POPUP_SILVER,
		    'allow' => array('hideCountdown', 'default', 'countToUp')
	    );
	    $options[] = array('name' => 'ypm-expire-text', 'type' => 'html', 'defaultValue' => __('', YPM_POPUP_TEXT_DOMAIN), 'ver' => YPM_POPUP_SILVER);
	    $options[] = array('name' => 'ypm-expire-url', 'type' => 'text', 'defaultValue' => __('', YPM_POPUP_TEXT_DOMAIN), 'ver' => YPM_POPUP_SILVER);
	    $options[] = array('name' => 'ypm-countdown-months-color', 'type' => 'text', 'defaultValue' => '#8A2BE2', 'ver' => YPM_POPUP_SILVER);
	    $options[] = array('name' => 'ypm-countdown-months-text-color', 'type' => 'text', 'defaultValue' => '#000000', 'ver' => YPM_POPUP_SILVER);
	    $options[] = array('name' => 'ypm-countdown-years-color', 'type' => 'text', 'defaultValue' => '#A52A2A', 'ver' => YPM_POPUP_SILVER);
	    $options[] = array('name' => 'ypm-countdown-years-text-color', 'type' => 'text', 'defaultValue' => '#000000', 'ver' => YPM_POPUP_SILVER);
	    $options[] = array('name' => 'ypm-countdown-days-color', 'type' => 'text', 'defaultValue' => '#FFCC66', 'ver' => YPM_POPUP_SILVER);
	    $options[] = array('name' => 'ypm-countdown-days-text-color', 'type' => 'text', 'defaultValue' => '#000000', 'ver' => YPM_POPUP_SILVER);
	    $options[] = array('name' => 'ypm-countdown-hours-color', 'type' => 'text', 'defaultValue' => '#99CCFF', 'ver' => YPM_POPUP_SILVER);
	    $options[] = array('name' => 'ypm-countdown-hours-text-color', 'type' => 'text', 'defaultValue' => '#000000', 'ver' => YPM_POPUP_SILVER);
	    $options[] = array('name' => 'ypm-countdown-minutes-color', 'type' => 'text', 'defaultValue' => '#BBFFBB', 'ver' => YPM_POPUP_SILVER);
	    $options[] = array('name' => 'ypm-countdown-minutes-text-color', 'type' => 'text', 'defaultValue' => '#000000', 'ver' => YPM_POPUP_SILVER);
	    $options[] = array('name' => 'ypm-countdown-seconds-color', 'type' => 'text', 'defaultValue' => '#FF9999', 'ver' => YPM_POPUP_SILVER);
	    $options[] = array('name' => 'ypm-countdown-seconds-text-color', 'type' => 'text', 'defaultValue' => '#000000', 'ver' => YPM_POPUP_SILVER);
	    $options[] = array('name' => 'ypm-circle-width', 'type' => 'text', 'defaultValue' => '0.1');
	    $options[] = array('name' => 'ypm-circle-bg-width', 'type' => 'text', 'defaultValue' => '1.2');
	    $options[] = array('name' => 'ypm-circle-start-angle', 'type' => 'text', 'defaultValue' => 0);
	    $options[] = array('name' => 'ypm-countdown-bg-image', 'type' => 'checkbox', 'defaultValue' => 0, 'ver' => YPM_POPUP_SILVER);
	    $options[] = array('name' => 'ypm-bg-image-size', 'type' => 'text', 'defaultValue' => 'cover', 'ver' => YPM_POPUP_SILVER);
	    $options[] = array('name' => 'ypm-bg-image-repeat', 'type' => 'text', 'defaultValue' => 'no-repeat', 'ver' => YPM_POPUP_SILVER);
	    $options[] = array('name' => 'ypm-bg-image-url', 'type' => 'text', 'defaultValue' => '', 'ver' => YPM_POPUP_SILVER);
	    $options[] = array('name' => 'ypm-countdown-bg-circle-color', 'type' => 'text', 'defaultValue' => '#60686F', 'ver' => YPM_POPUP_SILVER);
	    $options[] = array('name' => 'ypm-text-font-size', 'type' => 'text', 'defaultValue' => '9');
	    $options[] = array('name' => 'ypm-countdown-number-size', 'type' => 'text', 'defaultValue' => '35');
	    $options[] = array('name' => 'ypm-countdown-number-font-weight', 'type' => 'text', 'defaultValue' => 'bold');
	    $options[] = array('name' => 'ypm-countdown-font-weight', 'type' => 'text', 'defaultValue' => 'normal');
	    $options[] = array('name' => 'ypm-countdown-font-style', 'type' => 'text', 'defaultValue' => 'initial');
	    $options[] = array('name' => 'ypm-text-font-family', 'type' => 'text', 'defaultValue' => 'Century Gothic', 'ver' => YPM_POPUP_SILVER);
	    $options[] = array('name' => 'ypm-countdown-redirect-url', 'type' => 'text', 'defaultValue' => '');
	    $options[] = array('name' => 'ypm-countdown-redirect-url-tab', 'type' => 'checkbox', 'defaultValue' => '');
	    $options[] = array('name' => 'ypm-countdown-expire-text', 'type' => 'text', 'defaultValue' => '');

    	return $options;
    }

    private function extendDefaults()
    {
        global $YpmDefaults;


    }

    private function includeJsFiles()
    {
    	$jsUrl = YPM_POPUP_JS_URL.'/countdown/';
    	$cssUrl = YPM_POPUP_CSS_URL.'/countdown/';

	    ScriptsManager::registerScript('YpmTimeCircles.js', array('dirUrl' => $jsUrl, 'dep' => array('jquery')));
	    ScriptsManager::enqueueScript('YpmTimeCircles.js');
		ScriptsManager::registerScript('YpmCountdown.js', array('dirUrl' => $jsUrl));
		ScriptsManager::localizeScript('YpmCountdown.js', 'YPM_COUNTDOWN_ARGS', array(
                'isAdmin' => is_admin()
        ));
		ScriptsManager::enqueueScript('YpmCountdown.js');
	    ScriptsManager::registerScript('YpmMoment.js', array('dirUrl' => $jsUrl));
		ScriptsManager::enqueueScript('YpmMoment.js');

		ScriptsManager::registerStyle('YpmTimeCircles.css',  array('styleSrc' => $cssUrl));
		ScriptsManager::enqueueStyle('YpmTimeCircles.css');
    }

    public function renderView($args = array(), $content = array())
    {
        return $this->render();
    }

	public function getDataAllOptions()
	{
		$savedData = $this->getOptions();
		$savedData['id'] = $this->getId();

		return $savedData;
	}

	public function getCircleSeconds()
	{
		return 10;
	}

	public function generalOptionsData()
	{
		$options = array();

		$options['ypm-countdown-date-type'] = $this->getOptionValue('ypm-countdown-date-type');
		$options['ypm-countdown-duration-days'] = $this->getOptionValue('ypm-countdown-duration-days');
		$options['ypm-countdown-duration-hours'] = $this->getOptionValue('ypm-countdown-duration-hours');
		$options['ypm-countdown-duration-minutes'] = $this->getOptionValue('ypm-countdown-duration-minutes');
		$options['ypm-countdown-duration-seconds'] = $this->getOptionValue('ypm-countdown-duration-seconds');
		$options['ypm-countdown-save-duration'] = $this->getOptionValue('ypm-countdown-save-duration');
		$options['ypm-countdown-save-duration-each-user'] = $this->getOptionValue('ypm-countdown-save-duration-each-user');
		$options['ypm-date-time-picker'] = $this->getOptionValue('ypm-date-time-picker');
		$options['ypm-time-zone'] = $this->getOptionValue('ypm-circle-time-zone');
		$options['ypm-countdown-restart'] = $this->getOptionValue('ypm-countdown-restart');
		$options['ypm-countdown-restart-hour'] = $this->getOptionValue('ypm-countdown-restart-hour');

		do_action('ypmIncludeGeneralOptions', $this);

		return $options;
	}


	public function getCircleOptionsData()
	{
		$options = array();

		$modifiedObj = $this->getCircleSeconds();
		$modifiedSavedData = array();
		$options['id'] = $this->getId();
		$options['ypm-seconds'] = $modifiedObj;
		$options['ypm-countdown-date-type'] = $this->getOptionValue('ypm-countdown-date-type');
		$options += $this->generalOptionsData();

		$options['animation'] = $this->getOptionValue('ypm-circle-animation');
		$options['direction'] = $this->getOptionValue('ypm-countdown-direction');
		$options['fg_width'] = $this->getOptionValue('ypm-circle-width');
		$options['bg_width'] = $this->getOptionValue('ypm-circle-bg-width');
		$options['start_angle'] = $this->getOptionValue('ypm-circle-start-angle');
		$options['count_past_zero'] = false;
		if($this->getOptionValue('ypm-countdown-expire-behavior') == 'countToUp') {
			$options['count_past_zero'] = true;
		}
		$options['circle_bg_color'] = $this->getOptionValue('ypm-countdown-bg-circle-color');
		$options['use_background'] = $this->getOptionValue('ypm-countdown-background-circle');
		$options['ypm-count-up-from-end-date'] = $this->getOptionValue('ypm-count-up-from-end-date');
		$options['ypm-schedule-time-zone'] = $this->getOptionValue('ypm-schedule-time-zone');
		// Day numbers

		$options['startDay'] = $this->getOptionValue('ypm-schedule-start-day');
		$options['startDayNumber'] = @$modifiedSavedData['startDayNumber'];
		$options['endDay'] = $this->getOptionValue('ypm-schedule-end-day');
		$options['endDayNumber'] = @$modifiedSavedData['endDayNumber'];
		$options['currentDayNumber'] = @$modifiedSavedData['currentDayNumber'];
		$options['ypm-schedule-end-to'] = $this->getOptionValue('ypm-schedule-end-to');
		$options['ypm-schedule-start-from'] = $this->getOptionValue('ypm-schedule-start-from');
		$options['ypm-countdown-showing-limitation'] = $this->getOptionValue('ypm-countdown-showing-limitation');
		$options['ypm-countdown-expiration-time'] = $this->getOptionValue('ypm-countdown-expiration-time');
		$options['ypm-countdown-switch-number'] = $this->getOptionValue('ypm-countdown-switch-number');

		$options['time'] = array(
			'Years' => array(
				'text' =>  $this->getOptionValue('ypm-countdown-years-text'),
				'color' =>  $this->getOptionValue('ypm-countdown-years-color'),
				'show' => $this->getOptionValue('ypm-countdown-years')
			),
			'Months' => array(
				'text' => $this->getOptionValue('ypm-countdown-months-text'),
				'color' => $this->getOptionValue('ypm-countdown-months-color'),
				'show' => $this->getOptionValue('ypm-countdown-months')
			),
			'Days' => array(
				'text' =>  $this->getOptionValue('ypm-countdown-days-text'),
				'color' =>  $this->getOptionValue('ypm-countdown-days-color'),
				'show' => $this->getOptionValue('ypm-countdown-days')
			),
			'Hours' => array(
				'text' => $this->getOptionValue('ypm-countdown-hours-text'),
				'color' =>  $this->getOptionValue('ypm-countdown-hours-color'),
				'show' => $this->getOptionValue('ypm-countdown-hours')
			),
			'Minutes' => array(
				'text' => $this->getOptionValue('ypm-countdown-minutes-text'),
				'color' => $this->getOptionValue('ypm-countdown-minutes-color'),
				'show' => $this->getOptionValue('ypm-countdown-minutes')
			),
			'Seconds' => array(
				'text' => $this->getOptionValue('ypm-countdown-seconds-text'),
				'color' => $this->getOptionValue('ypm-countdown-seconds-color'),
				'show' => $this->getOptionValue('ypm-countdown-seconds')
			),
		);

		return $options;
	}

	private function getBgImageStyleStr()
	{
		$imageUrl = $this->getOptionValue('ypm-bg-image-url');
		$bgImageSize = $this->getOptionValue('ypm-bg-image-size');
		$imageRepeat = $this->getOptionValue('ypm-bg-image-repeat');
		$styles = 'background-image: url('.$imageUrl.'); background-repeat: '.$imageRepeat.'; background-size: '.$bgImageSize.'; ';

		return $styles;
	}

	private function getFontFamilyByName($name)
	{
		$fontFamily = $this->getOptionValue($name);
		if ($fontFamily == 'customFont') {
			$fontFamily = $this->getOptionValue($name.'-custom');
		}

		return $fontFamily;
	}

	private function renderStyles() {

		$id = $this->getId();
		// text styles
		$fontSize = $this->getOptionValue('ypm-text-font-size');
		$marginTop = $this->getOptionValue('ypm-text-margin-top');
		$fontWeight = $this->getOptionValue('ypm-countdown-font-weight');
		$fontStyle = $this->getOptionValue('ypm-countdown-font-style');
		$fontFamily = $this->getFontFamilyByName('ypm-text-font-family');
		// numbers styles
		$fontSizeNumber = $this->getOptionValue('ypm-countdown-number-size');
		$marginToNumber = $this->getOptionValue('ypm-number-margin-top');
		$fontWeightNumber = $this->getOptionValue('ypm-countdown-number-font-weight');
		$fontStyleNumber = $this->getOptionValue('ypm-countdown-number-font-style');
		$fontFamilyNumber = $this->getFontFamilyByName('ypm-countdown-number-font');

		$yearsColor = $this->getOptionValue('ypm-countdown-years-text-color');
		$monthsColor = $this->getOptionValue('ypm-countdown-months-text-color');
		$daysTextColor = $this->getOptionValue('ypm-countdown-days-text-color');
		$hoursTextColor = $this->getOptionValue('ypm-countdown-hours-text-color');
		$minutesTextColor = $this->getOptionValue('ypm-countdown-minutes-text-color');
		$secondsTextColor = $this->getOptionValue('ypm-countdown-seconds-text-color');
		$circleAlignment = $this->getOptionValue('ypm-circle-alignment');
		$padding = $this->getOptionValue('ypm-countdown-padding').'px';

		$shadowHorizontal = $this->getOptionValue('ypm-circle-box-shadow-horizontal-length').'px';
		$shadowVertical = $this->getOptionValue('ypm-circle-box-shadow-vertical-length').'px';
		$shadowBlurRadius = $this->getOptionValue('ypm-circle-box-blur-radius').'px';
		$shadowSpreadRadius = $this->getOptionValue('ypm-circle-box-spread-radius').'px';
		$shadowColor = $this->getOptionvalue('ypm-circle-box-shadow-color');

		ob_start();
		?>
		<style>
			#ypm-circle-<?php echo $id; ?> {
				padding: <?php echo $padding; ?>;
				box-sizing: border-box;
				display: inline-block;
			}
			#ypm-circle-<?php echo $id; ?> h4 {
				font-size: <?php echo $fontSize; ?>px !important;
				margin-top: <?php echo $marginTop; ?>px !important;
				font-weight: <?php echo $fontWeight; ?> !important;
				font-style: <?php echo $fontStyle; ?> !important;
				font-family: <?php echo $fontFamily; ?> !important;
			}
			#ypm-circle-<?php echo $id; ?> span {
				font-size: <?php echo $fontSizeNumber; ?>px !important;
				margin-top: <?php echo $marginToNumber; ?>px !important;
				font-weight: <?php echo $fontWeightNumber; ?> !important;
				font-style: <?php echo $fontStyleNumber; ?> !important;
				font-family: <?php echo $fontFamilyNumber; ?> !important;
			}
			#ypm-circle-<?php echo $id; ?> .textDiv_Years h4,
			#ypm-circle-<?php echo $id; ?> .textDiv_Years span {
				color: <?php echo $yearsColor; ?>
			}
			#ypm-circle-<?php echo $id; ?> .textDiv_Months h4,
			#ypm-circle-<?php echo $id; ?> .textDiv_Months span {
				color: <?php echo $monthsColor; ?>
			}
			#ypm-circle-<?php echo $id; ?> .textDiv_Days h4,
			#ypm-circle-<?php echo $id; ?> .textDiv_Days span {
				color: <?php echo $daysTextColor; ?>
			}
			#ypm-circle-<?php echo $id; ?> .textDiv_Hours h4,
			#ypm-circle-<?php echo $id; ?> .textDiv_Hours span {
				color: <?php echo $hoursTextColor; ?>
			}

			#ypm-circle-<?php echo $id; ?> .textDiv_Minutes h4,
			#ypm-circle-<?php echo $id; ?> .textDiv_Minutes span {
				color: <?php echo $minutesTextColor; ?>
			}
			#ypm-circle-<?php echo $id; ?> .textDiv_Seconds h4,
			#ypm-circle-<?php echo $id; ?> .textDiv_Seconds span {
				color: <?php echo $secondsTextColor; ?>
			}
			.ypm-circle-<?php echo $id; ?>-wrapper {
				text-align: <?php echo $circleAlignment; ?>;
			}
			<?php if($this->getOptionValue('ypm-circle-box-shadow')): ?>
			.ypm-circle-<?php echo $id; ?>-wrapper .time_circles {
				-webkit-box-shadow: <?php echo $shadowHorizontal.' '.$shadowVertical.' '.$shadowBlurRadius.' '.$shadowSpreadRadius.' '.$shadowColor; ?>;
				-moz-box-shadow: <?php echo $shadowHorizontal.' '.$shadowVertical.' '.$shadowBlurRadius.' '.$shadowSpreadRadius.' '.$shadowColor; ?>;
				box-shadow: <?php echo $shadowHorizontal.' '.$shadowVertical.' '.$shadowBlurRadius.' '.$shadowSpreadRadius.' '.$shadowColor; ?>;
			}
			<?php endif; ?>
		</style>
		<?php
		$styles = ob_get_contents();
		ob_get_clean();

		return $styles;
	}

	public function render()
	{
		$id = $this->getId();

		$seconds = 10;
		$bgImageStyleStr = $this->getBgImageStyleStr();
		$styles = $this->renderStyles();
		$allDataOptions = $this->getDataAllOptions();
		$allDataOptions = json_encode($allDataOptions, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
		$prepareOptions = $this->getCircleOptionsData();
		$prepareOptions = json_encode($prepareOptions, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
		$width = (int)$this->getOptionValue('ypm-countdown-width');
		$widthMeasure = $this->getOptionValue('ypm-dimension-measure');
		$width .= $widthMeasure;
		$content = '<div class="ypm-countdown-wrapper">';
		$content .= apply_filters('ypmCircleCountdownPrepend', '', $this);
		$content .= '<div class="ypm-circle-expiration-before-countdown" style="display: none">'.do_shortcode($this->getOptionValue('ypm-circle-countdown-expiration-before-countdown')).'</div>';
		$content .= '<div class="ypm-circle-before-countdown">'.do_shortcode($this->getOptionValue('ypm-circle-countdown-before-countdown')).'</div>';
		ob_start();
		?>
		<div class="ypm-circle-<?php echo esc_attr($id); ?>-wrapper ypm-circle-wrapper">
			<div id="ypm-circle-<?php echo esc_attr($id); ?>" class="ypm-time-circle" data-options='<?php echo $prepareOptions; ?>' data-all-options='<?php echo $allDataOptions; ?>' data-timer="<?php echo $seconds ?>" style="<?php echo $bgImageStyleStr ?> width: <?php echo $width; ?>; height: 100%; padding: 0; box-sizing: border-box; background-color: inherit"></div>
		</div>
		<?php
		$content .= ob_get_contents();
		ob_get_clean();
		$content .= '<div class="ypm-circle-expiration-after-countdown" data-key="" style="display: none">'.do_shortcode($this->getOptionValue('ypm-circle-countdown-expiration-after-countdown')).'</div>';
		$content .= '<div class="ypm-circle-after-countdown" data-key="">'.do_shortcode($this->getOptionValue('ypm-circle-countdown-after-countdown')).'</div>';
		$content .= '</div>';
		$content .= $styles;

		return $content;
	}

    public static function create($data, $obj = '')
    {
	    DataConfig::init();
        $obj = new self();
        parent::create($data, $obj);
    }
}