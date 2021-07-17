<?
	if ($this->Paginator->numbers()) {
		/*
		$options = array(
			'controller' => $this->request->controller,
			'action' => $this->request->action
		);
		*/
		$options = array();
		if ($this->request->query) {
			$options['?'] = $this->request->query;
		}
		$this->Paginator->options(array('url' => $options));
?>
<div class="pagination">
	<?=__('Pages')?>: <?=$this->Paginator->numbers(array('separator' => ' '))?>
</div>
<?
	}
?>