var theWheel = new Winwheel({
    'numSegments'       : 15,
    'outerRadius'       : 150,
    'drawMode'          : 'image',
    'drawText'          : true,
    'textFontSize'      : 12,
    'textOrientation'   : 'curved',
    'textDirection'     : 'reversed',
    'textAlignment'     : 'outer',
    'textMargin'        : 5,
    'textFontFamily'    : 'monospace',
    'textStrokeStyle'   : 'black',
    'textLineWidth'     : 2,
    'textFillStyle'     : 'white',
    'segments'          :
        [
            {'text' : '0'},
            {'text' : '1'},
            {'text' : '8'},
            {'text' : '2'},
            {'text' : '9'},
            {'text' : '3'},
            {'text' : '10'},
            {'text' : '4'},
            {'text' : '11'},
            {'text' : '5'},
            {'text' : '12'},
            {'text' : '6'},
            {'text' : '13'},
            {'text' : '7'},
            {'text' : '14'},
        ],
    'animation' :
        {
            'type'     : 'spinToStop',
            'duration' : 5,
            'spins'    : 8
        }
});

var loadedImg = new Image();

loadedImg.onload = function()
{
    theWheel.wheelImage = loadedImg;
    loadedImg.className = 'wheel_double';
    theWheel.draw();
}

loadedImg.src = "http://localhost.test/images/wheel.png";
console.log(loadedImg.width);
console.log(loadedImg.height);
loadedImg.style.height = '409';
loadedImg.style.width = "409";

var data;
conn.onmessage = function (e) {
    data = JSON.parse(e.data).data;
    if(data.segment) {
        //theWheel.startAnimation();
        console.log(typeof data.segment);
        theWheel.stopAngle = theWheel.getRandomForSegment(data.segment);
        startSpin();
    }

    if (data.timer === 30) resetWheel();
};

var wheelSpinning = false;

function startSpin()
{
    console.log('start');
    if (wheelSpinning === false)
    {
        console.log('start');
        theWheel.animation.spins = 8;
        theWheel.startAnimation();
        wheelSpinning = true;
    }
    console.log('stop');

}

function resetWheel()
{
    console.log(theWheel.rotationAngle);
    let rotate = theWheel.rotationAngle;
    theWheel.stopAnimation(false);  // Stop the animation, false as param so does not call callback function.
    theWheel.rotationAngle = rotate; /// 0.0174532925199432957;     // Re-set the wheel angle to 0 degrees.
    console.log(theWheel.rotationAngle);
    theWheel.draw();                // Call draw to render changes to the wheel.
    wheelSpinning = false;          // Reset to false to power buttons and spin can be clicked again.
}

// -------------------------------------------------------
// Called when the spin animation has finished by the callback feature of the wheel because I specified callback in the parameters.
// note the indicated segment is passed in as a parmeter as 99% of the time you will want to know this to inform the user of their prize.
// -------------------------------------------------------
function alertPrize(indicatedSegment)
{
    // Do basic alert of the segment text. You would probably want to do something more interesting with this information.
    alert("The wheel stopped on " + indicatedSegment.text);
}