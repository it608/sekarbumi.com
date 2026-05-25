<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
global $YpmDefaultsData;
$exitModeData = array();
if (!empty($YpmDefaultsData['exitMode'])) {
	$exitModeData = $YpmDefaultsData['exitMode'];
}
?>
<div class="ycf-bootstrap-wrapper ycf-pro-wrapper">

	<?php if(ypm_is_free()): ?>
		<div class="ypm-free-options-wrapper ypm-pro-options-wrapper ycf-pro-wrapper">
            <?php echo YpmUpgradeText('Upgrade Exit intent in PRO Version') ?>
            <?php require_once dirname(__FILE__).'/metaboxes/exitIntentOptions.php'; ?>
            <div class="ypm-pro-options"></div>
		</div>
	<?php endif;?>
    <?php if (!ypm_is_free()) require_once dirname(__FILE__).'/metaboxes/exitIntentOptions.php'; ?>
</div>
