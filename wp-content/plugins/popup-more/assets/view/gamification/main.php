<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
require_once(YPM_POPUP_HELPERS."/Tabs.php");
use ypmgamification\Tabs;
$tyneMceArgs = YpmAdminHelper::getTyneMceArgs();
$settingsTab = YpmAdminHelper::getGamificationSettingsTabConfig();
?>
<div class="ycf-bootstrap-wrapper">
    <?php
    if ($popupTypeObj->getOptionValue('ypm-popup-id')) {
        echo wp_kses(YpmAdminHelper::createTypePopupNotice(YPM_GAMIFICATION_POST_TYPE, $popupTypeObj->getOptionValue('ypm-popup-id')), YpmAdminHelper::getAllowedTags());
    }
    ?>
	<div class="row">
		<div class="col-md-12">
			<div class="ypm-tabs-content-wrapper">
				<?php
				$tabName = 'contents';
				if (!empty($_GET['ypmPageKeyTab'])) {
					$tabName = sanitize_text_field($_GET['ypmPageKeyTab']);
				}
				else if (!empty($_COOKIE['YPMGamificationActiveTab'])) {
					$tabName = sanitize_text_field($_COOKIE['YPMGamificationActiveTab']);
				}
				$tabs = Tabs::create($settingsTab, $tabName, $popupTypeObj);
				echo $tabs->render();
				?>
			</div>
		</div>
	</div>
	<!-- Input styles start -->
</div>
