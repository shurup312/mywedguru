<div class="well">В системе предусмотреный разные способы создания меню, <br> но в первую очередь это раздел со список страниц.</div>
    <?php
    $this->html->header.=" / <a href=\"/menu_core/newform\" class=\"btn bnt-xs btn-success\">Создать новое меню</a>";
    ?>


    <?php
    if (count($data)>=1)
    {
        ?>
    <table class="table">
        <tr>
            <th>ID</th>
            <th>Название меню</th>
            <th>Управление пунктами меню</th>
            <th>Редактировать</th>
            <th>Удалить</th>
        </tr>
        <?php
    foreach ($data as $k=>$v)
    {?>
        <tr>
            <td><?=$v->id;?></td>
            <td><?=$v->MenuName;?></td>
            <td><a href="/menu_core/menuList?id=<?=$v->id;?>"><i class="fa fa-list "></i> пункты меню</a> </td>
            <td><a href="/menu_core/editform?id=<?=$v->id;?>"><i class="fa fa-edit"></i> поправить</a></td>
            <td><a href="/menu_core/del?id=<?=$v->id;?>" class="btn btn-xs btn-danger"><i class="fa-trash-o fa"></i> удалить</a></td>
        </tr>
    <?php

    }
    ?>
    </table>
    <?php
   // var_dump($data);

}
?>