<?php

class YpmShowReviewNotice
{
	private $isMaxOpenPopup = false;
	private $maxPopupInfo = array();
	public function __toString()
	{
		$content = '';
		$isPopupPage = $this->isPopupPage();

		if(!$isPopupPage) {
			return $content;
		}
		$this->allowOpenMaxViewedPopup();
		$allowToShow = $this->allowToShow();
		if (!$allowToShow) {
			return $content;
		}
		$notifications = array();
		$ajaxNonce = wp_create_nonce('ypmReviewNotice');
		if ($this->isMaxOpenPopup) {
			$notifications[] = $this->getMaxOpenPopup();
		}
		else {
			$notifications[] = $this->getReviewContent('usageDayes');
		}

		$content = '<div class="ypm-notification-center-wrapper">
						<h3><span class="dashicons dashicons-flag"></span> Notifications ('.count($notifications).')</h3>';

		foreach ($notifications as $notification) {
			$content .= '<div class="ypm-each-notification-wrapper-js">
							<div class="ypm-single-notification-wrapper">
								<div class="ypm-single-notification" style="border-color:#01B9FF !important;">
									<span class="dashicons dashicons-no-alt ypm-notification-close ypm-already-did-review" data-ajaxnonce="'.esc_attr($ajaxNonce).'"></span>'.
									$notification.'
								</div>
							</div>
						</div>';
		}
		$content .= '</div>';

		return $content;
	}

	private function isPopupPage() {

		return ypmTypeNow() == YPM_POPUP_POST_TYPE;
	}

	public function allowToShow()
	{
		return $this->allowToShowUsageDays() || $this->isMaxOpenPopup;
	}

	private function allowOpenMaxViewedPopup() {
		$popupIds = get_posts(array(
			'fields'          => 'ids',
			'posts_per_page'  => -1,
			'post_type' => YPM_POPUP_POST_TYPE
		));
		$maxOpenedPopupInfo = array('max' => 0, 'popup' => 0);
		foreach ($popupIds as $popupId) {
			$count = (int)get_option('YpmPopupCount'.esc_attr($popupId));
			if ($count > $maxOpenedPopupInfo['max']) {
				$maxOpenedPopupInfo['max'] = $count;
				$maxOpenedPopupInfo['popup'] = $popupId;
			}
		}

		if ($maxOpenedPopupInfo['max'] >= YPM_MAX_OPEN_POPUP) {
			$this->isMaxOpenPopup = true;
			$this->maxPopupInfo = $maxOpenedPopupInfo;
		}
	}


	private function allowToShowUsageDays()
	{
		$shouldOpen = true;

		$dontShowAgain = get_option('YpmDontShowReviewNotice');
		$periodNextTime = get_option('YpmShowNextTime');

		if($dontShowAgain) {
			return !$shouldOpen;
		}

		// When period next time does not exits it means the user is old
		if(!$periodNextTime) {
			YpmShowReviewNotice::setInitialDates();

			return !$shouldOpen;
		}
		$currentData = new DateTime('now');
		$timeNow = $currentData->format('Y-m-d H:i:s');
		$timeNow = strtotime($timeNow);

		return $periodNextTime < $timeNow;
	}

	private function getReviewContent($type)
	{
		return $this->getMaxOpenDaysMessage($type);
		ob_start();
		?>
		<div id="welcome-panel" class="welcome-panel ypm-review-block">
			<div class="welcome-panel-content" id="ypm-welcome-panel-wrapper">
				<?php echo wp_kses($content, YpmAdminHelper::getAllowedTags()); ?>
			</div>
		</div>


		<?php
		$reviewContent = ob_get_contents();
		ob_end_clean();

		return $reviewContent;
	}

	private function getMainTableCreationDate()
	{
		global $wpdb;

		$query = $wpdb->prepare('SELECT table_name, create_time FROM information_schema.tables WHERE table_schema="%s" AND  table_name="%s"', DB_NAME, $wpdb->prefix.'expm_maker');
		$results = $wpdb->get_results($query, ARRAY_A);

		if(empty($results)) {
			return 0;
		}

		$createTime = $results[0]['create_time'];
		$createTime = strtotime($createTime);
		update_option('YpmInstallDate', $createTime);
		$diff = time()-$createTime;
		$days  = floor($diff/(60*60*24));

		return $days;
	}

	private function getPopupUsageDays()
	{
		$installDate = get_option('YpmInstallDate');

		$timeDate = new DateTime('now');
		$timeNow = strtotime($timeDate->format('Y-m-d H:i:s'));

		$diff = $timeNow-$installDate;

		$days  = floor($diff/(60*60*24));

		return $days;
	}

	private function getMaxOpenPopup() {
		$getUsageDays = $this->getPopupUsageDays();
		$firstHeader = '<h1 class="ypm-review-h1"><strong class="ypm-review-strong">Wow!</strong> <a href="'.YPM_REVIEW_URL.'" target="_blank"><b>Popup More</b></a> plugin helped you to share your message via <strong class="ypm-review-strong">'.esc_attr(get_the_title($this->maxPopupInfo['popup'])).'</strong> popup with your users for <strong class="sgrb-review-strong">'.esc_attr($this->maxPopupInfo['max']).' times!</strong></h1>';
		$popupContent = $this->getMaxOepnContent($firstHeader, 'maxOpenPopups');

		$popupContent .= $this->showReviewBlockJs();

		return $popupContent;
	}

	private  function getMaxOpenDaysMessage($type)
	{
		$ajaxNonce = wp_create_nonce('ypmReviewNotice');
		$getUsageDays = $this->getPopupUsageDays();
		$firstHeader = '<h1 class="ypm-review-h1"><strong class="ypm-review-strong">Wow!</strong> You’ve been using <a href="'.YPM_REVIEW_URL.'" target="_blank">Popup More</a> on your site for '.esc_attr($getUsageDays).' days</h1>';
		$popupContent = $this->getMaxOepnContent($firstHeader, $type);

		$popupContent .= $this->showReviewBlockJs();

		return $popupContent;
	}

	private function getMaxOepnContent($firstHeader, $type)
	{
		$ajaxNonce = wp_create_nonce('ypmReviewNotice');

		ob_start();
		?>
		<style>


		</style>
		<div class="ypm-review-wrapper">
			<div class="ypm-review-description">
				<?php echo wp_kses($firstHeader, YpmAdminHelper::getAllowedTags()); ?>
				<h2 class="ypm-review-h2">This is really great for your website score.</h2>
				<p class="ypm-review-mt20">Have your input in the development of our plugin, and we’ll provide better conversions for your site!<br /> Leave your 5-star positive review and help us go further to the perfection!</p>
			</div>
			<div class="ypm-buttons-wrapper">
				<button class="press press-grey ypm-button-1 ypm-already-did-review" data-ajaxnonce="<?php echo esc_attr($ajaxNonce); ?>">I already did</button>
				<button class="press press-lightblue ypm-button-3 ypm-already-did-review" data-ajaxnonce="<?php echo esc_attr($ajaxNonce); ?>" onclick="window.open('<?php echo YPM_REVIEW_URL; ?>')">You worth it!</button>
				<button class="press press-grey ypm-button-2 ypm-show-popup-period" data-ajaxnonce="<?php echo esc_attr($ajaxNonce); ?>" data-message-type="<?php echo esc_attr($type); ?>">Maybe later</button>
			</div>
		</div>
		<?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	private function showReviewBlockJs()
	{
		ob_start();
		?>
		<script type="text/javascript">
			var closeYpmPopup = function () {
				if (typeof jQuery.ypmcolorbox != 'undefined') {
					jQuery.ypmcolorbox.close()
				}
			};
			var ypmCloseNotifications = function () {
				jQuery('.ypm-notification-center-wrapper').remove();
				jQuery('.ypm-notification-icon').remove();
			}
			jQuery('.ypm-already-did-review').each(function () {
				jQuery(this).on('click', function () {
					var ajaxNonce = jQuery(this).attr('data-ajaxnonce');

					var data = {
						action: 'ypm_dont_show_review_notice',
						ajaxNonce: ajaxNonce
					};
					jQuery.post(ajaxurl, data, function(response,d) {
						if(jQuery('.ypm-review-block').length) {
							jQuery('.ypm-review-block').remove();
							closeYpmPopup();
						}
						ypmCloseNotifications();
					});
				});
			});

			jQuery('.ypm-show-popup-period').on('click', function () {
				var ajaxNonce = jQuery(this).attr('data-ajaxnonce');
				var messageType = jQuery(this).attr('data-message-type');

				var data = {
					action: 'ypm_change_review_show_period',
					messageType: messageType,
					ajaxNonce: ajaxNonce
				};
				jQuery.post(ajaxurl, data, function(response,d) {
					if(jQuery('.ypm-review-block').length) {
						jQuery('.ypm-review-block').remove();
						closeYpmPopup();
					}
					ypmCloseNotifications();
				});
			});
		</script>
		<?php
		$script = ob_get_contents();
		ob_end_clean();

		return $script;
	}

	public static function setInitialDates()
	{
		$usageDays = get_option('YpmUsageDays');
		if(!$usageDays) {
			update_option('YpmUsageDays', 0);

			$timeDate = new DateTime('now');
			$installTime = strtotime($timeDate->format('Y-m-d H:i:s'));
			update_option('YpmInstallDate', $installTime);
			$timeDate->modify('+'.YPM_SHOW_REVIEW_PERIOD.' day');

			$timeNow = strtotime($timeDate->format('Y-m-d H:i:s'));
			update_option('YpmShowNextTime', $timeNow);
		}
	}

	public static function deleteInitialDates()
	{
		delete_option('YpmDontShowReviewNotice');
		delete_option('YpmUsageDays');
		delete_option('YpmInstallDate');
		delete_option('YpmShowNextTime');
	}

    public function allowShowFeatureSuggestion()
    {
        $usageDays = get_option('YpmFeatureSuggestion');

        return !$usageDays;
    }

    public function renderSuggestion()
    {
        if (!$this->allowShowFeatureSuggestion()) {
            return '';
        }

        $isPopupPage = $this->isPopupPage();

        if(!$isPopupPage) {
            return '';
        }
        ob_start();
        ?>
       <div class="ypm-notification-center-wrapper">
           <div class="ypm-each-notification-wrapper-js">
               <div class="ypm-single-notification-wrapper">
                   <div class="ypm-single-notification" style="border-color:#01B9FF !important;">
                        <h3>Do you have feature suggestions?</h3>
                        <p>Here you can write what do you want to have on our upcoming updates.</p>
                       <div class="ycf-bootstrap-wrapper">
                       		<div class="alert alert-success ypm-fsend-successs ypm-hide" role="alert">
							  Your Suggestion sent successfully!
							</div>
							<div class="alert alert-warning ypm-fsend-error ypm-hide" role="alert">
							  Unable to send the message you could try a support request <a style="font-size: 20px" href="https://wordpress.org/support/plugin/popup-more/" target="_blank">HERE</a>
							</div>
                           <div class="row">
                               <div class="col-md-12" style="margin-bottom: 10px">
                                   <textarea
                                   			placeholder="Write your suggestions"
                                           style="width: 50%"
                                           class="form-control"
                                           id="ypm-feature-suggestions"
                                           rows="5"></textarea>
                               </div>
                               <div class="col-md-12">
                                   <button type="button" class="btn btn-primary" id="ypm-feature-suggestion-button">Send</button>
                               </div>
                               <div class="col-md-12 text-right">
                                   <a class="ypm-hide-suggestion">Dont show again</a>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </div>
       <?php
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }
}