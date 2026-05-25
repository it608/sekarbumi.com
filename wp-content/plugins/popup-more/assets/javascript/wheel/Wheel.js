var ypmWheels = {}
var ypmWheelCallBack = function(id) {
    var alertPrize = function(indicatedSegment, wheel) {
        let audio = new Audio(YPM_WHELL_PARAMS.winSpinSound);
        if(YPM_WHELL_PARAMS.winSpinSoundStatus) {
            audio.play();
        }
        console.log("Ddd ", wheel.canvasId)
            // Ensure the selected segment has a 'text' and 'prize' property
            let prizeText = indicatedSegment.text ? indicatedSegment.text : "Unknown";
            let prizeValue = indicatedSegment.prize ? indicatedSegment.prize : "?????????";
        
            // Show the result in a div
            jQuery('.response-wrapper-'+wheel.canvasId).html(`You won: <strong>${prizeText}</strong> (${prizeValue})`)
            // jQuery(".response-wrapper").html(`You won: <strong>${prizeText}</strong> (${prizeValue})`);
     }
     let audio = new Audio(YPM_WHELL_PARAMS.spinSound);
    
     var playSound = function() {
        audio.pause();
        audio.currentTime = 0;
        if(YPM_WHELL_PARAMS.wheelSound) {
            audio.play();
        }
     }
    
     let segmentsData = JSON.parse(YPM_WHELL_PARAMS.options);
     
     // Create new wheel object specifying the parameters at creation time.
     let theWheel = new Winwheel({
        'canvasId'     : id,
        'numSegments'  : YPM_WHELL_PARAMS.numSegments,
        'outerRadius'  : 212,
        'textFontSize' : 28,
        'segments'     : segmentsData.map(option => ({
            'fillStyle' : option.color,    
            'textFillStyle': option.textColor,  // Change this to your desired text color (e.g., black)
            'text' : option.label,
            'prize': option.prize
        })),
        'animation' : {
            'type'     : 'spinToStop',
            'duration' : 20,
            'spins'    : 8,
            'callbackFinished' : alertPrize,
            'callbackSound'    : playSound,
            'soundTrigger'     : 'segment'
        },
        'pins' : {
            'number' : 0
        }
    });

    ypmWheels[id] = theWheel;
    
     jQuery(".ypm-add-btn").on('click', function(e) {
         e.preventDefault();
            var id = jQuery(this).data('id')
            var theWheel = ypmWheels[id];

         // Reset the animation before each spin
         theWheel.stopAnimation(false); // Ensures animation resets
         theWheel.rotationAngle = 0;    // Reset the wheel to the initial position
         theWheel.draw();               // Redraw the wheel to reflect the reset
    
         let probabilities = segmentsData.map(option => parseFloat(option.probability));
         let totalProbability = probabilities.reduce((a, b) => a + b, 0);
    
         // Weighted random selection
         let randomValue = Math.random() * totalProbability;
         let accumulated = 0;
         let selectedIndex = 0;
    
         for (let i = 0; i < probabilities.length; i++) {
             accumulated += probabilities[i];
             if (randomValue <= accumulated) {
                 selectedIndex = i;
                 break;
             }
         }
    
         // Calculate the exact stopping angle
         let stopAt = (selectedIndex / segmentsData.length) * 360 + (360 / segmentsData.length) / 2;
    
         // Set stop angle manually
         theWheel.animation.stopAngle = stopAt;
         
         // Restart animation
         theWheel.startAnimation();
     });
    }
    
    // ypmWheelCallBack()
jQuery(document).ready(function() {

    setTimeout(function() {
        jQuery('.ypm-wheel-container').each(function() {
            var id = jQuery(this).data('id')
            ypmWheelCallBack(id)
        })
    }, 100)
})
    