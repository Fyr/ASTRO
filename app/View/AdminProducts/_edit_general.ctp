<?
	echo $this->element('AdminUI/checkboxes', array('labels' => array('published' => __('Published'), 'featured' => __('For home page'))));
	echo $this->PHForm->input('cat_id', array(
		'options' => $aCategories,
		'value' => $this->request->data('Product.cat_id'),
		'onchange' => 'category_onChange(this)',
		'label' => array('class' => 'col-md-3 control-label', 'text' => __('Category'))
	));

	$aSubcategories = Hash::combine($aSubcategories, '{n}.Subcategory.id', '{n}', '{n}.Subcategory.parent_id');
?>
	<div class="form-group">
		<label class="col-md-3 control-label" for="ProductSubCatId"><?=__('Subcategory')?></label>
		<div class="col-md-9">
			<select id="ProductSubCatId" class="form-control" name="data[Product][subcat_id]" autocomplete="off">
<?
	foreach($aCategories as $cat_id => $title) {
?>
				<optgroup id="cat-<?=$cat_id?>" label="<?=$title?>">
<?
		foreach($aSubcategories[$cat_id] as $subcat) {
			$selected = ($this->request->data('Product.subcat_id') == $subcat['Subcategory']['id']) ? ' selected="selected"' : '';
?>
					<option value="<?=$subcat['Subcategory']['id']?>"<?=$selected?>><?=$subcat['Subcategory']['title']?></option>
<?
		}
?>
				</optgroup>
<?
		}
?>

			</select>
		</div>
	</div>
<?
	echo $this->PHForm->input('brand_id', array(
		'options' => $aBrands,
		'value' => $this->request->data('Product.brand_id'),
		'label' => array('class' => 'col-md-3 control-label', 'text' => __('Brand'))
	));
	echo $this->PHForm->input('title');
	echo $this->PHForm->input('teaser');
	// echo $this->PHForm->input('sorting', array('class' => 'form-control input-small'));

	$checkboxes = array();
	$labels = array();
	$values = array();
	foreach($aTags as $tag_id => $tag) {
		$modelField = "ProductTag.".$tag_id;
		$checkboxes[] = $modelField;
		$labels[$modelField] = $tag;
	}
	if ($this->request->data('ProductTag')) {
		foreach ($this->request->data('ProductTag') as $tag) {
			$modelField = "ProductTag." . $tag['tag_id'];
			$this->request->data($modelField, 1);
		}
	}
	$title = __('Tags');
	echo $this->element('AdminUI/checkboxes', compact('title', 'checkboxes', 'labels', 'values'));

	$subcat_id = $this->request->data('Product.subcat_id');
?>
<script type="text/javascript">
function category_onChange(e, subcat_id) {
	$('#ProductSubCatId optgroup').hide();
	var $optgroup = $('#ProductSubCatId optgroup#cat-' + $(e).val());
	$optgroup.show();
	$('#ProductSubCatId').val((subcat_id) ? subcat_id : $('option:first', $optgroup).attr('value'));
}

$(document).ready(function() {
	category_onChange($('#ProductCatId').get(0), <?=($subcat_id) ? $subcat_id : '0'?>);
});
</script>