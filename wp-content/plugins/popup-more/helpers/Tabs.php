<?php
namespace ypmgamification;

class Tabs
{
	private $config;

	public function setConfig($config)
	{
		$this->config = $config;
	}

	public function getConfig()
	{
		return $this->config;
	}

	public static function create($config, $selectedCol = '', $popupTypeObj = '')
	{
		$obj = new self();
		$obj->setConfig($config);
		$obj->selectedCol = strtolower($selectedCol);
		$obj->popupTypeObj = $popupTypeObj;
		return $obj;
	}

	public function render()
	{
		$settings = $this->getConfig();
		$settingsCount = sizeof($settings);
		$colSize = 3;
		if ($settingsCount <= 6) {
			$colSize = 12/$settingsCount;
		}
		$selectedCol = $this->selectedCol;
		$content = '';
		$activeClassName = '';
		ob_start();
		?>
		<div class="ypm-options-tabs-wrapper">
			<?php foreach($settings as $key => $label): ?>
				<?php if ($selectedCol == $key): ?>
					<?php $activeClassName = 'ypm-active-tab'; ?>
				<?php endif; ?>
				<input type="button" value="<?php echo esc_attr($label); ?>" data-key="<?php echo esc_attr($key); ?>" class="btn ypm-options-tab-links ypm-option-tab-<?php echo esc_attr($key); ?> col-md-<?php echo esc_attr($colSize); ?> <?php echo esc_attr($activeClassName); ?>">
				<?php $activeClassName = ''; ?>
			<?php endforeach; ?>
		</div>
		<?php
		$content .= ob_get_contents();
		ob_end_clean();
		$content .= '<input type="hidden" class="ypm-active-tab-name" value="'.esc_attr($selectedCol).'">';
		$content .= $this->renderContents();

		return $content;
	}

	public function renderContents()
	{
		$settings = $this->getConfig();
		$tabName = $this->selectedCol;
		$popupTypeObj = $this->popupTypeObj;
		ob_start();
		foreach($settings as $key => $label) { ?>
			<div id="ypm-tab-content-wrapper-<?php echo esc_attr($key); ?>" class="ypm-tab-content-wrapper" <?php echo ($tabName == $key) ? 'style="display: block;"': 'style="display: none"'; ?>>
				<?php @include_once(YPM_POPUP_GAMIFICATION_VIEW.$key.'.php');?>
			</div>
		<?php }
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}

}