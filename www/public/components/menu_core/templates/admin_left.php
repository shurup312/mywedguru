<ul class="sidebar-menu" id="nav-accordion">
    <?php
    /*
     * $menuList = object(stdClass)[10]
     * public 'id' => string '11' (length=2)
     * public 'pid' => string '1' (length=1)
     * public 'MenuName' => string '&lt;i class=&quot;&quot;&gt;&lt;/i&gt;Страницы' (length=54)
     * public 'type' => string 'lin' (length=3)
     * public 'link' => string '/' (length=1)
     * public 'order' => string '0' (length=1)
     * public 'txt' => string '' (length=0)
     * public 'rights' => string '524287' (length=6)
     * public 'visible' => string 'y' (length=1)
     */
    foreach ($menuList as $k => $v) {
        ?>
        <li>
            <a href="<?=$v->link;?>">
                <?=html_entity_decode($v->MenuName);?>
            </a>
        </li>
    <?php
    }
    ?>
</ul>
