<?php
	if (TEST_ENV) {
?>
<h2><?php echo $name; ?></h2>
<p class="error">
	<strong><?=__d('cake', 'Error'); ?>: </strong>
	<?=__d('cake', 'An Internal Error Has Occurred.'); ?>
</p>
<?
		echo $this->element('exception_stack_trace');
	} else {
?>
<div class="container">
<?
		$title = __('Page not found');
		$class = 'light';
		echo $this->element('SiteUI/title', compact('title', 'class'));
?>
	<div class="article" style="margin-bottom: 100px">
		<p>
			<b>Внимание!</b> По вашему запросу произошла ошибка сервера.<br />
			Наши специалисты уже занимаются ей, в ближайее время ошибка будет исправлена.<br />
			<br />
			<a href="/">На главную</a>
		</p>
	</div>
</div>
<?
	}
?>
