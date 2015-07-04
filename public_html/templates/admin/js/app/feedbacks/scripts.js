/**
 * User: FomalhautRed
 * Mail: demirurg@gmail.com
 * Date: 18.05.2015
 * Time: 11:44
 */

var list = {
    deleteItem: function (e) {
        if (confirm('Точно удалить?')) {
            var itemID = $(e).parent().data('item-id');
            $.ajax({
                type: 'post',
                url: '/feedbacks/delete',
                data: {itemID: itemID},
                success: function(data){
                    if(data === 'ok')
                    {
                        $(e).parent().parent().remove();
                    }else{
                        alert('Произошла ошибка, обратитесь к администратору');
                    }
                }
            });
        }
    }
};

