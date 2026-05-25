<?php
namespace YpmPopup;

class YoutubePopupPro
{
	public function __construct()
	{
		$this->init();
	}

	public function init()
	{
		add_filter('ypmYoutubeVideoUrl', array($this, 'videoUrl'),2,2);
		add_filter('ypmYoutubeTypes', array($this, 'typesList'));
		add_filter('ypmNamesMap', array($this, 'namesMap'));
	}

	public function videoUrl($url, $obj)
	{
		$params = array();
		if (!empty($obj->getOptionValue('ypm-youtube-autoplay'))) {
			$params['autoplay'] = 1;
		}
		if (!empty($obj->getOptionValue('ypm-youtube-loop-video'))) {
			$params['loop'] = 1;
		}
		$url = add_query_arg($params, $url);

		return $url;
	}

	public function typesList($typesList)
	{
		$typesList['autoplay'] = 'bool';
		$typesList['color'] = 'text';
		$typesList['rel'] = 'zeroOrOne';
		$typesList['controls'] = 'zeroOrOne';

		return $typesList;
	}

	public function namesMap($namesMap)
	{
		$namesMap['autoplay'] = 'bool';
		$namesMap['color'] = 'ypm-youtube-autoplay';
		$namesMap['rel'] = 'ypm-youtube-color';
		$namesMap['controls'] = 'ypm-youtube-controls';

		return $namesMap;
	}
}

new YoutubePopupPro();