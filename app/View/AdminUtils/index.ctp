<?=$this->element('AdminUI/title', array('title' => __('Utils')))?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
<?
	$backURL = $this->Html->url(array('plugin' => '', 'controller' => 'AdminUtils', 'action' => 'index'));
	$aURL = array(
		__('Generate static labels') => array('plugin' => 'translate', 'controller' => 'index', 'action' => 'generate'),
		__('Clean image cache') => array('controller' => 'AdminUtils', 'action' => 'cleanImageCache')
	);
	foreach($aURL as $title => $url) {
		echo $this->Html->link($title, $url, array('class' => 'btn btn-primary')).'&nbsp;';
	}
?>
		</div>
	</div>
</div>
