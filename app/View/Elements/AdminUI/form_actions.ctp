<?
	if (!isset($backURL)) {
		if (isset($parent_id) && $parent_id) {
			$backURL = array('action' => 'index', $parent_id);
		} else {
			$backURL = array('action' => 'index');
		}
	}
?>

<div class="form-actions">
	<div class="row">
		<div class="col-md-3">
			<a class="btn default" href="<?=Router::url($backURL)?>">
				<i class="fa fa-angle-left"></i>&nbsp;&nbsp;<?=__('Back')?>
			</a>
		</div>
		<div class="col-md-6">
			<button type="submit" class="btn blue" name="save" value="save">
				<i class="fa fa-save"></i>&nbsp;&nbsp;<?=__('Save')?>
			</button>
		</div>
		<div class="col-md-3">
			<?=$this->element('AdminUI/form_apply')?>
		</div>
	</div>
</div>
