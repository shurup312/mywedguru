/**
 * User: FomalhautRed
 * Mail: demirurg@gmail.com
 * Date: 21.05.2015
 * Time: 15:59
 */

$(document).ready(function () {
    $('.pages-list td.vis>button').on('click', function () {
        var itemID = $(this).parent().data('item-id');
        var el = $(this);
        $.ajax({
            type: 'post',
            url: '/pages/togglevisibility/' + itemID,
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
    tabControl.init();
});

var tabControl = {
    init: function () {
        this.closeTabListen();
    },
    closeTabListen: function () {
        $('#pages-tabs').on('click', 'li>button.close-tab', function () {
            var tabToClose = $(this).parent();
            var tabID = tabToClose.data('id');
            if ($(this).parent().hasClass('active')) {
                $('#pages-tabs a:not([href="#tab-' + tabID + '"])').tab('show');
            }
            tabToClose.remove();
            $('.tab-content #tab-' + tabID).remove();
        });
    }
};

function str_replace(search, replace, subject) {
    return subject.replace(new RegExp(search, 'g'), replace);
}
function getLen(e) {
    var target = $(e).data('len-target');
    $(e).parent().parent().find(target).html('Знаков: ' + e.value.length);
}
