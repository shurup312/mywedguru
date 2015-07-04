$(document).ready(function() 
{
    // Инициализируем таблицу TableDND
    $("#table").tableDnD({
        onDragClass: "DragRow",
        dragHandle: ".dragHandle",
        onDrop: function(table, row)
        {
            $("p.result").html('<button type="button" class="btn btn-success btn-sm" id="save-order"><i class="glyphicon glyphicon-ok"></i> Сохранить</button>');
            $(row).addClass('DragRow');
            var rows = table.tBodies[0].rows;
            var items = [];
            for (var i=0; i<rows.length; i++) 
            {
                items[i] = rows[i].id;     
            }

            // Сохраняем порядок
            $('button#save-order').click(function() {
                $("p.result").html('<img src="/public/components/menu_core/img/loader.gif" />');
                // Пишем порядок меню в базу
                var pid = $(row).attr('pid');
                $.ajax({
                    type: "POST",
                    url : "orderList",
                    data: "items="+items+"&pid="+pid,
                    success: function(menu)
                    {
                        if (menu !== 'error')
                        {
                            $("p.result").html("<div class='alert alert-success' style='width: 240px;text-align: center;padding:7px;margin:-7px;display:none;' role='alert'>Порядок меню успешно обновлен</div>");
                            $('div.alert').fadeIn('slow').css('display', '');
                            setTimeout(function(){$('div.alert').fadeOut('slow')}, 2000);
                            // Если изменили админское меню
                            if (pid == 1)
                            {
                                $("div.leftside-navigation").css('display', 'none').html(menu).slideDown(550);
                            }
                            $("#table tr.DragRow").switchClass("DragRow", "", 800, "linear");
                        }
                        else
                        {
                            $("p.result").html("<div class='alert alert-danger' style='width: 240px;text-align: center;padding:7px;margin:-7px;display:none;' role='alert'>Ошибка обновления меню!</div>");
                            $('div.alert').fadeIn('slow').css('display', '');
                            setTimeout(function(){$('div.alert').fadeOut('slow')}, 2000);
                            $("#table tr.DragRow").switchClass("DragRow", "", 800, "linear");
                        }
                    }
                });
            });
            // Перерисовка таблицы
            $("#table tr").removeClass('alt');
            $("#table tr:even").addClass('alt');
        }
    });
    $("#table tr:even").addClass('alt');
    $('#table td.move i').mousedown(function(eventObject){
        $(this).parent().parent().addClass('DragRow');
    });
    $('#table td.move i').mouseup(function(eventObject){
        $(this).parent().parent().removeClass('DragRow');
    });
});