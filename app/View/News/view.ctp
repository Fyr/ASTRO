<?
	$this->ArticleVars->init($article, $url, $title, $teaser, $src, '');
?>
<div class="welcomeSection">
	<div class="wrapper">
		<?=$this->element('title', compact('title'))?>
		<span class="date"><?=date('d.m.Y', strtotime($article['News']['modified']))?></span>
		<br/><br/>
		<?=$this->ArticleVars->body($article)?>
	</div>
</div>
