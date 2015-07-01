/**
 * User: FomalhautRed
 * Mail: demirurg@gmail.com
 * Date: 21.05.2015
 * Time: 15:59
 */

$(document).ready(function () {
    $('.documents-list td.vis>button').on('click', function () {
        var itemID = $(this).parent().data('item-id');
        var el = $(this);
        $.ajax({
            type: 'post',
            url: '/documents/togglevisibility/' + itemID,
            data: {toggle: 'true'},
            success: function (data) {
                if (data == 'ok') {
                    $(el).find('i').toggleClass('fa-eye')
                        .toggleClass('fa-eye-slash')
                        .toggleClass('not-visible')
                        .toggleClass('visible');
                }
            }
        });
    });
});
