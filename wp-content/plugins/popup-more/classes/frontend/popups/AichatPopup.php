<?php
namespace YpmPopup;

use ypmFrontend\ProHelper;

class AIChatPopup extends Popup implements PopupViewInterface {

    public $shortCodeName = 'ypm_aichat';

    public function __construct()
    {
	    $this->includeJsFiles();

        add_filter('ypmDefaultOptions', array($this, 'defOptions'));
    }

    public  function __tostring() {
        return "";
    }

    public function defOptions($options)
    {
        $options[] = array('name' => 'ypm-ai-button-label', 'type' => 'text', 'defaultValue' => 'Send');
        $options[] = array('name' => 'ypm-ai-button-bg-color', 'type' => 'text', 'defaultValue' => '#007bff');
        $options[] = array('name' => 'ypm-ai-button-color', 'type' => 'text', 'defaultValue' => '#ffffff');

        return $options;
    }

    private function includeJsFiles()
	{
		$jsUrl = YPM_POPUP_JS_URL.'/AIChat/';

		ScriptsManager::registerScript('AIChat.js', array('dirUrl' => $jsUrl, 'dep' => array('jquery')));
        $backLocalizeData = array(
			'ajaxNonce' => wp_create_nonce('ycfAjaxNonce'),
			'ajaxurl' => admin_url('admin-ajax.php')
		);
		ScriptsManager::localizeScript('AIChat.js', 'YpmAILocalization', $backLocalizeData);
		ScriptsManager::enqueueScript('AIChat.js');

        ScriptsManager::registerStyle('font-awesome.min.css', array('styleSrc'=>YPM_POPUP_CSS_URL.'AIChat/'));
		ScriptsManager::enqueueStyle('font-awesome.min.css');
	}
    
    public static function create($data, $obj = '') {

		$obj = new self();
        $proOptions = array('ypm-ai-button-label', 'ypm-ai-button-bg-color', 'ypm-ai-button-color');
        if (ypm_is_free()) {
            foreach($proOptions as $proOption) {
                unset($data[$proOption]);
            }
        }
		parent::create($data, $obj);
	}

    public function renderView($args, $content) {
        $id = $this->getId();
        
        ob_start();
        ?>
        <div id="chat-popup" class="chat-popup-wrapper" data-id="<?php echo esc_attr($id)?>">
            <div id="chat-output" class="chat-output"></div>
            <form id="chat-form" class="chat-form">
                <?php 
                    $voiceIcon = apply_filters('ypmPrintVoiceIcon', $this);
                    if (!empty($voiceIcon)) {
                        echo wp_kses($voiceIcon, \YpmAdminHelper::getAllowedTags());
                    }
                ?>
                <input type="text" id="chat-input" class="chat-input" placeholder="Type your message here...">
                <button type="submit"><?php echo esc_attr($this->getOptionValue('ypm-ai-button-label'))?></button>
            </form>
            
        </div>
        <style>
            /* Chat popup wrapper */
            .ypm-hide {display: none;}
            .vcg-interface {display: flex;padding: 20;align-items: center;}
            .vcg-interface span {cursor: pointer;}
            .chat-popup-wrapper {
                /* position: fixed;
                bottom: 20px;
                right: 20px; */
                width: 100%;
                background-color: #fff;
                border: 1px solid #ccc;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                z-index: 1000;
            }

            /* Chat output area */
            .chat-output {
                height: 300px;
                padding: 10px;
                overflow-y: auto;
                border-bottom: 1px solid #eee;
            }

            /* Chat form */
            .chat-form {
                display: flex;
                border-top: 1px solid #eee;
            }

            /* Chat input field */
            .chat-input {
                flex: 1;
                border: none;
                padding: 10px;
                font-size: 14px;
                border-radius: 0;
            }

            /* Chat input placeholder style */
            .chat-input::placeholder {
                color: #aaa;
            }

            /* Remove outline from input field */
            .chat-input:focus {
                outline: none;
            }

            /* Send button */
            .chat-form button {
                background-color: <?php echo esc_attr($this->getOptionValue('ypm-ai-button-bg-color'))?>;
                color: <?php echo esc_attr($this->getOptionValue('ypm-ai-button-color')); ?>;
                border: none;
                padding: 10px 20px;
                cursor: pointer;
                transition: background-color 0.3s ease;
                border-radius: 0;
            }

            /* Send button hover effect */
            .chat-form button:hover {
                background-color: #0056b3;
            }
            .chat-form button:disabled {
                background-color: #cccccc; /* Light gray */
                cursor: not-allowed;
                opacity: 0.6;
            }
        </style>
        <?php
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }
}