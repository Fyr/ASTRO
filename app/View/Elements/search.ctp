<form action="<?=$this->Html->url(array('controller' => 'products', 'action' => 'index'))?>" class="outerSearch" method="get">
    <input type="text" name="q" placeholder="<?=__('search on site')?>" value="<?=$this->request->query('q')?>" />
    <button type="submit" class="icon-search"></button>
</form>