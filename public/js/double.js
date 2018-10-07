$(document).ready(function () {

function changeTimer() {
    $('.wheel-number').css("color", "#ffc107");
    $('.wheel-number').text(data.timer);
}

function startSpin() {
    //transition: transform 10s cubic-bezier(0.15, 0.15, 0, 1);
    $('.wheel').css('transform', "rotateZ("+ data.roll + "deg)");
    $('.wheel').css("transition", "transform 10s cubic-bezier(0.15, 0.15, 0, 1)");
}

function stopSpin() {
    $('.wheel').css('transform', "rotateZ("+ data.roll % 360 + "deg)");
    $('.wheel').css("transition", "transition: transform 0s linear");
}

function viewWinNumber() {
    if(parseInt(data.number) !== 0 && parseInt(data.number) >= 8) {

        $('.wheel-number').css("color", "#282828");
    } else if (parseInt(data.number) !== 0 && parseInt(data.number) <= 7) {
        $('.wheel-number').css("color", "#e1134b");
    } else  {
        $('.wheel-number').css("color", "#1cc345");
    }
    $('.wheel-number').text(data.number);
}

});