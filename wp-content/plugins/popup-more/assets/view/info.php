<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="ycf-bootstrap-wrapper">
    <div class="row">
        <div class="col-md-12">
            <?php
            $postId = (int) ($_GET['post'] ?? 0);
            if (empty($postId)) :
            ?>
                <label><?php esc_attr_e('Shortcode', 'popup_master'); ?></label>
                <p class="no-shortcode"><?php esc_html_e('Please save the Popup to get the shortcode.', 'popup_master'); ?></p>
            <?php else :
                $shortCodeKey = esc_attr($popupTypeObj->shortCodeName);
                $shortcodeLabel = $shortCodeKey === 'ypm_popup' ? 'Onload Shortcode' : 'Shortcode';
                $eventStr = '';
            ?>
                <label><?php echo esc_html($shortcodeLabel); ?></label>
                <?php
                $shortCodeName = sprintf('[%s id="%d" %s]', $shortCodeKey, $postId, wp_kses($eventStr, YpmAdminHelper::getAllowedTags()));
                echo YpmAdminHelper::copyClipboard($postId, $shortCodeName);
                ?>

                <label><?php esc_attr_e('Insert popup via PHP', 'popup_master'); ?></label>
                <?php
                $phpShortCode = sprintf('<?php echo do_shortcode("%s"); ?>', $shortCodeName);
                echo YpmAdminHelper::copyClipboard($postId, $phpShortCode, 'php');

                if ($shortCodeKey === 'ypm_popup') :
                ?>
                    <label class="ypm-margin-top-10"><?php esc_attr_e('Click Shortcode', 'popup_master'); ?></label>
                    <?php
                    $clickShortCode = sprintf('[%s id="%d" event="click"]Click here[/%s]', $shortCodeKey, $postId, $shortCodeKey);
                    echo YpmAdminHelper::copyClipboard($postId, $clickShortCode, 'click');
                    ?>

                    <label class="ypm-margin-top-10"><?php esc_attr_e('Hover Shortcode', 'popup_master'); ?></label>
                    <?php
                    $hoverShortCode = sprintf('[%s id="%d" event="hover"]Click here[/%s]', $shortCodeKey, $postId, $shortCodeKey);
                    echo YpmAdminHelper::copyClipboard($postId, $hoverShortCode, 'hover');
                endif;
            endif;
            ?>

            <label><?php esc_attr_e('Join our Telegram Group', 'popup_master'); ?></label>
            <?php echo YpmAdminHelper::copyClipboard("@aipopup", '@aipopup', 'telegram'); ?>
            <div class="telegram-image"></div>
        </div>
    </div>
</div>