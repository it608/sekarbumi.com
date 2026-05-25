<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
function ypmCheckIsChecked($name) {
	return get_option($name) ? 'checked': '';
}
?>
<?php if(!empty($_GET['saved'])) : ?>
	<div id="default-message" class="updated notice notice-success is-dismissible ypm-save-banner">
		<p><?php echo esc_attr_e('Settings saved.', 'popup_master');?></p><button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php echo esc_attr_e('Dismiss this notice.', 'popup_master');?></span></button>
	</div>
<?php endif; ?>
<div class="ycf-bootstrap-wrapper ypm-settings-wrapper">
	<div class="row">
		<div class="col-lg-8">
			<form method="POST" action="<?php echo admin_url().'admin-post.php?action=ypmSaveSettings'?>">
				<?php
				if(function_exists('wp_nonce_field')) {
					wp_nonce_field('ypm_popup_settings');
				}
				?>
				<div class="panel panel-default">
					<div class="panel-heading"><?php esc_attr_e('Settings', 'popup_master')?></div>
					<div class="panel-body">
						<div class="row ypm-margin-bottom-15">
							<div class="col-xs-3">
								<label class="control-label" for="ypm-hide-modules-menu"><?php esc_attr_e('Hide modules menu', 'popup_master');?>:</label>
							</div>
							<div class="col-xs-4">
								<label class="ypm-switch">
									<input type="checkbox" id="ypm-hide-modules-menu" name="ypm-hide-modules-menu" <?php echo ypmCheckIsChecked('ypm-hide-modules-menu') ?>>
									<span class="ypm-slider ypm-round"></span>
								</label>
								<br>
							</div>
						</div>
						<div class="row ypm-margin-bottom-15">
							<div class="col-xs-3">
								<label class="control-label" for="ypm-hide-modules-menu"><?php esc_attr_e('Hide media buttons', 'popup_master');?>:</label>
							</div>
							<div class="col-xs-4">
								<label class="ypm-switch">
									<input type="checkbox" id="ypm-hide-media-button" name="ypm-hide-media-button" <?php echo ypmCheckIsChecked('ypm-hide-media-button') ?>>
									<span class="ypm-slider ypm-round"></span>
								</label>
								<br>
							</div>
						</div>
						<input type="submit" class="button-primary" value="<?php esc_attr_e('Save setting', 'popup_master'); ?>">
					</div>
				</div>
			</form>
		</div>
	</div>
</div>