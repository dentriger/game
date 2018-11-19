$(document).ready(function () {
    var doubleGame;
    var conn = new ab.Session('ws://localhost:8080',
        function() {
            conn.subscribe('room:double', function(topic, data) {
                doubleGame = data.data;
                DoubleController();
            });
        },
        function() {
            console.warn('WebSocket connection closed');
        },
        {'skipSubprotocolCheck': true}
    );

    function DoubleController() {
        if(doubleGame.timer) {
            changeTimer()
        }

        if(doubleGame.roll) {
            startSpin();
        }

        if(doubleGame.number) {
            stopSpin();
            viewWinNumber()
        }

        if(doubleGame.bet) {
            console.log('New bet');
            addNewBet();
        }
    }

    function changeTimer() {
        $('.wheel-number').css("color", "#ffc107");
        $('.wheel-number').text(doubleGame.timer);
    }

    function startSpin() {
        $('.wheel').css('transform', "rotateZ("+ doubleGame.roll + "deg)");
        $('.wheel').css("transition", "transform 10s cubic-bezier(0.15, 0.15, 0, 1)");
    }

    function stopSpin() {
        $('.wheel').css('transform', "rotateZ("+ doubleGame.roll % 360 + "deg)");
        $('.wheel').css("transition", "transition: transform 0s linear");
    }

    function viewWinNumber() {
        if(parseInt(doubleGame.number) !== 0 && parseInt(doubleGame.number) >= 8) {

            $('.wheel-number').css("color", "#282828");
        } else if (parseInt(doubleGame.number) !== 0 && parseInt(doubleGame.number) <= 7) {
            $('.wheel-number').css("color", "#e1134b");
        } else  {
            $('.wheel-number').css("color", "#1cc345");
        }
        $('.wheel-number').text(doubleGame.number);
    }

    function addNewBet() {
        if(doubleGame.bet.anticipated_event === 'black') {

            $('.players-black').append(
                "<div class=\"player\">\n" +
                "                                <div class=\"player-avatar\">\n" +
                "                                    <img src=\"images/test-avatar.png\">\n" +
                "                                </div>\n" +
                "                                <div class=\"player-info\">\n" +
                "                                    <div class=\"name\">Name</div>\n" +
                "                                    <div class=\"bet-sum\">100 <i class=\"fa fa-rub\"></i></div>\n" +
                "                                </div>\n" +
                "                            </div>"
            )
        }

        if(doubleGame.bet.anticipated_event === 'red') {

            $('.players-red').append(
                "<div class=\"player\">\n" +
                "                                <div class=\"player-avatar\">\n" +
                "                                    <img src=\"images/test-avatar.png\">\n" +
                "                                </div>\n" +
                "                                <div class=\"player-info\">\n" +
                "                                    <div class=\"name\">Name</div>\n" +
                "                                    <div class=\"bet-sum\">100 <i class=\"fa fa-rub\"></i></div>\n" +
                "                                </div>\n" +
                "                            </div>"
            )
        }

        if(doubleGame.bet.anticipated_event === 'green') {

            $('.players-green').append(
                "<div class=\"player\">\n" +
                "                                <div class=\"player-avatar\">\n" +
                "                                    <img src=\"images/test-avatar.png\">\n" +
                "                                </div>\n" +
                "                                <div class=\"player-info\">\n" +
                "                                    <div class=\"name\">Name</div>\n" +
                "                                    <div class=\"bet-sum\">100 <i class=\"fa fa-rub\"></i></div>\n" +
                "                                </div>\n" +
                "                            </div>"
            )
        }


    }

    $('.bet-head').click(function (e) {
        let data = {
            'amount': 10,
            'anticipated_event': 'red'
        };
        success = function(data) {

        };
        $.ajax({
            url: '/setDoubleBet',
            data: data,
            dataType: 'json',
            success: success
        });
    })

});