<?
	$this->Html->script('vendor/ckeditor/ckeditor', array('inline' => false));
	$this->Html->css('/js/vendor/ckeditor/fixes', array('inline' => false));
	$field = (isset($field) && $field) ? $field : $this->PHForm->defaultModel.'.body';
	echo $this->PHForm->input($field, array('class' => 'ckeditor', 'label' => false));
/*
	$this->Html->script('components-editors', array('inline' => false));
?>
<div id="summernote_1">
	<?=$this->request->data($this->PHForm->defaultModel.'.body')?>
</div>
<input type="hidden" id="summernote_value" name="data[<?=$this->PHForm->defaultModel?>][body]" value="">
<script>
jQuery(function(){
	$('#summernote_1').closest('form').submit(function(){
		$('#summernote_value').val($('#summernote_1').code());
		return true;
	});
});
</script>

<?
*/
?>