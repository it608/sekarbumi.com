<?php
namespace YpmPopup;
use ypmFrontend\PopupIncluder;

class YpmShortcode {

	private $attrs;
	private $content;

	public function setAttrs($attrs) {

		$this->attrs = $attrs;
	}

	public function getAttrs() {

		return $this->attrs;
	}

	public function setContent($content) {

		$this->content = do_shortcode($content);
	}

	public function getContent() {

		return $this->content;
	}

	public function render() {

		$attrs = $this->getAttrs();
		$content = $this->getContent();
		$id = (int)$attrs['id'];

		if(empty($id)) {
			return;
		}
		$loadable = false;
		$attr = '';

		ob_start();

		if(!empty($content))  {
			echo "<a href='javascript:void(0)' class='ypm-open-popup' data-ypm-popup-id=" . esc_attr($id) . " $attr data-popup-event=" . esc_attr($attrs['event']) . ">" . esc_attr($content) . "</a>";
		}
		else {
			$loadable = true;
		}

		$shortcodeData = ob_get_contents();
		ob_end_clean();
		$popup = Popup::find($id);

		$includer = new PopupIncluder();
		$includer->setId($id);
		$includer->setPopup($popup);
		$includer->setLoadable($loadable);
		$includer->setReferBy(2);
		$includer->includePopup();

		return $shortcodeData;
	}
}