<?php
namespace YpmPopup;
use \YpmFunctions;

class YpmMediaButton {

	public static function addMediaButton() {

		global $pagenow, $typenow;
		$buttonTitle = 'Insert popup';
		$output = '';

		$pages = array(
			'post.php',
			'page.php',
			'post-new.php',
			'post-edit.php',
			'widgets.php'
		);

		$checkPage = in_array(
			$pagenow,
			$pages
		);


		if($checkPage && $typenow != 'download') {

			wp_enqueue_script('jquery-ui-dialog');
			ScriptsManager::registerStyle('jquery-ui.css', array('styleSrc' => YPM_POPUP_CSS_URL.'/jQueryDialog/'));
			ScriptsManager::enqueueStyle('jquery-ui.css');
			$img = '<span class="dashicons-before dashicons-admin-post ypm-dash-button" style="padding: 3px 2px 0px 0px"></span>';
			$output = '<a href="javascript:void(0);" onclick="jQuery(\'#ypm-thickbox\').dialog({ width: 450, modal: true, title: \'Insert the shortcode\', dialogClass: \'ypm-popup-master\'});" class="button" title="'.esc_attr($buttonTitle).'" style="padding-left: .4em;">'. wp_kses($img, \YpmAdminHelper::getAllowedTags()).esc_attr($buttonTitle).'</a>';
		}

		echo wp_kses($output, \YpmAdminHelper::getAllowedTags());
	}

	public static function ypmThickbox() {

		global $pagenow, $typenow;

		$pages = array(
			'post.php',
			'page.php',
			'post-new.php',
			'post-edit.php',
			'widgets.php'
		);

		$checkPage = in_array(
			$pagenow,
			$pages
		);

		if ($checkPage && $typenow != 'download') : ?>
			<script type="text/javascript">
				jQuery(document).ready(function ($) {

					$('#ypm-popup-insert').on('click', function () {
						var id = $('#ypm-insert-popup-id').val();
						if ('' === id) {
							alert('Select your popup');
							return;
						}
						var appearEvent = jQuery("#ypm-popup-event").val();

						var selectionText = '';
						if (typeof(tinyMCE.editors.content) != "undefined") {
							selectionText = (tinyMCE.activeEditor.selection.getContent()) ? tinyMCE.activeEditor.selection.getContent() : '';
						}
						/* For plugin editor selected text */
						else if(typeof(tinyMCE.editors[0]) != "undefined") {
							var pluginEditorId = tinyMCE.editors[0]['id'];
							selectionText = (tinyMCE['editors'][pluginEditorId].selection.getContent()) ? tinyMCE['editors'][pluginEditorId].selection.getContent() : '';
						}
						if(appearEvent == 'onload') {
							selectionText = '';
						}

						window.send_to_editor('[ypm_popup id="' + id + '" event="'+appearEvent+'"]'+selectionText+"[/ypm_popup]");
						jQuery('#ypm-thickbox').dialog( "close" );
					});
				});
			</script>
			<div id="ypm-thickbox" style="display: none;">
				<div class="wrap">
					<p>Insert the shortcode for showing a Popup.</p>
					<div class="ypm-select-popup">
						<span style="display: inline-block; width: 100px;margin-bottom: 10px;">Select Popup</span>
						<?php
							$popupData = Popup::getPopupIdTitleData();
							echo \YpmFunctions::createSelectBox($popupData, '', array('id' => 'ypm-insert-popup-id'));
						?>
					</div>
					<div class="ypm-select-popup">
						<span style="display: inline-block; width: 100px;">Select Event</span>
						<select id="ypm-popup-event">
							<option value="onload">On load</option>
							<option value="click">Click</option>
							<option value="hover">Hover</option>
						</select>
					</div>
					<p class="submit">
						<input type="button" id="ypm-popup-insert" class="button-primary dashicons-welcome-widgets-menus" value="Insert"/>
						<a id="ypm_popup_cancel" class="button-secondary" onclick="jQuery('#ypm-thickbox').dialog( 'close' )" title="Cancel">Cancel</a>
					</p>
				</div>
			</div>
		<?php endif;
	}

	public static function addMediaModuleButton() {

		global $typenow;
		$output = '';

		if($typenow == YPM_POPUP_POST_TYPE) {
			$data = Popup::getModulesDataArray();

			$buttonTitle = 'Insert popup modules';

			wp_enqueue_script('jquery-ui-dialog');
			ScriptsManager::registerStyle('jquery-ui.css', array('styleSrc' => YPM_POPUP_CSS_URL.'/jQueryDialog/'));
			ScriptsManager::enqueueStyle('jquery-ui.css');
			$img = '<span class="dashicons-before dashicons-admin-post ypm-dash-button" style="padding: 3px 2px 0 0"></span>';
			$output = '<a href="javascript:void(0);" onclick="jQuery(\'#ypm-modules-thickbox\').dialog({ width: 450, modal: true, title: \'Insert the shortcode\', dialogClass: \'ypm-popup-master\'});" class="button" title="'.esc_attr($buttonTitle).'" style="padding-left: .4em;">'. wp_kses($img, \YpmAdminHelper::getAllowedTags()).esc_attr($buttonTitle, \YpmAdminHelper::getAllowedTags()).'</a>';
		}

		echo wp_kses($output, \YpmAdminHelper::getAllowedTags());
	}

	public static function ypmModulesThickbox() {

		global $typenow;

		if($typenow != YPM_POPUP_POST_TYPE) {
			return '';
		}
		?>
		<script type="text/javascript">
			jQuery(document).ready(function ($) {

				$('#ypm-popup-module-insert').on('click', function () {
					var id = $('#ypm-insert-popup-module').val();
					if ('' === id) {
						alert('Select popup module');
						return;
					}

					var moduelName = $('#ypm-insert-popup-module option:selected').closest('optgroup').prop('label');
					moduelName = moduelName.toLowerCase();

					window.send_to_editor('[ypm_'+moduelName+' id="' + id + '"]');
					jQuery('#ypm-modules-thickbox').dialog( "close" );
				});
			});
			</script>
			<div id="ypm-modules-thickbox" style="display: none;">
				<div class="wrap">
					<p>Insert popup module shortcode.</p>
					<div class="ypm-select-popup">
						<span style="display: inline-block; width: 100px;margin-bottom: 10px;">Select Popup</span>
						<?php
						$popupData = Popup::getModulesDataArray();
						if(empty($popupData)) {
							$popupData[''] = 'Please create your first module';
						}
						echo YpmFunctions::createSelectBox($popupData, '', array('id' => 'ypm-insert-popup-module'));
						?>
		</div>
		<p class="submit">
			<input type="button" id="ypm-popup-module-insert" class="button-primary dashicons-welcome-widgets-menus" value="Insert"/>
			<a id="ypm_popup_cancel" class="button-secondary" onclick="jQuery('#ypm-modules-thickbox').dialog( 'close' )" title="Cancel">Cancel</a>
		</p>
		<style>
			.ypm-dash-button.dashicons-admin-post:before {
				vertical-align: middle !important;
			}
		</style>
		</div>
		</div>
		<?php
	}
}