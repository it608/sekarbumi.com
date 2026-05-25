<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="ycf-bootstrap-wrapper">
	<h3>Do you need some new Features?</h3>
	<p>Share your feature requests or suggestions with us, and we'll be glad to implement them!</p>
	<a href="https://wordpress.org/support/plugin/popup-more/" target="_blank">
		<button class="ypm-action-button ypm-action-color-primary ypm-upgrade-metabox-button-red" onclick="window.open('https://wordpress.org/support/plugin/popup-more/')">
			<b class="h2">Feature Request</b>
		</button>
	</a>
	<hr/>
	<h3>Demo Website</h3>
	<p>
		<span class="ypm-demo-label">Website</span><a href="<?php echo esc_attr(YPM_POPUP_DEMO_URL); ?>" target="_blank"><b>Link</b></a>
	</p>
	<p>
		<span class="ypm-demo-label">Username</span><span><b>demo</b></span>
	</p>
	<p>
		<span class="ypm-demo-label">Password</span><span><b>demo</b></span>
	</p>
</div>