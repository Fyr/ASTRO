<?
	$_aSubcategories = Hash::combine($aSubcategories, '{n}.Subcategory.id', '{n}', '{n}.Subcategory.parent_id');
?>
<div class="col-md-3 col-sm-4">
	<div class="subMenu">
		<div class="title"><?=$title?></div>
		<ul>
<?
	foreach($aCategories as $category) {
		$this->ArticleVars->init($category, $url, $title, $teaser, $src, 'noresize', $cat_id);
		$cat_active = (isset($filter['cat_id']) && $filter['cat_id'] == $cat_id) ? 'class="active"' : '';
		if (!isset($_aSubcategories[$cat_id])) {
?>
			<li <?=$cat_active?>><span><a id="cat_<?=$cat_id?>" href="<?=$url?>"><?=$title?></a></span></li>
<?
		} else {
?>
			<li <?=$cat_active?>>
				<span><a id="cat_<?=$cat_id?>" href="javascript:void(0)"><?=$title?></a></span>
				<ul <?=($cat_active) ? 'style="display: block"' : ''?>>
<?
			foreach ($_aSubcategories[$cat_id] as $subcategory) {
				$this->ArticleVars->init($subcategory, $url, $title, $teaser, $src, 'noresize', $subcat_id);
				$subcat_active = (isset($filter['subcat_id']) && $filter['subcat_id'] == $subcat_id) ? 'active' : '';
?>
					<li class="effective <?=$subcat_active?>"><a href="<?=$url?>"><?=$title?></a></li>
<?
			}
?>
				</ul>
			</li>
<?
		}
	}
?>

		</ul>
	</div>
</div>
