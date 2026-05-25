<?php
class YpmPopupData {

	public static function popupDefaultData() {

		$dataArray = array();
		$dataArray['ypm-popup-width'] = '';
		$dataArray['ypm-popup-height'] = '';

 		return apply_filters('YpmDefaultDataValues', $dataArray);
	}

	public static function popupOptionsKeys() {

		$popupOptions = self::popupDefaultData();
		if(empty($popupOptions)) {
			return array();
		}
		$popupOptionsName = array_keys($popupOptions);

		return $popupOptionsName;
	}

	public static function defaultsData() {

		$defaults = array();

		$defaults['themes'] = array(
			'colorbox1',
			'colorbox2',
			'colorbox3',
			'colorbox4',
			'colorbox5',
			'colorbox6'
		);

		$defaults['openAnimationEffects'] = array(
			'No effect' => esc_attr__('None', 'popup_master'),
			'ypm-flip' => esc_attr__('Flip', 'popup_master'),
			'ypm-shake' => esc_attr__('Shake', 'popup_master'),
			'ypm-wobble' => esc_attr__('Wobble', 'popup_master'),
			'ypm-swing' => esc_attr__('Swing', 'popup_master'),
			'ypm-flash' => esc_attr__('Flash', 'popup_master'),
			'ypm-bounce' => esc_attr__('Bounce', 'popup_master'),
			'ypm-bounceInRight' => esc_attr__('BounceInRight', 'popup_master'),
			'ypm-bounceIn' => esc_attr__('BounceIn', 'popup_master'),
			'ypm-pulse' => esc_attr__('Pulse', 'popup_master'),
			'ypm-rubberBand' => esc_attr__('RubberBand', 'popup_master'),
			'ypm-tada' => esc_attr__('Tada', 'popup_master'),
			'ypm-slideInUp' => esc_attr__('SlideInUp', 'popup_master'),
			'ypm-jello' => esc_attr__('Jello', 'popup_master'),
			'ypm-rotateIn' => esc_attr__('RotateIn', 'popup_master'),
			'ypm-fadeIn' => esc_attr__('FadeIn', 'popup_master')
		);

		$defaults['closeAnimationEffects'] = array(
			'No effect' => esc_attr__('None', 'popup_master'),
			'ypm-flipInX' => esc_attr__('Flip', 'popup_master'),
			'ypm-shake' => esc_attr__('Shake', 'popup_master'),
			'ypm-wobble' => esc_attr__('Wobble', 'popup_master'),
			'ypm-swing' => esc_attr__('Swing', 'popup_master'),
			'ypm-flash' => esc_attr__('Flash', 'popup_master'),
			'ypm-bounce' => esc_attr__('Bounce', 'popup_master'),
			'ypm-bounceOutLeft' => esc_attr__('BounceOutLeft', 'popup_master'),
			'ypm-bounceOut' => esc_attr__('BounceOut', 'popup_master'),
			'ypm-pulse' => esc_attr__('Pulse', 'popup_master'),
			'ypm-rubberBand' => esc_attr__('RubberBand', 'popup_master'),
			'ypm-tada' => esc_attr__('Tada', 'popup_master'),
			'ypm-slideOutUp' => esc_attr__('SlideOutUp', 'popup_master'),
			'ypm-jello' => esc_attr__('Jello', 'popup_master'),
			'ypm-rotateOut' => esc_attr__('RotateOut', 'popup_master'),
			'ypm-fadeOut' => esc_attr__('FadeOut', 'popup_master')
		);

		$defaults['fbButtons'] = array(
			'likeButton' => esc_attr__('Like Button', 'popup_master'),
			'shareButton' => esc_attr__('Share Button', 'popup_master')
		);

		$defaults['ageRestriction'] = array(
			'yesNo' => esc_attr__('Yes/No', 'popup_master'),
			'ageVerification' => esc_attr__('Age Verification', 'popup_master')
		);

		$defaults['contactFormTabs'] = array(
			'fields' => esc_attr__('Contact form fields', 'popup_master'),
			'design' => esc_attr__('Contact form design', 'popup_master'),
			'submitOption' => esc_attr__('Submit Options', 'popup_master'),
			'options' => esc_attr__('Options', 'popup_master')
		);

		$defaults['subscriptionTabs'] = array(
			'fields' => esc_attr__('Subscription form fields', 'popup_master'),
			'design' => esc_attr__('Subscription form design', 'popup_master'),
//			'submitOption' => esc_attr__('Submit Options', 'popup_master'),
			'options' => esc_attr__('Options', 'popup_master')
		);

		$defaults['youtubeColors'] = array(
			'read' => esc_attr__('Read', 'popup_master'),
			'white' => esc_attr__('White', 'popup_master')
		);

		$defaults['dimensionsSizes'] = array(
			'auto' => esc_attr__('Auto', 'popup_master'),
			'10' => esc_attr__('10%', 'popup_master'),
			'20' => esc_attr__('20%', 'popup_master'),
			'30' => esc_attr__('30%', 'popup_master'),
			'40' => esc_attr__('40%', 'popup_master'),
			'50' => esc_attr__('50%', 'popup_master'),
			'60' => esc_attr__('60%', 'popup_master'),
			'70' => esc_attr__('70%', 'popup_master'),
			'80' => esc_attr__('80%', 'popup_master'),
			'90' => esc_attr__('90%', 'popup_master'),
			'100' => esc_attr__('100%', 'popup_master')
		);

		$defaults['linkSelectorTypes'] =array(
			'template' => array(
				'fieldWrapperAttr' => array(
					'class' => 'col-md-3 ypm-choice-option-wrapper'
				),
				'labelAttr' => array(
					'class' => 'col-md-3 ypm-choice-option-wrapper'
				),
				'groupWrapperAttr' => array(
					'class' => 'row form-group ypm-choice-wrapper'
				)
			),
			'buttonPosition' => 'right',
			'nextNewLine' => true,
			'fields' => array(
				array(
					'attr' => array(
						'type' => 'radio',
						'name' => 'ypm-popup-link-selector',
						'class' => 'ypm-form-radio',
						'data-attr-href' => 'ypm-popup-links-all',
						'value' => 'all'
					),
					'label' => array(
						'name' => esc_attr__('All links', 'popup_master')
					)
				),
				array(
					'attr' => array(
						'type' => 'radio',
						'name' => 'ypm-popup-link-selector',
						'class' => 'ypm-popup-hide-behavior ypm-form-radio',
						'data-attr-href' => 'ypm-popup-links-contain-class',
						'value' => 'contain'
					),
					'label' => array(
						'name' => esc_attr__('Links contain classname', 'popup_master')
					)
				),
				array(
					'attr' => array(
						'type' => 'radio',
						'name' => 'ypm-popup-link-selector',
						'class' => 'ypm-popup-hide-behavior ypm-form-radio',
						'data-attr-href' => 'ypm-popup-link-does-not-contain-class',
						'value' => 'notContain'
					),
					'label' => array(
						'name' => esc_attr__('Links does not contain classname', 'popup_master')
					)
				)
			)
		);

		$defaults['dimensions-modes'] =array(
			'template' => array(
				'fieldWrapperAttr' => array(
					'class' => 'col-md-3 ypm-choice-option-wrapper'
				),
				'labelAttr' => array(
					'class' => 'col-md-3 ypm-choice-option-wrapper'
				),
				'groupWrapperAttr' => array(
					'class' => 'row form-group ypm-choice-wrapper'
				)
			),
			'buttonPosition' => 'right',
			'nextNewLine' => true,
			'fields' => array(
				array(
					'attr' => array(
						'type' => 'radio',
						'name' => 'ypm-popup-dimensions-mode',
						'class' => 'ypm-form-radio',
						'data-attr-href' => 'ypm-popup-dimensions-mode-auto',
						'value' => 'auto'
					),
					'label' => array(
						'name' => esc_attr__('Auto', 'popup_master')
					),
					'infoText' => esc_attr__('The sizes of the popup will be counted automatically, according to the content size of the popup. You can select the size in percentages, with this mode, to specify the size on the screen.', 'popup_master')
				),
				array(
					'attr' => array(
						'type' => 'radio',
						'name' => 'ypm-popup-dimensions-mode',
						'class' => 'ypm-popup-hide-behavior ypm-form-radio',
						'data-attr-href' => 'ypm-popup-dimensions-mode-custom',
						'value' => 'custom'
					),
					'label' => array(
						'name' => esc_attr__('Custom', 'popup_master')
					),
					'infoText' => esc_attr__('Add your own custom dimensions for the popup to get the exact sizing for your popup.', 'popup_master')
				)
			)
		);

		$defaults['close-behavior'] =array(
			'template' => array(
				'fieldWrapperAttr' => array(
					'class' => 'col-md-3 ypm-choice-option-wrapper'
				),
				'labelAttr' => array(
					'class' => 'col-md-3 ypm-choice-option-wrapper'
				),
				'groupWrapperAttr' => array(
					'class' => 'row form-group ypm-choice-wrapper'
				)
			),
			'buttonPosition' => 'right',
			'nextNewLine' => true,
			'fields' => array(
				array(
					'attr' => array(
						'type' => 'radio',
						'name' => 'ypm-popup-close-behavior',
						'class' => 'ypm-form-radio',
						'data-attr-href' => 'ypm-popup-close-default',
						'value' => 'default'
					),
					'label' => array(
						'name' => esc_attr__('Default', 'popup_master')
					),
					'infoText' => esc_attr__('Popup will close without any side effects', 'popup_master')
				),
				array(
					'attr' => array(
						'type' => 'radio',
						'name' => 'ypm-popup-close-behavior',
						'class' => 'ypm-form-radio',
						'data-attr-href' => 'ypm-popup-close-redirect',
						'value' => 'redirect'
					),
					'label' => array(
						'name' => esc_attr__('Redirect', 'popup_master')
					),
					'infoText' => esc_attr__('After the popup close will be redirected to the specified URL', 'popup_master')
				),
				array(
					'attr' => array(
						'type' => 'radio',
						'name' => 'ypm-popup-close-behavior',
						'class' => 'ypm-form-radio',
						'data-attr-href' => 'ypm-popup-close-open-popup',
						'value' => 'openPopup'
					),
					'label' => array(
						'name' => esc_attr__('Open new popup', 'popup_master')
					),
					'infoText' => esc_attr__('After close the popup will open a new popup', 'popup_master')
				),
			)
		);

		$defaults['youtube-after-expire'] =array(
			'template' => array(
				'fieldWrapperAttr' => array(
					'class' => 'col-md-6 ypm-choice-option-wrapper'
				),
				'labelAttr' => array(
					'class' => 'col-md-6 ypm-choice-option-wrapper'
				),
				'groupWrapperAttr' => array(
					'class' => 'row form-group ypm-choice-wrapper'
				)
			),
			'buttonPosition' => 'right',
			'nextNewLine' => true,
			'fields' => array(
				array(
					'attr' => array(
						'type' => 'radio',
						'name' => 'ypm-popup-expiration-behavior',
						'class' => 'ypm-countdown-hide-behavior ypm-form-radio',
						'data-attr-href' => 'ypm-popup-expiration-default',
						'value' => 'default'
					),
					'label' => array(
						'name' => esc_attr__('Default', 'popup_master')
					)
				),
				array(
					'attr' => array(
						'type' => 'radio',
						'name' => 'ypm-popup-expiration-behavior',
						'class' => 'ypm-popup-hide-behavior ypm-form-radio',
						'data-attr-href' => 'ypm-popup-expiration-redirect',
						'value' => 'redirect'
					),
					'label' => array(
						'name' => esc_attr__('Redirect', 'popup_master')
					)
				),
				array(
					'attr' => array(
						'type' => 'radio',
						'name' => 'ypm-popup-expiration-behavior',
						'class' => 'ypm-countdown-hide-behavior ypm-form-radio',
						'data-attr-href' => 'ypm-popup-expiration-hide',
						'value' => 'hide'
					),
					'label' => array(
						'name' => esc_attr__('Hide', 'popup_master')
					)
				),
			)
		);

		$defaults['subscription-after'] =array(
			'template' => array(
				'fieldWrapperAttr' => array(
					'class' => 'col-md-6 ypm-choice-option-wrapper'
				),
				'labelAttr' => array(
					'class' => 'col-md-6 ypm-choice-option-wrapper'
				),
				'groupWrapperAttr' => array(
					'class' => 'row form-group ypm-choice-wrapper'
				)
			),
			'buttonPosition' => 'right',
			'nextNewLine' => true,
			'fields' => array(
				array(
					'attr' => array(
						'type' => 'radio',
						'name' => 'ypm-popup-subscription-behavior',
						'class' => 'ypm-countdown-hide-behavior ypm-form-radio',
						'data-attr-href' => 'ypm-popup-subscription-message',
						'value' => 'message'
					),
					'label' => array(
						'name' => esc_attr__('Message', 'popup_master')
					)
				),
				array(
					'attr' => array(
						'type' => 'radio',
						'name' => 'ypm-popup-subscription-behavior',
						'class' => 'ypm-popup-hide-behavior ypm-form-radio',
						'data-attr-href' => 'ypm-popup-subscription-redirect',
						'value' => 'redirect'
					),
					'label' => array(
						'name' => esc_attr__('Redirect', 'popup_master')
					)
				),
				array(
					'attr' => array(
						'type' => 'radio',
						'name' => 'ypm-popup-subscription-behavior',
						'class' => 'ypm-countdown-hide-behavior ypm-form-radio',
						'data-attr-href' => 'ypm-popup-expiration-hide',
						'value' => 'hide'
					),
					'label' => array(
						'name' => esc_attr__('Hide', 'popup_master')
					)
				),
				array(
					'attr' => array(
						'type' => 'radio',
						'name' => 'ypm-popup-subscription-behavior',
						'class' => 'ypm-countdown-hide-behavior ypm-form-radio',
						'data-attr-href' => 'ypm-popup-expiration-popup',
						'value' => 'openPopup'
					),
					'label' => array(
						'name' => esc_attr__('Open popup', 'popup_master')
					)
				),
			)
		);

		$defaults['contact-form-after-expire'] =array(
			'template' => array(
				'fieldWrapperAttr' => array(
					'class' => 'col-md-6 ypm-choice-option-wrapper'
				),
				'labelAttr' => array(
					'class' => 'col-md-6 ypm-choice-option-wrapper'
				),
				'groupWrapperAttr' => array(
					'class' => 'row form-group ypm-choice-wrapper'
				)
			),
			'buttonPosition' => 'right',
			'nextNewLine' => true,
			'fields' => array(
				array(
					'attr' => array(
						'type' => 'radio',
						'name' => 'ypm-popup-contacted-behavior',
						'class' => 'ypm-countdown-hide-behavior ypm-form-radio',
						'data-attr-href' => 'ypm-popup-contact-message',
						'value' => 'message'
					),
					'label' => array(
						'name' => esc_attr__('Message', 'popup_master')
					)
				),
				array(
					'attr' => array(
						'type' => 'radio',
						'name' => 'ypm-popup-contacted-behavior',
						'class' => 'ypm-popup-hide-behavior ypm-form-radio',
						'data-attr-href' => 'ypm-popup-contact-redirect',
						'value' => 'redirect'
					),
					'label' => array(
						'name' => esc_attr__('Redirect', 'popup_master')
					)
				),
				array(
					'attr' => array(
						'type' => 'radio',
						'name' => 'ypm-popup-contacted-behavior',
						'class' => 'ypm-countdown-hide-behavior ypm-form-radio',
						'data-attr-href' => 'ypm-popup-expiration-hide',
						'value' => 'hide'
					),
					'label' => array(
						'name' => esc_attr__('Hide', 'popup_master')
					)
				),
			)
		);

		$defaults['close-button-positions'] = array(
			'top_left' => esc_attr__('Top Left', 'popup_master'),
			'top_right' => esc_attr__('Top Right', 'popup_master'),
			'bottom_left' => esc_attr__('Bottom Left', 'popup_master'),
			'bottom_right' => esc_attr__('Bottom Right', 'popup_master'),
		);

		$defaults['backroundImageModes'] = array(
			'no-repeat' => esc_attr__('None', 'popup_master'),
			'cover' => esc_attr__('Cover', 'popup_master'),
			'contain' => esc_attr__('Contain', 'popup_master'),
			'repeat' => esc_attr__('Repeat', 'popup_master')
		);

		$defaults['fontWeight'] = array(
			'' => __('Initial', 'popup_master'),
			'bold' => __('Bold', 'popup_master'),
			'bolder' => __('Bolder', 'popup_master'),
			'lighter' => __('Lighter', 'popup_master'),
			'100' => __('100', 'popup_master'),
			'200' => __('200', 'popup_master'),
			'300' => __('300', 'popup_master'),
			'400' => __('400', 'popup_master'),
			'500' => __('500', 'popup_master'),
			'600' => __('600', 'popup_master'),
			'700' => __('700', 'popup_master'),
			'800' => __('800', 'popup_master'),
			'900' => __('900', 'popup_master'),
			'inherit' => __('Inherit', 'popup_master')
		);

		$defaults['week-days'] = array(
			'Monday' => __('Monday', 'popup_master'),
			'Tuesday' => __('Tuesday', 'popup_master'),
			'Wednesday' => __('Wednesday', 'popup_master'),
			'Thursday' => __('Thursday', 'popup_master'),
			'Friday' => __('Friday', 'popup_master'),
			'Saturday' => __('Saturday', 'popup_master'),
			'Sunday' => __('Sunday', 'popup_master')
		);

		return apply_filters('YpmDefaultDataOptions', $defaults);
	}
}