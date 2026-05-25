<?php
class YpmTabBuilder {

	private $fbButtons;
	private $savedData;
	private $options;

	public function __construct($fbButtons, $savedData, $options) {

		$this->fbButtons = $fbButtons;
		$this->savedData = $savedData;
		$this->options = $options;
	}

	public function createNaveTabs() {

		$fbButtons = $this->fbButtons;
		$naveTabs = '<ul class="nav nav-tabs">';

		foreach($fbButtons as $buttonKey => $buttonLabel) {
			$activeTab = '';
			if($buttonKey == $this->savedData) {
				$activeTab = 'active';
			}
			$naveTabs .= '<li class="'.esc_attr($activeTab).'"><a data-toggle="tab" href="#'.esc_attr($buttonKey).'">'.wp_kses($buttonLabel, YpmAdminHelper::getAllowedTags()).'</a></li>';
		}
		$naveTabs .= '</ul>';

		return $naveTabs;
	}

	public function __toString() {

		$content = $this->createNaveTabs();
		$content .= $this->getTabsContent();

		return $content;
	}

	private function getTabsContent() {

		$fbButtons = $this->fbButtons;
		$popupTypeObj = $this->options['popupTypeObj'];
		ob_start();
		?>
		<div class="tab-content ypm-tab-content">
			<?php foreach($fbButtons as $buttonKey => $buttonLabel):
				$activeKey = '';
				if($this->savedData == $buttonKey) {
					$activeKey = 'in active';
				}
			?>
			<div id="<?php echo esc_attr($buttonKey); ?>" class="tab-pane fade <?php echo esc_attr($activeKey); ?>">
				<?php require_once $this->options['viewPath'].esc_attr($buttonKey).'.php'; ?>
			</div>
			<?php endforeach; ?>
		</div>
		<?php
		$tabsContent = ob_get_contents();
		ob_get_clean();

		return $tabsContent;
	}
}