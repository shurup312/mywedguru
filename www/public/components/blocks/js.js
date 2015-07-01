/**
 * Created by Женя on 17.01.2015.
 */
tinymce.init({
    theme: "modern",
    plugins: [
        "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
        "save table contextmenu directionality emoticons template paste textcolor"
    ],
    relative_urls : false,
    /*content_css: "css/content.css",*/
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | preview media fullpage | forecolor backcolor emoticons",
    style_formats: [
        {title: 'Bold text', inline: 'b'},
        {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
        {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
        {title: 'Example 1', inline: 'span', classes: 'example1'},
        {title: 'Example 2', inline: 'span', classes: 'example2'},
        {title: 'Table styles'},
        {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
    ],
    image_advtab: true,
    language : 'ru',
    external_filemanager_path:"/templates/admin/js/tinymce/plugins/filemanager/",
    filemanager_title:"Responsive Filemanager",
    external_plugins: { "filemanager" : "/templates/admin/js/tinymce/plugins/filemanager/plugin.min.js"}
});



function setup()
{

    tinymce.execCommand("mceAddEditor", true, "txt");
}
function desetup()
{
    tinymce.execCommand("mceRemoveEditor", true, "txt");
}
$(document).ready(function(){ if ($('#type3').attr("checked")){ setup();}});
var selected_rows = new Array();
// отметка строки
function clicked(t)
{
    if ($(t).parent('tr').hasClass('clicked')) {
        $(t).parent('tr').removeClass('clicked');
        selected_rows.splice(selected_rows.indexOf($(t).attr('id')), 1); //удаляем элемент массива
    } else {
        $(t).parent('tr').addClass('clicked');
        selected_rows.push($(t).attr('id'));
    }
    $('#nav_bat_panel').css({'display':((selected_rows.length == 0)?'none':'block')});
    //alert(selected_rows);
}
//удаление строк
function del_all_selected_rows()
{
    if (confirm("Уверены что хотите удалить ?"))
    {
        $.post(
            '/blocks',
            {
                'act': 'del_all',
                'id': selected_rows.join('_')
            },
            function (data) {
                for (var key in selected_rows) {
                    var val = selected_rows[key];
                    $('#'+val).parent('tr').remove();
                }
            }
        );
    }
}
