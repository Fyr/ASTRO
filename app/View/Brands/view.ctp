<?
	$this->ArticleVars->init($article, $url, $title, $teaser, $src, '1200x');
?>
<div class="welcomeSection">
	<div class="wrapper">
		<?=$this->element('title', compact('title'))?>
		<br/><br/>
		<?=$this->ArticleVars->body($article)?>
	</div>
</div>
