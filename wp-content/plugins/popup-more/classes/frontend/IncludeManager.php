<?php
namespace ypmFrontend;

use YpmPopup\ScriptsManager;

class IncludeManager
{
	private $includerObj;

	public function setIncluderObj($includerObj)
	{
		$this->includerObj = $includerObj;
	}

	public function getIncluderObj()
	{
		return $this->includerObj;
	}

	public function loadPopupToPage()
	{
		$includerObj = $this->getIncluderObj();
		$id = $includerObj->getId();

		$this->includeScripts();
		$this->includeContent();
		if($includerObj->getLoadable()) {
			$this->openByJs($id);
		}
	}
	private function includeScripts()
	{
		wp_enqueue_script('jquery');
		ScriptsManager::registerScript('YpmPopup.js', array('dirUrl' => YPM_POPUP_JS_URL));
		if (!ypm_is_free()) {
			ScriptsManager::registerScript('YpmAdvanced.js', array('dirUrl' => YPM_POPUP_PRO_JS_URL));
		}

		wp_register_script('YpmObserver', YPM_POPUP_JS_URL.'YpmObserver.js');
		wp_register_script('jquery.colorbox', YPM_POPUP_JS_URL.'jquery.colorbox.js');
		$includerObj = $this->getIncluderObj();
		$popupOptions = $includerObj->getOptions();

		do_action('YpmPopupLoadScripts', $popupOptions);

		ScriptsManager::localizeScript('YpmPopup.js', 'YpmParams', array(
			'ajaxNonce' => wp_create_nonce('ypmPMNonce'),
			'ajaxurl' => admin_url('admin-ajax.php'),
			'homePageUrl' => get_home_url().'/',
			'soundsURL' => YPM_POPUP_SOUNDS_URL,
		));
		ScriptsManager::enqueueScript('YpmPopup.js');
		if (!ypm_is_free()) {
			ScriptsManager::enqueueScript('YpmAdvanced.js');
		}
		wp_enqueue_script("jquery.colorbox");
		wp_enqueue_script("YpmObserver");

		wp_register_style('ypmcolorbox', YPM_POPUP_CSS_URL."colorbox/colorbox.css");
		wp_enqueue_style('ypmcolorbox');
	}

	private function includeContent()
	{

		$includerObj = $this->getIncluderObj();
		$content = $includerObj->getContent();
		$content = apply_filters('ypmRenderContent', $content, $includerObj);
		$id = $includerObj->getId();

		$options = $includerObj->getOptions();

		add_action('wp_footer', function() use ($content, $id, $options){
			$allOptions = $options;
			$options = json_encode($options);
			echo "<script>
				if (typeof YPM_DATA === 'undefined') {YPM_DATA = []}
				if (YPM_DATA.indexOf($id) == '-1') {YPM_DATA[$id] = $options;}
			</script>";
			$popupContent = "<div style=\"display:none\" class='ypm-hide'><div id=\"ypm-popup-content-wrapper-$id\" >".$content."</div></div>";
			$popupContent = apply_filters('ypmRenderContentEnd', $popupContent, $allOptions);

			echo wp_kses($popupContent, \YpmAdminHelper::getAllowedTags());
		}, 1);
	}

	private function openByJs($id)
	{
		add_action('wp_footer', function() use ($id){
			echo wp_kses("<script>
				if (typeof YPM_IDS != 'undefined') {
					if (YPM_IDS.indexOf($id) == '-1') {
						YPM_IDS.push(".esc_attr($id).");	
					}
				}
			</script>", \YpmAdminHelper::getAllowedTags());
		}, 1);
	}
}