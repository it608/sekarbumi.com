/*
    Winwheel.js, by Douglas McKechie @ www.dougtesting.net
    See website for tutorials and other documentation.

    The MIT License (MIT)

    Copyright (c) 2012-2019 Douglas McKechie

    Permission is hereby granted, free of charge, to any person obtaining a copy
    of this software and associated documentation files (the "Software"), to deal
    in the Software without restriction, including without limitation the rights
    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the Software is
    furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in all
    copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
    SOFTWARE.
*/

// ====================================================================================================================
// The constructor for the WinWheel object, a JSON-like array of options can be passed in.
// By default the wheel is drawn if canvas object exists on the page, but can pass false as second parameter if don't want this to happen.
// ====================================================================================================================
function Winwheel(options, drawWheel)
{
    defaultOptions = {
        'canvasId'          : 'canvas',     // Id of the canvas which the wheel is to draw on to.
        'centerX'           : null,         // X position of the center of the wheel. The default of these are null which means will be placed in center of the canvas.
        'centerY'           : null,         // Y position of the wheel center. If left null at time of construct the center of the canvas is used.
        'outerRadius'       : null,         // The radius of the outside of the wheel. If left null it will be set to the radius from the center of the canvas to its shortest side.
        'innerRadius'       : 0,            // Normally 0. Allows the creation of rings / doughnuts if set to value > 0. Should not exceed outer radius.
        'numSegments'       : 1,            // The number of segments. Need at least one to draw.
        'drawMode'          : 'code',       // The draw mode. Possible values are 'code', 'image', 'segmentImage'. Default is code which means segments are drawn using canvas arc() function.
        'rotationAngle'     : 0,            // The angle of rotation of the wheel - 0 is 12 o'clock position.
        'textFontFamily'    : 'Arial',      // Segment text font, you should use web safe fonts.
        'textFontSize'      : 20,           // Size of the segment text.
        'textFontWeight'    : 'bold',       // Font weight.
        'textOrientation'   : 'horizontal', // Either horizontal, vertical, or curved.
        'textAlignment'     : 'center',     // Either center, inner, or outer.
        'textDirection'     : 'normal',     // Either normal or reversed. In normal mode for horizontal text in segment at 3 o'clock is correct way up, in reversed text at 9 o'clock segment is correct way up.
        'textMargin'        : null,         // Margin between the inner or outer of the wheel (depends on textAlignment).
        'textFillStyle'     : 'black',      // This is basically the text colour.
        'textStrokeStyle'   : null,         // Basically the line colour for segment text, only looks good for large text so off by default.
        'textLineWidth'     : 1,            // Width of the lines around the text. Even though this defaults to 1, a line is only drawn if textStrokeStyle specified.
        'fillStyle'         : 'silver',     // The segment background colour.
        'strokeStyle'       : 'black',      // Segment line colour. Again segment lines only drawn if this is specified.
        'lineWidth'         : 1,            // Width of lines around segments.
        'clearTheCanvas'    : true,         // When set to true the canvas will be cleared before the wheel is drawn.
        'imageOverlay'      : false,        // If set to true in image drawing mode the outline of the segments will be displayed over the image. Does nothing in code drawMode.
        'drawText'          : true,         // By default the text of the segments is rendered in code drawMode and not in image drawMode.
        'pointerAngle'      : 0,            // Location of the pointer that indicates the prize when wheel has stopped. Default is 0 so the (corrected) 12 o'clock position.
        'wheelImage'        : null,         // Must be set to image data in order to use image to draw the wheel - drawMode must also be 'image'.
        'imageDirection'    : 'N',          // Used when drawMode is segmentImage. Default is north, can also be (E)ast, (S)outh, (W)est.
        'responsive'        : false,        // If set to true the wheel will resize when the window first loads and also onResize.
        'scaleFactor'       : 1,            // Set by the responsive function. Used in many calculations to scale the wheel.
    };

    // -----------------------------------------
    // Loop through the default options and create properties of this class set to the value for the option passed in
    // or if not value for the option was passed in then to the default.
    for (let key in defaultOptions) {
        if ((options != null) && (typeof(options[key]) !== 'undefined')) {
            this[key] = options[key];
        } else {
            this[key] = defaultOptions[key];
        }
    }

    // Also loop though the passed in options and add anything specified not part of the class in to it as a property.
    if (options != null) {
        for (let key in options) {
            if (typeof(this[key]) === 'undefined') {
                this[key] = options[key];
            }
        }
    }


    // ------------------------------------------
    // If the id of the canvas is set, try to get the canvas as we need it for drawing.
    if (this.canvasId) {
        this.canvas = document.getElementById(this.canvasId);

        if (this.canvas) {
            // If the centerX and centerY have not been specified in the options then default to center of the canvas
            // and make the outerRadius half of the canvas width - this means the wheel will fill the canvas.
            if (this.centerX == null) {
                this.centerX = this.canvas.width / 2;
            }

            if (this.centerY == null) {
                this.centerY = this.canvas.height / 2;
            }

            if (this.outerRadius == null) {
                // Need to set to half the width of the shortest dimension of the canvas as the canvas may not be square.
                // Minus the line segment line width otherwise the lines around the segments on the top,left,bottom,right
                // side are chopped by the edge of the canvas.
                if (this.canvas.width < this.canvas.height) {
                    this.outerRadius = (this.canvas.width / 2) - this.lineWidth;
                } else {
                    this.outerRadius = (this.canvas.height / 2) - this.lineWidth;
                }
            }

            // Also get a 2D context to the canvas as we need this to draw with.
            this.ctx = this.canvas.getContext('2d');
        } else {
            this.canvas = null;
            this.ctx = null;
        }
    } else {
        this.canvas = null;
        this.ctx = null;
    }

    // ------------------------------------------
    // Add array of segments to the wheel, then populate with segments if number of segments is specified for this object.
    this.segments = new Array(null);

    for (let x = 1; x <= this.numSegments; x++) {
        // If options for the segments have been specified then create a segment sending these options so
        // the specified values are used instead of the defaults.
        if ((options != null) && (options['segments']) && (typeof(options['segments'][x-1]) !== 'undefined')) {
            this.segments[x] = new Segment(options['segments'][x-1]);
        } else {
            this.segments[x] = new Segment();
        }
    }

    // ------------------------------------------
    // Call function to update the segment sizes setting the starting and ending angles.
    this.updateSegmentSizes();

    // If the text margin is null then set to same as font size as we want some by default.
    if (this.textMargin === null) {
        this.textMargin = (this.textFontSize / 1.7);
    }

    // ------------------------------------------
    // If the animation options have been passed in then create animation object as a property of this class
    // and pass the options to it so the animation is set. Otherwise create default animation object.
    if ((options != null) && (options['animation']) && (typeof(options['animation']) !== 'undefined')) {
        this.animation = new Animation(options['animation']);
    } else {
        this.animation = new Animation();
    }

    // ------------------------------------------
    // If some pin options then create create a pin object and then pass them in.
    if ((options != null) && (options['pins']) && (typeof(options['pins']) !== 'undefined')) {
        this.pins = new Pin(options['pins']);
    }

    // ------------------------------------------
    // If the drawMode is image change some defaults provided a value has not been specified.
    if ((this.drawMode == 'image') || (this.drawMode == 'segmentImage')) {
        // Remove grey fillStyle.
        if (typeof(options['fillStyle']) === 'undefined') {
            this.fillStyle = null;
        }

        // Set strokeStyle to red.
        if (typeof(options['strokeStyle']) === 'undefined') {
            this.strokeStyle = 'red';
        }

        // Set drawText to false as we will assume any text is part of the image.
        if (typeof(options['drawText']) === 'undefined') {
            this.drawText = false;
        }

        // Also set the lineWidth to 1 so that segment overlay will look correct.
        if (typeof(options['lineWidth']) === 'undefined') {
            this.lineWidth = 1;
        }

        // Set drawWheel to false as normally the image needs to be loaded first.
        if (typeof(drawWheel) === 'undefined') {
            drawWheel = false;
        }
    } else {
        // When in code drawMode the default is the wheel will draw.
        if (typeof(drawWheel) === 'undefined') {
            drawWheel = true;
        }
    }

    // Create pointer guide.
    if ((options != null) && (options['pointerGuide']) && (typeof(options['pointerGuide']) !== 'undefined')) {
        this.pointerGuide = new PointerGuide(options['pointerGuide']);
    } else {
        this.pointerGuide = new PointerGuide();
    }

    // Check if the wheel is to be responsive, if so then need to save the original size of the canvas
    // and also check for data- attributes on the canvas which help control the scaling.
    if (this.responsive) {
        winwheelToDrawDuringAnimation = this;

        // Save the original defined width and height of the canvas, this is needed later to work out the scaling.
        this._originalCanvasWidth = this.canvas.width;
        this._originalCanvasHeight = this.canvas.height;

        // Get data-attributes on the canvas.
        this._responsiveScaleHeight = this.canvas.dataset.responsivescaleheight;
        this._responsiveMinWidth = this.canvas.dataset.responsiveminwidth;
        this._responsiveMinHeight = this.canvas.dataset.responsiveminheight;
        this._responsiveMargin = this.canvas.dataset.responsivemargin;

        // Add event listeners for onload and onresize and call a function defined at the bottom
        // of this script which will handle that and work out the scale factor.
        window.addEventListener("load", winwheelResize);
        window.addEventListener("resize", winwheelResize);
    }

    // Finally if drawWheel is true then call function to render the wheel, segment text, overlay etc.
    if (drawWheel == true) {
        this.draw(this.clearTheCanvas);
    } else if (this.drawMode == 'segmentImage') {
        // If segment image then loop though all the segments and load the images for them setting a callback
        // which will call the draw function of the wheel once all the images have been loaded.
        winwheelToDrawDuringAnimation = this;
        winhweelAlreadyDrawn = false;

        for (let y = 1; y <= this.numSegments; y ++) {
            if (this.segments[y].image !== null) {
                this.segments[y].imgData = new Image();
                this.segments[y].imgData.onload = winwheelLoadedImage;
                this.segments[y].imgData.src = this.segments[y].image;
            }
        }
    }
}

// ====================================================================================================================
// This function sorts out the segment sizes. Some segments may have set sizes, for the others what is left out of
// 360 degrees is shared evenly. What this function actually does is set the start and end angle of the arcs.
// ====================================================================================================================
Winwheel.prototype.updateSegmentSizes = function()
{
    // If this object actually contains some segments
    if (this.segments) {
        // First add up the arc used for the segments where the size has been set.
        let arcUsed = 0;
        let numSet  = 0;

        // Remember, to make it easy to access segments, the position of the segments in the array starts from 1 (not 0).
        for (let x = 1; x <= this.numSegments; x ++) {
            if (this.segments[x].size !== null) {
                arcUsed += this.segments[x].size;
                numSet ++;
            }
        }

        let arcLeft = (360 - arcUsed);

        // Create variable to hold how much each segment with non-set size will get in terms of degrees.
        let degreesEach = 0;

        if (arcLeft > 0) {
            degreesEach = (arcLeft / (this.numSegments - numSet));
        }

        // ------------------------------------------
        // Now loop though and set the start and end angle of each segment.
        let currentDegree = 0;

        for (let x = 1; x <= this.numSegments; x ++) {
            // Set start angle.
            this.segments[x].startAngle = currentDegree;

            // If the size is set then add this to the current degree to get the end, else add the degreesEach to it.
            if (this.segments[x].size) {
                currentDegree += this.segments[x].size;
            } else {
                currentDegree += degreesEach;
            }

            // Set end angle.
            this.segments[x].endAngle = currentDegree;
        }
    }
}

// ====================================================================================================================
// This function clears the canvas. Will wipe anything else which happens to be drawn on it.
// ====================================================================================================================
Winwheel.prototype.clearCanvas = function()
{
    if (this.ctx) {
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
    }
}

// ====================================================================================================================
// This function draws / re-draws the wheel on the canvas therefore rendering any changes.
// ====================================================================================================================
Winwheel.prototype.draw = function(clearTheCanvas)
{
    // If have the canvas context.
    if (this.ctx) {
        // Clear the canvas, unless told not to.
        if (typeof(clearTheCanvas) !== 'undefined') {
            if (clearTheCanvas == true) {
                this.clearCanvas();
            }
        } else {
            this.clearCanvas();
        }

        // Call functions to draw the segments and then segment text.
        if (this.drawMode == 'image') {
            // Draw the wheel by loading and drawing an image such as a png on the canvas.
            this.drawWheelImage();

            // If we are to draw the text, do so before the overlay is drawn
            // as this allows the overlay to be used to create some interesting effects.
            if (this.drawText == true) {
                this.drawSegmentText();
            }

            // If image overlay is true then call function to draw the segments over the top of the image.
            // This is useful during development to check alignment between where the code thinks the segments are and where they appear on the image.
            if (this.imageOverlay == true) {
                this.drawSegments();
            }
        } else if (this.drawMode == 'segmentImage') {
            // Draw the wheel by rendering the image for each segment.
            this.drawSegmentImages();

            // If we are to draw the text, do so before the overlay is drawn
            // as this allows the overlay to be used to create some interesting effects.
            if (this.drawText == true) {
                this.drawSegmentText();
            }

            // If image overlay is true then call function to draw the segments over the top of the image.
            // This is useful during development to check alignment between where the code thinks the segments are and where they appear on the image.
            if (this.imageOverlay == true) {
                this.drawSegments();
            }
        } else {
            // The default operation is to draw the segments using code via the canvas arc() method.
            this.drawSegments();

            // The text is drawn on top.
            if (this.drawText == true) {
                this.drawSegmentText();
            }
        }

        // If this class has pins.
        if (typeof this.pins !== 'undefined') {
            // If they are to be visible then draw them.
            if (this.pins.visible == true) {
                this.drawPins();
            }
        }

        // If pointer guide is display property is set to true then call function to draw the pointer guide.
        if (this.pointerGuide.display == true) {
            this.drawPointerGuide();
        }
    }
}

// ====================================================================================================================
// Draws the pins around the outside of the wheel.
// ====================================================================================================================
Winwheel.prototype.drawPins = function()
{
    if ((this.pins) && (this.pins.number)) {
        // Get scaled centerX and centerY to use in the code below so pins will draw responsively too.
        let centerX = (this.centerX * this.scaleFactor);
        let centerY = (this.centerY * this.scaleFactor);
        let outerRadius = (this.outerRadius * this.scaleFactor);

        // Check if the pin's size is to be responsive too, if so set the pinOuterRadius to a scaled version number.
        let pinOuterRadius = this.pins.outerRadius;
        let pinMargin = this.pins.margin;

        if (this.pins.responsive) {
            pinOuterRadius = (this.pins.outerRadius * this.scaleFactor);
            pinMargin = (this.pins.margin * this.scaleFactor);
        }

        // Work out the angle to draw each pin a which is simply 360 / the number of pins as they space evenly around.
        //++ There is a slight oddity with the pins in that there is a pin at 0 and also one at 360 and these will be drawn
        //++ directly over the top of each other. Also pins are 0 indexed which could possibly cause some confusion
        //++ with the getCurrentPin function - for now this is just used for audio so probably not a problem.
        let pinSpacing = (360 / this.pins.number);

        for(let i=1; i<=this.pins.number; i ++) {
            this.ctx.save();

            // Set the stroke style and line width.
            this.ctx.strokeStyle = this.pins.strokeStyle;
            this.ctx.lineWidth = this.pins.lineWidth;
            this.ctx.fillStyle = this.pins.fillStyle;

            // Move to the center.
            this.ctx.translate(centerX, centerY);

            // Rotate to to the pin location which is i * the pinSpacing.
            this.ctx.rotate(this.degToRad(i * pinSpacing + this.rotationAngle));

            // Move back out.
            this.ctx.translate(-centerX, -centerY);

            // Create a path for the pin circle.
            this.ctx.beginPath();
            // x, y, radius, startAngle, endAngle.
            this.ctx.arc(centerX,(centerY - outerRadius) + pinOuterRadius + pinMargin, pinOuterRadius, 0, 2*Math.PI);

            if (this.pins.fillStyle) {
                this.ctx.fill();
            }

            if (this.pins.strokeStyle) {
                this.ctx.stroke();
            }

            this.ctx.restore();
        }
    }
}

// ====================================================================================================================
// Draws a line from the center of the wheel to the outside at the angle where the code thinks the pointer is.
// ====================================================================================================================
Winwheel.prototype.drawPointerGuide = function()
{
    // If have canvas context.
    if (this.ctx) {
        // Get scaled center x an y and also the outer radius.
        let centerX = (this.centerX * this.scaleFactor);
        let centerY = (this.centerY * this.scaleFactor);
        let outerRadius = (this.outerRadius * this.scaleFactor);

        this.ctx.save();

        // Rotate the canvas to the line goes towards the location of the pointer.
        this.ctx.translate(centerX, centerY);
        this.ctx.rotate(this.degToRad(this.pointerAngle));
        this.ctx.translate(-centerX, -centerY);

        // Set line colour and width.
        this.ctx.strokeStyle = this.pointerGuide.strokeStyle;
        this.ctx.lineWidth = this.pointerGuide.lineWidth;

        // Draw from the center of the wheel outwards past the wheel outer radius.
        this.ctx.beginPath();
        this.ctx.moveTo(centerX, centerY);
        this.ctx.lineTo(centerX, -(outerRadius / 4));

        this.ctx.stroke();
        this.ctx.restore();
    }
}

// ====================================================================================================================
// This function takes an image such as PNG and draws it on the canvas making its center at the centerX and center for the wheel.
// ====================================================================================================================
Winwheel.prototype.drawWheelImage = function()
{
    // Double check the wheelImage property of this class is not null. This does not actually detect that an image
    // source was set and actually loaded so might get error if this is not the case. This is why the initial call
    // to draw() should be done from a wheelImage.onload callback as detailed in example documentation.
    if (this.wheelImage != null) {
        // Get the centerX and centerY in to variables, adjust by the scaleFactor.
        let centerX = (this.centerX * this.scaleFactor);
        let centerY = (this.centerY * this.scaleFactor);

        // Get the scaled width and height of the image.
        let scaledWidth = (this.wheelImage.width * this.scaleFactor);
        let scaledHeight = (this.wheelImage.height * this.scaleFactor);

        // Work out the correct X and Y to draw the image at. We need to get the center point of the image
        // aligned over the center point of the wheel, we can't just place it at 0, 0.
        let imageLeft = (centerX - (scaledWidth / 2));
        let imageTop  = (centerY - (scaledHeight / 2));

        // Rotate and then draw the wheel.
        // We must rotate by the rotationAngle before drawing to ensure that image wheels will spin.
        this.ctx.save();
        this.ctx.translate(centerX, centerY);
        this.ctx.rotate(this.degToRad(this.rotationAngle));
        this.ctx.translate(-centerX, -centerY);

        // Draw the image passing the scaled width and height which will ensure the image will be responsive.
        this.ctx.drawImage(this.wheelImage, imageLeft, imageTop, scaledWidth, scaledHeight);

        this.ctx.restore();
    }
}

// ====================================================================================================================
// This function draws the wheel on the canvas by rendering the image for each segment.
// ====================================================================================================================
Winwheel.prototype.drawSegmentImages = function()
{
    // Again check have context in case this function was called directly and not via draw function.
    if (this.ctx) {
        // Get the centerX and centerY of the wheel adjusted with the scale factor.
        let centerX = (this.centerX * this.scaleFactor);
        let centerY = (this.centerY * this.scaleFactor);

        // Draw the segments if there is at least one in the segments array.
        if (this.segments) {
            // Loop though and output all segments - position 0 of the array is not used, so start loop from index 1
            // this is to avoid confusion when talking about the first segment.
            for (let x = 1; x <= this.numSegments; x ++) {
                // Get the segment object as we need it to read options from.
                let seg = this.segments[x];

                // Check image has loaded so a property such as height has a value.
                if (seg.imgData.height) {
                    // Work out the correct X and Y to draw the image at which depends on the direction of the image.
                    // Images can be created in 4 directions. North, South, East, West.
                    // North: Outside at top, inside at bottom. Sits evenly over the 0 degrees angle.
                    // South: Outside at bottom, inside at top. Sits evenly over the 180 degrees angle.
                    // East: Outside at right, inside at left. Sits evenly over the 90 degrees angle.
                    // West: Outside at left, inside at right. Sits evenly over the 270 degrees angle.
                    let imageLeft = 0;
                    let imageTop = 0;
                    let imageAngle = 0;
                    let imageDirection = '';

                    // Get scaled width and height of the segment image.
                    let scaledWidth = (seg.imgData.width * this.scaleFactor);
                    let scaledHeight = (seg.imgData.height * this.scaleFactor);

                    if (seg.imageDirection !== null) {
                        imageDirection = seg.imageDirection;
                    } else {
                        imageDirection = this.imageDirection;
                    }

                    if (imageDirection == 'S') {
                        // Left set so image sits half/half over the 180 degrees point.
                        imageLeft = (centerX - (scaledWidth / 2));

                        // Top so image starts at the centerY.
                        imageTop = centerY;

                        // Angle to draw the image is its starting angle + half its size.
                        // Here we add 180 to the angle to the segment is poistioned correctly.
                        imageAngle = (seg.startAngle + 180 + ((seg.endAngle - seg.startAngle) / 2));
                    } else if (imageDirection == 'E') {
                        // Left set so image starts and the center point.
                        imageLeft = centerX;

                        // Top is so that it sits half/half over the 90 degree point.
                        imageTop = (centerY - (scaledHeight / 2));

                        // Again get the angle in the center of the segment and add it to the rotation angle.
                        // this time we need to add 270 to that to the segment is rendered the correct place.
                        imageAngle = (seg.startAngle + 270 + ((seg.endAngle - seg.startAngle) / 2));
                    } else if (imageDirection == 'W') {
                        // Left is the centerX minus the width of the image.
                        imageLeft = (centerX - scaledWidth);

                        // Top is so that it sits half/half over the 270 degree point.
                        imageTop = (centerY - (scaledHeight / 2));

                        // Again get the angle in the center of the segment and add it to the rotation angle.
                        // this time we need to add 90 to that to the segment is rendered the correct place.
                        imageAngle = (seg.startAngle + 90 + ((seg.endAngle - seg.startAngle) / 2));
                    } else {
                        // North is the default.
                        // Left set so image sits half/half over the 0 degrees point.
                        imageLeft = (centerX - (scaledWidth / 2));

                        // Top so image is its height out (above) the center point.
                        imageTop = (centerY - scaledHeight);

                        // Angle to draw the image is its starting angle + half its size.
                        // this sits it half/half over the center angle of the segment.
                        imageAngle = (seg.startAngle + ((seg.endAngle - seg.startAngle) / 2));
                    }

                    // --------------------------------------------------
                    // Rotate to the position of the segment and then draw the image.
                    this.ctx.save();
                    this.ctx.translate(centerX, centerY);

                    // So math here is the rotation angle of the wheel plus half way between the start and end angle of the segment.
                    this.ctx.rotate(this.degToRad(this.rotationAngle + imageAngle));
                    this.ctx.translate(-centerX, -centerY);

                    // Draw the image passing the scaled width and height so that it can be responsive.
                    this.ctx.drawImage(seg.imgData, imageLeft, imageTop, scaledWidth, scaledHeight);

                    this.ctx.restore();
                } else {
                    console.log('Segment ' + x + ' imgData is not loaded');
                }
            }
        }
    }
}

// ====================================================================================================================
// This function draws the wheel on the page by rendering the segments on the canvas.
// ====================================================================================================================
Winwheel.prototype.drawSegments = function()
{
    // Again check have context in case this function was called directly and not via draw function.
    if (this.ctx) {
        // Draw the segments if there is at least one in the segments array.
        if (this.segments) {
            // Get scaled centerX and centerY and also scaled inner and outer radius.
            let centerX = (this.centerX * this.scaleFactor);
            let centerY = (this.centerY * this.scaleFactor);
            let innerRadius = (this.innerRadius * this.scaleFactor);
            let outerRadius = (this.outerRadius * this.scaleFactor);

            // Loop though and output all segments - position 0 of the array is not used, so start loop from index 1
            // this is to avoid confusion when talking about the first segment.
            for (let x = 1; x <= this.numSegments; x ++) {
                // Get the segment object as we need it to read options from.
                let seg = this.segments[x];

                let fillStyle;
                let lineWidth;
                let strokeStyle;

                // Set the variables that defined in the segment, or use the default options.
                if (seg.fillStyle !== null) {
                    fillStyle = seg.fillStyle;
                } else {
                    fillStyle = this.fillStyle;
                }

                this.ctx.fillStyle = fillStyle;

                if (seg.lineWidth !== null) {
                    lineWidth = seg.lineWidth;
                } else {
                    lineWidth = this.lineWidth;
                }

                this.ctx.lineWidth = lineWidth;

                if (seg.strokeStyle !== null) {
                    strokeStyle = seg.strokeStyle;
                } else {
                    strokeStyle = this.strokeStyle;
                }

                this.ctx.strokeStyle = strokeStyle;


                // Check there is a strokeStyle or fillStyle, if not the segment is invisible so should not try to draw it otherwise a path is began but not ended.
                if ((strokeStyle) || (fillStyle)) {
                    // Begin a path as the segment consists of an arc and 2 lines.
                    this.ctx.beginPath();

                    // If don't have an inner radius then move to the center of the wheel as we want a line out from the center
                    // to the start of the arc for the outside of the wheel when we arc. Canvas will draw the connecting line for us.
                    if (!this.innerRadius) {
                        this.ctx.moveTo(centerX, centerY);
                    } else {
                        // Work out the x and y values for the starting point of the segment which is at its starting angle
                        // but out from the center point of the wheel by the value of the innerRadius. Some correction for line width is needed.
                        let iX = Math.cos(this.degToRad(seg.startAngle + this.rotationAngle - 90)) * (innerRadius - lineWidth / 2);
                        let iY = Math.sin(this.degToRad(seg.startAngle + this.rotationAngle - 90)) * (innerRadius - lineWidth / 2);

                        // Now move here relative to the center point of the wheel.
                        this.ctx.moveTo(centerX + iX, centerY + iY);
                    }

                    // Draw the outer arc of the segment clockwise in direction -->
                    this.ctx.arc(centerX, centerY, outerRadius, this.degToRad(seg.startAngle + this.rotationAngle - 90), this.degToRad(seg.endAngle + this.rotationAngle - 90), false);

                    if (this.innerRadius) {
                        // Draw another arc, this time anticlockwise <-- at the innerRadius between the end angle and the start angle.
                        // Canvas will draw a connecting line from the end of the outer arc to the beginning of the inner arc completing the shape.
                        this.ctx.arc(centerX, centerY, innerRadius, this.degToRad(seg.endAngle + this.rotationAngle - 90), this.degToRad(seg.startAngle + this.rotationAngle - 90), true);
                    } else {
                        // If no inner radius then we draw a line back to the center of the wheel.
                        this.ctx.lineTo(centerX, centerY);
                    }

                    // Fill and stroke the segment. Only do either if a style was specified, if the style is null then
                    // we assume the developer did not want that particular thing.
                    // For example no stroke style so no lines to be drawn.
                    if (fillStyle) {
                        this.ctx.fill();
                    }

                    if (strokeStyle) {
                        this.ctx.stroke();
                    }
                }
            }
        }
    }
}

// ====================================================================================================================
// This draws the text on the segments using the specified text options.
// ====================================================================================================================
Winwheel.prototype.drawSegmentText = function()
{
    // Again only draw the text if have a canvas context.
    if (this.ctx) {
        // Declare variables to hold the values. These are populated either with the value for the specific segment,
        // or if not specified then the global default value.
        let fontFamily;
        let fontSize;
        let fontWeight;
        let orientation;
        let alignment;
        let direction;
        let margin;
        let fillStyle;
        let strokeStyle;
        let lineWidth;
        let fontSetting;

        // Get the centerX and centerY scaled with the scale factor, also the same for outer and inner radius.
        let centerX = (this.centerX * this.scaleFactor);
        let centerY = (this.centerY * this.scaleFactor);
        let outerRadius = (this.outerRadius * this.scaleFactor);
        let innerRadius = (this.innerRadius * this.scaleFactor);

        // Loop though all the segments.
        for (let x = 1; x <= this.numSegments; x ++) {
            // Save the context so it is certain that each segment text option will not affect the other.
            this.ctx.save();

            // Get the segment object as we need it to read options from.
            let seg = this.segments[x];

            // Check is text as no point trying to draw if there is no text to render.
            if (seg.text) {
                // Set values to those for the specific segment or use global default if null.
                if (seg.textFontFamily  !== null)   fontFamily  = seg.textFontFamily;  else fontFamily  = this.textFontFamily;
                if (seg.textFontSize    !== null)   fontSize    = seg.textFontSize;    else fontSize    = this.textFontSize;
                if (seg.textFontWeight  !== null)   fontWeight  = seg.textFontWeight;  else fontWeight  = this.textFontWeight;
                if (seg.textOrientation !== null)   orientation = seg.textOrientation; else orientation = this.textOrientation;
                if (seg.textAlignment   !== null)   alignment   = seg.textAlignment;   else alignment   = this.textAlignment;
                if (seg.textDirection   !== null)   direction   = seg.textDirection;   else direction   = this.textDirection;
                if (seg.textMargin      !== null)   margin      = seg.textMargin;      else margin      = this.textMargin;
                if (seg.textFillStyle   !== null)   fillStyle   = seg.textFillStyle;   else fillStyle   = this.textFillStyle;
                if (seg.textStrokeStyle !== null)   strokeStyle = seg.textStrokeStyle; else strokeStyle = this.textStrokeStyle;
                if (seg.textLineWidth   !== null)   lineWidth   = seg.textLineWidth;   else lineWidth   = this.textLineWidth;

                // Scale the font size and the margin by the scale factor so the text can be responsive.
                fontSize = (fontSize * this.scaleFactor);
                margin = (margin * this.scaleFactor);

                // ------------------------------
                // We need to put the font bits together in to one string.
                let fontSetting = '';

                if (fontWeight != null) {
                    fontSetting += fontWeight + ' ';
                }

                if (fontSize != null) {
                    fontSetting += fontSize + 'px ';    // Fonts on canvas are always a px value.
                }

                if (fontFamily != null) {
                    fontSetting += fontFamily;
                }

                // Now set the canvas context to the decided values.
                this.ctx.font        = fontSetting;
                this.ctx.fillStyle   = fillStyle;
                this.ctx.strokeStyle = strokeStyle;
                this.ctx.lineWidth   = lineWidth;

                // Split the text in to multiple lines on the \n character.
                let lines = seg.text.split('\n');

                // Figure out the starting offset for the lines as when there are multiple lines need to center the text
                // vertically in the segment (when thinking of normal horozontal text).
                let lineOffset = 0 - (fontSize * (lines.length / 2)) + (fontSize / 2);

                // The offset works great for horozontal and vertial text, also centered curved. But when the text is curved
                // and the alignment is outer then the multiline text should not have some text outside the wheel. Same if inner curved.
                if ((orientation == 'curved') && ((alignment == 'inner') || (alignment == 'outer'))) {
                    lineOffset = 0;
                }

                for (let i = 0; i < lines.length; i ++) {
                    // If direction is reversed then do things differently than if normal (which is the default - see further down)
                    if (direction == 'reversed') {
                        // When drawing reversed or 'upside down' we need to do some trickery on our part.
                        // The canvas text rendering function still draws the text left to right and the correct way up,
                        // so we need to overcome this with rotating the opposite side of the wheel the correct way up then pulling the text
                        // through the center point to the correct segment it is supposed to be on.
                        if (orientation == 'horizontal') {
                            if (alignment == 'inner') {
                                this.ctx.textAlign = 'right';
                            } else if (alignment == 'outer') {
                                this.ctx.textAlign = 'left';
                            } else {
                                this.ctx.textAlign = 'center';
                            }

                            this.ctx.textBaseline = 'middle';

                            // Work out the angle to rotate the wheel, this is in the center of the segment but on the opposite side of the wheel which is why do -180.
                            let textAngle = this.degToRad((seg.endAngle - ((seg.endAngle - seg.startAngle) / 2) + this.rotationAngle - 90) - 180);

                            this.ctx.save();
                            this.ctx.translate(centerX, centerY);
                            this.ctx.rotate(textAngle);
                            this.ctx.translate(-centerX, -centerY);

                            if (alignment == 'inner') {
                                // In reversed state the margin is subtracted from the innerX.
                                // When inner the inner radius also comes in to play.
                                if (fillStyle) {
                                    this.ctx.fillText(lines[i], centerX - innerRadius - margin, centerY + lineOffset);
                                }

                                if (strokeStyle) {
                                    this.ctx.strokeText(lines[i], centerX - innerRadius - margin, centerY + lineOffset);
                                }
                            } else if (alignment == 'outer') {
                                // In reversed state the position is the center minus the radius + the margin for outer aligned text.
                                if (fillStyle) {
                                    this.ctx.fillText(lines[i], centerX - outerRadius + margin, centerY + lineOffset);
                                }

                                if (strokeStyle) {
                                    this.ctx.strokeText(lines[i], centerX - outerRadius + margin, centerY + lineOffset);
                                }
                            } else {
                                // In reversed state the everything in minused.
                                if (fillStyle) {
                                    this.ctx.fillText(lines[i], centerX - innerRadius - ((outerRadius - innerRadius) / 2) - margin, centerY + lineOffset);
                                }

                                if (strokeStyle) {
                                    this.ctx.strokeText(lines[i], centerX - innerRadius - ((outerRadius - innerRadius) / 2) - margin, centerY + lineOffset);
                                }
                            }

                            this.ctx.restore();

                        } else if (orientation == 'vertical') {
                            // See normal code further down for comments on how it works, this is similar by plus/minus is reversed.
                            this.ctx.textAlign = 'center';

                            // In reversed mode this are reversed.
                            if (alignment == 'inner') {
                                this.ctx.textBaseline = 'top';
                            } else if (alignment == 'outer') {
                                this.ctx.textBaseline = 'bottom';
                            } else {
                                this.ctx.textBaseline = 'middle';
                            }

                            let textAngle = (seg.endAngle - ((seg.endAngle - seg.startAngle) / 2) - 180);
                            textAngle += this.rotationAngle;

                            this.ctx.save();
                            this.ctx.translate(centerX, centerY);
                            this.ctx.rotate(this.degToRad(textAngle));
                            this.ctx.translate(-centerX, -centerY);

                            //++ @TODO double-check the default of 0 is correct.
                            let yPos = 0;
                            if (alignment == 'outer') {
                                yPos = (centerY + outerRadius - margin);
                            } else if (alignment == 'inner') {
                                yPos = (centerY + innerRadius + margin);
                            }

                            // I have found that the text looks best when a fraction of the font size is shaved off.
                            let yInc = (fontSize - (fontSize / 9));

                            // Loop though and output the characters.
                            if (alignment == 'outer') {
                                // In reversed mode outer means text in 6 o'clock segment sits at bottom of the wheel and we draw up.
                                for (let c = (lines[i].length -1); c >= 0; c--) {
                                    let character = lines[i].charAt(c);

                                    if (fillStyle) {
                                        this.ctx.fillText(character, centerX + lineOffset, yPos);
                                    }

                                    if (strokeStyle) {
                                        this.ctx.strokeText(character, centerX + lineOffset, yPos);
                                    }

                                    yPos -= yInc;
                                }
                            } else if (alignment == 'inner') {
                                // In reversed mode inner text is drawn from top of segment at 6 o'clock position to bottom of the wheel.
                                for (let c = 0; c < lines[i].length; c++) {
                                    let character = lines[i].charAt(c);

                                    if (fillStyle) {
                                        this.ctx.fillText(character, centerX + lineOffset, yPos);
                                    }

                                    if (strokeStyle) {
                                        this.ctx.strokeText(character, centerX + lineOffset, yPos);
                                    }

                                    yPos += yInc;
                                }
                            } else if (alignment == 'center') {
                                // Again for reversed this is the opposite of before.
                                // If there is more than one character in the text then an adjustment to the position needs to be done.
                                // What we are aiming for is to position the center of the text at the center point between the inner and outer radius.
                                let centerAdjustment = 0;

                                if (lines[i].length > 1) {
                                    centerAdjustment = (yInc * (lines[i].length -1) / 2);
                                }

                                let yPos = (centerY + innerRadius + ((outerRadius - innerRadius) / 2)) + centerAdjustment + margin;

                                for (let c = (lines[i].length -1); c >= 0; c--) {
                                    let character = lines[i].charAt(c);

                                    if (fillStyle) {
                                        this.ctx.fillText(character, centerX + lineOffset, yPos);
                                    }

                                    if (strokeStyle) {
                                        this.ctx.strokeText(character, centerX + lineOffset, yPos);
                                    }

                                    yPos -= yInc;
                                }
                            }

                            this.ctx.restore();

                        } else if (orientation == 'curved') {
                            // There is no built in canvas function to draw text around an arc,
                            // so we need to do this ourselves.
                            let radius = 0;

                            // Set the alignment of the text - inner, outer, or center by calculating
                            // how far out from the center point of the wheel the text is drawn.
                            if (alignment == 'inner') {
                                // When alignment is inner the radius is the innerRadius plus any margin.
                                radius = innerRadius + margin;
                                this.ctx.textBaseline = 'top';
                            } else if (alignment == 'outer') {
                                // Outer it is the outerRadius minus any margin.
                                radius = outerRadius - margin;
                                this.ctx.textBaseline = 'bottom';

                                // We need to adjust the radius in this case to take in to multiline text.
                                // In this case the radius needs to be further out, not at the inner radius.
                                radius -= (fontSize * (lines.length - 1));
                            } else if (alignment == 'center') {
                                // When center we want the text halfway between the inner and outer radius.
                                radius = innerRadius + margin + ((outerRadius - innerRadius) / 2);
                                this.ctx.textBaseline = 'middle';
                            }

                            // Set the angle to increment by when looping though and outputting the characters in the text
                            // as we do this by rotating the wheel small amounts adding each character.
                            let anglePerChar = 0;
                            let drawAngle = 0;

                            // If more than one character in the text then...
                            if (lines[i].length > 1) {
                                // Text is drawn from the left.
                                this.ctx.textAlign = 'left';

                                // Work out how much angle the text rendering loop below needs to rotate by for each character to render them next to each other.
                                // I have discovered that 4 * the font size / 10 at 100px radius is the correct spacing for between the characters
                                // using a monospace font, non monospace may look a little odd as in there will appear to be extra spaces between chars.
                                anglePerChar = (4 * (fontSize / 10));

                                // Work out what percentage the radius the text will be drawn at is of 100px.
                                let radiusPercent = (100 / radius);

                                // Then use this to scale up or down the anglePerChar value.
                                // When the radius is less than 100px we need more angle between the letters, when radius is greater (so the text is further
                                // away from the center of the wheel) the angle needs to be less otherwise the characters will appear further apart.
                                anglePerChar = (anglePerChar * radiusPercent);

                                // Next we want the text to be drawn in the middle of the segment, without this it would start at the beginning of the segment.
                                // To do this we need to work out how much arc the text will take up in total then subtract half of this from the center
                                // of the segment so that it sits centred.
                                let totalArc = (anglePerChar * lines[i].length);

                                // Now set initial draw angle to half way between the start and end of the segment.
                                drawAngle = seg.startAngle + (((seg.endAngle - seg.startAngle) / 2) - (totalArc / 2));
                            } else {
                                // The initial draw angle is the center of the segment when only one character.
                                drawAngle = (seg.startAngle + ((seg.endAngle - seg.startAngle) / 2));

                                // To ensure is dead-center the text alignment also needs to be centered.
                                this.ctx.textAlign = 'center';
                            }

                            // ----------------------
                            // Adjust the initial draw angle as needed to take in to account the rotationAngle of the wheel.
                            drawAngle += this.rotationAngle;

                            // And as with other 'reverse' text direction functions we need to subtract 180 degrees from the angle
                            // because when it comes to draw the characters in the loop below we add the radius instead of subtract it.
                            drawAngle -= 180;

                            // ----------------------
                            // Now the drawing itself.
                            // In reversed direction mode we loop through the characters in the text backwards in order for them to appear on screen correctly
                            for (let c = lines[i].length; c >= 0; c--) {
                                this.ctx.save();

                                let character = lines[i].charAt(c);

                                // Rotate the wheel to the draw angle as we need to add the character at this location.
                                this.ctx.translate(centerX, centerY);
                                this.ctx.rotate(this.degToRad(drawAngle));
                                this.ctx.translate(-centerX, -centerY);

                                // Now draw the character directly below the center point of the wheel at the appropriate radius.
                                // Note in the reversed mode we add the radius to the this.centerY instead of subtract.
                                if (strokeStyle) {
                                    this.ctx.strokeText(character, centerX, centerY + radius + lineOffset);
                                }

                                if (fillStyle) {
                                    this.ctx.fillText(character, centerX, centerY + radius + lineOffset);
                                }

                                // Increment the drawAngle by the angle per character so next loop we rotate
                                // to the next angle required to draw the character at.
                                drawAngle += anglePerChar;

                                this.ctx.restore();
                            }
                        }
                    } else {
                        // Normal direction so do things normally.
                        // Check text orientation, of horizontal then reasonably straight forward, if vertical then a bit more work to do.
                        if (orientation == 'horizontal') {
                            // Based on the text alignment, set the correct value in the context.
                            if (alignment == 'inner') {
                                this.ctx.textAlign = 'left';
                            } else if (alignment == 'outer') {
                                this.ctx.textAlign = 'right';
                            } else {
                                this.ctx.textAlign = 'center';
                            }

                            // Set this too.
                            this.ctx.textBaseline = 'middle';

                            // Work out the angle around the wheel to draw the text at, which is simply in the middle of the segment the text is for.
                            // The rotation angle is added in to correct the annoyance with the canvas arc drawing functions which put the 0 degrees at the 3 oclock
                            let textAngle = this.degToRad(seg.endAngle - ((seg.endAngle - seg.startAngle) / 2) + this.rotationAngle - 90);

                            // We need to rotate in order to draw the text because it is output horizontally, so to
                            // place correctly around the wheel for all but a segment at 3 o'clock we need to rotate.
                            this.ctx.save();
                            this.ctx.translate(centerX, centerY);
                            this.ctx.rotate(textAngle);
                            this.ctx.translate(-centerX, -centerY);

                            // --------------------------
                            // Draw the text based on its alignment adding margin if inner or outer.
                            if (alignment == 'inner') {
                                // Inner means that the text is aligned with the inner of the wheel. If looking at a segment in in the 3 o'clock position
                                // it would look like the text is left aligned within the segment.

                                // Because the segments are smaller towards the inner of the wheel, in order for the text to fit is is a good idea that
                                // a margin is added which pushes the text towards the outer a bit.

                                // The inner radius also needs to be taken in to account as when inner aligned.

                                // If fillstyle is set the draw the text filled in.
                                if (fillStyle) {
                                    this.ctx.fillText(lines[i], centerX + innerRadius + margin, centerY + lineOffset);
                                }

                                // If stroke style is set draw the text outline.
                                if (strokeStyle) {
                                    this.ctx.strokeText(lines[i], centerX + innerRadius + margin, centerY + lineOffset);
                                }
                            } else if (alignment == 'outer') {
                                // Outer means the text is aligned with the outside of the wheel, so if looking at a segment in the 3 o'clock position
                                // it would appear the text is right aligned. To position we add the radius of the wheel in to the equation
                                // and subtract the margin this time, rather than add it.

                                // I don't understand why, but in order of the text to render correctly with stroke and fill, the stroke needs to
                                // come first when drawing outer, rather than second when doing inner.
                                if (fillStyle) {
                                    this.ctx.fillText(lines[i], centerX + outerRadius - margin, centerY + lineOffset);
                                }

                                // If fillstyle the fill the text.
                                if (strokeStyle) {
                                    this.ctx.strokeText(lines[i], centerX + outerRadius - margin, centerY + lineOffset);
                                }
                            } else {
                                // In this case the text is to drawn centred in the segment.
                                // Typically no margin is required, however even though centred the text can look closer to the inner of the wheel
                                // due to the way the segments narrow in (is optical effect), so if a margin is specified it is placed on the inner
                                // side so the text is pushed towards the outer.

                                // If stoke style the stroke the text.
                                if (fillStyle) {
                                    this.ctx.fillText(lines[i], centerX + innerRadius + ((outerRadius - innerRadius) / 2) + margin, centerY + lineOffset);
                                }

                                // If fillstyle the fill the text.
                                if (strokeStyle) {
                                    this.ctx.strokeText(lines[i], centerX + innerRadius + ((outerRadius - innerRadius) / 2) + margin, centerY + lineOffset);
                                }
                            }

                            // Restore the context so that wheel is returned to original position.
                            this.ctx.restore();

                        } else if (orientation == 'vertical') {
                            // If vertical then we need to do this ourselves because as far as I am aware there is no option built in to html canvas
                            // which causes the text to draw downwards or upwards one character after another.

                            // In this case the textAlign is always center, but the baseline is either top or bottom
                            // depending on if inner or outer alignment has been specified.
                            this.ctx.textAlign = 'center';

                            if (alignment == 'inner') {
                                this.ctx.textBaseline = 'bottom';
                            } else if (alignment == 'outer') {
                                this.ctx.textBaseline = 'top';
                            } else {
                                this.ctx.textBaseline = 'middle';
                            }

                            // The angle to draw the text at is halfway between the end and the starting angle of the segment.
                            let textAngle = seg.endAngle - ((seg.endAngle - seg.startAngle) / 2);

                            // Ensure the rotation angle of the wheel is added in, otherwise the test placement won't match
                            // the segments they are supposed to be for.
                            textAngle += this.rotationAngle;

                            // Rotate so can begin to place the text.
                            this.ctx.save();
                            this.ctx.translate(centerX, centerY);
                            this.ctx.rotate(this.degToRad(textAngle));
                            this.ctx.translate(-centerX, -centerY);

                            // Work out the position to start drawing in based on the alignment.
                            // If outer then when considering a segment at the 12 o'clock position want to start drawing down from the top of the wheel.
                            //++ TODO check this as yPos did not seem to have a defualt before.
                            let yPos = 0;

                            if (alignment == 'outer') {
                                yPos = (centerY - outerRadius + margin);
                            } else if (alignment == 'inner') {
                                yPos = (centerY - innerRadius - margin);
                            }

                            // We need to know how much to move the y axis each time.
                            // This is not quite simply the font size as that puts a larger gap in between the letters
                            // than expected, especially with monospace fonts. I found that shaving a little off makes it look "right".
                            let yInc = (fontSize - (fontSize / 9));

                            // Loop though and output the characters.
                            if (alignment == 'outer') {
                                // For this alignment we draw down from the top of a segment at the 12 o'clock position to simply
                                // loop though the characters in order.
                                for (let c = 0; c < lines[i].length; c++) {
                                    let character = lines[i].charAt(c);

                                    if (fillStyle) {
                                        this.ctx.fillText(character, centerX + lineOffset, yPos);
                                    }

                                    if (strokeStyle) {
                                        this.ctx.strokeText(character, centerX + lineOffset, yPos);
                                    }

                                    yPos += yInc;
                                }
                            } else if (alignment == 'inner') {
                                // Here we draw from the inner of the wheel up, but in order for the letters in the text text to
                                // remain in the correct order when reading, we actually need to loop though the text characters backwards.
                                for (let c = (lines[i].length -1); c >= 0; c--) {
                                    let character = lines[i].charAt(c);

                                    if (fillStyle) {
                                        this.ctx.fillText(character, centerX + lineOffset, yPos);
                                    }

                                    if (strokeStyle) {
                                        this.ctx.strokeText(character, centerX + lineOffset, yPos);
                                    }

                                    yPos -= yInc;
                                }
                            } else if (alignment == 'center') {
                                // This is the most complex of the three as we need to draw the text top down centred between the inner and outer of the wheel.
                                // So logically we have to put the middle character of the text in the center then put the others each side of it.
                                // In reality that is a really bad way to do it, we can achieve the same if not better positioning using a
                                // variation on the method used for the rendering of outer aligned text once we have figured out the height of the text.

                                // If there is more than one character in the text then an adjustment to the position needs to be done.
                                // What we are aiming for is to position the center of the text at the center point between the inner and outer radius.
                                let centerAdjustment = 0;

                                if (lines[i].length > 1) {
                                    centerAdjustment = (yInc * (lines[i].length -1) / 2);
                                }

                                // Now work out where to start rendering the string. This is half way between the inner and outer of the wheel, with the
                                // centerAdjustment included to correctly position texts with more than one character over the center.
                                // If there is a margin it is used to push the text away from the center of the wheel.
                                let yPos = (centerY - innerRadius - ((outerRadius - innerRadius) / 2)) - centerAdjustment - margin;

                                // Now loop and draw just like outer text rendering.
                                for (let c = 0; c < lines[i].length; c++) {
                                    let character = lines[i].charAt(c);

                                    if (fillStyle) {
                                        this.ctx.fillText(character, centerX + lineOffset, yPos);
                                    }

                                    if (strokeStyle) {
                                        this.ctx.strokeText(character, centerX + lineOffset, yPos);
                                    }

                                    yPos += yInc;
                                }
                            }

                            this.ctx.restore();

                        } else if (orientation == 'curved') {
                            // There is no built in canvas function to draw text around an arc, so
                            // we need to do this ourselves.
                            let radius = 0;

                            // Set the alignment of the text - inner, outer, or center by calculating
                            // how far out from the center point of the wheel the text is drawn.
                            if (alignment == 'inner') {
                                // When alignment is inner the radius is the innerRadius plus any margin.
                                radius = innerRadius + margin;
                                this.ctx.textBaseline = 'bottom';

                                // We need to adjust the radius in this case to take in to multiline text.
                                // In this case the radius needs to be further out, not at the inner radius.
                                radius += (fontSize * (lines.length - 1));
                            } else if (alignment == 'outer') {
                                // Outer it is the outerRadius minus any margin.
                                radius = outerRadius - margin;
                                this.ctx.textBaseline = 'top';
                            } else if (alignment == 'center') {
                                // When center we want the text halfway between the inner and outer radius.
                                radius = innerRadius + margin + ((outerRadius - innerRadius) / 2);
                                this.ctx.textBaseline = 'middle';
                            }

                            // Set the angle to increment by when looping though and outputting the characters in the text
                            // as we do this by rotating the wheel small amounts adding each character.
                            let anglePerChar = 0;
                            let drawAngle = 0;

                            // If more than one character in the text then...
                            if (lines[i].length > 1) {
                                // Text is drawn from the left.
                                this.ctx.textAlign = 'left';

                                // Work out how much angle the text rendering loop below needs to rotate by for each character to render them next to each other.
                                // I have discovered that 4 * the font size / 10 at 100px radius is the correct spacing for between the characters
                                // using a monospace font, non monospace may look a little odd as in there will appear to be extra spaces between chars.
                                anglePerChar = (4 * (fontSize / 10));

                                // Work out what percentage the radius the text will be drawn at is of 100px.
                                let radiusPercent = (100 / radius);

                                // Then use this to scale up or down the anglePerChar value.
                                // When the radius is less than 100px we need more angle between the letters, when radius is greater (so the text is further
                                // away from the center of the wheel) the angle needs to be less otherwise the characters will appear further apart.
                                anglePerChar = (anglePerChar * radiusPercent);

                                // Next we want the text to be drawn in the middle of the segment, without this it would start at the beginning of the segment.
                                // To do this we need to work out how much arc the text will take up in total then subtract half of this from the center
                                // of the segment so that it sits centred.
                                let totalArc = (anglePerChar * lines[i].length);

                                // Now set initial draw angle to half way between the start and end of the segment.
                                drawAngle = seg.startAngle + (((seg.endAngle - seg.startAngle) / 2) - (totalArc / 2));
                            } else {
                                // The initial draw angle is the center of the segment when only one character.
                                drawAngle = (seg.startAngle + ((seg.endAngle - seg.startAngle) / 2));

                                // To ensure is dead-center the text alignment also needs to be centred.
                                this.ctx.textAlign = 'center';
                            }

                            // ----------------------
                            // Adjust the initial draw angle as needed to take in to account the rotationAngle of the wheel.
                            drawAngle += this.rotationAngle;

                            // ----------------------
                            // Now the drawing itself.
                            // Loop for each character in the text.
                            for (let c = 0; c < (lines[i].length); c++) {
                                this.ctx.save();

                                let character = lines[i].charAt(c);

                                // Rotate the wheel to the draw angle as we need to add the character at this location.
                                this.ctx.translate(centerX, centerY);
                                this.ctx.rotate(this.degToRad(drawAngle));
                                this.ctx.translate(-centerX, -centerY);

                                // Now draw the character directly above the center point of the wheel at the appropriate radius.
                                if (strokeStyle) {
                                    this.ctx.strokeText(character, centerX, centerY - radius + lineOffset);
                                }

                                if (fillStyle) {
                                    this.ctx.fillText(character, centerX, centerY - radius + lineOffset);
                                }

                                // Increment the drawAngle by the angle per character so next loop we rotate
                                // to the next angle required to draw the character at.
                                drawAngle += anglePerChar;

                                this.ctx.restore();
                            }
                        }
                    }

                    // Increment this ready for the next time.
                    lineOffset += fontSize;
                }
            }

            // Restore so all text options are reset ready for the next text.
            this.ctx.restore();
        }
    }
}

// ====================================================================================================================
// Converts degrees to radians which is what is used when specifying the angles on HTML5 canvas arcs.
// ====================================================================================================================
Winwheel.prototype.degToRad = function(d)
{
    return d * 0.0174532925199432957;
}

// ====================================================================================================================
// This function sets the center location of the wheel, saves a function call to set x then y.
// ====================================================================================================================
Winwheel.prototype.setCenter = function(x, y)
{
    this.centerX = x;
    this.centerY = y;
}

// ====================================================================================================================
// This function allows a segment to be added to the wheel. The position of the segment is optional,
// if not specified the new segment will be added to the end of the wheel.
// ====================================================================================================================
Winwheel.prototype.addSegment = function(options, position)
{
    // Create a new segment object passing the options in.
    let newSegment = new Segment(options);

    // Increment the numSegments property of the class since new segment being added.
    this.numSegments ++;
    let segmentPos;

    // Work out where to place the segment, the default is simply as a new segment at the end of the wheel.
    if (typeof position !== 'undefined') {
        // Because we need to insert the segment at this position, not overwrite it, we need to move all segments after this
        // location along one in the segments array, before finally adding this new segment at the specified location.
        for (let x = this.numSegments; x > position; x --) {
            this.segments[x] = this.segments[x -1];
        }

        this.segments[position] = newSegment;
        segmentPos = position;
    } else {
        this.segments[this.numSegments] = newSegment;
        segmentPos = this.numSegments;
    }

    // Since a segment has been added the segment sizes need to be re-computed so call function to do this.
    this.updateSegmentSizes();

    // Return the segment object just created in the wheel (JavaScript will return it by reference), so that
    // further things can be done with it by the calling code if desired.
    return this.segments[segmentPos];
}

// ====================================================================================================================
// This function must be used if the canvasId is changed as we also need to get the context of the new canvas.
// ====================================================================================================================
Winwheel.prototype.setCanvasId = function(canvasId)
{
    if (canvasId) {
        this.canvasId = canvasId;
        this.canvas = document.getElementById(this.canvasId);

        if (this.canvas) {
            this.ctx = this.canvas.getContext('2d');
        }
    } else {
        this.canvasId = null
        this.ctx = null;
        this.canvas = null;
    }
}

// ====================================================================================================================
// This function deletes the specified segment from the wheel by removing it from the segments array.
// It then sorts out the other bits such as update of the numSegments.
// ====================================================================================================================
Winwheel.prototype.deleteSegment = function(position)
{
    // There needs to be at least one segment in order for the wheel to draw, so only allow delete if there
    // is more than one segment currently left in the wheel.

    //++ check that specifying a position that does not exist - say 10 in a 6 segment wheel does not cause issues.
    if (this.numSegments > 1) {
        // If the position of the segment to remove has been specified.
        if (typeof position !== 'undefined') {
            // The array is to be shortened so we need to move all segments after the one
            // to be removed down one so there is no gap.
            for (let x = position; x < this.numSegments; x ++) {
                this.segments[x] = this.segments[x + 1];
            }
        }

        // Unset the last item in the segments array since there is now one less.
        this.segments[this.numSegments] = undefined;

        // Decrement the number of segments,
        // then call function to update the segment sizes.
        this.numSegments --;
        this.updateSegmentSizes();
    }
}

// ====================================================================================================================
// This function takes the x an the y of a mouse event, such as click or move, and converts the x and the y in to
// co-ordinates on the canvas as the raw values are the x and the y from the top and left of the user's browser.
// ====================================================================================================================
Winwheel.prototype.windowToCanvas = function(x, y)
{
    let bbox = this.canvas.getBoundingClientRect();

    return {
        x: Math.floor(x - bbox.left * (this.canvas.width / bbox.width)),
        y: Math.floor(y - bbox.top *  (this.canvas.height / bbox.height))
    };
}

// ====================================================================================================================
// This function returns the segment object located at the specified x and y coordinates on the canvas.
// It is used to allow things to be done with a segment clicked by the user, such as highlight, display or change some values, etc.
// ====================================================================================================================
Winwheel.prototype.getSegmentAt = function(x, y)
{
    let foundSegment = null;

    // Call function to return segment number.
    let segmentNumber = this.getSegmentNumberAt(x, y);

    // If found one then set found segment to pointer to the segment object.
    if (segmentNumber !== null) {
        foundSegment = this.segments[segmentNumber];
    }

    return foundSegment;
}

// ====================================================================================================================
// Returns the number of the segment clicked instead of the segment object.
// This does not work correctly if the canvas width or height is altered by CSS but does work correctly with the scale factor.
// ====================================================================================================================
Winwheel.prototype.getSegmentNumberAt = function(x, y)
{
    // Call function above to convert the raw x and y from the user's browser to canvas coordinates
    // i.e. top and left is top and left of canvas, not top and left of the user's browser.
    let loc = this.windowToCanvas(x, y);

    // ------------------------------------------
    // Now start the process of working out the segment clicked.
    // First we need to figure out the angle of an imaginary line between the centerX and centerY of the wheel and
    // the X and Y of the location (for example a mouse click).
    let topBottom;
    let leftRight;
    let adjacentSideLength;
    let oppositeSideLength;
    let hypotenuseSideLength;

    // Get the centerX and centerY scaled with the scale factor, also the same for outer and inner radius.
    let centerX = (this.centerX * this.scaleFactor);
    let centerY = (this.centerY * this.scaleFactor);
    let outerRadius = (this.outerRadius * this.scaleFactor);
    let innerRadius = (this.innerRadius * this.scaleFactor);

    // We will use right triangle maths with the TAN function.
    // The start of the triangle is the wheel center, the adjacent side is along the x axis, and the opposite side is along the y axis.

    // We only ever use positive numbers to work out the triangle and the center of the wheel needs to be considered as 0 for the numbers
    // in the maths which is why there is the subtractions below. We also remember what quadrant of the wheel the location is in as we
    // need this information later to add 90, 180, 270 degrees to the angle worked out from the triangle to get the position around a 360 degree wheel.
    if (loc.x > centerX) {
        adjacentSideLength = (loc.x - centerX);
        leftRight = 'R';    // Location is in the right half of the wheel.
    } else {
        adjacentSideLength = (centerX - loc.x);
        leftRight = 'L';    // Location is in the left half of the wheel.
    }

    if (loc.y > centerY) {
        oppositeSideLength = (loc.y - centerY);
        topBottom = 'B';    // Bottom half of wheel.
    } else {
        oppositeSideLength = (centerY - loc.y);
        topBottom = 'T';    // Top Half of wheel.
    }

    // Now divide opposite by adjacent to get tan value.
    let tanVal = oppositeSideLength / adjacentSideLength;

    // Use the tan function and convert results to degrees since that is what we work with.
    let result = (Math.atan(tanVal) * 180/Math.PI);
    let locationAngle = 0;

    // We also need the length of the hypotenuse as later on we need to compare this to the outerRadius of the segment / circle.
    hypotenuseSideLength = Math.sqrt((oppositeSideLength * oppositeSideLength) + (adjacentSideLength * adjacentSideLength));

    // ------------------------------------------
    // Now to make sense around the wheel we need to alter the values based on if the location was in top or bottom half
    // and also right or left half of the wheel, by adding 90, 180, 270 etc. Also for some the initial locationAngle needs to be inverted.
    if ((topBottom == 'T') && (leftRight == 'R')) {
        locationAngle = Math.round(90 - result);
    } else if ((topBottom == 'B') && (leftRight == 'R')) {
        locationAngle = Math.round(result + 90);
    } else if ((topBottom == 'B') && (leftRight == 'L')) {
        locationAngle = Math.round((90 - result) + 180);
    } else if ((topBottom == 'T') && (leftRight == 'L')) {
        locationAngle = Math.round(result + 270);
    }

    // ------------------------------------------
    // And now we have to adjust to make sense when the wheel is rotated from the 0 degrees either
    // positive or negative and it can be many times past 360 degrees.
    if (this.rotationAngle != 0) {
        let rotatedPosition = this.getRotationPosition();

        // So we have this, now we need to alter the locationAngle as a result of this.
        locationAngle = (locationAngle - rotatedPosition);

        // If negative then take the location away from 360.
        if (locationAngle < 0) {
            locationAngle = (360 - Math.abs(locationAngle));
        }
    }

    // ------------------------------------------
    // OK, so after all of that we have the angle of a line between the centerX and centerY of the wheel and
    // the X and Y of the location on the canvas where the mouse was clicked. Now time to work out the segment
    // this corresponds to. We can use the segment start and end angles for this.
    let foundSegmentNumber = null;

    for (let x = 1; x <= this.numSegments; x ++) {
        // Due to segments sharing start and end angles, if line is clicked will pick earlier segment.
        if ((locationAngle >= this.segments[x].startAngle) && (locationAngle <= this.segments[x].endAngle)) {
            // To ensure that a click anywhere on the canvas in the segment direction will not cause a
            // segment to be matched, as well as the angles, we need to ensure the click was within the radius
            // of the segment (or circle if no segment radius).

            // If the hypotenuseSideLength (length of location from the center of the wheel) is with the radius
            // then we can assign the segment to the found segment and break out the loop.

            // Have to take in to account hollow wheels (doughnuts) so check is greater than innerRadius as
            // well as less than or equal to the outerRadius of the wheel.
            if ((hypotenuseSideLength >= innerRadius) && (hypotenuseSideLength <= outerRadius)) {
                foundSegmentNumber = x;
                break;
            }
        }
    }

    // Finally return the number.
    return foundSegmentNumber;
}

// ====================================================================================================================
// Returns a reference to the segment that is at the location of the pointer on the wheel.
// ====================================================================================================================
Winwheel.prototype.getIndicatedSegment = function()
{
    // Call function below to work this out and return the prizeNumber.
    let prizeNumber = this.getIndicatedSegmentNumber();

    // Then simply return the segment in the segments array at that position.
    return this.segments[prizeNumber];
}

// ====================================================================================================================
// Works out the segment currently pointed to by the pointer of the wheel. Normally called when the spinning has stopped
// to work out the prize the user has won. Returns the number of the segment in the segments array.
// ====================================================================================================================
Winwheel.prototype.getIndicatedSegmentNumber = function()
{
    let indicatedPrize = 0;
    let rawAngle = this.getRotationPosition();

    // Now we have the angle of the wheel, but we need to take in to account where the pointer is because
    // will not always be at the 12 o'clock 0 degrees location.
    let relativeAngle = Math.floor(this.pointerAngle - rawAngle);

    if (relativeAngle < 0) {
        relativeAngle = 360 - Math.abs(relativeAngle);
    }

    // Now we can work out the prize won by seeing what prize segment startAngle and endAngle the relativeAngle is between.
    for (let x = 1; x < (this.segments.length); x ++) {
        if ((relativeAngle >= this.segments[x]['startAngle']) && (relativeAngle <= this.segments[x]['endAngle'])) {
            indicatedPrize = x;
            break;
        }
    }

    return indicatedPrize;
}

// ====================================================================================================================
// Works out what Pin around the wheel is considered the current one which is the one which just passed the pointer.
// Used to work out if the pin has changed during the animation to tigger a sound.
// ====================================================================================================================
Winwheel.prototype.getCurrentPinNumber = function()
{
    let currentPin = 0;

    if (this.pins) {
        let rawAngle = this.getRotationPosition();

        // Now we have the angle of the wheel, but we need to take in to account where the pointer is because
        // will not always be at the 12 o'clock 0 degrees location.
        let relativeAngle = Math.floor(this.pointerAngle - rawAngle);

        if (relativeAngle < 0) {
            relativeAngle = 360 - Math.abs(relativeAngle);
        }

        // Work out the angle of the pins as this is simply 360 / the number of pins as they space evenly around.
        let pinSpacing = (360 / this.pins.number);
        let totalPinAngle = 0;

        // Now we can work out the pin by seeing what pins relativeAngle is between.
        for (let x = 0; x < (this.pins.number); x ++) {
            if ((relativeAngle >= totalPinAngle) && (relativeAngle <= (totalPinAngle + pinSpacing))) {
                currentPin = x;
                break;
            }

            totalPinAngle += pinSpacing;
        }

        // Now if rotating clockwise we must add 1 to the current pin as we want the pin which has just passed
        // the pointer to be returned as the current pin, not the start of the one we are between.
        if (this.animation.direction == 'clockwise') {
            currentPin ++;

            if (currentPin > this.pins.number) {
                currentPin = 0;
            }
        }
    }

    return currentPin;
}

// ==================================================================================================================================================
// Returns the rotation angle of the wheel corrected to 0-360 (i.e. removes all the multiples of 360).
// ==================================================================================================================================================
Winwheel.prototype.getRotationPosition = function()
{
    let rawAngle = this.rotationAngle;  // Get current rotation angle of wheel.

    // If positive work out how many times past 360 this is and then take the floor of this off the rawAngle.
    if (rawAngle >= 0) {
        if (rawAngle > 360) {
            // Get floor of the number of times past 360 degrees.
            let timesPast360 = Math.floor(rawAngle / 360);

            // Take all this extra off to get just the angle 0-360 degrees.
            rawAngle = (rawAngle - (360 * timesPast360));
        }
    } else {
        // Is negative, need to take off the extra then convert in to 0-360 degree value
        // so if, for example, was -90 then final value will be (360 - 90) = 270 degrees.
        if (rawAngle < -360) {
            let timesPast360 = Math.ceil(rawAngle / 360);   // Ceil when negative.

            rawAngle = (rawAngle - (360 * timesPast360));   // Is minus because dealing with negative.
        }

        rawAngle = (360 + rawAngle);    // Make in the range 0-360. Is plus because raw is still negative.
    }

    return rawAngle;
}

// ==================================================================================================================================================
// This function starts the wheel's animation by using the properties of the animation object of of the wheel to begin the a greensock tween.
// ==================================================================================================================================================
Winwheel.prototype.startAnimation = function()
{
    if (this.animation) {
        // Call function to compute the animation properties.
        this.computeAnimation();

        // Set this global variable to this object as an external function is required to call the draw() function on the wheel
        // each loop of the animation as Greensock cannot call the draw function directly on this class.
        winwheelToDrawDuringAnimation = this;

        // Put together the properties of the greesock animation.
        let properties = new Array(null);
        properties[this.animation.propertyName] = this.animation.propertyValue; // Here we set the property to be animated and its value.
        properties['yoyo']       = this.animation.yoyo;     // Set others.
        properties['repeat']     = this.animation.repeat;
        properties['ease']       = this.animation.easing;
        properties['onUpdate']   = winwheelAnimationLoop;   // Call function to re-draw the canvas.
        properties['onComplete'] = winwheelStopAnimation;   // Call function to perform actions when animation has finished.

        // Do the tween animation passing the properties from the animation object as an array of key => value pairs.
        // Keep reference to the tween object in the wheel as that allows pausing, resuming, and stopping while the animation is still running.
        this.tween = TweenMax.to(this, this.animation.duration, properties);
    }
}

// ==================================================================================================================================================
// Use same function which needs to be outside the class for the callback when it stops because is finished.
// ==================================================================================================================================================
Winwheel.prototype.stopAnimation = function(canCallback)
{
    // @TODO as part of multiwheel, need to work out how to stop the tween for a single wheel but allow others to continue.

    // We can kill the animation using our tween object.
    if (winwheelToDrawDuringAnimation) {
        // If the wheel has a tween animation then kill it.
        if (winwheelToDrawDuringAnimation.tween) {
            winwheelToDrawDuringAnimation.tween.kill();
        }

        // Call the callback function.
        winwheelStopAnimation(canCallback);
    }

    // Ensure the winwheelToDrawDuringAnimation is set to this class.
    winwheelToDrawDuringAnimation = this;
}

// ==================================================================================================================================================
// Pause animation by telling tween to pause.
// ==================================================================================================================================================
Winwheel.prototype.pauseAnimation = function()
{
    if (this.tween) {
        this.tween.pause();
    }
}

// ==================================================================================================================================================
// Resume the animation by telling tween to continue playing it.
// ==================================================================================================================================================
Winwheel.prototype.resumeAnimation = function()
{
    if (this.tween) {
        this.tween.play();
    }
}

// ====================================================================================================================
// Called at the beginning of the startAnimation function and computes the values needed to do the animation
// before it starts. This allows the developer to change the animation properties after the wheel has been created
// and have the animation use the new values of the animation properties.
// ====================================================================================================================
Winwheel.prototype.computeAnimation = function()
{
    if (this.animation) {
        // Set the animation parameters for the specified animation type including some sensible defaults if values have not been specified.
        if (this.animation.type == 'spinOngoing') {
            // When spinning the rotationAngle is the wheel property which is animated.
            this.animation.propertyName = 'rotationAngle';

            if (this.animation.spins == null) {
                this.animation.spins = 5;
            }

            if (this.animation.repeat == null) {
                this.animation.repeat = -1;           // -1 means it will repeat forever.
            }

            if (this.animation.easing == null) {
                this.animation.easing = 'Linear.easeNone';
            }

            if (this.animation.yoyo == null) {
                this.animation.yoyo = false;
            }

            // We need to calculate the propertyValue and this is the spins * 360 degrees.
            this.animation.propertyValue = (this.animation.spins * 360);

            // If the direction is anti-clockwise then make the property value negative.
            if (this.animation.direction == 'anti-clockwise') {
                this.animation.propertyValue = (0 - this.animation.propertyValue);
            }
        } else if (this.animation.type == 'spinToStop') {
            // Spin to stop the rotation angle is affected.
            this.animation.propertyName = 'rotationAngle';

            if (this.animation.spins == null) {
                this.animation.spins = 5;
            }

            if (this.animation.repeat == null) {
                this.animation.repeat = 0;        // As this is spin to stop we don't normally want it repeated.
            }

            if (this.animation.easing == null) {
                this.animation.easing = 'Power3.easeOut';     // This easing is fast start and slows over time.
            }

            if (this.animation.stopAngle == null) {
                // If the stop angle has not been specified then pick random between 0 and 359.
                this.animation._stopAngle = Math.floor((Math.random() * 359));
            } else {
                // We need to set the internal to 360 minus what the user entered because the wheel spins past 0 without
                // this it would indicate the prize on the opposite side of the wheel. We aslo need to take in to account
                // the pointerAngle as the stop angle needs to be relative to that.
                this.animation._stopAngle = (360 - this.animation.stopAngle + this.pointerAngle);
            }

            if (this.animation.yoyo == null) {
                this.animation.yoyo = false;
            }

            // The property value is the spins * 360 then plus or minus the stopAngle depending on if the rotation is clockwise or anti-clockwise.
            this.animation.propertyValue = (this.animation.spins * 360);

            if (this.animation.direction == 'anti-clockwise') {
                this.animation.propertyValue = (0 - this.animation.propertyValue);

                // Also if the value is anti-clockwise we need subtract the stopAngle (but to get the wheel to stop in the correct
                // place this is 360 minus the stop angle as the wheel is rotating backwards).
                this.animation.propertyValue -= (360 - this.animation._stopAngle);
            } else {
                // Add the stopAngle to the propertyValue as the wheel must rotate around to this place and stop there.
                this.animation.propertyValue += this.animation._stopAngle;
            }
        } else if (this.animation.type == 'spinAndBack') {
            // This is basically is a spin for a number of times then the animation reverses and goes back to start.
            // If a repeat is specified then this can be used to make the wheel "rock" left and right.

            // Again this is a spin so the rotationAngle the property which is animated.
            this.animation.propertyName = 'rotationAngle';

            if (this.animation.spins == null) {
                this.animation.spins = 5;
            }

            if (this.animation.repeat == null) {
                this.animation.repeat = 1;          // This needs to be set to at least 1 in order for the animation to reverse.
            }

            if (this.animation.easing == null) {
                this.animation.easing = 'Power2.easeInOut';     // This is slow at the start and end and fast in the middle.
            }

            if (this.animation.yoyo == null) {
                this.animation.yoyo = true;       // This needs to be set to true to have the animation reverse back like a yo-yo.
            }

            if (this.animation.stopAngle == null) {
                this.animation._stopAngle = 0;
            } else {
                // We need to set the internal to 360 minus what the user entered
                // because the wheel spins past 0 without this it would indicate the
                // prize on the opposite side of the wheel.
                this.animation._stopAngle = (360 - this.animation.stopAngle);
            }

            // The property value is the spins * 360 then plus or minus the stopAngle depending on if the rotation is clockwise or anti-clockwise.
            this.animation.propertyValue = (this.animation.spins * 360);

            if (this.animation.direction == 'anti-clockwise') {
                this.animation.propertyValue = (0 - this.animation.propertyValue);

                // Also if the value is anti-clockwise we need subtract the stopAngle (but to get the wheel to stop in the correct
                // place this is 360 minus the stop angle as the wheel is rotating backwards).
                this.animation.propertyValue -= (360 - this.animation._stopAngle);
            } else {
                // Add the stopAngle to the propertyValue as the wheel must rotate around to this place and stop there.
                this.animation.propertyValue += this.animation._stopAngle;
            }
        } else if (this.animation.type == 'custom') {
            // Do nothing as all values must be set by the developer in the parameters
            // especially the propertyName and propertyValue.
        }
    }
}

// ====================================================================================================================
// Calculates and returns a random stop angle inside the specified segment number. Value will always be 1 degree inside
// the start and end of the segment to avoid issue with the segment overlap.
// ====================================================================================================================
Winwheel.prototype.getRandomForSegment = function(segmentNumber)
{
    let stopAngle = 0;

    if (segmentNumber) {
        if (typeof this.segments[segmentNumber] !== 'undefined') {
            let startAngle = this.segments[segmentNumber].startAngle;
            let endAngle = this.segments[segmentNumber].endAngle;
            let range = (endAngle - startAngle) - 2;

            if (range > 0) {
                stopAngle = (startAngle + 1 + Math.floor((Math.random() * range)));
            } else {
               console.log('Segment size is too small to safely get random angle inside it');
            }
        } else {
            console.log('Segment ' + segmentNumber + ' undefined');
        }
    } else {
        console.log('Segment number not specified');
    }

    return stopAngle;
}

// ====================================================================================================================
// Class for the wheel pins.
// ====================================================================================================================
function Pin(options)
{
    let defaultOptions = {
        'visible'        : true,     // In future there might be some functionality related to the pins even if they are not displayed.
        'number'         : 36,       // The number of pins. These are evenly distributed around the wheel.
        'outerRadius'    : 3,        // Radius of the pins which determines their size.
        'fillStyle'      : 'grey',   // Fill colour of the pins.
        'strokeStyle'    : 'black',  // Line colour of the pins.
        'lineWidth'      : 1,        // Line width of the pins.
        'margin'         : 3,        // The space between outside edge of the wheel and the pins.
        'responsive'     : false,    // If set to true the diameter of the pin will resize when the wheel is responsive.
    };

    // Now loop through the default options and create properties of this class set to the value for
    // the option passed in if a value was, or if not then set the value of the default.
    for (let key in defaultOptions) {
        if ((options != null) && (typeof(options[key]) !== 'undefined')) {
            this[key] = options[key];
        } else {
            this[key] = defaultOptions[key];
        }
    }

    // Also loop though the passed in options and add anything specified not part of the class in to it as a property.
    if (options != null) {
        for (let key in options) {
            if (typeof(this[key]) === 'undefined') {
                this[key] = options[key];
            }
        }
    }
}

// ====================================================================================================================
// Class for the wheel spinning animation which like a segment becomes a property of the wheel.
// ====================================================================================================================
function Animation(options)
{
    // Most of these options are null because the defaults are different depending on the type of animation.
    let defaultOptions = {
        'type'              : 'spinOngoing',   // For now there are only supported types are spinOngoing (continuous), spinToStop, spinAndBack, custom.
        'direction'         : 'clockwise',     // clockwise or anti-clockwise.
        'propertyName'      : null,            // The name of the winning wheel property to be affected by the animation.
        'propertyValue'     : null,            // The value the property is to be set to at the end of the animation.
        'duration'          : 10,              // Duration of the animation.
        'yoyo'              : false,           // If the animation is to reverse back again i.e. yo-yo.
        'repeat'            : null,            // The number of times the animation is to repeat, -1 will cause it to repeat forever.
        'easing'            : null,            // The easing to use for the animation, default is the best for spin to stop. Use Linear.easeNone for no easing.
        'stopAngle'         : null,            // Used for spinning, the angle at which the wheel is to stop.
        'spins'             : null,            // Used for spinning, the number of complete 360 degree rotations the wheel is to do.
        'clearTheCanvas'    : null,            // If set to true the canvas will be cleared before the wheel is re-drawn, false it will not, null the animation will abide by the value of this property for the parent wheel object.
        'callbackFinished'  : null,            // Function to callback when the animation has finished.
        'callbackBefore'    : null,            // Function to callback before the wheel is drawn each animation loop.
        'callbackAfter'     : null,            // Function to callback after the wheel is drawn each animation loop.
        'callbackSound'     : null,            // Function to callback if a sound should be triggered on change of segment or pin.
        'soundTrigger'      : 'segment'        // Sound trigger type. Default is segment which triggers when segment changes, can be pin if to trigger when pin passes the pointer.
    };

    // Now loop through the default options and create properties of this class set to the value for
    // the option passed in if a value was, or if not then set the value of the default.
    for (let key in defaultOptions) {
        if ((options != null) && (typeof(options[key]) !== 'undefined')) {
            this[key] = options[key];
        } else {
            this[key] = defaultOptions[key];
        }
    }

    // Also loop though the passed in options and add anything specified not part of the class in to it as a property.
    if (options != null) {
        for (let key in options) {
            if (typeof(this[key]) === 'undefined') {
                this[key] = options[key];
            }
        }
    }
}

// ====================================================================================================================
// Class for segments. When creating a json of options can be passed in.
// ====================================================================================================================
function Segment(options)
{
    // Define default options for segments, most are null so that the global defaults for the wheel
    // are used if the values for a particular segment are not specifically set.
    let defaultOptions = {
        'size'              : null, // Leave null for automatic. Valid values are degrees 0-360. Use percentToDegrees function if needed to convert.
        'text'              : '',   // Default is blank.
        'fillStyle'         : null, // If null for the rest the global default will be used.
        'strokeStyle'       : null,
        'lineWidth'         : null,
        'textFontFamily'    : null,
        'textFontSize'      : null,
        'textFontWeight'    : null,
        'textOrientation'   : null,
        'textAlignment'     : null,
        'textDirection'     : null,
        'textMargin'        : null,
        'textFillStyle'     : null,
        'textStrokeStyle'   : null,
        'textLineWidth'     : null,
        'image'             : null, // Name/path to the image
        'imageDirection'    : null, // Direction of the image, can be set globally for the whole wheel.
        'imgData'           : null  // Image object created here and loaded with image data.
    };

    // Now loop through the default options and create properties of this class set to the value for
    // the option passed in if a value was, or if not then set the value of the default.
    for (let key in defaultOptions) {
        if ((options != null) && (typeof(options[key]) !== 'undefined')) {
            this[key] = options[key];
        } else {
            this[key] = defaultOptions[key];
        }
    }

    // Also loop though the passed in options and add anything specified not part of the class in to it as a property.
    // This allows the developer to easily add properties to segments at construction time.
    if (options != null) {
        for (let key in options) {
            if (typeof(this[key]) === 'undefined') {
                this[key] = options[key];
            }
        }
    }

    // There are 2 additional properties which are set by the code, so need to define them here.
    // They are not in the default options because they are not something that should be set by the user,
    // the values are updated every time the updateSegmentSizes() function is called.
    this.startAngle = 0;
    this.endAngle   = 0;
}

// ====================================================================================================================
// Changes an image for a segment by setting a callback to render the wheel once the image has loaded.
// ====================================================================================================================
Segment.prototype.changeImage = function(image, imageDirection)
{
    // Change image name, blank image data.
    this.image = image;
    this.imgData = null;

    // Set direction.
    if (imageDirection) {
        this.imageDirection = imageDirection;
    }

    // Set imgData to a new image object, change set callback and change src (just like in wheel constructor).
    winhweelAlreadyDrawn = false;
    this.imgData = new Image();
    this.imgData.onload = winwheelLoadedImage;
    this.imgData.src = this.image;
}

// ====================================================================================================================
// Class that is created as property of the wheel. Draws line from center of the wheel out to edge of canvas to
// indicate where the code thinks the pointer location is. Helpful to get alignment correct esp when using images.
// ====================================================================================================================
function PointerGuide(options)
{
    let defaultOptions = {
        'display'     : false,
        'strokeStyle' : 'red',
        'lineWidth'   : 3
    };

    // Now loop through the default options and create properties of this class set to the value for
    // the option passed in if a value was, or if not then set the value of the default.
    for (let key in defaultOptions) {
        if ((options != null) && (typeof(options[key]) !== 'undefined')) {
            this[key] = options[key];
        } else {
            this[key] = defaultOptions[key];
        }
    }
}

// ====================================================================================================================
// This function takes the percent 0-100 and returns the number of degrees 0-360 this equates to.
// ====================================================================================================================
function winwheelPercentToDegrees(percentValue)
{
    let degrees = 0;

    if ((percentValue > 0) && (percentValue <= 100)) {
        let divider = (percentValue / 100);
        degrees = (360 * divider);
    }

    return degrees;
}

// ====================================================================================================================
// In order for the wheel to be re-drawn during the spin animation the function greesock calls needs to be outside
// of the class as for some reason it errors if try to call winwheel.draw() directly.
// ====================================================================================================================
function winwheelAnimationLoop()
{
    if (winwheelToDrawDuringAnimation) {
        // Check if the clearTheCanvas is specified for this animation, if not or it is not false then clear the canvas.
        if (winwheelToDrawDuringAnimation.animation.clearTheCanvas != false) {
            winwheelToDrawDuringAnimation.ctx.clearRect(0, 0, winwheelToDrawDuringAnimation.canvas.width, winwheelToDrawDuringAnimation.canvas.height);
        }

        let callbackBefore = winwheelToDrawDuringAnimation.animation.callbackBefore;
        let callbackAfter = winwheelToDrawDuringAnimation.animation.callbackAfter;

        // If there is a callback function which is supposed to be called before the wheel is drawn then do that.
        if (callbackBefore != null) {
            // If the property is a function then call it, otherwise eval the proptery as javascript code.
            if (typeof callbackBefore === 'function') {
                callbackBefore();
            } else {
                eval(callbackBefore);
            }
        }

        // Call code to draw the wheel, pass in false as we never want it to clear the canvas as that would wipe anything drawn in the callbackBefore.
        winwheelToDrawDuringAnimation.draw(false);

        // If there is a callback function which is supposed to be called after the wheel has been drawn then do that.
        if (callbackAfter != null) {
            // If the property is a function then call it, otherwise eval the proptery as javascript code.
            if (typeof callbackAfter === 'function') {
                callbackAfter();
            } else {
                eval(callbackAfter);
            }
        }

        // If there is a sound callback then call a function which figures out if the sound should be triggered
        // and if so then call the function specified by the developer.
        if (winwheelToDrawDuringAnimation.animation.callbackSound) {
            winwheelTriggerSound();
        }
    }
}

// ====================================================================================================================
// This function figures out if the callbackSound function needs to be called by working out if the segment or pin
// has changed since the last animation loop.
// ====================================================================================================================
function winwheelTriggerSound()
{
    // If this property does not exist then add it as a property of the winwheel.
    if (winwheelToDrawDuringAnimation.hasOwnProperty('_lastSoundTriggerNumber') == false) {
        winwheelToDrawDuringAnimation._lastSoundTriggerNumber = 0;
    }

    let callbackSound = winwheelToDrawDuringAnimation.animation.callbackSound;
    let currentTriggerNumber = 0;

    // Now figure out if the sound callback should be called depending on the sound trigger type.
    if (winwheelToDrawDuringAnimation.animation.soundTrigger == 'pin') {
        // So for the pin type we need to work out which pin we are between.
        currentTriggerNumber = winwheelToDrawDuringAnimation.getCurrentPinNumber();
    } else {
        // Check on the change of segment by working out which segment we are in.
        // We can utilise the existing getIndiatedSegmentNumber function.
        currentTriggerNumber = winwheelToDrawDuringAnimation.getIndicatedSegmentNumber();
    }

    // If the current number is not the same as last time then call the sound callback.
    if (currentTriggerNumber != winwheelToDrawDuringAnimation._lastSoundTriggerNumber) {
        // If the property is a function then call it, otherwise eval the proptery as javascript code.
        if (typeof callbackSound === 'function') {
            callbackSound();
        } else {
            eval(callbackSound);
        }

        // Also update the last sound trigger with the current number.
        winwheelToDrawDuringAnimation._lastSoundTriggerNumber = currentTriggerNumber;
    }
}

// ====================================================================================================================
// This function is called-back when the greensock animation has finished.
// ====================================================================================================================
let winwheelToDrawDuringAnimation = null;  // This global is set by the winwheel class to the wheel object to be re-drawn.

function winwheelStopAnimation(canCallback)
{
    // When the animation is stopped if canCallback is not false then try to call the callback.
    // false can be passed in to stop the after happening if the animation has been stopped before it ended normally.
    if (canCallback != false) {
        let callback = winwheelToDrawDuringAnimation.animation.callbackFinished;

        if (callback != null) {
            // If the callback is a function then call it, otherwise evaluate the property as javascript code.
            if (typeof callback === 'function') {
                // Pass back the indicated segment as 99% of the time you will want to know this to inform the user of their prize.
                callback(winwheelToDrawDuringAnimation.getIndicatedSegment(), winwheelToDrawDuringAnimation);
            } else {
                eval(callback);
            }
        }
    }
}

// ====================================================================================================================
// Called after the image has loaded for each segment. Once all the images are loaded it then calls the draw function
// on the wheel to render it. Used in constructor and also when a segment image is changed.
// ====================================================================================================================
let winhweelAlreadyDrawn = false;

function winwheelLoadedImage()
{
    // Prevent multiple drawings of the wheel which ocurrs without this check due to timing of function calls.
    if (winhweelAlreadyDrawn == false) {
        // Set to 0.
        let winwheelImageLoadCount = 0;

        // Loop though all the segments of the wheel and check if image data loaded, if so increment counter.
        for (let i = 1; i <= winwheelToDrawDuringAnimation.numSegments; i ++) {
            // Check the image data object is not null and also that the image has completed loading by checking
            // that a property of it such as the height has some sort of true value.
            if ((winwheelToDrawDuringAnimation.segments[i].imgData != null) && (winwheelToDrawDuringAnimation.segments[i].imgData.height)) {
                winwheelImageLoadCount ++;
            }
        }

        // If number of images loaded matches the segments then all the images for the wheel are loaded.
        if (winwheelImageLoadCount == winwheelToDrawDuringAnimation.numSegments) {
            // Call draw function to render the wheel.
            winhweelAlreadyDrawn = true;
            winwheelToDrawDuringAnimation.draw();
        }
    }
}

// ====================================================================================================================
// Called when the wheel is to resize. This is normally called from a onresize of the window, also called from onload
// so the initial size is correct. Here we must re-size the canvas and work out the scaleFactor for the wheel.
// ====================================================================================================================
function winwheelResize()
{
    // By default set the margin to 40px, this can be overridden if needed.
    // This is to stop the canvas going right to the right edge of the screen and being overlayed by a scrollbar though
    // if the canvas is center aligned, half the magin will be applied to each side since the margin actually reduces the width of the canvas.
    let margin = 40;

    // If a value has been specified for this then update the margin to it.
    if (typeof(winwheelToDrawDuringAnimation._responsiveMargin) !== 'undefined') {
        margin = winwheelToDrawDuringAnimation._responsiveMargin;
    }

    // Get the current width and also optional min and max width properties.
    let width = window.innerWidth - margin;
    let minWidth = winwheelToDrawDuringAnimation._responsiveMinWidth;
    let minHeight = winwheelToDrawDuringAnimation._responsiveMinHeight;

    // Adjust the width as it cannot be larger than the original size of the wheel and we don't want
    // the canvas and wheel inside it to be too small so check the min width.
    if (width < minWidth) {
        width = minWidth;
    } else if (width > winwheelToDrawDuringAnimation._originalCanvasWidth) {
        width = winwheelToDrawDuringAnimation._originalCanvasWidth;
    }

    // Work out the percent the new width is smaller than the original width.
    let percent = (width / winwheelToDrawDuringAnimation._originalCanvasWidth);

    // Set the canvas width to the width to a percentage of the original width.
    winwheelToDrawDuringAnimation.canvas.width = (winwheelToDrawDuringAnimation._originalCanvasWidth * percent);

    // Scale the height if we are supposed to but ensure it does not go below the minHeight.
    if (winwheelToDrawDuringAnimation._responsiveScaleHeight) {
        let height = (winwheelToDrawDuringAnimation._originalCanvasHeight * percent);

        if (height < minHeight) {
            height = minHeight;
        } else if (height > winwheelToDrawDuringAnimation._originalCanvasHeight) {
            height = winwheelToDrawDuringAnimation._originalCanvasHeight;
        }

        winwheelToDrawDuringAnimation.canvas.height = height;
    }

    // OK so now we have the percent, set the scaleFactor of the wheel to this.
    winwheelToDrawDuringAnimation.scaleFactor = percent;

    // Now re-draw the wheel to ensure the changes in size are rendered.
    winwheelToDrawDuringAnimation.draw();
}

/*!
 * VERSION: 1.18.0
 * DATE: 2015-09-05
 * UPDATES AND DOCS AT: http://greensock.com
 * 
 * Includes all of the following: TweenLite, TweenMax, TimelineLite, TimelineMax, EasePack, CSSPlugin, RoundPropsPlugin, BezierPlugin, AttrPlugin, DirectionalRotationPlugin
 *
 * @license Copyright (c) 2008-2015, GreenSock. All rights reserved.
 * This work is subject to the terms at http://greensock.com/standard-license or for
 * Club GreenSock members, the software agreement that was issued with your membership.
 * 
 * @author: Jack Doyle, jack@greensock.com
 **/
var _gsScope="undefined"!=typeof module&&module.exports&&"undefined"!=typeof global?global:this||window;(_gsScope._gsQueue||(_gsScope._gsQueue=[])).push(function(){"use strict";_gsScope._gsDefine("TweenMax",["core.Animation","core.SimpleTimeline","TweenLite"],function(t,e,i){var s=function(t){var e,i=[],s=t.length;for(e=0;e!==s;i.push(t[e++]));return i},r=function(t,e,i){var s,r,n=t.cycle;for(s in n)r=n[s],t[s]="function"==typeof r?r.call(e[i],i):r[i%r.length];delete t.cycle},n=function(t,e,s){i.call(this,t,e,s),this._cycle=0,this._yoyo=this.vars.yoyo===!0,this._repeat=this.vars.repeat||0,this._repeatDelay=this.vars.repeatDelay||0,this._dirty=!0,this.render=n.prototype.render},a=1e-10,o=i._internals,l=o.isSelector,h=o.isArray,_=n.prototype=i.to({},.1,{}),u=[];n.version="1.18.0",_.constructor=n,_.kill()._gc=!1,n.killTweensOf=n.killDelayedCallsTo=i.killTweensOf,n.getTweensOf=i.getTweensOf,n.lagSmoothing=i.lagSmoothing,n.ticker=i.ticker,n.render=i.render,_.invalidate=function(){return this._yoyo=this.vars.yoyo===!0,this._repeat=this.vars.repeat||0,this._repeatDelay=this.vars.repeatDelay||0,this._uncache(!0),i.prototype.invalidate.call(this)},_.updateTo=function(t,e){var s,r=this.ratio,n=this.vars.immediateRender||t.immediateRender;e&&this._startTime<this._timeline._time&&(this._startTime=this._timeline._time,this._uncache(!1),this._gc?this._enabled(!0,!1):this._timeline.insert(this,this._startTime-this._delay));for(s in t)this.vars[s]=t[s];if(this._initted||n)if(e)this._initted=!1,n&&this.render(0,!0,!0);else if(this._gc&&this._enabled(!0,!1),this._notifyPluginsOfEnabled&&this._firstPT&&i._onPluginEvent("_onDisable",this),this._time/this._duration>.998){var a=this._time;this.render(0,!0,!1),this._initted=!1,this.render(a,!0,!1)}else if(this._time>0||n){this._initted=!1,this._init();for(var o,l=1/(1-r),h=this._firstPT;h;)o=h.s+h.c,h.c*=l,h.s=o-h.c,h=h._next}return this},_.render=function(t,e,i){this._initted||0===this._duration&&this.vars.repeat&&this.invalidate();var s,r,n,l,h,_,u,c,f=this._dirty?this.totalDuration():this._totalDuration,p=this._time,m=this._totalTime,d=this._cycle,g=this._duration,v=this._rawPrevTime;if(t>=f?(this._totalTime=f,this._cycle=this._repeat,this._yoyo&&0!==(1&this._cycle)?(this._time=0,this.ratio=this._ease._calcEnd?this._ease.getRatio(0):0):(this._time=g,this.ratio=this._ease._calcEnd?this._ease.getRatio(1):1),this._reversed||(s=!0,r="onComplete",i=i||this._timeline.autoRemoveChildren),0===g&&(this._initted||!this.vars.lazy||i)&&(this._startTime===this._timeline._duration&&(t=0),(0===t||0>v||v===a)&&v!==t&&(i=!0,v>a&&(r="onReverseComplete")),this._rawPrevTime=c=!e||t||v===t?t:a)):1e-7>t?(this._totalTime=this._time=this._cycle=0,this.ratio=this._ease._calcEnd?this._ease.getRatio(0):0,(0!==m||0===g&&v>0)&&(r="onReverseComplete",s=this._reversed),0>t&&(this._active=!1,0===g&&(this._initted||!this.vars.lazy||i)&&(v>=0&&(i=!0),this._rawPrevTime=c=!e||t||v===t?t:a)),this._initted||(i=!0)):(this._totalTime=this._time=t,0!==this._repeat&&(l=g+this._repeatDelay,this._cycle=this._totalTime/l>>0,0!==this._cycle&&this._cycle===this._totalTime/l&&this._cycle--,this._time=this._totalTime-this._cycle*l,this._yoyo&&0!==(1&this._cycle)&&(this._time=g-this._time),this._time>g?this._time=g:0>this._time&&(this._time=0)),this._easeType?(h=this._time/g,_=this._easeType,u=this._easePower,(1===_||3===_&&h>=.5)&&(h=1-h),3===_&&(h*=2),1===u?h*=h:2===u?h*=h*h:3===u?h*=h*h*h:4===u&&(h*=h*h*h*h),this.ratio=1===_?1-h:2===_?h:.5>this._time/g?h/2:1-h/2):this.ratio=this._ease.getRatio(this._time/g)),p===this._time&&!i&&d===this._cycle)return m!==this._totalTime&&this._onUpdate&&(e||this._callback("onUpdate")),void 0;if(!this._initted){if(this._init(),!this._initted||this._gc)return;if(!i&&this._firstPT&&(this.vars.lazy!==!1&&this._duration||this.vars.lazy&&!this._duration))return this._time=p,this._totalTime=m,this._rawPrevTime=v,this._cycle=d,o.lazyTweens.push(this),this._lazy=[t,e],void 0;this._time&&!s?this.ratio=this._ease.getRatio(this._time/g):s&&this._ease._calcEnd&&(this.ratio=this._ease.getRatio(0===this._time?0:1))}for(this._lazy!==!1&&(this._lazy=!1),this._active||!this._paused&&this._time!==p&&t>=0&&(this._active=!0),0===m&&(2===this._initted&&t>0&&this._init(),this._startAt&&(t>=0?this._startAt.render(t,e,i):r||(r="_dummyGS")),this.vars.onStart&&(0!==this._totalTime||0===g)&&(e||this._callback("onStart"))),n=this._firstPT;n;)n.f?n.t[n.p](n.c*this.ratio+n.s):n.t[n.p]=n.c*this.ratio+n.s,n=n._next;this._onUpdate&&(0>t&&this._startAt&&this._startTime&&this._startAt.render(t,e,i),e||(this._totalTime!==m||s)&&this._callback("onUpdate")),this._cycle!==d&&(e||this._gc||this.vars.onRepeat&&this._callback("onRepeat")),r&&(!this._gc||i)&&(0>t&&this._startAt&&!this._onUpdate&&this._startTime&&this._startAt.render(t,e,i),s&&(this._timeline.autoRemoveChildren&&this._enabled(!1,!1),this._active=!1),!e&&this.vars[r]&&this._callback(r),0===g&&this._rawPrevTime===a&&c!==a&&(this._rawPrevTime=0))},n.to=function(t,e,i){return new n(t,e,i)},n.from=function(t,e,i){return i.runBackwards=!0,i.immediateRender=0!=i.immediateRender,new n(t,e,i)},n.fromTo=function(t,e,i,s){return s.startAt=i,s.immediateRender=0!=s.immediateRender&&0!=i.immediateRender,new n(t,e,s)},n.staggerTo=n.allTo=function(t,e,a,o,_,c,f){o=o||0;var p,m,d,g,v=a.delay||0,y=[],T=function(){a.onComplete&&a.onComplete.apply(a.onCompleteScope||this,arguments),_.apply(f||a.callbackScope||this,c||u)},x=a.cycle,w=a.startAt&&a.startAt.cycle;for(h(t)||("string"==typeof t&&(t=i.selector(t)||t),l(t)&&(t=s(t))),t=t||[],0>o&&(t=s(t),t.reverse(),o*=-1),p=t.length-1,d=0;p>=d;d++){m={};for(g in a)m[g]=a[g];if(x&&r(m,t,d),w){w=m.startAt={};for(g in a.startAt)w[g]=a.startAt[g];r(m.startAt,t,d)}m.delay=v,d===p&&_&&(m.onComplete=T),y[d]=new n(t[d],e,m),v+=o}return y},n.staggerFrom=n.allFrom=function(t,e,i,s,r,a,o){return i.runBackwards=!0,i.immediateRender=0!=i.immediateRender,n.staggerTo(t,e,i,s,r,a,o)},n.staggerFromTo=n.allFromTo=function(t,e,i,s,r,a,o,l){return s.startAt=i,s.immediateRender=0!=s.immediateRender&&0!=i.immediateRender,n.staggerTo(t,e,s,r,a,o,l)},n.delayedCall=function(t,e,i,s,r){return new n(e,0,{delay:t,onComplete:e,onCompleteParams:i,callbackScope:s,onReverseComplete:e,onReverseCompleteParams:i,immediateRender:!1,useFrames:r,overwrite:0})},n.set=function(t,e){return new n(t,0,e)},n.isTweening=function(t){return i.getTweensOf(t,!0).length>0};var c=function(t,e){for(var s=[],r=0,n=t._first;n;)n instanceof i?s[r++]=n:(e&&(s[r++]=n),s=s.concat(c(n,e)),r=s.length),n=n._next;return s},f=n.getAllTweens=function(e){return c(t._rootTimeline,e).concat(c(t._rootFramesTimeline,e))};n.killAll=function(t,i,s,r){null==i&&(i=!0),null==s&&(s=!0);var n,a,o,l=f(0!=r),h=l.length,_=i&&s&&r;for(o=0;h>o;o++)a=l[o],(_||a instanceof e||(n=a.target===a.vars.onComplete)&&s||i&&!n)&&(t?a.totalTime(a._reversed?0:a.totalDuration()):a._enabled(!1,!1))},n.killChildTweensOf=function(t,e){if(null!=t){var r,a,_,u,c,f=o.tweenLookup;if("string"==typeof t&&(t=i.selector(t)||t),l(t)&&(t=s(t)),h(t))for(u=t.length;--u>-1;)n.killChildTweensOf(t[u],e);else{r=[];for(_ in f)for(a=f[_].target.parentNode;a;)a===t&&(r=r.concat(f[_].tweens)),a=a.parentNode;for(c=r.length,u=0;c>u;u++)e&&r[u].totalTime(r[u].totalDuration()),r[u]._enabled(!1,!1)}}};var p=function(t,i,s,r){i=i!==!1,s=s!==!1,r=r!==!1;for(var n,a,o=f(r),l=i&&s&&r,h=o.length;--h>-1;)a=o[h],(l||a instanceof e||(n=a.target===a.vars.onComplete)&&s||i&&!n)&&a.paused(t)};return n.pauseAll=function(t,e,i){p(!0,t,e,i)},n.resumeAll=function(t,e,i){p(!1,t,e,i)},n.globalTimeScale=function(e){var s=t._rootTimeline,r=i.ticker.time;return arguments.length?(e=e||a,s._startTime=r-(r-s._startTime)*s._timeScale/e,s=t._rootFramesTimeline,r=i.ticker.frame,s._startTime=r-(r-s._startTime)*s._timeScale/e,s._timeScale=t._rootTimeline._timeScale=e,e):s._timeScale},_.progress=function(t){return arguments.length?this.totalTime(this.duration()*(this._yoyo&&0!==(1&this._cycle)?1-t:t)+this._cycle*(this._duration+this._repeatDelay),!1):this._time/this.duration()},_.totalProgress=function(t){return arguments.length?this.totalTime(this.totalDuration()*t,!1):this._totalTime/this.totalDuration()},_.time=function(t,e){return arguments.length?(this._dirty&&this.totalDuration(),t>this._duration&&(t=this._duration),this._yoyo&&0!==(1&this._cycle)?t=this._duration-t+this._cycle*(this._duration+this._repeatDelay):0!==this._repeat&&(t+=this._cycle*(this._duration+this._repeatDelay)),this.totalTime(t,e)):this._time},_.duration=function(e){return arguments.length?t.prototype.duration.call(this,e):this._duration},_.totalDuration=function(t){return arguments.length?-1===this._repeat?this:this.duration((t-this._repeat*this._repeatDelay)/(this._repeat+1)):(this._dirty&&(this._totalDuration=-1===this._repeat?999999999999:this._duration*(this._repeat+1)+this._repeatDelay*this._repeat,this._dirty=!1),this._totalDuration)},_.repeat=function(t){return arguments.length?(this._repeat=t,this._uncache(!0)):this._repeat},_.repeatDelay=function(t){return arguments.length?(this._repeatDelay=t,this._uncache(!0)):this._repeatDelay},_.yoyo=function(t){return arguments.length?(this._yoyo=t,this):this._yoyo},n},!0),_gsScope._gsDefine("TimelineLite",["core.Animation","core.SimpleTimeline","TweenLite"],function(t,e,i){var s=function(t){e.call(this,t),this._labels={},this.autoRemoveChildren=this.vars.autoRemoveChildren===!0,this.smoothChildTiming=this.vars.smoothChildTiming===!0,this._sortChildren=!0,this._onUpdate=this.vars.onUpdate;var i,s,r=this.vars;for(s in r)i=r[s],l(i)&&-1!==i.join("").indexOf("{self}")&&(r[s]=this._swapSelfInParams(i));l(r.tweens)&&this.add(r.tweens,0,r.align,r.stagger)},r=1e-10,n=i._internals,a=s._internals={},o=n.isSelector,l=n.isArray,h=n.lazyTweens,_=n.lazyRender,u=_gsScope._gsDefine.globals,c=function(t){var e,i={};for(e in t)i[e]=t[e];return i},f=function(t,e,i){var s,r,n=t.cycle;for(s in n)r=n[s],t[s]="function"==typeof r?r.call(e[i],i):r[i%r.length];delete t.cycle},p=a.pauseCallback=function(){},m=function(t){var e,i=[],s=t.length;for(e=0;e!==s;i.push(t[e++]));return i},d=s.prototype=new e;return s.version="1.18.0",d.constructor=s,d.kill()._gc=d._forcingPlayhead=d._hasPause=!1,d.to=function(t,e,s,r){var n=s.repeat&&u.TweenMax||i;return e?this.add(new n(t,e,s),r):this.set(t,s,r)},d.from=function(t,e,s,r){return this.add((s.repeat&&u.TweenMax||i).from(t,e,s),r)},d.fromTo=function(t,e,s,r,n){var a=r.repeat&&u.TweenMax||i;return e?this.add(a.fromTo(t,e,s,r),n):this.set(t,r,n)},d.staggerTo=function(t,e,r,n,a,l,h,_){var u,p,d=new s({onComplete:l,onCompleteParams:h,callbackScope:_,smoothChildTiming:this.smoothChildTiming}),g=r.cycle;for("string"==typeof t&&(t=i.selector(t)||t),t=t||[],o(t)&&(t=m(t)),n=n||0,0>n&&(t=m(t),t.reverse(),n*=-1),p=0;t.length>p;p++)u=c(r),u.startAt&&(u.startAt=c(u.startAt),u.startAt.cycle&&f(u.startAt,t,p)),g&&f(u,t,p),d.to(t[p],e,u,p*n);return this.add(d,a)},d.staggerFrom=function(t,e,i,s,r,n,a,o){return i.immediateRender=0!=i.immediateRender,i.runBackwards=!0,this.staggerTo(t,e,i,s,r,n,a,o)},d.staggerFromTo=function(t,e,i,s,r,n,a,o,l){return s.startAt=i,s.immediateRender=0!=s.immediateRender&&0!=i.immediateRender,this.staggerTo(t,e,s,r,n,a,o,l)},d.call=function(t,e,s,r){return this.add(i.delayedCall(0,t,e,s),r)},d.set=function(t,e,s){return s=this._parseTimeOrLabel(s,0,!0),null==e.immediateRender&&(e.immediateRender=s===this._time&&!this._paused),this.add(new i(t,0,e),s)},s.exportRoot=function(t,e){t=t||{},null==t.smoothChildTiming&&(t.smoothChildTiming=!0);var r,n,a=new s(t),o=a._timeline;for(null==e&&(e=!0),o._remove(a,!0),a._startTime=0,a._rawPrevTime=a._time=a._totalTime=o._time,r=o._first;r;)n=r._next,e&&r instanceof i&&r.target===r.vars.onComplete||a.add(r,r._startTime-r._delay),r=n;return o.add(a,0),a},d.add=function(r,n,a,o){var h,_,u,c,f,p;if("number"!=typeof n&&(n=this._parseTimeOrLabel(n,0,!0,r)),!(r instanceof t)){if(r instanceof Array||r&&r.push&&l(r)){for(a=a||"normal",o=o||0,h=n,_=r.length,u=0;_>u;u++)l(c=r[u])&&(c=new s({tweens:c})),this.add(c,h),"string"!=typeof c&&"function"!=typeof c&&("sequence"===a?h=c._startTime+c.totalDuration()/c._timeScale:"start"===a&&(c._startTime-=c.delay())),h+=o;return this._uncache(!0)}if("string"==typeof r)return this.addLabel(r,n);if("function"!=typeof r)throw"Cannot add "+r+" into the timeline; it is not a tween, timeline, function, or string.";r=i.delayedCall(0,r)}if(e.prototype.add.call(this,r,n),(this._gc||this._time===this._duration)&&!this._paused&&this._duration<this.duration())for(f=this,p=f.rawTime()>r._startTime;f._timeline;)p&&f._timeline.smoothChildTiming?f.totalTime(f._totalTime,!0):f._gc&&f._enabled(!0,!1),f=f._timeline;return this},d.remove=function(e){if(e instanceof t){this._remove(e,!1);var i=e._timeline=e.vars.useFrames?t._rootFramesTimeline:t._rootTimeline;return e._startTime=(e._paused?e._pauseTime:i._time)-(e._reversed?e.totalDuration()-e._totalTime:e._totalTime)/e._timeScale,this}if(e instanceof Array||e&&e.push&&l(e)){for(var s=e.length;--s>-1;)this.remove(e[s]);return this}return"string"==typeof e?this.removeLabel(e):this.kill(null,e)},d._remove=function(t,i){e.prototype._remove.call(this,t,i);var s=this._last;return s?this._time>s._startTime+s._totalDuration/s._timeScale&&(this._time=this.duration(),this._totalTime=this._totalDuration):this._time=this._totalTime=this._duration=this._totalDuration=0,this},d.append=function(t,e){return this.add(t,this._parseTimeOrLabel(null,e,!0,t))},d.insert=d.insertMultiple=function(t,e,i,s){return this.add(t,e||0,i,s)},d.appendMultiple=function(t,e,i,s){return this.add(t,this._parseTimeOrLabel(null,e,!0,t),i,s)},d.addLabel=function(t,e){return this._labels[t]=this._parseTimeOrLabel(e),this},d.addPause=function(t,e,s,r){var n=i.delayedCall(0,p,s,r||this);return n.vars.onComplete=n.vars.onReverseComplete=e,n.data="isPause",this._hasPause=!0,this.add(n,t)},d.removeLabel=function(t){return delete this._labels[t],this},d.getLabelTime=function(t){return null!=this._labels[t]?this._labels[t]:-1},d._parseTimeOrLabel=function(e,i,s,r){var n;if(r instanceof t&&r.timeline===this)this.remove(r);else if(r&&(r instanceof Array||r.push&&l(r)))for(n=r.length;--n>-1;)r[n]instanceof t&&r[n].timeline===this&&this.remove(r[n]);if("string"==typeof i)return this._parseTimeOrLabel(i,s&&"number"==typeof e&&null==this._labels[i]?e-this.duration():0,s);if(i=i||0,"string"!=typeof e||!isNaN(e)&&null==this._labels[e])null==e&&(e=this.duration());else{if(n=e.indexOf("="),-1===n)return null==this._labels[e]?s?this._labels[e]=this.duration()+i:i:this._labels[e]+i;i=parseInt(e.charAt(n-1)+"1",10)*Number(e.substr(n+1)),e=n>1?this._parseTimeOrLabel(e.substr(0,n-1),0,s):this.duration()}return Number(e)+i},d.seek=function(t,e){return this.totalTime("number"==typeof t?t:this._parseTimeOrLabel(t),e!==!1)},d.stop=function(){return this.paused(!0)},d.gotoAndPlay=function(t,e){return this.play(t,e)},d.gotoAndStop=function(t,e){return this.pause(t,e)},d.render=function(t,e,i){this._gc&&this._enabled(!0,!1);var s,n,a,o,l,u,c=this._dirty?this.totalDuration():this._totalDuration,f=this._time,p=this._startTime,m=this._timeScale,d=this._paused;if(t>=c)this._totalTime=this._time=c,this._reversed||this._hasPausedChild()||(n=!0,o="onComplete",l=!!this._timeline.autoRemoveChildren,0===this._duration&&(0===t||0>this._rawPrevTime||this._rawPrevTime===r)&&this._rawPrevTime!==t&&this._first&&(l=!0,this._rawPrevTime>r&&(o="onReverseComplete"))),this._rawPrevTime=this._duration||!e||t||this._rawPrevTime===t?t:r,t=c+1e-4;else if(1e-7>t)if(this._totalTime=this._time=0,(0!==f||0===this._duration&&this._rawPrevTime!==r&&(this._rawPrevTime>0||0>t&&this._rawPrevTime>=0))&&(o="onReverseComplete",n=this._reversed),0>t)this._active=!1,this._timeline.autoRemoveChildren&&this._reversed?(l=n=!0,o="onReverseComplete"):this._rawPrevTime>=0&&this._first&&(l=!0),this._rawPrevTime=t;else{if(this._rawPrevTime=this._duration||!e||t||this._rawPrevTime===t?t:r,0===t&&n)for(s=this._first;s&&0===s._startTime;)s._duration||(n=!1),s=s._next;t=0,this._initted||(l=!0)}else{if(this._hasPause&&!this._forcingPlayhead&&!e){if(t>=f)for(s=this._first;s&&t>=s._startTime&&!u;)s._duration||"isPause"!==s.data||s.ratio||0===s._startTime&&0===this._rawPrevTime||(u=s),s=s._next;else for(s=this._last;s&&s._startTime>=t&&!u;)s._duration||"isPause"===s.data&&s._rawPrevTime>0&&(u=s),s=s._prev;u&&(this._time=t=u._startTime,this._totalTime=t+this._cycle*(this._totalDuration+this._repeatDelay))}this._totalTime=this._time=this._rawPrevTime=t}if(this._time!==f&&this._first||i||l||u){if(this._initted||(this._initted=!0),this._active||!this._paused&&this._time!==f&&t>0&&(this._active=!0),0===f&&this.vars.onStart&&0!==this._time&&(e||this._callback("onStart")),this._time>=f)for(s=this._first;s&&(a=s._next,!this._paused||d);)(s._active||s._startTime<=this._time&&!s._paused&&!s._gc)&&(u===s&&this.pause(),s._reversed?s.render((s._dirty?s.totalDuration():s._totalDuration)-(t-s._startTime)*s._timeScale,e,i):s.render((t-s._startTime)*s._timeScale,e,i)),s=a;else for(s=this._last;s&&(a=s._prev,!this._paused||d);){if(s._active||f>=s._startTime&&!s._paused&&!s._gc){if(u===s){for(u=s._prev;u&&u.endTime()>this._time;)u.render(u._reversed?u.totalDuration()-(t-u._startTime)*u._timeScale:(t-u._startTime)*u._timeScale,e,i),u=u._prev;u=null,this.pause()}s._reversed?s.render((s._dirty?s.totalDuration():s._totalDuration)-(t-s._startTime)*s._timeScale,e,i):s.render((t-s._startTime)*s._timeScale,e,i)}s=a}this._onUpdate&&(e||(h.length&&_(),this._callback("onUpdate"))),o&&(this._gc||(p===this._startTime||m!==this._timeScale)&&(0===this._time||c>=this.totalDuration())&&(n&&(h.length&&_(),this._timeline.autoRemoveChildren&&this._enabled(!1,!1),this._active=!1),!e&&this.vars[o]&&this._callback(o)))}},d._hasPausedChild=function(){for(var t=this._first;t;){if(t._paused||t instanceof s&&t._hasPausedChild())return!0;t=t._next}return!1},d.getChildren=function(t,e,s,r){r=r||-9999999999;for(var n=[],a=this._first,o=0;a;)r>a._startTime||(a instanceof i?e!==!1&&(n[o++]=a):(s!==!1&&(n[o++]=a),t!==!1&&(n=n.concat(a.getChildren(!0,e,s)),o=n.length))),a=a._next;return n},d.getTweensOf=function(t,e){var s,r,n=this._gc,a=[],o=0;for(n&&this._enabled(!0,!0),s=i.getTweensOf(t),r=s.length;--r>-1;)(s[r].timeline===this||e&&this._contains(s[r]))&&(a[o++]=s[r]);return n&&this._enabled(!1,!0),a},d.recent=function(){return this._recent},d._contains=function(t){for(var e=t.timeline;e;){if(e===this)return!0;e=e.timeline}return!1},d.shiftChildren=function(t,e,i){i=i||0;for(var s,r=this._first,n=this._labels;r;)r._startTime>=i&&(r._startTime+=t),r=r._next;if(e)for(s in n)n[s]>=i&&(n[s]+=t);return this._uncache(!0)},d._kill=function(t,e){if(!t&&!e)return this._enabled(!1,!1);for(var i=e?this.getTweensOf(e):this.getChildren(!0,!0,!1),s=i.length,r=!1;--s>-1;)i[s]._kill(t,e)&&(r=!0);return r},d.clear=function(t){var e=this.getChildren(!1,!0,!0),i=e.length;for(this._time=this._totalTime=0;--i>-1;)e[i]._enabled(!1,!1);return t!==!1&&(this._labels={}),this._uncache(!0)},d.invalidate=function(){for(var e=this._first;e;)e.invalidate(),e=e._next;return t.prototype.invalidate.call(this)},d._enabled=function(t,i){if(t===this._gc)for(var s=this._first;s;)s._enabled(t,!0),s=s._next;return e.prototype._enabled.call(this,t,i)},d.totalTime=function(){this._forcingPlayhead=!0;var e=t.prototype.totalTime.apply(this,arguments);return this._forcingPlayhead=!1,e},d.duration=function(t){return arguments.length?(0!==this.duration()&&0!==t&&this.timeScale(this._duration/t),this):(this._dirty&&this.totalDuration(),this._duration)},d.totalDuration=function(t){if(!arguments.length){if(this._dirty){for(var e,i,s=0,r=this._last,n=999999999999;r;)e=r._prev,r._dirty&&r.totalDuration(),r._startTime>n&&this._sortChildren&&!r._paused?this.add(r,r._startTime-r._delay):n=r._startTime,0>r._startTime&&!r._paused&&(s-=r._startTime,this._timeline.smoothChildTiming&&(this._startTime+=r._startTime/this._timeScale),this.shiftChildren(-r._startTime,!1,-9999999999),n=0),i=r._startTime+r._totalDuration/r._timeScale,i>s&&(s=i),r=e;this._duration=this._totalDuration=s,this._dirty=!1}return this._totalDuration}return 0!==this.totalDuration()&&0!==t&&this.timeScale(this._totalDuration/t),this},d.paused=function(e){if(!e)for(var i=this._first,s=this._time;i;)i._startTime===s&&"isPause"===i.data&&(i._rawPrevTime=0),i=i._next;return t.prototype.paused.apply(this,arguments)},d.usesFrames=function(){for(var e=this._timeline;e._timeline;)e=e._timeline;return e===t._rootFramesTimeline},d.rawTime=function(){return this._paused?this._totalTime:(this._timeline.rawTime()-this._startTime)*this._timeScale},s},!0),_gsScope._gsDefine("TimelineMax",["TimelineLite","TweenLite","easing.Ease"],function(t,e,i){var s=function(e){t.call(this,e),this._repeat=this.vars.repeat||0,this._repeatDelay=this.vars.repeatDelay||0,this._cycle=0,this._yoyo=this.vars.yoyo===!0,this._dirty=!0},r=1e-10,n=e._internals,a=n.lazyTweens,o=n.lazyRender,l=new i(null,null,1,0),h=s.prototype=new t;return h.constructor=s,h.kill()._gc=!1,s.version="1.18.0",h.invalidate=function(){return this._yoyo=this.vars.yoyo===!0,this._repeat=this.vars.repeat||0,this._repeatDelay=this.vars.repeatDelay||0,this._uncache(!0),t.prototype.invalidate.call(this)},h.addCallback=function(t,i,s,r){return this.add(e.delayedCall(0,t,s,r),i)},h.removeCallback=function(t,e){if(t)if(null==e)this._kill(null,t);else for(var i=this.getTweensOf(t,!1),s=i.length,r=this._parseTimeOrLabel(e);--s>-1;)i[s]._startTime===r&&i[s]._enabled(!1,!1);return this},h.removePause=function(e){return this.removeCallback(t._internals.pauseCallback,e)},h.tweenTo=function(t,i){i=i||{};var s,r,n,a={ease:l,useFrames:this.usesFrames(),immediateRender:!1};for(r in i)a[r]=i[r];return a.time=this._parseTimeOrLabel(t),s=Math.abs(Number(a.time)-this._time)/this._timeScale||.001,n=new e(this,s,a),a.onStart=function(){n.target.paused(!0),n.vars.time!==n.target.time()&&s===n.duration()&&n.duration(Math.abs(n.vars.time-n.target.time())/n.target._timeScale),i.onStart&&n._callback("onStart")},n},h.tweenFromTo=function(t,e,i){i=i||{},t=this._parseTimeOrLabel(t),i.startAt={onComplete:this.seek,onCompleteParams:[t],callbackScope:this},i.immediateRender=i.immediateRender!==!1;var s=this.tweenTo(e,i);return s.duration(Math.abs(s.vars.time-t)/this._timeScale||.001)},h.render=function(t,e,i){this._gc&&this._enabled(!0,!1);var s,n,l,h,_,u,c,f=this._dirty?this.totalDuration():this._totalDuration,p=this._duration,m=this._time,d=this._totalTime,g=this._startTime,v=this._timeScale,y=this._rawPrevTime,T=this._paused,x=this._cycle;if(t>=f)this._locked||(this._totalTime=f,this._cycle=this._repeat),this._reversed||this._hasPausedChild()||(n=!0,h="onComplete",_=!!this._timeline.autoRemoveChildren,0===this._duration&&(0===t||0>y||y===r)&&y!==t&&this._first&&(_=!0,y>r&&(h="onReverseComplete"))),this._rawPrevTime=this._duration||!e||t||this._rawPrevTime===t?t:r,this._yoyo&&0!==(1&this._cycle)?this._time=t=0:(this._time=p,t=p+1e-4);else if(1e-7>t)if(this._locked||(this._totalTime=this._cycle=0),this._time=0,(0!==m||0===p&&y!==r&&(y>0||0>t&&y>=0)&&!this._locked)&&(h="onReverseComplete",n=this._reversed),0>t)this._active=!1,this._timeline.autoRemoveChildren&&this._reversed?(_=n=!0,h="onReverseComplete"):y>=0&&this._first&&(_=!0),this._rawPrevTime=t;else{if(this._rawPrevTime=p||!e||t||this._rawPrevTime===t?t:r,0===t&&n)for(s=this._first;s&&0===s._startTime;)s._duration||(n=!1),s=s._next;t=0,this._initted||(_=!0)}else if(0===p&&0>y&&(_=!0),this._time=this._rawPrevTime=t,this._locked||(this._totalTime=t,0!==this._repeat&&(u=p+this._repeatDelay,this._cycle=this._totalTime/u>>0,0!==this._cycle&&this._cycle===this._totalTime/u&&this._cycle--,this._time=this._totalTime-this._cycle*u,this._yoyo&&0!==(1&this._cycle)&&(this._time=p-this._time),this._time>p?(this._time=p,t=p+1e-4):0>this._time?this._time=t=0:t=this._time)),this._hasPause&&!this._forcingPlayhead&&!e){if(t=this._time,t>=m)for(s=this._first;s&&t>=s._startTime&&!c;)s._duration||"isPause"!==s.data||s.ratio||0===s._startTime&&0===this._rawPrevTime||(c=s),s=s._next;else for(s=this._last;s&&s._startTime>=t&&!c;)s._duration||"isPause"===s.data&&s._rawPrevTime>0&&(c=s),s=s._prev;c&&(this._time=t=c._startTime,this._totalTime=t+this._cycle*(this._totalDuration+this._repeatDelay))}if(this._cycle!==x&&!this._locked){var w=this._yoyo&&0!==(1&x),b=w===(this._yoyo&&0!==(1&this._cycle)),P=this._totalTime,k=this._cycle,S=this._rawPrevTime,R=this._time;if(this._totalTime=x*p,x>this._cycle?w=!w:this._totalTime+=p,this._time=m,this._rawPrevTime=0===p?y-1e-4:y,this._cycle=x,this._locked=!0,m=w?0:p,this.render(m,e,0===p),e||this._gc||this.vars.onRepeat&&this._callback("onRepeat"),b&&(m=w?p+1e-4:-1e-4,this.render(m,!0,!1)),this._locked=!1,this._paused&&!T)return;this._time=R,this._totalTime=P,this._cycle=k,this._rawPrevTime=S}if(!(this._time!==m&&this._first||i||_||c))return d!==this._totalTime&&this._onUpdate&&(e||this._callback("onUpdate")),void 0;if(this._initted||(this._initted=!0),this._active||!this._paused&&this._totalTime!==d&&t>0&&(this._active=!0),0===d&&this.vars.onStart&&0!==this._totalTime&&(e||this._callback("onStart")),this._time>=m)for(s=this._first;s&&(l=s._next,!this._paused||T);)(s._active||s._startTime<=this._time&&!s._paused&&!s._gc)&&(c===s&&this.pause(),s._reversed?s.render((s._dirty?s.totalDuration():s._totalDuration)-(t-s._startTime)*s._timeScale,e,i):s.render((t-s._startTime)*s._timeScale,e,i)),s=l;else for(s=this._last;s&&(l=s._prev,!this._paused||T);){if(s._active||m>=s._startTime&&!s._paused&&!s._gc){if(c===s){for(c=s._prev;c&&c.endTime()>this._time;)c.render(c._reversed?c.totalDuration()-(t-c._startTime)*c._timeScale:(t-c._startTime)*c._timeScale,e,i),c=c._prev;c=null,this.pause()}s._reversed?s.render((s._dirty?s.totalDuration():s._totalDuration)-(t-s._startTime)*s._timeScale,e,i):s.render((t-s._startTime)*s._timeScale,e,i)}s=l}this._onUpdate&&(e||(a.length&&o(),this._callback("onUpdate"))),h&&(this._locked||this._gc||(g===this._startTime||v!==this._timeScale)&&(0===this._time||f>=this.totalDuration())&&(n&&(a.length&&o(),this._timeline.autoRemoveChildren&&this._enabled(!1,!1),this._active=!1),!e&&this.vars[h]&&this._callback(h)))},h.getActive=function(t,e,i){null==t&&(t=!0),null==e&&(e=!0),null==i&&(i=!1);var s,r,n=[],a=this.getChildren(t,e,i),o=0,l=a.length;for(s=0;l>s;s++)r=a[s],r.isActive()&&(n[o++]=r);return n},h.getLabelAfter=function(t){t||0!==t&&(t=this._time);var e,i=this.getLabelsArray(),s=i.length;for(e=0;s>e;e++)if(i[e].time>t)return i[e].name;return null},h.getLabelBefore=function(t){null==t&&(t=this._time);for(var e=this.getLabelsArray(),i=e.length;--i>-1;)if(t>e[i].time)return e[i].name;return null},h.getLabelsArray=function(){var t,e=[],i=0;for(t in this._labels)e[i++]={time:this._labels[t],name:t};return e.sort(function(t,e){return t.time-e.time}),e},h.progress=function(t,e){return arguments.length?this.totalTime(this.duration()*(this._yoyo&&0!==(1&this._cycle)?1-t:t)+this._cycle*(this._duration+this._repeatDelay),e):this._time/this.duration()},h.totalProgress=function(t,e){return arguments.length?this.totalTime(this.totalDuration()*t,e):this._totalTime/this.totalDuration()},h.totalDuration=function(e){return arguments.length?-1===this._repeat?this:this.duration((e-this._repeat*this._repeatDelay)/(this._repeat+1)):(this._dirty&&(t.prototype.totalDuration.call(this),this._totalDuration=-1===this._repeat?999999999999:this._duration*(this._repeat+1)+this._repeatDelay*this._repeat),this._totalDuration)},h.time=function(t,e){return arguments.length?(this._dirty&&this.totalDuration(),t>this._duration&&(t=this._duration),this._yoyo&&0!==(1&this._cycle)?t=this._duration-t+this._cycle*(this._duration+this._repeatDelay):0!==this._repeat&&(t+=this._cycle*(this._duration+this._repeatDelay)),this.totalTime(t,e)):this._time},h.repeat=function(t){return arguments.length?(this._repeat=t,this._uncache(!0)):this._repeat},h.repeatDelay=function(t){return arguments.length?(this._repeatDelay=t,this._uncache(!0)):this._repeatDelay},h.yoyo=function(t){return arguments.length?(this._yoyo=t,this):this._yoyo},h.currentLabel=function(t){return arguments.length?this.seek(t,!0):this.getLabelBefore(this._time+1e-8)},s},!0),function(){var t=180/Math.PI,e=[],i=[],s=[],r={},n=_gsScope._gsDefine.globals,a=function(t,e,i,s){this.a=t,this.b=e,this.c=i,this.d=s,this.da=s-t,this.ca=i-t,this.ba=e-t},o=",x,y,z,left,top,right,bottom,marginTop,marginLeft,marginRight,marginBottom,paddingLeft,paddingTop,paddingRight,paddingBottom,backgroundPosition,backgroundPosition_y,",l=function(t,e,i,s){var r={a:t},n={},a={},o={c:s},l=(t+e)/2,h=(e+i)/2,_=(i+s)/2,u=(l+h)/2,c=(h+_)/2,f=(c-u)/8;return r.b=l+(t-l)/4,n.b=u+f,r.c=n.a=(r.b+n.b)/2,n.c=a.a=(u+c)/2,a.b=c-f,o.b=_+(s-_)/4,a.c=o.a=(a.b+o.b)/2,[r,n,a,o]},h=function(t,r,n,a,o){var h,_,u,c,f,p,m,d,g,v,y,T,x,w=t.length-1,b=0,P=t[0].a;for(h=0;w>h;h++)f=t[b],_=f.a,u=f.d,c=t[b+1].d,o?(y=e[h],T=i[h],x=.25*(T+y)*r/(a?.5:s[h]||.5),p=u-(u-_)*(a?.5*r:0!==y?x/y:0),m=u+(c-u)*(a?.5*r:0!==T?x/T:0),d=u-(p+((m-p)*(3*y/(y+T)+.5)/4||0))):(p=u-.5*(u-_)*r,m=u+.5*(c-u)*r,d=u-(p+m)/2),p+=d,m+=d,f.c=g=p,f.b=0!==h?P:P=f.a+.6*(f.c-f.a),f.da=u-_,f.ca=g-_,f.ba=P-_,n?(v=l(_,P,g,u),t.splice(b,1,v[0],v[1],v[2],v[3]),b+=4):b++,P=m;f=t[b],f.b=P,f.c=P+.4*(f.d-P),f.da=f.d-f.a,f.ca=f.c-f.a,f.ba=P-f.a,n&&(v=l(f.a,P,f.c,f.d),t.splice(b,1,v[0],v[1],v[2],v[3]))},_=function(t,s,r,n){var o,l,h,_,u,c,f=[];if(n)for(t=[n].concat(t),l=t.length;--l>-1;)"string"==typeof(c=t[l][s])&&"="===c.charAt(1)&&(t[l][s]=n[s]+Number(c.charAt(0)+c.substr(2)));if(o=t.length-2,0>o)return f[0]=new a(t[0][s],0,0,t[-1>o?0:1][s]),f;for(l=0;o>l;l++)h=t[l][s],_=t[l+1][s],f[l]=new a(h,0,0,_),r&&(u=t[l+2][s],e[l]=(e[l]||0)+(_-h)*(_-h),i[l]=(i[l]||0)+(u-_)*(u-_));return f[l]=new a(t[l][s],0,0,t[l+1][s]),f},u=function(t,n,a,l,u,c){var f,p,m,d,g,v,y,T,x={},w=[],b=c||t[0];u="string"==typeof u?","+u+",":o,null==n&&(n=1);for(p in t[0])w.push(p);if(t.length>1){for(T=t[t.length-1],y=!0,f=w.length;--f>-1;)if(p=w[f],Math.abs(b[p]-T[p])>.05){y=!1;break}y&&(t=t.concat(),c&&t.unshift(c),t.push(t[1]),c=t[t.length-3])}for(e.length=i.length=s.length=0,f=w.length;--f>-1;)p=w[f],r[p]=-1!==u.indexOf(","+p+","),x[p]=_(t,p,r[p],c);for(f=e.length;--f>-1;)e[f]=Math.sqrt(e[f]),i[f]=Math.sqrt(i[f]);if(!l){for(f=w.length;--f>-1;)if(r[p])for(m=x[w[f]],v=m.length-1,d=0;v>d;d++)g=m[d+1].da/i[d]+m[d].da/e[d],s[d]=(s[d]||0)+g*g;for(f=s.length;--f>-1;)s[f]=Math.sqrt(s[f])}for(f=w.length,d=a?4:1;--f>-1;)p=w[f],m=x[p],h(m,n,a,l,r[p]),y&&(m.splice(0,d),m.splice(m.length-d,d));return x},c=function(t,e,i){e=e||"soft";var s,r,n,o,l,h,_,u,c,f,p,m={},d="cubic"===e?3:2,g="soft"===e,v=[];if(g&&i&&(t=[i].concat(t)),null==t||d+1>t.length)throw"invalid Bezier data";for(c in t[0])v.push(c);for(h=v.length;--h>-1;){for(c=v[h],m[c]=l=[],f=0,u=t.length,_=0;u>_;_++)s=null==i?t[_][c]:"string"==typeof(p=t[_][c])&&"="===p.charAt(1)?i[c]+Number(p.charAt(0)+p.substr(2)):Number(p),g&&_>1&&u-1>_&&(l[f++]=(s+l[f-2])/2),l[f++]=s;for(u=f-d+1,f=0,_=0;u>_;_+=d)s=l[_],r=l[_+1],n=l[_+2],o=2===d?0:l[_+3],l[f++]=p=3===d?new a(s,r,n,o):new a(s,(2*r+s)/3,(2*r+n)/3,n);l.length=f}return m},f=function(t,e,i){for(var s,r,n,a,o,l,h,_,u,c,f,p=1/i,m=t.length;--m>-1;)for(c=t[m],n=c.a,a=c.d-n,o=c.c-n,l=c.b-n,s=r=0,_=1;i>=_;_++)h=p*_,u=1-h,s=r-(r=(h*h*a+3*u*(h*o+u*l))*h),f=m*i+_-1,e[f]=(e[f]||0)+s*s},p=function(t,e){e=e>>0||6;var i,s,r,n,a=[],o=[],l=0,h=0,_=e-1,u=[],c=[];for(i in t)f(t[i],a,e);for(r=a.length,s=0;r>s;s++)l+=Math.sqrt(a[s]),n=s%e,c[n]=l,n===_&&(h+=l,n=s/e>>0,u[n]=c,o[n]=h,l=0,c=[]);return{length:h,lengths:o,segments:u}},m=_gsScope._gsDefine.plugin({propName:"bezier",priority:-1,version:"1.3.4",API:2,global:!0,init:function(t,e,i){this._target=t,e instanceof Array&&(e={values:e}),this._func={},this._round={},this._props=[],this._timeRes=null==e.timeResolution?6:parseInt(e.timeResolution,10);var s,r,n,a,o,l=e.values||[],h={},_=l[0],f=e.autoRotate||i.vars.orientToBezier;this._autoRotate=f?f instanceof Array?f:[["x","y","rotation",f===!0?0:Number(f)||0]]:null;
for(s in _)this._props.push(s);for(n=this._props.length;--n>-1;)s=this._props[n],this._overwriteProps.push(s),r=this._func[s]="function"==typeof t[s],h[s]=r?t[s.indexOf("set")||"function"!=typeof t["get"+s.substr(3)]?s:"get"+s.substr(3)]():parseFloat(t[s]),o||h[s]!==l[0][s]&&(o=h);if(this._beziers="cubic"!==e.type&&"quadratic"!==e.type&&"soft"!==e.type?u(l,isNaN(e.curviness)?1:e.curviness,!1,"thruBasic"===e.type,e.correlate,o):c(l,e.type,h),this._segCount=this._beziers[s].length,this._timeRes){var m=p(this._beziers,this._timeRes);this._length=m.length,this._lengths=m.lengths,this._segments=m.segments,this._l1=this._li=this._s1=this._si=0,this._l2=this._lengths[0],this._curSeg=this._segments[0],this._s2=this._curSeg[0],this._prec=1/this._curSeg.length}if(f=this._autoRotate)for(this._initialRotations=[],f[0]instanceof Array||(this._autoRotate=f=[f]),n=f.length;--n>-1;){for(a=0;3>a;a++)s=f[n][a],this._func[s]="function"==typeof t[s]?t[s.indexOf("set")||"function"!=typeof t["get"+s.substr(3)]?s:"get"+s.substr(3)]:!1;s=f[n][2],this._initialRotations[n]=this._func[s]?this._func[s].call(this._target):this._target[s]}return this._startRatio=i.vars.runBackwards?1:0,!0},set:function(e){var i,s,r,n,a,o,l,h,_,u,c=this._segCount,f=this._func,p=this._target,m=e!==this._startRatio;if(this._timeRes){if(_=this._lengths,u=this._curSeg,e*=this._length,r=this._li,e>this._l2&&c-1>r){for(h=c-1;h>r&&e>=(this._l2=_[++r]););this._l1=_[r-1],this._li=r,this._curSeg=u=this._segments[r],this._s2=u[this._s1=this._si=0]}else if(this._l1>e&&r>0){for(;r>0&&(this._l1=_[--r])>=e;);0===r&&this._l1>e?this._l1=0:r++,this._l2=_[r],this._li=r,this._curSeg=u=this._segments[r],this._s1=u[(this._si=u.length-1)-1]||0,this._s2=u[this._si]}if(i=r,e-=this._l1,r=this._si,e>this._s2&&u.length-1>r){for(h=u.length-1;h>r&&e>=(this._s2=u[++r]););this._s1=u[r-1],this._si=r}else if(this._s1>e&&r>0){for(;r>0&&(this._s1=u[--r])>=e;);0===r&&this._s1>e?this._s1=0:r++,this._s2=u[r],this._si=r}o=(r+(e-this._s1)/(this._s2-this._s1))*this._prec}else i=0>e?0:e>=1?c-1:c*e>>0,o=(e-i*(1/c))*c;for(s=1-o,r=this._props.length;--r>-1;)n=this._props[r],a=this._beziers[n][i],l=(o*o*a.da+3*s*(o*a.ca+s*a.ba))*o+a.a,this._round[n]&&(l=Math.round(l)),f[n]?p[n](l):p[n]=l;if(this._autoRotate){var d,g,v,y,T,x,w,b=this._autoRotate;for(r=b.length;--r>-1;)n=b[r][2],x=b[r][3]||0,w=b[r][4]===!0?1:t,a=this._beziers[b[r][0]],d=this._beziers[b[r][1]],a&&d&&(a=a[i],d=d[i],g=a.a+(a.b-a.a)*o,y=a.b+(a.c-a.b)*o,g+=(y-g)*o,y+=(a.c+(a.d-a.c)*o-y)*o,v=d.a+(d.b-d.a)*o,T=d.b+(d.c-d.b)*o,v+=(T-v)*o,T+=(d.c+(d.d-d.c)*o-T)*o,l=m?Math.atan2(T-v,y-g)*w+x:this._initialRotations[r],f[n]?p[n](l):p[n]=l)}}}),d=m.prototype;m.bezierThrough=u,m.cubicToQuadratic=l,m._autoCSS=!0,m.quadraticToCubic=function(t,e,i){return new a(t,(2*e+t)/3,(2*e+i)/3,i)},m._cssRegister=function(){var t=n.CSSPlugin;if(t){var e=t._internals,i=e._parseToProxy,s=e._setPluginRatio,r=e.CSSPropTween;e._registerComplexSpecialProp("bezier",{parser:function(t,e,n,a,o,l){e instanceof Array&&(e={values:e}),l=new m;var h,_,u,c=e.values,f=c.length-1,p=[],d={};if(0>f)return o;for(h=0;f>=h;h++)u=i(t,c[h],a,o,l,f!==h),p[h]=u.end;for(_ in e)d[_]=e[_];return d.values=p,o=new r(t,"bezier",0,0,u.pt,2),o.data=u,o.plugin=l,o.setRatio=s,0===d.autoRotate&&(d.autoRotate=!0),!d.autoRotate||d.autoRotate instanceof Array||(h=d.autoRotate===!0?0:Number(d.autoRotate),d.autoRotate=null!=u.end.left?[["left","top","rotation",h,!1]]:null!=u.end.x?[["x","y","rotation",h,!1]]:!1),d.autoRotate&&(a._transform||a._enableTransforms(!1),u.autoRotate=a._target._gsTransform),l._onInitTween(u.proxy,d,a._tween),o}})}},d._roundProps=function(t,e){for(var i=this._overwriteProps,s=i.length;--s>-1;)(t[i[s]]||t.bezier||t.bezierThrough)&&(this._round[i[s]]=e)},d._kill=function(t){var e,i,s=this._props;for(e in this._beziers)if(e in t)for(delete this._beziers[e],delete this._func[e],i=s.length;--i>-1;)s[i]===e&&s.splice(i,1);return this._super._kill.call(this,t)}}(),_gsScope._gsDefine("plugins.CSSPlugin",["plugins.TweenPlugin","TweenLite"],function(t,e){var i,s,r,n,a=function(){t.call(this,"css"),this._overwriteProps.length=0,this.setRatio=a.prototype.setRatio},o=_gsScope._gsDefine.globals,l={},h=a.prototype=new t("css");h.constructor=a,a.version="1.18.0",a.API=2,a.defaultTransformPerspective=0,a.defaultSkewType="compensated",a.defaultSmoothOrigin=!0,h="px",a.suffixMap={top:h,right:h,bottom:h,left:h,width:h,height:h,fontSize:h,padding:h,margin:h,perspective:h,lineHeight:""};var _,u,c,f,p,m,d=/(?:\d|\-\d|\.\d|\-\.\d)+/g,g=/(?:\d|\-\d|\.\d|\-\.\d|\+=\d|\-=\d|\+=.\d|\-=\.\d)+/g,v=/(?:\+=|\-=|\-|\b)[\d\-\.]+[a-zA-Z0-9]*(?:%|\b)/gi,y=/(?![+-]?\d*\.?\d+|[+-]|e[+-]\d+)[^0-9]/g,T=/(?:\d|\-|\+|=|#|\.)*/g,x=/opacity *= *([^)]*)/i,w=/opacity:([^;]*)/i,b=/alpha\(opacity *=.+?\)/i,P=/^(rgb|hsl)/,k=/([A-Z])/g,S=/-([a-z])/gi,R=/(^(?:url\(\"|url\())|(?:(\"\))$|\)$)/gi,O=function(t,e){return e.toUpperCase()},A=/(?:Left|Right|Width)/i,C=/(M11|M12|M21|M22)=[\d\-\.e]+/gi,D=/progid\:DXImageTransform\.Microsoft\.Matrix\(.+?\)/i,M=/,(?=[^\)]*(?:\(|$))/gi,z=Math.PI/180,F=180/Math.PI,I={},E=document,N=function(t){return E.createElementNS?E.createElementNS("http://www.w3.org/1999/xhtml",t):E.createElement(t)},L=N("div"),X=N("img"),B=a._internals={_specialProps:l},j=navigator.userAgent,Y=function(){var t=j.indexOf("Android"),e=N("a");return c=-1!==j.indexOf("Safari")&&-1===j.indexOf("Chrome")&&(-1===t||Number(j.substr(t+8,1))>3),p=c&&6>Number(j.substr(j.indexOf("Version/")+8,1)),f=-1!==j.indexOf("Firefox"),(/MSIE ([0-9]{1,}[\.0-9]{0,})/.exec(j)||/Trident\/.*rv:([0-9]{1,}[\.0-9]{0,})/.exec(j))&&(m=parseFloat(RegExp.$1)),e?(e.style.cssText="top:1px;opacity:.55;",/^0.55/.test(e.style.opacity)):!1}(),U=function(t){return x.test("string"==typeof t?t:(t.currentStyle?t.currentStyle.filter:t.style.filter)||"")?parseFloat(RegExp.$1)/100:1},q=function(t){window.console&&console.log(t)},V="",G="",W=function(t,e){e=e||L;var i,s,r=e.style;if(void 0!==r[t])return t;for(t=t.charAt(0).toUpperCase()+t.substr(1),i=["O","Moz","ms","Ms","Webkit"],s=5;--s>-1&&void 0===r[i[s]+t];);return s>=0?(G=3===s?"ms":i[s],V="-"+G.toLowerCase()+"-",G+t):null},Z=E.defaultView?E.defaultView.getComputedStyle:function(){},Q=a.getStyle=function(t,e,i,s,r){var n;return Y||"opacity"!==e?(!s&&t.style[e]?n=t.style[e]:(i=i||Z(t))?n=i[e]||i.getPropertyValue(e)||i.getPropertyValue(e.replace(k,"-$1").toLowerCase()):t.currentStyle&&(n=t.currentStyle[e]),null==r||n&&"none"!==n&&"auto"!==n&&"auto auto"!==n?n:r):U(t)},$=B.convertToPixels=function(t,i,s,r,n){if("px"===r||!r)return s;if("auto"===r||!s)return 0;var o,l,h,_=A.test(i),u=t,c=L.style,f=0>s;if(f&&(s=-s),"%"===r&&-1!==i.indexOf("border"))o=s/100*(_?t.clientWidth:t.clientHeight);else{if(c.cssText="border:0 solid red;position:"+Q(t,"position")+";line-height:0;","%"!==r&&u.appendChild&&"v"!==r.charAt(0)&&"rem"!==r)c[_?"borderLeftWidth":"borderTopWidth"]=s+r;else{if(u=t.parentNode||E.body,l=u._gsCache,h=e.ticker.frame,l&&_&&l.time===h)return l.width*s/100;c[_?"width":"height"]=s+r}u.appendChild(L),o=parseFloat(L[_?"offsetWidth":"offsetHeight"]),u.removeChild(L),_&&"%"===r&&a.cacheWidths!==!1&&(l=u._gsCache=u._gsCache||{},l.time=h,l.width=100*(o/s)),0!==o||n||(o=$(t,i,s,r,!0))}return f?-o:o},H=B.calculateOffset=function(t,e,i){if("absolute"!==Q(t,"position",i))return 0;var s="left"===e?"Left":"Top",r=Q(t,"margin"+s,i);return t["offset"+s]-($(t,e,parseFloat(r),r.replace(T,""))||0)},K=function(t,e){var i,s,r,n={};if(e=e||Z(t,null))if(i=e.length)for(;--i>-1;)r=e[i],(-1===r.indexOf("-transform")||ke===r)&&(n[r.replace(S,O)]=e.getPropertyValue(r));else for(i in e)(-1===i.indexOf("Transform")||Pe===i)&&(n[i]=e[i]);else if(e=t.currentStyle||t.style)for(i in e)"string"==typeof i&&void 0===n[i]&&(n[i.replace(S,O)]=e[i]);return Y||(n.opacity=U(t)),s=Ne(t,e,!1),n.rotation=s.rotation,n.skewX=s.skewX,n.scaleX=s.scaleX,n.scaleY=s.scaleY,n.x=s.x,n.y=s.y,Re&&(n.z=s.z,n.rotationX=s.rotationX,n.rotationY=s.rotationY,n.scaleZ=s.scaleZ),n.filters&&delete n.filters,n},J=function(t,e,i,s,r){var n,a,o,l={},h=t.style;for(a in i)"cssText"!==a&&"length"!==a&&isNaN(a)&&(e[a]!==(n=i[a])||r&&r[a])&&-1===a.indexOf("Origin")&&("number"==typeof n||"string"==typeof n)&&(l[a]="auto"!==n||"left"!==a&&"top"!==a?""!==n&&"auto"!==n&&"none"!==n||"string"!=typeof e[a]||""===e[a].replace(y,"")?n:0:H(t,a),void 0!==h[a]&&(o=new pe(h,a,h[a],o)));if(s)for(a in s)"className"!==a&&(l[a]=s[a]);return{difs:l,firstMPT:o}},te={width:["Left","Right"],height:["Top","Bottom"]},ee=["marginLeft","marginRight","marginTop","marginBottom"],ie=function(t,e,i){var s=parseFloat("width"===e?t.offsetWidth:t.offsetHeight),r=te[e],n=r.length;for(i=i||Z(t,null);--n>-1;)s-=parseFloat(Q(t,"padding"+r[n],i,!0))||0,s-=parseFloat(Q(t,"border"+r[n]+"Width",i,!0))||0;return s},se=function(t,e){if("contain"===t||"auto"===t||"auto auto"===t)return t+" ";(null==t||""===t)&&(t="0 0");var i=t.split(" "),s=-1!==t.indexOf("left")?"0%":-1!==t.indexOf("right")?"100%":i[0],r=-1!==t.indexOf("top")?"0%":-1!==t.indexOf("bottom")?"100%":i[1];return null==r?r="center"===s?"50%":"0":"center"===r&&(r="50%"),("center"===s||isNaN(parseFloat(s))&&-1===(s+"").indexOf("="))&&(s="50%"),t=s+" "+r+(i.length>2?" "+i[2]:""),e&&(e.oxp=-1!==s.indexOf("%"),e.oyp=-1!==r.indexOf("%"),e.oxr="="===s.charAt(1),e.oyr="="===r.charAt(1),e.ox=parseFloat(s.replace(y,"")),e.oy=parseFloat(r.replace(y,"")),e.v=t),e||t},re=function(t,e){return"string"==typeof t&&"="===t.charAt(1)?parseInt(t.charAt(0)+"1",10)*parseFloat(t.substr(2)):parseFloat(t)-parseFloat(e)},ne=function(t,e){return null==t?e:"string"==typeof t&&"="===t.charAt(1)?parseInt(t.charAt(0)+"1",10)*parseFloat(t.substr(2))+e:parseFloat(t)},ae=function(t,e,i,s){var r,n,a,o,l,h=1e-6;return null==t?o=e:"number"==typeof t?o=t:(r=360,n=t.split("_"),l="="===t.charAt(1),a=(l?parseInt(t.charAt(0)+"1",10)*parseFloat(n[0].substr(2)):parseFloat(n[0]))*(-1===t.indexOf("rad")?1:F)-(l?0:e),n.length&&(s&&(s[i]=e+a),-1!==t.indexOf("short")&&(a%=r,a!==a%(r/2)&&(a=0>a?a+r:a-r)),-1!==t.indexOf("_cw")&&0>a?a=(a+9999999999*r)%r-(0|a/r)*r:-1!==t.indexOf("ccw")&&a>0&&(a=(a-9999999999*r)%r-(0|a/r)*r)),o=e+a),h>o&&o>-h&&(o=0),o},oe={aqua:[0,255,255],lime:[0,255,0],silver:[192,192,192],black:[0,0,0],maroon:[128,0,0],teal:[0,128,128],blue:[0,0,255],navy:[0,0,128],white:[255,255,255],fuchsia:[255,0,255],olive:[128,128,0],yellow:[255,255,0],orange:[255,165,0],gray:[128,128,128],purple:[128,0,128],green:[0,128,0],red:[255,0,0],pink:[255,192,203],cyan:[0,255,255],transparent:[255,255,255,0]},le=function(t,e,i){return t=0>t?t+1:t>1?t-1:t,0|255*(1>6*t?e+6*(i-e)*t:.5>t?i:2>3*t?e+6*(i-e)*(2/3-t):e)+.5},he=a.parseColor=function(t,e){var i,s,r,n,a,o,l,h,_,u,c;if(t)if("number"==typeof t)i=[t>>16,255&t>>8,255&t];else{if(","===t.charAt(t.length-1)&&(t=t.substr(0,t.length-1)),oe[t])i=oe[t];else if("#"===t.charAt(0))4===t.length&&(s=t.charAt(1),r=t.charAt(2),n=t.charAt(3),t="#"+s+s+r+r+n+n),t=parseInt(t.substr(1),16),i=[t>>16,255&t>>8,255&t];else if("hsl"===t.substr(0,3))if(i=c=t.match(d),e){if(-1!==t.indexOf("="))return t.match(g)}else a=Number(i[0])%360/360,o=Number(i[1])/100,l=Number(i[2])/100,r=.5>=l?l*(o+1):l+o-l*o,s=2*l-r,i.length>3&&(i[3]=Number(t[3])),i[0]=le(a+1/3,s,r),i[1]=le(a,s,r),i[2]=le(a-1/3,s,r);else i=t.match(d)||oe.transparent;i[0]=Number(i[0]),i[1]=Number(i[1]),i[2]=Number(i[2]),i.length>3&&(i[3]=Number(i[3]))}else i=oe.black;return e&&!c&&(s=i[0]/255,r=i[1]/255,n=i[2]/255,h=Math.max(s,r,n),_=Math.min(s,r,n),l=(h+_)/2,h===_?a=o=0:(u=h-_,o=l>.5?u/(2-h-_):u/(h+_),a=h===s?(r-n)/u+(n>r?6:0):h===r?(n-s)/u+2:(s-r)/u+4,a*=60),i[0]=0|a+.5,i[1]=0|100*o+.5,i[2]=0|100*l+.5),i},_e=function(t,e){var i,s,r,n=t.match(ue)||[],a=0,o=n.length?"":t;for(i=0;n.length>i;i++)s=n[i],r=t.substr(a,t.indexOf(s,a)-a),a+=r.length+s.length,s=he(s,e),3===s.length&&s.push(1),o+=r+(e?"hsla("+s[0]+","+s[1]+"%,"+s[2]+"%,"+s[3]:"rgba("+s.join(","))+")";return o},ue="(?:\\b(?:(?:rgb|rgba|hsl|hsla)\\(.+?\\))|\\B#.+?\\b";for(h in oe)ue+="|"+h+"\\b";ue=RegExp(ue+")","gi"),a.colorStringFilter=function(t){var e,i=t[0]+t[1];ue.lastIndex=0,ue.test(i)&&(e=-1!==i.indexOf("hsl(")||-1!==i.indexOf("hsla("),t[0]=_e(t[0],e),t[1]=_e(t[1],e))},e.defaultStringFilter||(e.defaultStringFilter=a.colorStringFilter);var ce=function(t,e,i,s){if(null==t)return function(t){return t};var r,n=e?(t.match(ue)||[""])[0]:"",a=t.split(n).join("").match(v)||[],o=t.substr(0,t.indexOf(a[0])),l=")"===t.charAt(t.length-1)?")":"",h=-1!==t.indexOf(" ")?" ":",",_=a.length,u=_>0?a[0].replace(d,""):"";return _?r=e?function(t){var e,c,f,p;if("number"==typeof t)t+=u;else if(s&&M.test(t)){for(p=t.replace(M,"|").split("|"),f=0;p.length>f;f++)p[f]=r(p[f]);return p.join(",")}if(e=(t.match(ue)||[n])[0],c=t.split(e).join("").match(v)||[],f=c.length,_>f--)for(;_>++f;)c[f]=i?c[0|(f-1)/2]:a[f];return o+c.join(h)+h+e+l+(-1!==t.indexOf("inset")?" inset":"")}:function(t){var e,n,c;if("number"==typeof t)t+=u;else if(s&&M.test(t)){for(n=t.replace(M,"|").split("|"),c=0;n.length>c;c++)n[c]=r(n[c]);return n.join(",")}if(e=t.match(v)||[],c=e.length,_>c--)for(;_>++c;)e[c]=i?e[0|(c-1)/2]:a[c];return o+e.join(h)+l}:function(t){return t}},fe=function(t){return t=t.split(","),function(e,i,s,r,n,a,o){var l,h=(i+"").split(" ");for(o={},l=0;4>l;l++)o[t[l]]=h[l]=h[l]||h[(l-1)/2>>0];return r.parse(e,o,n,a)}},pe=(B._setPluginRatio=function(t){this.plugin.setRatio(t);for(var e,i,s,r,n=this.data,a=n.proxy,o=n.firstMPT,l=1e-6;o;)e=a[o.v],o.r?e=Math.round(e):l>e&&e>-l&&(e=0),o.t[o.p]=e,o=o._next;if(n.autoRotate&&(n.autoRotate.rotation=a.rotation),1===t)for(o=n.firstMPT;o;){if(i=o.t,i.type){if(1===i.type){for(r=i.xs0+i.s+i.xs1,s=1;i.l>s;s++)r+=i["xn"+s]+i["xs"+(s+1)];i.e=r}}else i.e=i.s+i.xs0;o=o._next}},function(t,e,i,s,r){this.t=t,this.p=e,this.v=i,this.r=r,s&&(s._prev=this,this._next=s)}),me=(B._parseToProxy=function(t,e,i,s,r,n){var a,o,l,h,_,u=s,c={},f={},p=i._transform,m=I;for(i._transform=null,I=e,s=_=i.parse(t,e,s,r),I=m,n&&(i._transform=p,u&&(u._prev=null,u._prev&&(u._prev._next=null)));s&&s!==u;){if(1>=s.type&&(o=s.p,f[o]=s.s+s.c,c[o]=s.s,n||(h=new pe(s,"s",o,h,s.r),s.c=0),1===s.type))for(a=s.l;--a>0;)l="xn"+a,o=s.p+"_"+l,f[o]=s.data[l],c[o]=s[l],n||(h=new pe(s,l,o,h,s.rxp[l]));s=s._next}return{proxy:c,end:f,firstMPT:h,pt:_}},B.CSSPropTween=function(t,e,s,r,a,o,l,h,_,u,c){this.t=t,this.p=e,this.s=s,this.c=r,this.n=l||e,t instanceof me||n.push(this.n),this.r=h,this.type=o||0,_&&(this.pr=_,i=!0),this.b=void 0===u?s:u,this.e=void 0===c?s+r:c,a&&(this._next=a,a._prev=this)}),de=function(t,e,i,s,r,n){var a=new me(t,e,i,s-i,r,-1,n);return a.b=i,a.e=a.xs0=s,a},ge=a.parseComplex=function(t,e,i,s,r,n,a,o,l,h){i=i||n||"",a=new me(t,e,0,0,a,h?2:1,null,!1,o,i,s),s+="";var u,c,f,p,m,v,y,T,x,w,b,P,k,S=i.split(", ").join(",").split(" "),R=s.split(", ").join(",").split(" "),O=S.length,A=_!==!1;for((-1!==s.indexOf(",")||-1!==i.indexOf(","))&&(S=S.join(" ").replace(M,", ").split(" "),R=R.join(" ").replace(M,", ").split(" "),O=S.length),O!==R.length&&(S=(n||"").split(" "),O=S.length),a.plugin=l,a.setRatio=h,ue.lastIndex=0,u=0;O>u;u++)if(p=S[u],m=R[u],T=parseFloat(p),T||0===T)a.appendXtra("",T,re(m,T),m.replace(g,""),A&&-1!==m.indexOf("px"),!0);else if(r&&ue.test(p))P=","===m.charAt(m.length-1)?"),":")",k=-1!==m.indexOf("hsl")&&Y,p=he(p,k),m=he(m,k),x=p.length+m.length>6,x&&!Y&&0===m[3]?(a["xs"+a.l]+=a.l?" transparent":"transparent",a.e=a.e.split(R[u]).join("transparent")):(Y||(x=!1),k?a.appendXtra(x?"hsla(":"hsl(",p[0],re(m[0],p[0]),",",!1,!0).appendXtra("",p[1],re(m[1],p[1]),"%,",!1).appendXtra("",p[2],re(m[2],p[2]),x?"%,":"%"+P,!1):a.appendXtra(x?"rgba(":"rgb(",p[0],m[0]-p[0],",",!0,!0).appendXtra("",p[1],m[1]-p[1],",",!0).appendXtra("",p[2],m[2]-p[2],x?",":P,!0),x&&(p=4>p.length?1:p[3],a.appendXtra("",p,(4>m.length?1:m[3])-p,P,!1))),ue.lastIndex=0;else if(v=p.match(d)){if(y=m.match(g),!y||y.length!==v.length)return a;for(f=0,c=0;v.length>c;c++)b=v[c],w=p.indexOf(b,f),a.appendXtra(p.substr(f,w-f),Number(b),re(y[c],b),"",A&&"px"===p.substr(w+b.length,2),0===c),f=w+b.length;a["xs"+a.l]+=p.substr(f)}else a["xs"+a.l]+=a.l?" "+p:p;if(-1!==s.indexOf("=")&&a.data){for(P=a.xs0+a.data.s,u=1;a.l>u;u++)P+=a["xs"+u]+a.data["xn"+u];a.e=P+a["xs"+u]}return a.l||(a.type=-1,a.xs0=a.e),a.xfirst||a},ve=9;for(h=me.prototype,h.l=h.pr=0;--ve>0;)h["xn"+ve]=0,h["xs"+ve]="";h.xs0="",h._next=h._prev=h.xfirst=h.data=h.plugin=h.setRatio=h.rxp=null,h.appendXtra=function(t,e,i,s,r,n){var a=this,o=a.l;return a["xs"+o]+=n&&o?" "+t:t||"",i||0===o||a.plugin?(a.l++,a.type=a.setRatio?2:1,a["xs"+a.l]=s||"",o>0?(a.data["xn"+o]=e+i,a.rxp["xn"+o]=r,a["xn"+o]=e,a.plugin||(a.xfirst=new me(a,"xn"+o,e,i,a.xfirst||a,0,a.n,r,a.pr),a.xfirst.xs0=0),a):(a.data={s:e+i},a.rxp={},a.s=e,a.c=i,a.r=r,a)):(a["xs"+o]+=e+(s||""),a)};var ye=function(t,e){e=e||{},this.p=e.prefix?W(t)||t:t,l[t]=l[this.p]=this,this.format=e.formatter||ce(e.defaultValue,e.color,e.collapsible,e.multi),e.parser&&(this.parse=e.parser),this.clrs=e.color,this.multi=e.multi,this.keyword=e.keyword,this.dflt=e.defaultValue,this.pr=e.priority||0},Te=B._registerComplexSpecialProp=function(t,e,i){"object"!=typeof e&&(e={parser:i});var s,r,n=t.split(","),a=e.defaultValue;for(i=i||[a],s=0;n.length>s;s++)e.prefix=0===s&&e.prefix,e.defaultValue=i[s]||a,r=new ye(n[s],e)},xe=function(t){if(!l[t]){var e=t.charAt(0).toUpperCase()+t.substr(1)+"Plugin";Te(t,{parser:function(t,i,s,r,n,a,h){var _=o.com.greensock.plugins[e];return _?(_._cssRegister(),l[s].parse(t,i,s,r,n,a,h)):(q("Error: "+e+" js file not loaded."),n)}})}};h=ye.prototype,h.parseComplex=function(t,e,i,s,r,n){var a,o,l,h,_,u,c=this.keyword;if(this.multi&&(M.test(i)||M.test(e)?(o=e.replace(M,"|").split("|"),l=i.replace(M,"|").split("|")):c&&(o=[e],l=[i])),l){for(h=l.length>o.length?l.length:o.length,a=0;h>a;a++)e=o[a]=o[a]||this.dflt,i=l[a]=l[a]||this.dflt,c&&(_=e.indexOf(c),u=i.indexOf(c),_!==u&&(-1===u?o[a]=o[a].split(c).join(""):-1===_&&(o[a]+=" "+c)));e=o.join(", "),i=l.join(", ")}return ge(t,this.p,e,i,this.clrs,this.dflt,s,this.pr,r,n)},h.parse=function(t,e,i,s,n,a){return this.parseComplex(t.style,this.format(Q(t,this.p,r,!1,this.dflt)),this.format(e),n,a)},a.registerSpecialProp=function(t,e,i){Te(t,{parser:function(t,s,r,n,a,o){var l=new me(t,r,0,0,a,2,r,!1,i);return l.plugin=o,l.setRatio=e(t,s,n._tween,r),l},priority:i})},a.useSVGTransformAttr=c||f;var we,be="scaleX,scaleY,scaleZ,x,y,z,skewX,skewY,rotation,rotationX,rotationY,perspective,xPercent,yPercent".split(","),Pe=W("transform"),ke=V+"transform",Se=W("transformOrigin"),Re=null!==W("perspective"),Oe=B.Transform=function(){this.perspective=parseFloat(a.defaultTransformPerspective)||0,this.force3D=a.defaultForce3D!==!1&&Re?a.defaultForce3D||"auto":!1},Ae=window.SVGElement,Ce=function(t,e,i){var s,r=E.createElementNS("http://www.w3.org/2000/svg",t),n=/([a-z])([A-Z])/g;for(s in i)r.setAttributeNS(null,s.replace(n,"$1-$2").toLowerCase(),i[s]);return e.appendChild(r),r},De=E.documentElement,Me=function(){var t,e,i,s=m||/Android/i.test(j)&&!window.chrome;return E.createElementNS&&!s&&(t=Ce("svg",De),e=Ce("rect",t,{width:100,height:50,x:100}),i=e.getBoundingClientRect().width,e.style[Se]="50% 50%",e.style[Pe]="scaleX(0.5)",s=i===e.getBoundingClientRect().width&&!(f&&Re),De.removeChild(t)),s}(),ze=function(t,e,i,s,r){var n,o,l,h,_,u,c,f,p,m,d,g,v,y,T=t._gsTransform,x=Ee(t,!0);T&&(v=T.xOrigin,y=T.yOrigin),(!s||2>(n=s.split(" ")).length)&&(c=t.getBBox(),e=se(e).split(" "),n=[(-1!==e[0].indexOf("%")?parseFloat(e[0])/100*c.width:parseFloat(e[0]))+c.x,(-1!==e[1].indexOf("%")?parseFloat(e[1])/100*c.height:parseFloat(e[1]))+c.y]),i.xOrigin=h=parseFloat(n[0]),i.yOrigin=_=parseFloat(n[1]),s&&x!==Ie&&(u=x[0],c=x[1],f=x[2],p=x[3],m=x[4],d=x[5],g=u*p-c*f,o=h*(p/g)+_*(-f/g)+(f*d-p*m)/g,l=h*(-c/g)+_*(u/g)-(u*d-c*m)/g,h=i.xOrigin=n[0]=o,_=i.yOrigin=n[1]=l),T&&(r||r!==!1&&a.defaultSmoothOrigin!==!1?(o=h-v,l=_-y,T.xOffset+=o*x[0]+l*x[2]-o,T.yOffset+=o*x[1]+l*x[3]-l):T.xOffset=T.yOffset=0),t.setAttribute("data-svg-origin",n.join(" "))},Fe=function(t){return!!(Ae&&"function"==typeof t.getBBox&&t.getCTM&&(!t.parentNode||t.parentNode.getBBox&&t.parentNode.getCTM))},Ie=[1,0,0,1,0,0],Ee=function(t,e){var i,s,r,n,a,o=t._gsTransform||new Oe,l=1e5;if(Pe?s=Q(t,ke,null,!0):t.currentStyle&&(s=t.currentStyle.filter.match(C),s=s&&4===s.length?[s[0].substr(4),Number(s[2].substr(4)),Number(s[1].substr(4)),s[3].substr(4),o.x||0,o.y||0].join(","):""),i=!s||"none"===s||"matrix(1, 0, 0, 1, 0, 0)"===s,(o.svg||t.getBBox&&Fe(t))&&(i&&-1!==(t.style[Pe]+"").indexOf("matrix")&&(s=t.style[Pe],i=0),r=t.getAttribute("transform"),i&&r&&(-1!==r.indexOf("matrix")?(s=r,i=0):-1!==r.indexOf("translate")&&(s="matrix(1,0,0,1,"+r.match(/(?:\-|\b)[\d\-\.e]+\b/gi).join(",")+")",i=0))),i)return Ie;for(r=(s||"").match(/(?:\-|\b)[\d\-\.e]+\b/gi)||[],ve=r.length;--ve>-1;)n=Number(r[ve]),r[ve]=(a=n-(n|=0))?(0|a*l+(0>a?-.5:.5))/l+n:n;return e&&r.length>6?[r[0],r[1],r[4],r[5],r[12],r[13]]:r},Ne=B.getTransform=function(t,i,s,n){if(t._gsTransform&&s&&!n)return t._gsTransform;var o,l,h,_,u,c,f=s?t._gsTransform||new Oe:new Oe,p=0>f.scaleX,m=2e-5,d=1e5,g=Re?parseFloat(Q(t,Se,i,!1,"0 0 0").split(" ")[2])||f.zOrigin||0:0,v=parseFloat(a.defaultTransformPerspective)||0;if(f.svg=!(!t.getBBox||!Fe(t)),f.svg&&(ze(t,Q(t,Se,r,!1,"50% 50%")+"",f,t.getAttribute("data-svg-origin")),we=a.useSVGTransformAttr||Me),o=Ee(t),o!==Ie){if(16===o.length){var y,T,x,w,b,P=o[0],k=o[1],S=o[2],R=o[3],O=o[4],A=o[5],C=o[6],D=o[7],M=o[8],z=o[9],I=o[10],E=o[12],N=o[13],L=o[14],X=o[11],B=Math.atan2(C,I);f.zOrigin&&(L=-f.zOrigin,E=M*L-o[12],N=z*L-o[13],L=I*L+f.zOrigin-o[14]),f.rotationX=B*F,B&&(w=Math.cos(-B),b=Math.sin(-B),y=O*w+M*b,T=A*w+z*b,x=C*w+I*b,M=O*-b+M*w,z=A*-b+z*w,I=C*-b+I*w,X=D*-b+X*w,O=y,A=T,C=x),B=Math.atan2(M,I),f.rotationY=B*F,B&&(w=Math.cos(-B),b=Math.sin(-B),y=P*w-M*b,T=k*w-z*b,x=S*w-I*b,z=k*b+z*w,I=S*b+I*w,X=R*b+X*w,P=y,k=T,S=x),B=Math.atan2(k,P),f.rotation=B*F,B&&(w=Math.cos(-B),b=Math.sin(-B),P=P*w+O*b,T=k*w+A*b,A=k*-b+A*w,C=S*-b+C*w,k=T),f.rotationX&&Math.abs(f.rotationX)+Math.abs(f.rotation)>359.9&&(f.rotationX=f.rotation=0,f.rotationY+=180),f.scaleX=(0|Math.sqrt(P*P+k*k)*d+.5)/d,f.scaleY=(0|Math.sqrt(A*A+z*z)*d+.5)/d,f.scaleZ=(0|Math.sqrt(C*C+I*I)*d+.5)/d,f.skewX=0,f.perspective=X?1/(0>X?-X:X):0,f.x=E,f.y=N,f.z=L,f.svg&&(f.x-=f.xOrigin-(f.xOrigin*P-f.yOrigin*O),f.y-=f.yOrigin-(f.yOrigin*k-f.xOrigin*A))}else if(!(Re&&!n&&o.length&&f.x===o[4]&&f.y===o[5]&&(f.rotationX||f.rotationY)||void 0!==f.x&&"none"===Q(t,"display",i))){var j=o.length>=6,Y=j?o[0]:1,U=o[1]||0,q=o[2]||0,V=j?o[3]:1;f.x=o[4]||0,f.y=o[5]||0,h=Math.sqrt(Y*Y+U*U),_=Math.sqrt(V*V+q*q),u=Y||U?Math.atan2(U,Y)*F:f.rotation||0,c=q||V?Math.atan2(q,V)*F+u:f.skewX||0,Math.abs(c)>90&&270>Math.abs(c)&&(p?(h*=-1,c+=0>=u?180:-180,u+=0>=u?180:-180):(_*=-1,c+=0>=c?180:-180)),f.scaleX=h,f.scaleY=_,f.rotation=u,f.skewX=c,Re&&(f.rotationX=f.rotationY=f.z=0,f.perspective=v,f.scaleZ=1),f.svg&&(f.x-=f.xOrigin-(f.xOrigin*Y+f.yOrigin*q),f.y-=f.yOrigin-(f.xOrigin*U+f.yOrigin*V))}f.zOrigin=g;for(l in f)m>f[l]&&f[l]>-m&&(f[l]=0)}return s&&(t._gsTransform=f,f.svg&&(we&&t.style[Pe]?e.delayedCall(.001,function(){je(t.style,Pe)}):!we&&t.getAttribute("transform")&&e.delayedCall(.001,function(){t.removeAttribute("transform")}))),f},Le=function(t){var e,i,s=this.data,r=-s.rotation*z,n=r+s.skewX*z,a=1e5,o=(0|Math.cos(r)*s.scaleX*a)/a,l=(0|Math.sin(r)*s.scaleX*a)/a,h=(0|Math.sin(n)*-s.scaleY*a)/a,_=(0|Math.cos(n)*s.scaleY*a)/a,u=this.t.style,c=this.t.currentStyle;if(c){i=l,l=-h,h=-i,e=c.filter,u.filter="";var f,p,d=this.t.offsetWidth,g=this.t.offsetHeight,v="absolute"!==c.position,y="progid:DXImageTransform.Microsoft.Matrix(M11="+o+", M12="+l+", M21="+h+", M22="+_,w=s.x+d*s.xPercent/100,b=s.y+g*s.yPercent/100;if(null!=s.ox&&(f=(s.oxp?.01*d*s.ox:s.ox)-d/2,p=(s.oyp?.01*g*s.oy:s.oy)-g/2,w+=f-(f*o+p*l),b+=p-(f*h+p*_)),v?(f=d/2,p=g/2,y+=", Dx="+(f-(f*o+p*l)+w)+", Dy="+(p-(f*h+p*_)+b)+")"):y+=", sizingMethod='auto expand')",u.filter=-1!==e.indexOf("DXImageTransform.Microsoft.Matrix(")?e.replace(D,y):y+" "+e,(0===t||1===t)&&1===o&&0===l&&0===h&&1===_&&(v&&-1===y.indexOf("Dx=0, Dy=0")||x.test(e)&&100!==parseFloat(RegExp.$1)||-1===e.indexOf("gradient("&&e.indexOf("Alpha"))&&u.removeAttribute("filter")),!v){var P,k,S,R=8>m?1:-1;for(f=s.ieOffsetX||0,p=s.ieOffsetY||0,s.ieOffsetX=Math.round((d-((0>o?-o:o)*d+(0>l?-l:l)*g))/2+w),s.ieOffsetY=Math.round((g-((0>_?-_:_)*g+(0>h?-h:h)*d))/2+b),ve=0;4>ve;ve++)k=ee[ve],P=c[k],i=-1!==P.indexOf("px")?parseFloat(P):$(this.t,k,parseFloat(P),P.replace(T,""))||0,S=i!==s[k]?2>ve?-s.ieOffsetX:-s.ieOffsetY:2>ve?f-s.ieOffsetX:p-s.ieOffsetY,u[k]=(s[k]=Math.round(i-S*(0===ve||2===ve?1:R)))+"px"}}},Xe=B.set3DTransformRatio=B.setTransformRatio=function(t){var e,i,s,r,n,a,o,l,h,_,u,c,p,m,d,g,v,y,T,x,w,b,P,k=this.data,S=this.t.style,R=k.rotation,O=k.rotationX,A=k.rotationY,C=k.scaleX,D=k.scaleY,M=k.scaleZ,F=k.x,I=k.y,E=k.z,N=k.svg,L=k.perspective,X=k.force3D;if(!(((1!==t&&0!==t||"auto"!==X||this.tween._totalTime!==this.tween._totalDuration&&this.tween._totalTime)&&X||E||L||A||O)&&(!we||!N)&&Re))return R||k.skewX||N?(R*=z,b=k.skewX*z,P=1e5,e=Math.cos(R)*C,r=Math.sin(R)*C,i=Math.sin(R-b)*-D,n=Math.cos(R-b)*D,b&&"simple"===k.skewType&&(v=Math.tan(b),v=Math.sqrt(1+v*v),i*=v,n*=v,k.skewY&&(e*=v,r*=v)),N&&(F+=k.xOrigin-(k.xOrigin*e+k.yOrigin*i)+k.xOffset,I+=k.yOrigin-(k.xOrigin*r+k.yOrigin*n)+k.yOffset,we&&(k.xPercent||k.yPercent)&&(m=this.t.getBBox(),F+=.01*k.xPercent*m.width,I+=.01*k.yPercent*m.height),m=1e-6,m>F&&F>-m&&(F=0),m>I&&I>-m&&(I=0)),T=(0|e*P)/P+","+(0|r*P)/P+","+(0|i*P)/P+","+(0|n*P)/P+","+F+","+I+")",N&&we?this.t.setAttribute("transform","matrix("+T):S[Pe]=(k.xPercent||k.yPercent?"translate("+k.xPercent+"%,"+k.yPercent+"%) matrix(":"matrix(")+T):S[Pe]=(k.xPercent||k.yPercent?"translate("+k.xPercent+"%,"+k.yPercent+"%) matrix(":"matrix(")+C+",0,0,"+D+","+F+","+I+")",void 0;if(f&&(m=1e-4,m>C&&C>-m&&(C=M=2e-5),m>D&&D>-m&&(D=M=2e-5),!L||k.z||k.rotationX||k.rotationY||(L=0)),R||k.skewX)R*=z,d=e=Math.cos(R),g=r=Math.sin(R),k.skewX&&(R-=k.skewX*z,d=Math.cos(R),g=Math.sin(R),"simple"===k.skewType&&(v=Math.tan(k.skewX*z),v=Math.sqrt(1+v*v),d*=v,g*=v,k.skewY&&(e*=v,r*=v))),i=-g,n=d;else{if(!(A||O||1!==M||L||N))return S[Pe]=(k.xPercent||k.yPercent?"translate("+k.xPercent+"%,"+k.yPercent+"%) translate3d(":"translate3d(")+F+"px,"+I+"px,"+E+"px)"+(1!==C||1!==D?" scale("+C+","+D+")":""),void 0;e=n=1,i=r=0}h=1,s=a=o=l=_=u=0,c=L?-1/L:0,p=k.zOrigin,m=1e-6,x=",",w="0",R=A*z,R&&(d=Math.cos(R),g=Math.sin(R),o=-g,_=c*-g,s=e*g,a=r*g,h=d,c*=d,e*=d,r*=d),R=O*z,R&&(d=Math.cos(R),g=Math.sin(R),v=i*d+s*g,y=n*d+a*g,l=h*g,u=c*g,s=i*-g+s*d,a=n*-g+a*d,h*=d,c*=d,i=v,n=y),1!==M&&(s*=M,a*=M,h*=M,c*=M),1!==D&&(i*=D,n*=D,l*=D,u*=D),1!==C&&(e*=C,r*=C,o*=C,_*=C),(p||N)&&(p&&(F+=s*-p,I+=a*-p,E+=h*-p+p),N&&(F+=k.xOrigin-(k.xOrigin*e+k.yOrigin*i)+k.xOffset,I+=k.yOrigin-(k.xOrigin*r+k.yOrigin*n)+k.yOffset),m>F&&F>-m&&(F=w),m>I&&I>-m&&(I=w),m>E&&E>-m&&(E=0)),T=k.xPercent||k.yPercent?"translate("+k.xPercent+"%,"+k.yPercent+"%) matrix3d(":"matrix3d(",T+=(m>e&&e>-m?w:e)+x+(m>r&&r>-m?w:r)+x+(m>o&&o>-m?w:o),T+=x+(m>_&&_>-m?w:_)+x+(m>i&&i>-m?w:i)+x+(m>n&&n>-m?w:n),O||A?(T+=x+(m>l&&l>-m?w:l)+x+(m>u&&u>-m?w:u)+x+(m>s&&s>-m?w:s),T+=x+(m>a&&a>-m?w:a)+x+(m>h&&h>-m?w:h)+x+(m>c&&c>-m?w:c)+x):T+=",0,0,0,0,1,0,",T+=F+x+I+x+E+x+(L?1+-E/L:1)+")",S[Pe]=T};h=Oe.prototype,h.x=h.y=h.z=h.skewX=h.skewY=h.rotation=h.rotationX=h.rotationY=h.zOrigin=h.xPercent=h.yPercent=h.xOffset=h.yOffset=0,h.scaleX=h.scaleY=h.scaleZ=1,Te("transform,scale,scaleX,scaleY,scaleZ,x,y,z,rotation,rotationX,rotationY,rotationZ,skewX,skewY,shortRotation,shortRotationX,shortRotationY,shortRotationZ,transformOrigin,svgOrigin,transformPerspective,directionalRotation,parseTransform,force3D,skewType,xPercent,yPercent,smoothOrigin",{parser:function(t,e,i,s,n,o,l){if(s._lastParsedTransform===l)return n;s._lastParsedTransform=l;var h,_,u,c,f,p,m,d,g,v,y=t._gsTransform,T=t.style,x=1e-6,w=be.length,b=l,P={},k="transformOrigin";if(l.display?(c=Q(t,"display"),T.display="block",h=Ne(t,r,!0,l.parseTransform),T.display=c):h=Ne(t,r,!0,l.parseTransform),s._transform=h,"string"==typeof b.transform&&Pe)c=L.style,c[Pe]=b.transform,c.display="block",c.position="absolute",E.body.appendChild(L),_=Ne(L,null,!1),E.body.removeChild(L),_.perspective||(_.perspective=h.perspective),null!=b.xPercent&&(_.xPercent=ne(b.xPercent,h.xPercent)),null!=b.yPercent&&(_.yPercent=ne(b.yPercent,h.yPercent));else if("object"==typeof b){if(_={scaleX:ne(null!=b.scaleX?b.scaleX:b.scale,h.scaleX),scaleY:ne(null!=b.scaleY?b.scaleY:b.scale,h.scaleY),scaleZ:ne(b.scaleZ,h.scaleZ),x:ne(b.x,h.x),y:ne(b.y,h.y),z:ne(b.z,h.z),xPercent:ne(b.xPercent,h.xPercent),yPercent:ne(b.yPercent,h.yPercent),perspective:ne(b.transformPerspective,h.perspective)},d=b.directionalRotation,null!=d)if("object"==typeof d)for(c in d)b[c]=d[c];else b.rotation=d;"string"==typeof b.x&&-1!==b.x.indexOf("%")&&(_.x=0,_.xPercent=ne(b.x,h.xPercent)),"string"==typeof b.y&&-1!==b.y.indexOf("%")&&(_.y=0,_.yPercent=ne(b.y,h.yPercent)),_.rotation=ae("rotation"in b?b.rotation:"shortRotation"in b?b.shortRotation+"_short":"rotationZ"in b?b.rotationZ:h.rotation,h.rotation,"rotation",P),Re&&(_.rotationX=ae("rotationX"in b?b.rotationX:"shortRotationX"in b?b.shortRotationX+"_short":h.rotationX||0,h.rotationX,"rotationX",P),_.rotationY=ae("rotationY"in b?b.rotationY:"shortRotationY"in b?b.shortRotationY+"_short":h.rotationY||0,h.rotationY,"rotationY",P)),_.skewX=null==b.skewX?h.skewX:ae(b.skewX,h.skewX),_.skewY=null==b.skewY?h.skewY:ae(b.skewY,h.skewY),(u=_.skewY-h.skewY)&&(_.skewX+=u,_.rotation+=u)}for(Re&&null!=b.force3D&&(h.force3D=b.force3D,m=!0),h.skewType=b.skewType||h.skewType||a.defaultSkewType,p=h.force3D||h.z||h.rotationX||h.rotationY||_.z||_.rotationX||_.rotationY||_.perspective,p||null==b.scale||(_.scaleZ=1);--w>-1;)i=be[w],f=_[i]-h[i],(f>x||-x>f||null!=b[i]||null!=I[i])&&(m=!0,n=new me(h,i,h[i],f,n),i in P&&(n.e=P[i]),n.xs0=0,n.plugin=o,s._overwriteProps.push(n.n));return f=b.transformOrigin,h.svg&&(f||b.svgOrigin)&&(g=h.xOffset,v=h.yOffset,ze(t,se(f),_,b.svgOrigin,b.smoothOrigin),n=de(h,"xOrigin",(y?h:_).xOrigin,_.xOrigin,n,k),n=de(h,"yOrigin",(y?h:_).yOrigin,_.yOrigin,n,k),(g!==h.xOffset||v!==h.yOffset)&&(n=de(h,"xOffset",y?g:h.xOffset,h.xOffset,n,k),n=de(h,"yOffset",y?v:h.yOffset,h.yOffset,n,k)),f=we?null:"0px 0px"),(f||Re&&p&&h.zOrigin)&&(Pe?(m=!0,i=Se,f=(f||Q(t,i,r,!1,"50% 50%"))+"",n=new me(T,i,0,0,n,-1,k),n.b=T[i],n.plugin=o,Re?(c=h.zOrigin,f=f.split(" "),h.zOrigin=(f.length>2&&(0===c||"0px"!==f[2])?parseFloat(f[2]):c)||0,n.xs0=n.e=f[0]+" "+(f[1]||"50%")+" 0px",n=new me(h,"zOrigin",0,0,n,-1,n.n),n.b=c,n.xs0=n.e=h.zOrigin):n.xs0=n.e=f):se(f+"",h)),m&&(s._transformType=h.svg&&we||!p&&3!==this._transformType?2:3),n},prefix:!0}),Te("boxShadow",{defaultValue:"0px 0px 0px 0px #999",prefix:!0,color:!0,multi:!0,keyword:"inset"}),Te("borderRadius",{defaultValue:"0px",parser:function(t,e,i,n,a){e=this.format(e);var o,l,h,_,u,c,f,p,m,d,g,v,y,T,x,w,b=["borderTopLeftRadius","borderTopRightRadius","borderBottomRightRadius","borderBottomLeftRadius"],P=t.style;for(m=parseFloat(t.offsetWidth),d=parseFloat(t.offsetHeight),o=e.split(" "),l=0;b.length>l;l++)this.p.indexOf("border")&&(b[l]=W(b[l])),u=_=Q(t,b[l],r,!1,"0px"),-1!==u.indexOf(" ")&&(_=u.split(" "),u=_[0],_=_[1]),c=h=o[l],f=parseFloat(u),v=u.substr((f+"").length),y="="===c.charAt(1),y?(p=parseInt(c.charAt(0)+"1",10),c=c.substr(2),p*=parseFloat(c),g=c.substr((p+"").length-(0>p?1:0))||""):(p=parseFloat(c),g=c.substr((p+"").length)),""===g&&(g=s[i]||v),g!==v&&(T=$(t,"borderLeft",f,v),x=$(t,"borderTop",f,v),"%"===g?(u=100*(T/m)+"%",_=100*(x/d)+"%"):"em"===g?(w=$(t,"borderLeft",1,"em"),u=T/w+"em",_=x/w+"em"):(u=T+"px",_=x+"px"),y&&(c=parseFloat(u)+p+g,h=parseFloat(_)+p+g)),a=ge(P,b[l],u+" "+_,c+" "+h,!1,"0px",a);return a},prefix:!0,formatter:ce("0px 0px 0px 0px",!1,!0)}),Te("backgroundPosition",{defaultValue:"0 0",parser:function(t,e,i,s,n,a){var o,l,h,_,u,c,f="background-position",p=r||Z(t,null),d=this.format((p?m?p.getPropertyValue(f+"-x")+" "+p.getPropertyValue(f+"-y"):p.getPropertyValue(f):t.currentStyle.backgroundPositionX+" "+t.currentStyle.backgroundPositionY)||"0 0"),g=this.format(e);
if(-1!==d.indexOf("%")!=(-1!==g.indexOf("%"))&&(c=Q(t,"backgroundImage").replace(R,""),c&&"none"!==c)){for(o=d.split(" "),l=g.split(" "),X.setAttribute("src",c),h=2;--h>-1;)d=o[h],_=-1!==d.indexOf("%"),_!==(-1!==l[h].indexOf("%"))&&(u=0===h?t.offsetWidth-X.width:t.offsetHeight-X.height,o[h]=_?parseFloat(d)/100*u+"px":100*(parseFloat(d)/u)+"%");d=o.join(" ")}return this.parseComplex(t.style,d,g,n,a)},formatter:se}),Te("backgroundSize",{defaultValue:"0 0",formatter:se}),Te("perspective",{defaultValue:"0px",prefix:!0}),Te("perspectiveOrigin",{defaultValue:"50% 50%",prefix:!0}),Te("transformStyle",{prefix:!0}),Te("backfaceVisibility",{prefix:!0}),Te("userSelect",{prefix:!0}),Te("margin",{parser:fe("marginTop,marginRight,marginBottom,marginLeft")}),Te("padding",{parser:fe("paddingTop,paddingRight,paddingBottom,paddingLeft")}),Te("clip",{defaultValue:"rect(0px,0px,0px,0px)",parser:function(t,e,i,s,n,a){var o,l,h;return 9>m?(l=t.currentStyle,h=8>m?" ":",",o="rect("+l.clipTop+h+l.clipRight+h+l.clipBottom+h+l.clipLeft+")",e=this.format(e).split(",").join(h)):(o=this.format(Q(t,this.p,r,!1,this.dflt)),e=this.format(e)),this.parseComplex(t.style,o,e,n,a)}}),Te("textShadow",{defaultValue:"0px 0px 0px #999",color:!0,multi:!0}),Te("autoRound,strictUnits",{parser:function(t,e,i,s,r){return r}}),Te("border",{defaultValue:"0px solid #000",parser:function(t,e,i,s,n,a){return this.parseComplex(t.style,this.format(Q(t,"borderTopWidth",r,!1,"0px")+" "+Q(t,"borderTopStyle",r,!1,"solid")+" "+Q(t,"borderTopColor",r,!1,"#000")),this.format(e),n,a)},color:!0,formatter:function(t){var e=t.split(" ");return e[0]+" "+(e[1]||"solid")+" "+(t.match(ue)||["#000"])[0]}}),Te("borderWidth",{parser:fe("borderTopWidth,borderRightWidth,borderBottomWidth,borderLeftWidth")}),Te("float,cssFloat,styleFloat",{parser:function(t,e,i,s,r){var n=t.style,a="cssFloat"in n?"cssFloat":"styleFloat";return new me(n,a,0,0,r,-1,i,!1,0,n[a],e)}});var Be=function(t){var e,i=this.t,s=i.filter||Q(this.data,"filter")||"",r=0|this.s+this.c*t;100===r&&(-1===s.indexOf("atrix(")&&-1===s.indexOf("radient(")&&-1===s.indexOf("oader(")?(i.removeAttribute("filter"),e=!Q(this.data,"filter")):(i.filter=s.replace(b,""),e=!0)),e||(this.xn1&&(i.filter=s=s||"alpha(opacity="+r+")"),-1===s.indexOf("pacity")?0===r&&this.xn1||(i.filter=s+" alpha(opacity="+r+")"):i.filter=s.replace(x,"opacity="+r))};Te("opacity,alpha,autoAlpha",{defaultValue:"1",parser:function(t,e,i,s,n,a){var o=parseFloat(Q(t,"opacity",r,!1,"1")),l=t.style,h="autoAlpha"===i;return"string"==typeof e&&"="===e.charAt(1)&&(e=("-"===e.charAt(0)?-1:1)*parseFloat(e.substr(2))+o),h&&1===o&&"hidden"===Q(t,"visibility",r)&&0!==e&&(o=0),Y?n=new me(l,"opacity",o,e-o,n):(n=new me(l,"opacity",100*o,100*(e-o),n),n.xn1=h?1:0,l.zoom=1,n.type=2,n.b="alpha(opacity="+n.s+")",n.e="alpha(opacity="+(n.s+n.c)+")",n.data=t,n.plugin=a,n.setRatio=Be),h&&(n=new me(l,"visibility",0,0,n,-1,null,!1,0,0!==o?"inherit":"hidden",0===e?"hidden":"inherit"),n.xs0="inherit",s._overwriteProps.push(n.n),s._overwriteProps.push(i)),n}});var je=function(t,e){e&&(t.removeProperty?(("ms"===e.substr(0,2)||"webkit"===e.substr(0,6))&&(e="-"+e),t.removeProperty(e.replace(k,"-$1").toLowerCase())):t.removeAttribute(e))},Ye=function(t){if(this.t._gsClassPT=this,1===t||0===t){this.t.setAttribute("class",0===t?this.b:this.e);for(var e=this.data,i=this.t.style;e;)e.v?i[e.p]=e.v:je(i,e.p),e=e._next;1===t&&this.t._gsClassPT===this&&(this.t._gsClassPT=null)}else this.t.getAttribute("class")!==this.e&&this.t.setAttribute("class",this.e)};Te("className",{parser:function(t,e,s,n,a,o,l){var h,_,u,c,f,p=t.getAttribute("class")||"",m=t.style.cssText;if(a=n._classNamePT=new me(t,s,0,0,a,2),a.setRatio=Ye,a.pr=-11,i=!0,a.b=p,_=K(t,r),u=t._gsClassPT){for(c={},f=u.data;f;)c[f.p]=1,f=f._next;u.setRatio(1)}return t._gsClassPT=a,a.e="="!==e.charAt(1)?e:p.replace(RegExp("\\s*\\b"+e.substr(2)+"\\b"),"")+("+"===e.charAt(0)?" "+e.substr(2):""),t.setAttribute("class",a.e),h=J(t,_,K(t),l,c),t.setAttribute("class",p),a.data=h.firstMPT,t.style.cssText=m,a=a.xfirst=n.parse(t,h.difs,a,o)}});var Ue=function(t){if((1===t||0===t)&&this.data._totalTime===this.data._totalDuration&&"isFromStart"!==this.data.data){var e,i,s,r,n,a=this.t.style,o=l.transform.parse;if("all"===this.e)a.cssText="",r=!0;else for(e=this.e.split(" ").join("").split(","),s=e.length;--s>-1;)i=e[s],l[i]&&(l[i].parse===o?r=!0:i="transformOrigin"===i?Se:l[i].p),je(a,i);r&&(je(a,Pe),n=this.t._gsTransform,n&&(n.svg&&this.t.removeAttribute("data-svg-origin"),delete this.t._gsTransform))}};for(Te("clearProps",{parser:function(t,e,s,r,n){return n=new me(t,s,0,0,n,2),n.setRatio=Ue,n.e=e,n.pr=-10,n.data=r._tween,i=!0,n}}),h="bezier,throwProps,physicsProps,physics2D".split(","),ve=h.length;ve--;)xe(h[ve]);h=a.prototype,h._firstPT=h._lastParsedTransform=h._transform=null,h._onInitTween=function(t,e,o){if(!t.nodeType)return!1;this._target=t,this._tween=o,this._vars=e,_=e.autoRound,i=!1,s=e.suffixMap||a.suffixMap,r=Z(t,""),n=this._overwriteProps;var h,f,m,d,g,v,y,T,x,b=t.style;if(u&&""===b.zIndex&&(h=Q(t,"zIndex",r),("auto"===h||""===h)&&this._addLazySet(b,"zIndex",0)),"string"==typeof e&&(d=b.cssText,h=K(t,r),b.cssText=d+";"+e,h=J(t,h,K(t)).difs,!Y&&w.test(e)&&(h.opacity=parseFloat(RegExp.$1)),e=h,b.cssText=d),this._firstPT=f=e.className?l.className.parse(t,e.className,"className",this,null,null,e):this.parse(t,e,null),this._transformType){for(x=3===this._transformType,Pe?c&&(u=!0,""===b.zIndex&&(y=Q(t,"zIndex",r),("auto"===y||""===y)&&this._addLazySet(b,"zIndex",0)),p&&this._addLazySet(b,"WebkitBackfaceVisibility",this._vars.WebkitBackfaceVisibility||(x?"visible":"hidden"))):b.zoom=1,m=f;m&&m._next;)m=m._next;T=new me(t,"transform",0,0,null,2),this._linkCSSP(T,null,m),T.setRatio=Pe?Xe:Le,T.data=this._transform||Ne(t,r,!0),T.tween=o,T.pr=-1,n.pop()}if(i){for(;f;){for(v=f._next,m=d;m&&m.pr>f.pr;)m=m._next;(f._prev=m?m._prev:g)?f._prev._next=f:d=f,(f._next=m)?m._prev=f:g=f,f=v}this._firstPT=d}return!0},h.parse=function(t,e,i,n){var a,o,h,u,c,f,p,m,d,g,v=t.style;for(a in e)f=e[a],o=l[a],o?i=o.parse(t,f,a,this,i,n,e):(c=Q(t,a,r)+"",d="string"==typeof f,"color"===a||"fill"===a||"stroke"===a||-1!==a.indexOf("Color")||d&&P.test(f)?(d||(f=he(f),f=(f.length>3?"rgba(":"rgb(")+f.join(",")+")"),i=ge(v,a,c,f,!0,"transparent",i,0,n)):!d||-1===f.indexOf(" ")&&-1===f.indexOf(",")?(h=parseFloat(c),p=h||0===h?c.substr((h+"").length):"",(""===c||"auto"===c)&&("width"===a||"height"===a?(h=ie(t,a,r),p="px"):"left"===a||"top"===a?(h=H(t,a,r),p="px"):(h="opacity"!==a?0:1,p="")),g=d&&"="===f.charAt(1),g?(u=parseInt(f.charAt(0)+"1",10),f=f.substr(2),u*=parseFloat(f),m=f.replace(T,"")):(u=parseFloat(f),m=d?f.replace(T,""):""),""===m&&(m=a in s?s[a]:p),f=u||0===u?(g?u+h:u)+m:e[a],p!==m&&""!==m&&(u||0===u)&&h&&(h=$(t,a,h,p),"%"===m?(h/=$(t,a,100,"%")/100,e.strictUnits!==!0&&(c=h+"%")):"em"===m||"rem"===m?h/=$(t,a,1,m):"px"!==m&&(u=$(t,a,u,m),m="px"),g&&(u||0===u)&&(f=u+h+m)),g&&(u+=h),!h&&0!==h||!u&&0!==u?void 0!==v[a]&&(f||"NaN"!=f+""&&null!=f)?(i=new me(v,a,u||h||0,0,i,-1,a,!1,0,c,f),i.xs0="none"!==f||"display"!==a&&-1===a.indexOf("Style")?f:c):q("invalid "+a+" tween value: "+e[a]):(i=new me(v,a,h,u-h,i,0,a,_!==!1&&("px"===m||"zIndex"===a),0,c,f),i.xs0=m)):i=ge(v,a,c,f,!0,null,i,0,n)),n&&i&&!i.plugin&&(i.plugin=n);return i},h.setRatio=function(t){var e,i,s,r=this._firstPT,n=1e-6;if(1!==t||this._tween._time!==this._tween._duration&&0!==this._tween._time)if(t||this._tween._time!==this._tween._duration&&0!==this._tween._time||this._tween._rawPrevTime===-1e-6)for(;r;){if(e=r.c*t+r.s,r.r?e=Math.round(e):n>e&&e>-n&&(e=0),r.type)if(1===r.type)if(s=r.l,2===s)r.t[r.p]=r.xs0+e+r.xs1+r.xn1+r.xs2;else if(3===s)r.t[r.p]=r.xs0+e+r.xs1+r.xn1+r.xs2+r.xn2+r.xs3;else if(4===s)r.t[r.p]=r.xs0+e+r.xs1+r.xn1+r.xs2+r.xn2+r.xs3+r.xn3+r.xs4;else if(5===s)r.t[r.p]=r.xs0+e+r.xs1+r.xn1+r.xs2+r.xn2+r.xs3+r.xn3+r.xs4+r.xn4+r.xs5;else{for(i=r.xs0+e+r.xs1,s=1;r.l>s;s++)i+=r["xn"+s]+r["xs"+(s+1)];r.t[r.p]=i}else-1===r.type?r.t[r.p]=r.xs0:r.setRatio&&r.setRatio(t);else r.t[r.p]=e+r.xs0;r=r._next}else for(;r;)2!==r.type?r.t[r.p]=r.b:r.setRatio(t),r=r._next;else for(;r;){if(2!==r.type)if(r.r&&-1!==r.type)if(e=Math.round(r.s+r.c),r.type){if(1===r.type){for(s=r.l,i=r.xs0+e+r.xs1,s=1;r.l>s;s++)i+=r["xn"+s]+r["xs"+(s+1)];r.t[r.p]=i}}else r.t[r.p]=e+r.xs0;else r.t[r.p]=r.e;else r.setRatio(t);r=r._next}},h._enableTransforms=function(t){this._transform=this._transform||Ne(this._target,r,!0),this._transformType=this._transform.svg&&we||!t&&3!==this._transformType?2:3};var qe=function(){this.t[this.p]=this.e,this.data._linkCSSP(this,this._next,null,!0)};h._addLazySet=function(t,e,i){var s=this._firstPT=new me(t,e,0,0,this._firstPT,2);s.e=i,s.setRatio=qe,s.data=this},h._linkCSSP=function(t,e,i,s){return t&&(e&&(e._prev=t),t._next&&(t._next._prev=t._prev),t._prev?t._prev._next=t._next:this._firstPT===t&&(this._firstPT=t._next,s=!0),i?i._next=t:s||null!==this._firstPT||(this._firstPT=t),t._next=e,t._prev=i),t},h._kill=function(e){var i,s,r,n=e;if(e.autoAlpha||e.alpha){n={};for(s in e)n[s]=e[s];n.opacity=1,n.autoAlpha&&(n.visibility=1)}return e.className&&(i=this._classNamePT)&&(r=i.xfirst,r&&r._prev?this._linkCSSP(r._prev,i._next,r._prev._prev):r===this._firstPT&&(this._firstPT=i._next),i._next&&this._linkCSSP(i._next,i._next._next,r._prev),this._classNamePT=null),t.prototype._kill.call(this,n)};var Ve=function(t,e,i){var s,r,n,a;if(t.slice)for(r=t.length;--r>-1;)Ve(t[r],e,i);else for(s=t.childNodes,r=s.length;--r>-1;)n=s[r],a=n.type,n.style&&(e.push(K(n)),i&&i.push(n)),1!==a&&9!==a&&11!==a||!n.childNodes.length||Ve(n,e,i)};return a.cascadeTo=function(t,i,s){var r,n,a,o,l=e.to(t,i,s),h=[l],_=[],u=[],c=[],f=e._internals.reservedProps;for(t=l._targets||l.target,Ve(t,_,c),l.render(i,!0,!0),Ve(t,u),l.render(0,!0,!0),l._enabled(!0),r=c.length;--r>-1;)if(n=J(c[r],_[r],u[r]),n.firstMPT){n=n.difs;for(a in s)f[a]&&(n[a]=s[a]);o={};for(a in n)o[a]=_[r][a];h.push(e.fromTo(c[r],i,o,n))}return h},t.activate([a]),a},!0),function(){var t=_gsScope._gsDefine.plugin({propName:"roundProps",version:"1.5",priority:-1,API:2,init:function(t,e,i){return this._tween=i,!0}}),e=function(t){for(;t;)t.f||t.blob||(t.r=1),t=t._next},i=t.prototype;i._onInitAllProps=function(){for(var t,i,s,r=this._tween,n=r.vars.roundProps.join?r.vars.roundProps:r.vars.roundProps.split(","),a=n.length,o={},l=r._propLookup.roundProps;--a>-1;)o[n[a]]=1;for(a=n.length;--a>-1;)for(t=n[a],i=r._firstPT;i;)s=i._next,i.pg?i.t._roundProps(o,!0):i.n===t&&(2===i.f&&i.t?e(i.t._firstPT):(this._add(i.t,t,i.s,i.c),s&&(s._prev=i._prev),i._prev?i._prev._next=s:r._firstPT===i&&(r._firstPT=s),i._next=i._prev=null,r._propLookup[t]=l)),i=s;return!1},i._add=function(t,e,i,s){this._addTween(t,e,i,i+s,e,!0),this._overwriteProps.push(e)}}(),function(){_gsScope._gsDefine.plugin({propName:"attr",API:2,version:"0.5.0",init:function(t,e){var i;if("function"!=typeof t.setAttribute)return!1;for(i in e)this._addTween(t,"setAttribute",t.getAttribute(i)+"",e[i]+"",i,!1,i),this._overwriteProps.push(i);return!0}})}(),_gsScope._gsDefine.plugin({propName:"directionalRotation",version:"0.2.1",API:2,init:function(t,e){"object"!=typeof e&&(e={rotation:e}),this.finals={};var i,s,r,n,a,o,l=e.useRadians===!0?2*Math.PI:360,h=1e-6;for(i in e)"useRadians"!==i&&(o=(e[i]+"").split("_"),s=o[0],r=parseFloat("function"!=typeof t[i]?t[i]:t[i.indexOf("set")||"function"!=typeof t["get"+i.substr(3)]?i:"get"+i.substr(3)]()),n=this.finals[i]="string"==typeof s&&"="===s.charAt(1)?r+parseInt(s.charAt(0)+"1",10)*Number(s.substr(2)):Number(s)||0,a=n-r,o.length&&(s=o.join("_"),-1!==s.indexOf("short")&&(a%=l,a!==a%(l/2)&&(a=0>a?a+l:a-l)),-1!==s.indexOf("_cw")&&0>a?a=(a+9999999999*l)%l-(0|a/l)*l:-1!==s.indexOf("ccw")&&a>0&&(a=(a-9999999999*l)%l-(0|a/l)*l)),(a>h||-h>a)&&(this._addTween(t,i,r,r+a,i),this._overwriteProps.push(i)));return!0},set:function(t){var e;if(1!==t)this._super.setRatio.call(this,t);else for(e=this._firstPT;e;)e.f?e.t[e.p](this.finals[e.p]):e.t[e.p]=this.finals[e.p],e=e._next}})._autoCSS=!0,_gsScope._gsDefine("easing.Back",["easing.Ease"],function(t){var e,i,s,r=_gsScope.GreenSockGlobals||_gsScope,n=r.com.greensock,a=2*Math.PI,o=Math.PI/2,l=n._class,h=function(e,i){var s=l("easing."+e,function(){},!0),r=s.prototype=new t;return r.constructor=s,r.getRatio=i,s},_=t.register||function(){},u=function(t,e,i,s){var r=l("easing."+t,{easeOut:new e,easeIn:new i,easeInOut:new s},!0);return _(r,t),r},c=function(t,e,i){this.t=t,this.v=e,i&&(this.next=i,i.prev=this,this.c=i.v-e,this.gap=i.t-t)},f=function(e,i){var s=l("easing."+e,function(t){this._p1=t||0===t?t:1.70158,this._p2=1.525*this._p1},!0),r=s.prototype=new t;return r.constructor=s,r.getRatio=i,r.config=function(t){return new s(t)},s},p=u("Back",f("BackOut",function(t){return(t-=1)*t*((this._p1+1)*t+this._p1)+1}),f("BackIn",function(t){return t*t*((this._p1+1)*t-this._p1)}),f("BackInOut",function(t){return 1>(t*=2)?.5*t*t*((this._p2+1)*t-this._p2):.5*((t-=2)*t*((this._p2+1)*t+this._p2)+2)})),m=l("easing.SlowMo",function(t,e,i){e=e||0===e?e:.7,null==t?t=.7:t>1&&(t=1),this._p=1!==t?e:0,this._p1=(1-t)/2,this._p2=t,this._p3=this._p1+this._p2,this._calcEnd=i===!0},!0),d=m.prototype=new t;return d.constructor=m,d.getRatio=function(t){var e=t+(.5-t)*this._p;return this._p1>t?this._calcEnd?1-(t=1-t/this._p1)*t:e-(t=1-t/this._p1)*t*t*t*e:t>this._p3?this._calcEnd?1-(t=(t-this._p3)/this._p1)*t:e+(t-e)*(t=(t-this._p3)/this._p1)*t*t*t:this._calcEnd?1:e},m.ease=new m(.7,.7),d.config=m.config=function(t,e,i){return new m(t,e,i)},e=l("easing.SteppedEase",function(t){t=t||1,this._p1=1/t,this._p2=t+1},!0),d=e.prototype=new t,d.constructor=e,d.getRatio=function(t){return 0>t?t=0:t>=1&&(t=.999999999),(this._p2*t>>0)*this._p1},d.config=e.config=function(t){return new e(t)},i=l("easing.RoughEase",function(e){e=e||{};for(var i,s,r,n,a,o,l=e.taper||"none",h=[],_=0,u=0|(e.points||20),f=u,p=e.randomize!==!1,m=e.clamp===!0,d=e.template instanceof t?e.template:null,g="number"==typeof e.strength?.4*e.strength:.4;--f>-1;)i=p?Math.random():1/u*f,s=d?d.getRatio(i):i,"none"===l?r=g:"out"===l?(n=1-i,r=n*n*g):"in"===l?r=i*i*g:.5>i?(n=2*i,r=.5*n*n*g):(n=2*(1-i),r=.5*n*n*g),p?s+=Math.random()*r-.5*r:f%2?s+=.5*r:s-=.5*r,m&&(s>1?s=1:0>s&&(s=0)),h[_++]={x:i,y:s};for(h.sort(function(t,e){return t.x-e.x}),o=new c(1,1,null),f=u;--f>-1;)a=h[f],o=new c(a.x,a.y,o);this._prev=new c(0,0,0!==o.t?o:o.next)},!0),d=i.prototype=new t,d.constructor=i,d.getRatio=function(t){var e=this._prev;if(t>e.t){for(;e.next&&t>=e.t;)e=e.next;e=e.prev}else for(;e.prev&&e.t>=t;)e=e.prev;return this._prev=e,e.v+(t-e.t)/e.gap*e.c},d.config=function(t){return new i(t)},i.ease=new i,u("Bounce",h("BounceOut",function(t){return 1/2.75>t?7.5625*t*t:2/2.75>t?7.5625*(t-=1.5/2.75)*t+.75:2.5/2.75>t?7.5625*(t-=2.25/2.75)*t+.9375:7.5625*(t-=2.625/2.75)*t+.984375}),h("BounceIn",function(t){return 1/2.75>(t=1-t)?1-7.5625*t*t:2/2.75>t?1-(7.5625*(t-=1.5/2.75)*t+.75):2.5/2.75>t?1-(7.5625*(t-=2.25/2.75)*t+.9375):1-(7.5625*(t-=2.625/2.75)*t+.984375)}),h("BounceInOut",function(t){var e=.5>t;return t=e?1-2*t:2*t-1,t=1/2.75>t?7.5625*t*t:2/2.75>t?7.5625*(t-=1.5/2.75)*t+.75:2.5/2.75>t?7.5625*(t-=2.25/2.75)*t+.9375:7.5625*(t-=2.625/2.75)*t+.984375,e?.5*(1-t):.5*t+.5})),u("Circ",h("CircOut",function(t){return Math.sqrt(1-(t-=1)*t)}),h("CircIn",function(t){return-(Math.sqrt(1-t*t)-1)}),h("CircInOut",function(t){return 1>(t*=2)?-.5*(Math.sqrt(1-t*t)-1):.5*(Math.sqrt(1-(t-=2)*t)+1)})),s=function(e,i,s){var r=l("easing."+e,function(t,e){this._p1=t>=1?t:1,this._p2=(e||s)/(1>t?t:1),this._p3=this._p2/a*(Math.asin(1/this._p1)||0),this._p2=a/this._p2},!0),n=r.prototype=new t;return n.constructor=r,n.getRatio=i,n.config=function(t,e){return new r(t,e)},r},u("Elastic",s("ElasticOut",function(t){return this._p1*Math.pow(2,-10*t)*Math.sin((t-this._p3)*this._p2)+1},.3),s("ElasticIn",function(t){return-(this._p1*Math.pow(2,10*(t-=1))*Math.sin((t-this._p3)*this._p2))},.3),s("ElasticInOut",function(t){return 1>(t*=2)?-.5*this._p1*Math.pow(2,10*(t-=1))*Math.sin((t-this._p3)*this._p2):.5*this._p1*Math.pow(2,-10*(t-=1))*Math.sin((t-this._p3)*this._p2)+1},.45)),u("Expo",h("ExpoOut",function(t){return 1-Math.pow(2,-10*t)}),h("ExpoIn",function(t){return Math.pow(2,10*(t-1))-.001}),h("ExpoInOut",function(t){return 1>(t*=2)?.5*Math.pow(2,10*(t-1)):.5*(2-Math.pow(2,-10*(t-1)))})),u("Sine",h("SineOut",function(t){return Math.sin(t*o)}),h("SineIn",function(t){return-Math.cos(t*o)+1}),h("SineInOut",function(t){return-.5*(Math.cos(Math.PI*t)-1)})),l("easing.EaseLookup",{find:function(e){return t.map[e]}},!0),_(r.SlowMo,"SlowMo","ease,"),_(i,"RoughEase","ease,"),_(e,"SteppedEase","ease,"),p},!0)}),_gsScope._gsDefine&&_gsScope._gsQueue.pop()(),function(t,e){"use strict";var i=t.GreenSockGlobals=t.GreenSockGlobals||t;if(!i.TweenLite){var s,r,n,a,o,l=function(t){var e,s=t.split("."),r=i;for(e=0;s.length>e;e++)r[s[e]]=r=r[s[e]]||{};return r},h=l("com.greensock"),_=1e-10,u=function(t){var e,i=[],s=t.length;for(e=0;e!==s;i.push(t[e++]));return i},c=function(){},f=function(){var t=Object.prototype.toString,e=t.call([]);return function(i){return null!=i&&(i instanceof Array||"object"==typeof i&&!!i.push&&t.call(i)===e)}}(),p={},m=function(s,r,n,a){this.sc=p[s]?p[s].sc:[],p[s]=this,this.gsClass=null,this.func=n;var o=[];this.check=function(h){for(var _,u,c,f,d,g=r.length,v=g;--g>-1;)(_=p[r[g]]||new m(r[g],[])).gsClass?(o[g]=_.gsClass,v--):h&&_.sc.push(this);if(0===v&&n)for(u=("com.greensock."+s).split("."),c=u.pop(),f=l(u.join("."))[c]=this.gsClass=n.apply(n,o),a&&(i[c]=f,d="undefined"!=typeof module&&module.exports,!d&&"function"==typeof define&&define.amd?define((t.GreenSockAMDPath?t.GreenSockAMDPath+"/":"")+s.split(".").pop(),[],function(){return f}):s===e&&d&&(module.exports=f)),g=0;this.sc.length>g;g++)this.sc[g].check()},this.check(!0)},d=t._gsDefine=function(t,e,i,s){return new m(t,e,i,s)},g=h._class=function(t,e,i){return e=e||function(){},d(t,[],function(){return e},i),e};d.globals=i;var v=[0,0,1,1],y=[],T=g("easing.Ease",function(t,e,i,s){this._func=t,this._type=i||0,this._power=s||0,this._params=e?v.concat(e):v},!0),x=T.map={},w=T.register=function(t,e,i,s){for(var r,n,a,o,l=e.split(","),_=l.length,u=(i||"easeIn,easeOut,easeInOut").split(",");--_>-1;)for(n=l[_],r=s?g("easing."+n,null,!0):h.easing[n]||{},a=u.length;--a>-1;)o=u[a],x[n+"."+o]=x[o+n]=r[o]=t.getRatio?t:t[o]||new t};for(n=T.prototype,n._calcEnd=!1,n.getRatio=function(t){if(this._func)return this._params[0]=t,this._func.apply(null,this._params);var e=this._type,i=this._power,s=1===e?1-t:2===e?t:.5>t?2*t:2*(1-t);return 1===i?s*=s:2===i?s*=s*s:3===i?s*=s*s*s:4===i&&(s*=s*s*s*s),1===e?1-s:2===e?s:.5>t?s/2:1-s/2},s=["Linear","Quad","Cubic","Quart","Quint,Strong"],r=s.length;--r>-1;)n=s[r]+",Power"+r,w(new T(null,null,1,r),n,"easeOut",!0),w(new T(null,null,2,r),n,"easeIn"+(0===r?",easeNone":"")),w(new T(null,null,3,r),n,"easeInOut");x.linear=h.easing.Linear.easeIn,x.swing=h.easing.Quad.easeInOut;var b=g("events.EventDispatcher",function(t){this._listeners={},this._eventTarget=t||this});n=b.prototype,n.addEventListener=function(t,e,i,s,r){r=r||0;var n,l,h=this._listeners[t],_=0;for(null==h&&(this._listeners[t]=h=[]),l=h.length;--l>-1;)n=h[l],n.c===e&&n.s===i?h.splice(l,1):0===_&&r>n.pr&&(_=l+1);h.splice(_,0,{c:e,s:i,up:s,pr:r}),this!==a||o||a.wake()},n.removeEventListener=function(t,e){var i,s=this._listeners[t];if(s)for(i=s.length;--i>-1;)if(s[i].c===e)return s.splice(i,1),void 0},n.dispatchEvent=function(t){var e,i,s,r=this._listeners[t];if(r)for(e=r.length,i=this._eventTarget;--e>-1;)s=r[e],s&&(s.up?s.c.call(s.s||i,{type:t,target:i}):s.c.call(s.s||i))};var P=t.requestAnimationFrame,k=t.cancelAnimationFrame,S=Date.now||function(){return(new Date).getTime()},R=S();for(s=["ms","moz","webkit","o"],r=s.length;--r>-1&&!P;)P=t[s[r]+"RequestAnimationFrame"],k=t[s[r]+"CancelAnimationFrame"]||t[s[r]+"CancelRequestAnimationFrame"];g("Ticker",function(t,e){var i,s,r,n,l,h=this,u=S(),f=e!==!1&&P,p=500,m=33,d="tick",g=function(t){var e,a,o=S()-R;o>p&&(u+=o-m),R+=o,h.time=(R-u)/1e3,e=h.time-l,(!i||e>0||t===!0)&&(h.frame++,l+=e+(e>=n?.004:n-e),a=!0),t!==!0&&(r=s(g)),a&&h.dispatchEvent(d)};b.call(h),h.time=h.frame=0,h.tick=function(){g(!0)},h.lagSmoothing=function(t,e){p=t||1/_,m=Math.min(e,p,0)},h.sleep=function(){null!=r&&(f&&k?k(r):clearTimeout(r),s=c,r=null,h===a&&(o=!1))},h.wake=function(){null!==r?h.sleep():h.frame>10&&(R=S()-p+5),s=0===i?c:f&&P?P:function(t){return setTimeout(t,0|1e3*(l-h.time)+1)},h===a&&(o=!0),g(2)},h.fps=function(t){return arguments.length?(i=t,n=1/(i||60),l=this.time+n,h.wake(),void 0):i},h.useRAF=function(t){return arguments.length?(h.sleep(),f=t,h.fps(i),void 0):f},h.fps(t),setTimeout(function(){f&&5>h.frame&&h.useRAF(!1)},1500)}),n=h.Ticker.prototype=new h.events.EventDispatcher,n.constructor=h.Ticker;var O=g("core.Animation",function(t,e){if(this.vars=e=e||{},this._duration=this._totalDuration=t||0,this._delay=Number(e.delay)||0,this._timeScale=1,this._active=e.immediateRender===!0,this.data=e.data,this._reversed=e.reversed===!0,W){o||a.wake();var i=this.vars.useFrames?G:W;i.add(this,i._time),this.vars.paused&&this.paused(!0)}});a=O.ticker=new h.Ticker,n=O.prototype,n._dirty=n._gc=n._initted=n._paused=!1,n._totalTime=n._time=0,n._rawPrevTime=-1,n._next=n._last=n._onUpdate=n._timeline=n.timeline=null,n._paused=!1;var A=function(){o&&S()-R>2e3&&a.wake(),setTimeout(A,2e3)};A(),n.play=function(t,e){return null!=t&&this.seek(t,e),this.reversed(!1).paused(!1)},n.pause=function(t,e){return null!=t&&this.seek(t,e),this.paused(!0)},n.resume=function(t,e){return null!=t&&this.seek(t,e),this.paused(!1)},n.seek=function(t,e){return this.totalTime(Number(t),e!==!1)},n.restart=function(t,e){return this.reversed(!1).paused(!1).totalTime(t?-this._delay:0,e!==!1,!0)},n.reverse=function(t,e){return null!=t&&this.seek(t||this.totalDuration(),e),this.reversed(!0).paused(!1)},n.render=function(){},n.invalidate=function(){return this._time=this._totalTime=0,this._initted=this._gc=!1,this._rawPrevTime=-1,(this._gc||!this.timeline)&&this._enabled(!0),this},n.isActive=function(){var t,e=this._timeline,i=this._startTime;return!e||!this._gc&&!this._paused&&e.isActive()&&(t=e.rawTime())>=i&&i+this.totalDuration()/this._timeScale>t},n._enabled=function(t,e){return o||a.wake(),this._gc=!t,this._active=this.isActive(),e!==!0&&(t&&!this.timeline?this._timeline.add(this,this._startTime-this._delay):!t&&this.timeline&&this._timeline._remove(this,!0)),!1},n._kill=function(){return this._enabled(!1,!1)},n.kill=function(t,e){return this._kill(t,e),this},n._uncache=function(t){for(var e=t?this:this.timeline;e;)e._dirty=!0,e=e.timeline;return this},n._swapSelfInParams=function(t){for(var e=t.length,i=t.concat();--e>-1;)"{self}"===t[e]&&(i[e]=this);return i},n._callback=function(t){var e=this.vars;e[t].apply(e[t+"Scope"]||e.callbackScope||this,e[t+"Params"]||y)},n.eventCallback=function(t,e,i,s){if("on"===(t||"").substr(0,2)){var r=this.vars;if(1===arguments.length)return r[t];null==e?delete r[t]:(r[t]=e,r[t+"Params"]=f(i)&&-1!==i.join("").indexOf("{self}")?this._swapSelfInParams(i):i,r[t+"Scope"]=s),"onUpdate"===t&&(this._onUpdate=e)}return this},n.delay=function(t){return arguments.length?(this._timeline.smoothChildTiming&&this.startTime(this._startTime+t-this._delay),this._delay=t,this):this._delay},n.duration=function(t){return arguments.length?(this._duration=this._totalDuration=t,this._uncache(!0),this._timeline.smoothChildTiming&&this._time>0&&this._time<this._duration&&0!==t&&this.totalTime(this._totalTime*(t/this._duration),!0),this):(this._dirty=!1,this._duration)},n.totalDuration=function(t){return this._dirty=!1,arguments.length?this.duration(t):this._totalDuration},n.time=function(t,e){return arguments.length?(this._dirty&&this.totalDuration(),this.totalTime(t>this._duration?this._duration:t,e)):this._time},n.totalTime=function(t,e,i){if(o||a.wake(),!arguments.length)return this._totalTime;if(this._timeline){if(0>t&&!i&&(t+=this.totalDuration()),this._timeline.smoothChildTiming){this._dirty&&this.totalDuration();var s=this._totalDuration,r=this._timeline;if(t>s&&!i&&(t=s),this._startTime=(this._paused?this._pauseTime:r._time)-(this._reversed?s-t:t)/this._timeScale,r._dirty||this._uncache(!1),r._timeline)for(;r._timeline;)r._timeline._time!==(r._startTime+r._totalTime)/r._timeScale&&r.totalTime(r._totalTime,!0),r=r._timeline}this._gc&&this._enabled(!0,!1),(this._totalTime!==t||0===this._duration)&&(F.length&&Q(),this.render(t,e,!1),F.length&&Q())}return this},n.progress=n.totalProgress=function(t,e){var i=this.duration();return arguments.length?this.totalTime(i*t,e):i?this._time/i:this.ratio},n.startTime=function(t){return arguments.length?(t!==this._startTime&&(this._startTime=t,this.timeline&&this.timeline._sortChildren&&this.timeline.add(this,t-this._delay)),this):this._startTime},n.endTime=function(t){return this._startTime+(0!=t?this.totalDuration():this.duration())/this._timeScale},n.timeScale=function(t){if(!arguments.length)return this._timeScale;if(t=t||_,this._timeline&&this._timeline.smoothChildTiming){var e=this._pauseTime,i=e||0===e?e:this._timeline.totalTime();this._startTime=i-(i-this._startTime)*this._timeScale/t}return this._timeScale=t,this._uncache(!1)},n.reversed=function(t){return arguments.length?(t!=this._reversed&&(this._reversed=t,this.totalTime(this._timeline&&!this._timeline.smoothChildTiming?this.totalDuration()-this._totalTime:this._totalTime,!0)),this):this._reversed},n.paused=function(t){if(!arguments.length)return this._paused;var e,i,s=this._timeline;return t!=this._paused&&s&&(o||t||a.wake(),e=s.rawTime(),i=e-this._pauseTime,!t&&s.smoothChildTiming&&(this._startTime+=i,this._uncache(!1)),this._pauseTime=t?e:null,this._paused=t,this._active=this.isActive(),!t&&0!==i&&this._initted&&this.duration()&&(e=s.smoothChildTiming?this._totalTime:(e-this._startTime)/this._timeScale,this.render(e,e===this._totalTime,!0))),this._gc&&!t&&this._enabled(!0,!1),this};var C=g("core.SimpleTimeline",function(t){O.call(this,0,t),this.autoRemoveChildren=this.smoothChildTiming=!0});n=C.prototype=new O,n.constructor=C,n.kill()._gc=!1,n._first=n._last=n._recent=null,n._sortChildren=!1,n.add=n.insert=function(t,e){var i,s;if(t._startTime=Number(e||0)+t._delay,t._paused&&this!==t._timeline&&(t._pauseTime=t._startTime+(this.rawTime()-t._startTime)/t._timeScale),t.timeline&&t.timeline._remove(t,!0),t.timeline=t._timeline=this,t._gc&&t._enabled(!0,!0),i=this._last,this._sortChildren)for(s=t._startTime;i&&i._startTime>s;)i=i._prev;return i?(t._next=i._next,i._next=t):(t._next=this._first,this._first=t),t._next?t._next._prev=t:this._last=t,t._prev=i,this._recent=t,this._timeline&&this._uncache(!0),this},n._remove=function(t,e){return t.timeline===this&&(e||t._enabled(!1,!0),t._prev?t._prev._next=t._next:this._first===t&&(this._first=t._next),t._next?t._next._prev=t._prev:this._last===t&&(this._last=t._prev),t._next=t._prev=t.timeline=null,t===this._recent&&(this._recent=this._last),this._timeline&&this._uncache(!0)),this},n.render=function(t,e,i){var s,r=this._first;for(this._totalTime=this._time=this._rawPrevTime=t;r;)s=r._next,(r._active||t>=r._startTime&&!r._paused)&&(r._reversed?r.render((r._dirty?r.totalDuration():r._totalDuration)-(t-r._startTime)*r._timeScale,e,i):r.render((t-r._startTime)*r._timeScale,e,i)),r=s},n.rawTime=function(){return o||a.wake(),this._totalTime};var D=g("TweenLite",function(e,i,s){if(O.call(this,i,s),this.render=D.prototype.render,null==e)throw"Cannot tween a null target.";this.target=e="string"!=typeof e?e:D.selector(e)||e;var r,n,a,o=e.jquery||e.length&&e!==t&&e[0]&&(e[0]===t||e[0].nodeType&&e[0].style&&!e.nodeType),l=this.vars.overwrite;if(this._overwrite=l=null==l?V[D.defaultOverwrite]:"number"==typeof l?l>>0:V[l],(o||e instanceof Array||e.push&&f(e))&&"number"!=typeof e[0])for(this._targets=a=u(e),this._propLookup=[],this._siblings=[],r=0;a.length>r;r++)n=a[r],n?"string"!=typeof n?n.length&&n!==t&&n[0]&&(n[0]===t||n[0].nodeType&&n[0].style&&!n.nodeType)?(a.splice(r--,1),this._targets=a=a.concat(u(n))):(this._siblings[r]=$(n,this,!1),1===l&&this._siblings[r].length>1&&K(n,this,null,1,this._siblings[r])):(n=a[r--]=D.selector(n),"string"==typeof n&&a.splice(r+1,1)):a.splice(r--,1);else this._propLookup={},this._siblings=$(e,this,!1),1===l&&this._siblings.length>1&&K(e,this,null,1,this._siblings);(this.vars.immediateRender||0===i&&0===this._delay&&this.vars.immediateRender!==!1)&&(this._time=-_,this.render(-this._delay))},!0),M=function(e){return e&&e.length&&e!==t&&e[0]&&(e[0]===t||e[0].nodeType&&e[0].style&&!e.nodeType)},z=function(t,e){var i,s={};for(i in t)q[i]||i in e&&"transform"!==i&&"x"!==i&&"y"!==i&&"width"!==i&&"height"!==i&&"className"!==i&&"border"!==i||!(!j[i]||j[i]&&j[i]._autoCSS)||(s[i]=t[i],delete t[i]);t.css=s};n=D.prototype=new O,n.constructor=D,n.kill()._gc=!1,n.ratio=0,n._firstPT=n._targets=n._overwrittenProps=n._startAt=null,n._notifyPluginsOfEnabled=n._lazy=!1,D.version="1.18.0",D.defaultEase=n._ease=new T(null,null,1,1),D.defaultOverwrite="auto",D.ticker=a,D.autoSleep=120,D.lagSmoothing=function(t,e){a.lagSmoothing(t,e)},D.selector=t.$||t.jQuery||function(e){var i=t.$||t.jQuery;return i?(D.selector=i,i(e)):"undefined"==typeof document?e:document.querySelectorAll?document.querySelectorAll(e):document.getElementById("#"===e.charAt(0)?e.substr(1):e)};var F=[],I={},E=/(?:(-|-=|\+=)?\d*\.?\d*(?:e[\-+]?\d+)?)[0-9]/gi,N=function(t){for(var e,i=this._firstPT,s=1e-6;i;)e=i.blob?t?this.join(""):this.start:i.c*t+i.s,i.r?e=Math.round(e):s>e&&e>-s&&(e=0),i.f?i.fp?i.t[i.p](i.fp,e):i.t[i.p](e):i.t[i.p]=e,i=i._next},L=function(t,e,i,s){var r,n,a,o,l,h,_,u=[t,e],c=0,f="",p=0;for(u.start=t,i&&(i(u),t=u[0],e=u[1]),u.length=0,r=t.match(E)||[],n=e.match(E)||[],s&&(s._next=null,s.blob=1,u._firstPT=s),l=n.length,o=0;l>o;o++)_=n[o],h=e.substr(c,e.indexOf(_,c)-c),f+=h||!o?h:",",c+=h.length,p?p=(p+1)%5:"rgba("===h.substr(-5)&&(p=1),_===r[o]||o>=r.length?f+=_:(f&&(u.push(f),f=""),a=parseFloat(r[o]),u.push(a),u._firstPT={_next:u._firstPT,t:u,p:u.length-1,s:a,c:("="===_.charAt(1)?parseInt(_.charAt(0)+"1",10)*parseFloat(_.substr(2)):parseFloat(_)-a)||0,f:0,r:p&&4>p}),c+=_.length;return f+=e.substr(c),f&&u.push(f),u.setRatio=N,u},X=function(t,e,i,s,r,n,a,o){var l,h,_="get"===i?t[e]:i,u=typeof t[e],c="string"==typeof s&&"="===s.charAt(1),f={t:t,p:e,s:_,f:"function"===u,pg:0,n:r||e,r:n,pr:0,c:c?parseInt(s.charAt(0)+"1",10)*parseFloat(s.substr(2)):parseFloat(s)-_||0};return"number"!==u&&("function"===u&&"get"===i&&(h=e.indexOf("set")||"function"!=typeof t["get"+e.substr(3)]?e:"get"+e.substr(3),f.s=_=a?t[h](a):t[h]()),"string"==typeof _&&(a||isNaN(_))?(f.fp=a,l=L(_,s,o||D.defaultStringFilter,f),f={t:l,p:"setRatio",s:0,c:1,f:2,pg:0,n:r||e,pr:0}):c||(f.c=parseFloat(s)-parseFloat(_)||0)),f.c?((f._next=this._firstPT)&&(f._next._prev=f),this._firstPT=f,f):void 0},B=D._internals={isArray:f,isSelector:M,lazyTweens:F,blobDif:L},j=D._plugins={},Y=B.tweenLookup={},U=0,q=B.reservedProps={ease:1,delay:1,overwrite:1,onComplete:1,onCompleteParams:1,onCompleteScope:1,useFrames:1,runBackwards:1,startAt:1,onUpdate:1,onUpdateParams:1,onUpdateScope:1,onStart:1,onStartParams:1,onStartScope:1,onReverseComplete:1,onReverseCompleteParams:1,onReverseCompleteScope:1,onRepeat:1,onRepeatParams:1,onRepeatScope:1,easeParams:1,yoyo:1,immediateRender:1,repeat:1,repeatDelay:1,data:1,paused:1,reversed:1,autoCSS:1,lazy:1,onOverwrite:1,callbackScope:1,stringFilter:1},V={none:0,all:1,auto:2,concurrent:3,allOnStart:4,preexisting:5,"true":1,"false":0},G=O._rootFramesTimeline=new C,W=O._rootTimeline=new C,Z=30,Q=B.lazyRender=function(){var t,e=F.length;for(I={};--e>-1;)t=F[e],t&&t._lazy!==!1&&(t.render(t._lazy[0],t._lazy[1],!0),t._lazy=!1);F.length=0};W._startTime=a.time,G._startTime=a.frame,W._active=G._active=!0,setTimeout(Q,1),O._updateRoot=D.render=function(){var t,e,i;if(F.length&&Q(),W.render((a.time-W._startTime)*W._timeScale,!1,!1),G.render((a.frame-G._startTime)*G._timeScale,!1,!1),F.length&&Q(),a.frame>=Z){Z=a.frame+(parseInt(D.autoSleep,10)||120);
for(i in Y){for(e=Y[i].tweens,t=e.length;--t>-1;)e[t]._gc&&e.splice(t,1);0===e.length&&delete Y[i]}if(i=W._first,(!i||i._paused)&&D.autoSleep&&!G._first&&1===a._listeners.tick.length){for(;i&&i._paused;)i=i._next;i||a.sleep()}}},a.addEventListener("tick",O._updateRoot);var $=function(t,e,i){var s,r,n=t._gsTweenID;if(Y[n||(t._gsTweenID=n="t"+U++)]||(Y[n]={target:t,tweens:[]}),e&&(s=Y[n].tweens,s[r=s.length]=e,i))for(;--r>-1;)s[r]===e&&s.splice(r,1);return Y[n].tweens},H=function(t,e,i,s){var r,n,a=t.vars.onOverwrite;return a&&(r=a(t,e,i,s)),a=D.onOverwrite,a&&(n=a(t,e,i,s)),r!==!1&&n!==!1},K=function(t,e,i,s,r){var n,a,o,l;if(1===s||s>=4){for(l=r.length,n=0;l>n;n++)if((o=r[n])!==e)o._gc||o._kill(null,t,e)&&(a=!0);else if(5===s)break;return a}var h,u=e._startTime+_,c=[],f=0,p=0===e._duration;for(n=r.length;--n>-1;)(o=r[n])===e||o._gc||o._paused||(o._timeline!==e._timeline?(h=h||J(e,0,p),0===J(o,h,p)&&(c[f++]=o)):u>=o._startTime&&o._startTime+o.totalDuration()/o._timeScale>u&&((p||!o._initted)&&2e-10>=u-o._startTime||(c[f++]=o)));for(n=f;--n>-1;)if(o=c[n],2===s&&o._kill(i,t,e)&&(a=!0),2!==s||!o._firstPT&&o._initted){if(2!==s&&!H(o,e))continue;o._enabled(!1,!1)&&(a=!0)}return a},J=function(t,e,i){for(var s=t._timeline,r=s._timeScale,n=t._startTime;s._timeline;){if(n+=s._startTime,r*=s._timeScale,s._paused)return-100;s=s._timeline}return n/=r,n>e?n-e:i&&n===e||!t._initted&&2*_>n-e?_:(n+=t.totalDuration()/t._timeScale/r)>e+_?0:n-e-_};n._init=function(){var t,e,i,s,r,n=this.vars,a=this._overwrittenProps,o=this._duration,l=!!n.immediateRender,h=n.ease;if(n.startAt){this._startAt&&(this._startAt.render(-1,!0),this._startAt.kill()),r={};for(s in n.startAt)r[s]=n.startAt[s];if(r.overwrite=!1,r.immediateRender=!0,r.lazy=l&&n.lazy!==!1,r.startAt=r.delay=null,this._startAt=D.to(this.target,0,r),l)if(this._time>0)this._startAt=null;else if(0!==o)return}else if(n.runBackwards&&0!==o)if(this._startAt)this._startAt.render(-1,!0),this._startAt.kill(),this._startAt=null;else{0!==this._time&&(l=!1),i={};for(s in n)q[s]&&"autoCSS"!==s||(i[s]=n[s]);if(i.overwrite=0,i.data="isFromStart",i.lazy=l&&n.lazy!==!1,i.immediateRender=l,this._startAt=D.to(this.target,0,i),l){if(0===this._time)return}else this._startAt._init(),this._startAt._enabled(!1),this.vars.immediateRender&&(this._startAt=null)}if(this._ease=h=h?h instanceof T?h:"function"==typeof h?new T(h,n.easeParams):x[h]||D.defaultEase:D.defaultEase,n.easeParams instanceof Array&&h.config&&(this._ease=h.config.apply(h,n.easeParams)),this._easeType=this._ease._type,this._easePower=this._ease._power,this._firstPT=null,this._targets)for(t=this._targets.length;--t>-1;)this._initProps(this._targets[t],this._propLookup[t]={},this._siblings[t],a?a[t]:null)&&(e=!0);else e=this._initProps(this.target,this._propLookup,this._siblings,a);if(e&&D._onPluginEvent("_onInitAllProps",this),a&&(this._firstPT||"function"!=typeof this.target&&this._enabled(!1,!1)),n.runBackwards)for(i=this._firstPT;i;)i.s+=i.c,i.c=-i.c,i=i._next;this._onUpdate=n.onUpdate,this._initted=!0},n._initProps=function(e,i,s,r){var n,a,o,l,h,_;if(null==e)return!1;I[e._gsTweenID]&&Q(),this.vars.css||e.style&&e!==t&&e.nodeType&&j.css&&this.vars.autoCSS!==!1&&z(this.vars,e);for(n in this.vars)if(_=this.vars[n],q[n])_&&(_ instanceof Array||_.push&&f(_))&&-1!==_.join("").indexOf("{self}")&&(this.vars[n]=_=this._swapSelfInParams(_,this));else if(j[n]&&(l=new j[n])._onInitTween(e,this.vars[n],this)){for(this._firstPT=h={_next:this._firstPT,t:l,p:"setRatio",s:0,c:1,f:1,n:n,pg:1,pr:l._priority},a=l._overwriteProps.length;--a>-1;)i[l._overwriteProps[a]]=this._firstPT;(l._priority||l._onInitAllProps)&&(o=!0),(l._onDisable||l._onEnable)&&(this._notifyPluginsOfEnabled=!0),h._next&&(h._next._prev=h)}else i[n]=X.call(this,e,n,"get",_,n,0,null,this.vars.stringFilter);return r&&this._kill(r,e)?this._initProps(e,i,s,r):this._overwrite>1&&this._firstPT&&s.length>1&&K(e,this,i,this._overwrite,s)?(this._kill(i,e),this._initProps(e,i,s,r)):(this._firstPT&&(this.vars.lazy!==!1&&this._duration||this.vars.lazy&&!this._duration)&&(I[e._gsTweenID]=!0),o)},n.render=function(t,e,i){var s,r,n,a,o=this._time,l=this._duration,h=this._rawPrevTime;if(t>=l)this._totalTime=this._time=l,this.ratio=this._ease._calcEnd?this._ease.getRatio(1):1,this._reversed||(s=!0,r="onComplete",i=i||this._timeline.autoRemoveChildren),0===l&&(this._initted||!this.vars.lazy||i)&&(this._startTime===this._timeline._duration&&(t=0),(0===t||0>h||h===_&&"isPause"!==this.data)&&h!==t&&(i=!0,h>_&&(r="onReverseComplete")),this._rawPrevTime=a=!e||t||h===t?t:_);else if(1e-7>t)this._totalTime=this._time=0,this.ratio=this._ease._calcEnd?this._ease.getRatio(0):0,(0!==o||0===l&&h>0)&&(r="onReverseComplete",s=this._reversed),0>t&&(this._active=!1,0===l&&(this._initted||!this.vars.lazy||i)&&(h>=0&&(h!==_||"isPause"!==this.data)&&(i=!0),this._rawPrevTime=a=!e||t||h===t?t:_)),this._initted||(i=!0);else if(this._totalTime=this._time=t,this._easeType){var u=t/l,c=this._easeType,f=this._easePower;(1===c||3===c&&u>=.5)&&(u=1-u),3===c&&(u*=2),1===f?u*=u:2===f?u*=u*u:3===f?u*=u*u*u:4===f&&(u*=u*u*u*u),this.ratio=1===c?1-u:2===c?u:.5>t/l?u/2:1-u/2}else this.ratio=this._ease.getRatio(t/l);if(this._time!==o||i){if(!this._initted){if(this._init(),!this._initted||this._gc)return;if(!i&&this._firstPT&&(this.vars.lazy!==!1&&this._duration||this.vars.lazy&&!this._duration))return this._time=this._totalTime=o,this._rawPrevTime=h,F.push(this),this._lazy=[t,e],void 0;this._time&&!s?this.ratio=this._ease.getRatio(this._time/l):s&&this._ease._calcEnd&&(this.ratio=this._ease.getRatio(0===this._time?0:1))}for(this._lazy!==!1&&(this._lazy=!1),this._active||!this._paused&&this._time!==o&&t>=0&&(this._active=!0),0===o&&(this._startAt&&(t>=0?this._startAt.render(t,e,i):r||(r="_dummyGS")),this.vars.onStart&&(0!==this._time||0===l)&&(e||this._callback("onStart"))),n=this._firstPT;n;)n.f?n.t[n.p](n.c*this.ratio+n.s):n.t[n.p]=n.c*this.ratio+n.s,n=n._next;this._onUpdate&&(0>t&&this._startAt&&t!==-1e-4&&this._startAt.render(t,e,i),e||(this._time!==o||s)&&this._callback("onUpdate")),r&&(!this._gc||i)&&(0>t&&this._startAt&&!this._onUpdate&&t!==-1e-4&&this._startAt.render(t,e,i),s&&(this._timeline.autoRemoveChildren&&this._enabled(!1,!1),this._active=!1),!e&&this.vars[r]&&this._callback(r),0===l&&this._rawPrevTime===_&&a!==_&&(this._rawPrevTime=0))}},n._kill=function(t,e,i){if("all"===t&&(t=null),null==t&&(null==e||e===this.target))return this._lazy=!1,this._enabled(!1,!1);e="string"!=typeof e?e||this._targets||this.target:D.selector(e)||e;var s,r,n,a,o,l,h,_,u,c=i&&this._time&&i._startTime===this._startTime&&this._timeline===i._timeline;if((f(e)||M(e))&&"number"!=typeof e[0])for(s=e.length;--s>-1;)this._kill(t,e[s],i)&&(l=!0);else{if(this._targets){for(s=this._targets.length;--s>-1;)if(e===this._targets[s]){o=this._propLookup[s]||{},this._overwrittenProps=this._overwrittenProps||[],r=this._overwrittenProps[s]=t?this._overwrittenProps[s]||{}:"all";break}}else{if(e!==this.target)return!1;o=this._propLookup,r=this._overwrittenProps=t?this._overwrittenProps||{}:"all"}if(o){if(h=t||o,_=t!==r&&"all"!==r&&t!==o&&("object"!=typeof t||!t._tempKill),i&&(D.onOverwrite||this.vars.onOverwrite)){for(n in h)o[n]&&(u||(u=[]),u.push(n));if((u||!t)&&!H(this,i,e,u))return!1}for(n in h)(a=o[n])&&(c&&(a.f?a.t[a.p](a.s):a.t[a.p]=a.s,l=!0),a.pg&&a.t._kill(h)&&(l=!0),a.pg&&0!==a.t._overwriteProps.length||(a._prev?a._prev._next=a._next:a===this._firstPT&&(this._firstPT=a._next),a._next&&(a._next._prev=a._prev),a._next=a._prev=null),delete o[n]),_&&(r[n]=1);!this._firstPT&&this._initted&&this._enabled(!1,!1)}}return l},n.invalidate=function(){return this._notifyPluginsOfEnabled&&D._onPluginEvent("_onDisable",this),this._firstPT=this._overwrittenProps=this._startAt=this._onUpdate=null,this._notifyPluginsOfEnabled=this._active=this._lazy=!1,this._propLookup=this._targets?{}:[],O.prototype.invalidate.call(this),this.vars.immediateRender&&(this._time=-_,this.render(-this._delay)),this},n._enabled=function(t,e){if(o||a.wake(),t&&this._gc){var i,s=this._targets;if(s)for(i=s.length;--i>-1;)this._siblings[i]=$(s[i],this,!0);else this._siblings=$(this.target,this,!0)}return O.prototype._enabled.call(this,t,e),this._notifyPluginsOfEnabled&&this._firstPT?D._onPluginEvent(t?"_onEnable":"_onDisable",this):!1},D.to=function(t,e,i){return new D(t,e,i)},D.from=function(t,e,i){return i.runBackwards=!0,i.immediateRender=0!=i.immediateRender,new D(t,e,i)},D.fromTo=function(t,e,i,s){return s.startAt=i,s.immediateRender=0!=s.immediateRender&&0!=i.immediateRender,new D(t,e,s)},D.delayedCall=function(t,e,i,s,r){return new D(e,0,{delay:t,onComplete:e,onCompleteParams:i,callbackScope:s,onReverseComplete:e,onReverseCompleteParams:i,immediateRender:!1,lazy:!1,useFrames:r,overwrite:0})},D.set=function(t,e){return new D(t,0,e)},D.getTweensOf=function(t,e){if(null==t)return[];t="string"!=typeof t?t:D.selector(t)||t;var i,s,r,n;if((f(t)||M(t))&&"number"!=typeof t[0]){for(i=t.length,s=[];--i>-1;)s=s.concat(D.getTweensOf(t[i],e));for(i=s.length;--i>-1;)for(n=s[i],r=i;--r>-1;)n===s[r]&&s.splice(i,1)}else for(s=$(t).concat(),i=s.length;--i>-1;)(s[i]._gc||e&&!s[i].isActive())&&s.splice(i,1);return s},D.killTweensOf=D.killDelayedCallsTo=function(t,e,i){"object"==typeof e&&(i=e,e=!1);for(var s=D.getTweensOf(t,e),r=s.length;--r>-1;)s[r]._kill(i,t)};var te=g("plugins.TweenPlugin",function(t,e){this._overwriteProps=(t||"").split(","),this._propName=this._overwriteProps[0],this._priority=e||0,this._super=te.prototype},!0);if(n=te.prototype,te.version="1.18.0",te.API=2,n._firstPT=null,n._addTween=X,n.setRatio=N,n._kill=function(t){var e,i=this._overwriteProps,s=this._firstPT;if(null!=t[this._propName])this._overwriteProps=[];else for(e=i.length;--e>-1;)null!=t[i[e]]&&i.splice(e,1);for(;s;)null!=t[s.n]&&(s._next&&(s._next._prev=s._prev),s._prev?(s._prev._next=s._next,s._prev=null):this._firstPT===s&&(this._firstPT=s._next)),s=s._next;return!1},n._roundProps=function(t,e){for(var i=this._firstPT;i;)(t[this._propName]||null!=i.n&&t[i.n.split(this._propName+"_").join("")])&&(i.r=e),i=i._next},D._onPluginEvent=function(t,e){var i,s,r,n,a,o=e._firstPT;if("_onInitAllProps"===t){for(;o;){for(a=o._next,s=r;s&&s.pr>o.pr;)s=s._next;(o._prev=s?s._prev:n)?o._prev._next=o:r=o,(o._next=s)?s._prev=o:n=o,o=a}o=e._firstPT=r}for(;o;)o.pg&&"function"==typeof o.t[t]&&o.t[t]()&&(i=!0),o=o._next;return i},te.activate=function(t){for(var e=t.length;--e>-1;)t[e].API===te.API&&(j[(new t[e])._propName]=t[e]);return!0},d.plugin=function(t){if(!(t&&t.propName&&t.init&&t.API))throw"illegal plugin definition.";var e,i=t.propName,s=t.priority||0,r=t.overwriteProps,n={init:"_onInitTween",set:"setRatio",kill:"_kill",round:"_roundProps",initAll:"_onInitAllProps"},a=g("plugins."+i.charAt(0).toUpperCase()+i.substr(1)+"Plugin",function(){te.call(this,i,s),this._overwriteProps=r||[]},t.global===!0),o=a.prototype=new te(i);o.constructor=a,a.API=t.API;for(e in n)"function"==typeof t[e]&&(o[n[e]]=t[e]);return a.version=t.version,te.activate([a]),a},s=t._gsQueue){for(r=0;s.length>r;r++)s[r]();for(n in p)p[n].func||t.console.log("GSAP encountered missing dependency: com.greensock."+n)}o=!1}}("undefined"!=typeof module&&module.exports&&"undefined"!=typeof global?global:this||window,"TweenMax");