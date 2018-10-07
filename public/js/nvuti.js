$(document).ready(function(){
    $('.nvuti-amount').change(function () {
        let win_amount = $('.nvuti-amount').val()/$('.nvuti-chance').val() * 100;
        $('.nvuti-win').text(win_amount.toFixed(2));
    });

    $('.nvuti-chance').change(function () {
        let win_amount = $('.nvuti-amount').val()/$('.nvuti-chance').val() * 100;
        $('.nvuti-win').text(win_amount.toFixed(2));
        let min = Math.floor(parseInt($('.nvuti-chance').val())/100 * 999999);
        let max = Math.floor(999999 - parseInt($('.nvuti-chance').val())/100 * 999999);
        $('.nvuti-min').text('0-'+min);
        $('.nvuti-max').text(max+'-999999');
    });

    $('.nvuti-btn').click(function (e) {
        let chance = parseInt($('.nvuti-chance').val());
        let amount = parseInt($('.nvuti-amount').val());
        let stake = $('.nvuti-btn').attr('about');
        let data = {
            'chance': chance,
            'amount': amount,
            'stake': stake
        };
        success = function(data) {

            console.log(data);
            $('.hash-value').text(data.hash);
            $('.wallet-balance').text(data.wallet.balance)
        };
        $.ajax({
            url: '/setBet',
            data: data,
            dataType: 'json',
            success: success
        });
    })
});