<?php
namespace YpmPopup;
require_once(dirname(__FILE__).'/Popup.php');
require_once(dirname(__FILE__).'/PopupViewInterface.php');

if (YPM_POPUP_PKG != YPM_POPUP_FREE) {
	require_once(dirname(__FILE__).'/YoutubePopupPro.php');
}

class YoutubePopup extends Popup implements PopupViewInterface
{
	public $shortCodeName = 'ypm_youtube';

	public function __construct()
	{
		$this->includeJsFiles();
	}

	public function getStylesNames()
	{
		$settings = array(
			'width' => 'ypm-youtube-width',
			'height' => 'ypm-youtube-height'
		);

		return $settings;
	}
	public static function create($data, $obj = '')
	{
		$obj = new self();
		parent::create($data, $obj);
	}

	private function getVideoId()
	{
		$savedUrl = $this->getOptionValue('ypm-youtube-url');
		$parsed = parse_url($savedUrl);
		if (empty($parsed['query'])) {
			return false;
		}
		parse_str(@$parsed['query'], $output);
		if (empty($output['v'])) {
			return false;
		}

		return $output['v'];
	}

	public function getStyles($id)
	{
		$stylesValues = $this->getStylesNames();

		$styles = '<style type="text/css">';
		$styles .= '.ypm-iframe-'.$id.' {';
		foreach ($stylesValues as $key => $optionName) {
			$styles .= $key.": ".$this->getOptionValue($optionName).';';
		}
		$styles .= 'max-width: 100%;';
		$styles .= '}';
		$styles .= '</style>';

		return $styles;
	}

	private function getCurrentAllOptions()
	{
		$options = array(
			'ypm-youtube-url',
			'ypm-youtube-width',
			'ypm-youtube-height',
			'ypm-youtube-autoplay',
			'ypm-youtube-start',
			'ypm-youtube-color',
			'ypm-youtube-controls',
			'ypm-popup-expiration-behavior',
			'ypm-youtube-redirect-url',
			'ypm-youtube-redirect-url-tab',
		);

		$data = array();
		foreach ($options as $option) {
			$data[$option] = $this->getOptionValue($option);
		}

		return $data;
	}

	public function playerVars($savedOptions)
	{
		$varTypes = array(
			'start' => 'number'
		);

		$varTypes = apply_filters('ypmYoutubeTypes', $varTypes);

		$namesMap = array(
			'start' => 	'ypm-youtube-start'
		);

		$namesMap = apply_filters('ypmNamesMap', $namesMap);

		$values = array();
		foreach ($namesMap as $option => $name) {
			$savedValue = '';
			if (!empty($savedOptions[$name])) {
				$savedValue = $savedOptions[$name];
			}
			$type = $varTypes[$option];
			if ($type == 'bool') {
				$savedValue = (bool)$savedValue;
			}
			if ($type == 'zeroOrOne') {
				$savedValue = $savedValue ? 1 : 0;
			}
			$values[$option] = $savedValue;
		}

		return $values;
	}

	private function getVideo()
	{
		$videoId = $this->getVideoId();

		$options = $this->getCurrentAllOptions();
		$options['videoId'] = $videoId;
		$options['playerVars'] = $this->playerVars($options);
		$id = $this->getPopupId();
		$videoUrl = 'https://www.youtube.com/embed/'.$videoId;
		$videoUrl = apply_filters('ypmYoutubeVideoUrl', $videoUrl, $this);

		ob_start();
		?>
		<div class="ypm-iframe-div ypm-iframe-<?php echo esc_attr($id); ?>" data-video-id="<?php echo esc_attr($id); ?>" data-options='<?php echo wp_json_encode($options); ?>'></div>
		<?php
		$content = ob_get_contents();
		ob_end_clean();
		$content .= $this->getStyles($id);

		return $content;
	}

	public function renderView($args, $content)
	{
		return $this->getVideo();
	}

	public function includeJsFiles()
	{
		if (YPM_POPUP_PKG != YPM_POPUP_FREE) {
			ScriptsManager::registerScript('YpmYoutubePro.js', array('dirUrl' => YPM_POPUP_JS_URL.'youtube/'));
			ScriptsManager::enqueueScript('YpmYoutubePro.js');
		}
		ScriptsManager::registerScript('YpmYoutube.js', array('dirUrl' => YPM_POPUP_JS_URL.'youtube/'));
		ScriptsManager::enqueueScript('YpmYoutube.js');

		ScriptsManager::registerScript('YpmYoutubeLib.js', array('dirUrl' => YPM_POPUP_JS_URL.'youtube/'));
		ScriptsManager::enqueueScript('YpmYoutubeLib.js');
	}

	public function renderLivePreview()
	{
		echo wp_kses($this->getVideo(), \YpmAdminHelper::getAllowedTags());
	}
}