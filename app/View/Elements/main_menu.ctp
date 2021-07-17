<ul class="<?=(isset($class)) ? $class : 'menu'?>">
<?
    foreach($aNavBar as $curr => $item) {
        $active = ($curr == $currMenu) ? 'class="active"' : '';
?>
    <li <?=$active?>><?=$this->Html->link(__($item['title']), $item['url'])?></li>
<?
    }
?>
</ul>