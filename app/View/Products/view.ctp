<?
    $this->Html->css('jquery.fancybox', array('inline' => false));
    $this->Html->script(array('vendor/jquery.fancybox.pack'), array('inline' => false));

    $this->ArticleVars->init($article, $url, $title, $teaser, $src, '');
    $aFiles = array();
    $aImages = array();
    foreach($aMedia as $media) {
        if ($media['Media']['media_type'] == 'raw_file') {
            $aFiles[] = $media;
        } else if ($media['Media']['media_type'] == 'image') {
            $aImages[] = $media;
        }
    }
    $tags = array();
    foreach($article['ProductTag'] as $tag) {
        $tags[] = $aTags[$tag['tag_id']];
    }
    $aTable = array(
        __('Category') => $article['ProductCategory']['title'].' / '.$article['ProductSubcategory']['title'],
        __('Brand') => $article['ProductBrand']['title'],
        __('Tags') => implode(', ', $tags)
    );
?>
<div class="welcomeSection">
    <div class="wrapper">
        <?=$this->element('title', compact('title'))?>
<?
    foreach($aTable as $title => $val) {
?>
        <b><?=$title?></b>: <?=$val?><br />
<?
    }
?>
        <br/>
        <?=$this->ArticleVars->body($article)?>
        <div class="article" style="text-align: left; margin: 1em 0;">
<?
    if ($aFiles) {
?>
        <b><?=__('Download')?>:</b><br/>
<?
    }
    foreach($aFiles as $media) {
        $src = 'raw_file.png';
        if ($media['Media']['ext'] == '.pdf') {
            $src = 'pdf.png';
        } else if ($media['Media']['ext'] == '.doc' || $media['Media']['ext'] == '.docx') {
            $src = 'ms-word.png';
        }
?>
            <a class="img-icon" href="<?=$media['Media']['url_download']?>">
                <img src="/media/img/<?=$src?>" /> <span><?=$media['Media']['orig_fname']?></span>
            </a>
            <br/>
<?
    }
?>
        </div>

    </div>
</div>
<div class="recentProductsSection" style="padding-top: 0">
    <div class="wrapper">
        <ul class="products">
<?
    foreach($aImages as $media) {
        $thumb = $this->Media->imageUrl($media, '400x');
        $noresize = $this->Media->imageUrl($media, 'noresize');
?>
            <li>
                <a class="picture fancybox" href="<?=$noresize?>" rel="gallery" style="background-image: url('<?=$thumb?>');"></a>
            </li>
<?
    }
?>
        </ul>
    </div>
</div>
<script>
$(function(){
    if ($('.fancybox').length) {
        $('.fancybox').fancybox({
            padding: 5
        });
    }
});
</script>