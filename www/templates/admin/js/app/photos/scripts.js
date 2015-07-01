/**
 * User: FomalhautRed
 * Mail: demirurg@gmail.com
 * Date: 28.05.2015
 * Time: 15:53
 */

var photo = {
    init: function () {
        this.initSortable();
    },
    uploadModal: function (cat_id) {
        $('<div/>').dialog2({
            //title: "Загрузка",
            content: '/photos/addmodal/' + cat_id
        });
    },
    newCatModal: function () {
        $('<div/>').dialog2({
            content: '/photos/catmodal/'
        });
    },
    editCatModal: function (id) {
        $('<div/>').dialog2({
            content: '/photos/editcategory/' + id
        });
    },
    deleteCat: function (cat_id) {
        if (confirm('Вы уверены?')) {
            $.ajax({
                type: 'post',
                url: '/photos/deletecat',
                data: {id: cat_id},
                success: function () {
                    window.location = '/photos/list'
                }
            });
        }
    },
    initSortable: function () {
        $(".sortable").sortable({
            placeholder: "ui-state-highlight",
            opacity: 0.6,
            update: function (event, ui) {
                var cat_id = $('.row[data-cat-id]').data('cat-id');
                var order = [];
                var items = $(".sortable .item");
                items.each(function () {
                    order.push($(this).data('id'));
                });
                var orderJSON = JSON.stringify(order);
                $.ajax({
                    type: 'post',
                    url: '/photos/order/' + cat_id,
                    data: {order: orderJSON},
                    success: function (data) {
                        //alert(data);
                    }
                });
            }
        });
    }
};
$(function () {
    photo.init();
});
