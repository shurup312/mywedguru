// После загрузки страницы

$(document).ready(function (){
    /*
    ВСЕ ссылки с class=pages_edit_form обрабатываются как вывод формы...
     */
    $('.pages_edit_form').click(function(e) {
        /*
        Перехватили значение id
         */
       id=$(this).attr('data-id');
        /*
        Получили ЭКШН
         */
       act =$(this).attr('data-action');
        if (act=='del_page')
        {
            if(!confirm('УВЕРЕНЫ ?'))
            {
                return false;
            }
        }
        /*
        Если айди = 0 то это создание страницы!!! по любому
         */
        url="/pages/adm/"+act;
        paree = $(this).closest('li');
        $.post(
            url,
            {
                "id": id
            }, //ЕСЛИ ВСЕ ПРОШЛО УСПЕШНО
            function (data) {
                if (act=='del_page')
                {
                    if (data=='') {
                        $(paree).hide();
                    } else {
                        alert(data);
                    }



                } else {
                    $('#com_pages_forma').html(data);
                    CKEDITOR.replace('content', {
                        filebrowserBrowseUrl: '/templates/admin/js/kcfinder/browse.php?opener=ckeditor&type=files',
                        filebrowserImageBrowseUrl: '/templates/admin/js/kcfinder/browse.php?opener=ckeditor&type=images',
                        filebrowserFlashBrowseUrl: '/templates/admin/js/kcfinder/browse.php?opener=ckeditor&type=flash',
                        filebrowserUploadUrl: '/templates/admin/js/kcfinder/upload.php?opener=ckeditor&type=files',
                        filebrowserImageUploadUrl: '/templates/admin/js/kcfinder/upload.php?opener=ckeditor&type=images',
                        filebrowserFlashUploadUrl: '/templates/admin/js/kcfinder/upload.php?opener=ckeditor&type=flash'
                    });
                }
            }
        );


    });

    $("#sidebar").addClass('hide-left-bar');
    $("#main-content").addClass('merge-left');

});

function cls ()
{
   document.getElementById('com_pages_forma').innerHTML="";

}
