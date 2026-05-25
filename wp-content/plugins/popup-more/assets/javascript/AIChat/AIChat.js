function YpmAiChat() {
    var that = this;

    jQuery('.chat-popup-wrapper').each(function() {
        var current = jQuery(this);
        that.voice(current);
        var id = jQuery(this).data('id');
        that.id = id;
        
        // var chatInput = jQuery('.chat-input', current);
        // that.chatInput = chatInput;
        var chatForm = jQuery('.chat-form', current);
        that.chatForm = chatForm;
        // var chatOutput = jQuery('.chat-output', current);
        // that.chatOutput = chatOutput;

        chatForm.on('submit', function(e) {
            e.preventDefault();
            var chatOutput = jQuery(this).closest('.chat-popup-wrapper').find('.chat-output');
            that.chatOutput = chatOutput;
            var chatInput = jQuery(this).closest('.chat-popup-wrapper').find('.chat-input');
            that.chatInput = chatInput;

            that.submitButton = jQuery('button', this);
            const userMessage = chatInput.val();
            that.sendMessage(userMessage)
        });
    })
}

YpmAiChat.prototype.sendMessage = function(userMessage, cb = function() {}) {
    var that = this;

    var data = {
        action: 'ypm_chatgpt_chat',
        ajaxNonce: YpmAILocalization.ajaxNonce,
        message: userMessage,
        beforeSend: function() {
            that.submitButton.prop('disabled', true);
        },
        id: that.id
    };

    jQuery.post(YpmAILocalization.ajaxurl, data, function(response) {
        that.submitButton.prop('disabled', false);
        var  data = response;
        that.chatOutput.append(`<p><strong>You:</strong> ${userMessage}</p>`)
        that.chatOutput.append(`<p><strong>AI:</strong> ${data.reply}</p>`)

        that.chatInput.val('');
    })
}

YpmAiChat.prototype.voice = function(current) {
    var curr = jQuery(current);
    var that = this;
    const startButton = jQuery('.vcg-start-recording', curr);
    const stopButton = jQuery('.vcg-stop-recording', curr);
    const responseDiv = jQuery('.vcg-response', curr);
    let recognition;
    
    if ('webkitSpeechRecognition' in window) {
        recognition = new webkitSpeechRecognition();
    } else if ('SpeechRecognition' in window) {
        recognition = new SpeechRecognition();
    } else {
        responseDiv.innerHTML = 'Speech recognition not supported in this browser.';
        return;
    }
    
    recognition.continuous = false;
    recognition.interimResults = false;
    
    recognition.onstart = function () {
        startButton.disabled = true;
        stopButton.disabled = false;
        startButton.addClass('ypm-hide');
        stopButton.removeClass('ypm-hide');
    };
    
    recognition.onresult = function (event) {
        const transcript = event.results[0][0].transcript;
        fetchChatGPTResponse(transcript);
    };
    
    recognition.onerror = function (event) {
        responseDiv.innerHTML = 'Error occurred in recognition: ' + event.error;
    };
    
    recognition.onend = function () {
        startButton.disabled = false;
        stopButton.disabled = true;
        stopButton.addClass('ypm-hide');
        startButton.removeClass('ypm-hide');
    };
    
    startButton.bind('click', function () {
        recognition.start();
    });
    
    stopButton.bind('click', function () {
        recognition.stop();
    });
    
    function fetchChatGPTResponse(query) {
        const formData = new FormData();
        // that.sendMessage(query);
        that.chatInput.val(query)
    }
    
    function speakResponse(text) {
        console.log("responseDiv ", text)
        if ('speechSynthesis' in window) {
            const utterance = new SpeechSynthesisUtterance(text);
            speechSynthesis.speak(utterance);
        }
    }
}

jQuery(document).ready(function() {
    new YpmAiChat();
});
