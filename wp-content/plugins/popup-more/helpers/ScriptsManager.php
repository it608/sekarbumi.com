<?php
namespace YpmPopup;

class ScriptsManager
{
	public static function registerStyle($styleName, $args = array())
	{
		$src = YPM_POPUP_CSS_URL;
		$deps = array();
		$ver = YPM_POPUP_VERSION;
		$media = 'all';

		if(!empty($args['styleSrc'])) {
			$src = $args['styleSrc'];
		}

		if(!empty($args['deps'])) {
			$deps = $args['deps'];
		}

		$fileName = $styleName;
		if(!empty($args['fileName'])) {
			$fileName = $args['fileName'];
		}

		if(!empty($args['version'])) {
			$ver = $args['version'];
		}

		if(!empty($args['media'])) {
			$media = $args['media'];
		}

		$src = $src.$fileName;

		wp_register_style($styleName, $src, $deps, $ver, $media);
	}

	public static function enqueueStyle($style)
	{
		wp_enqueue_style($style);
	}

	public static function loadStyle($style)
	{
		self::registerStyle($style);
		self::enqueueStyle($style);
	}

	/**
	 * Countdown register style
	 *
	 * @since 1.5.8
	 *
	 * @param string $fileName file address
	 * @param array $args wordpress register  script args dep|ver|inFooter|dirUrl
	 *
	 * @return void
	 */
	public static function registerScript($fileName, $args = array())
	{
		if(empty($fileName)) {
			return;
		}

		$dep = array();
		$ver = YPM_POPUP_VERSION;
		$inFooter = false;
		$dirUrl = YPM_POPUP_JS_URL;

		if(!empty($args['dep'])) {
			$dep = $args['dep'];
		}

		if(!empty($args['ver'])) {
			$ver = $args['ver'];
		}

		if(!empty($args['inFooter'])) {
			$inFooter = $args['inFooter'];
		}

		if(!empty($args['dirUrl'])) {
			$dirUrl = $args['dirUrl'];
		}

		wp_register_script($fileName, $dirUrl.''.$fileName, $dep, $ver, $inFooter);
	}

	/**
	 * Popup register style
	 *
	 * @since 1.5.8
	 *
	 * @param string $fileName file address
	 *
	 * @return void
	 */
	public static function enqueueScript($fileName)
	{
		if(empty($fileName)) {
			return;
		}
		wp_enqueue_script($fileName);
	}

	public static function localizeScript($handle, $name, $data)
	{
		wp_localize_script($handle, $name, $data);
	}

	public static function loadScript($scriptName)
	{
		self::registerScript($scriptName);
		self::enqueueScript($scriptName);
	}
}