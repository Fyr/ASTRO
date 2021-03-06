<?
/**
 * Renders Media Widget
 * @param str $object_type
 * @param int $object_id
 */
/*
	$this->Html->css(array('jquery.fileupload-ui', '/Media/css/media'), array('inline' => false));
	$this->Html->script(array(
	   'vendor/jquery/jquery.iframe-transport', 
	   'vendor/jquery/jquery.fileupload',
	   'vendor/tmpl.min',
	   '/Table/js/format', 
	   '/Core/js/json_handler',
	   '/Media/js/media_grid',
	   '/Media/js/media_ui'
	), array('inline' => false));
*/
	$this->Html->css(array('/Media/css/media'), array('inline' => false));
	$this->Html->script(array(
		'vendor/tmpl.min',
		'/Table/js/format',
		'/Core/js/json_handler',
		'/Media/js/media_grid',
		'/Media/js/media_ui'
	), array('inline' => false));
?>
	<table width="100%">
	<tr>
		<td width="40%" valign="middle">
            <span class="btn blue fileinput-button">
    	        <i class="fa fa-plus"></i>
    	        <span><?=__d('media', 'Upload files...');?></span>
    	        <input id="fileupload" type="file" name="files[]" data-object_type="<?=$object_type?>" data-object_id="<?=$object_id?>" multiple>
    	    </span>
		</td>
		<td width="60%" align="center" valign="middle" style="padding-top: 10px;">
			<!--div id="progress" class="progress progress-primary progress-striped" style="margin-bottom: 0;">
                <div class="bar"></div>
            </div-->
			<div id="progress" class="progress progress-striped active">
				<div class="bar progress-bar progress-bar-info" role="progressbar"></div>
			</div>
		</td>
	</tr>
	</table>
	<br/>
	<table class="media-grid" width="100%">
	<tr>
		<td class="media-thumbs" width="65%">
			<!-- thumbs content here -->
		</td>
		<td class="media-info form-horizontal" width="35%">
			<script type="text/x-tmpl" id="media-info">
				{% if (o.media_type == 'image') { %}
				<button type="button" class="btn btn-success" onclick="mediaGrid.actionSetMain({%=o.id%})"><i class="icon-white icon-ok"></i> <?=__d('media', 'Set as main')?></button>
				{% } %}
				<button type="button" class="btn btn-danger" onclick="mediaGrid.actionDelete({%=o.id%})"><i class="icon-white icon-trash"></i> <?=__d('media', 'Delete')?></button>
				<br/>
				<h4><?=__d('media', 'Original file')?></h4>
				<?=__d('media', 'Uploaded')?>: {%=o.created%}<br/>
				<?=__d('media', 'File name')?>: {%=o.orig_fname%}<br/>
				<?=__d('media', 'File size')?>: {%=Format.fileSize(o.orig_fsize)%}<br/>
				{% if (o.media_type == 'image') { %}
				<?=__d('media', 'Resolution')?>: {%=o.orig_w%}x{%=o.orig_h%}px<br/>
				{% } %}
				<h4><?=__d('media', 'Links')?></h4>
				<div class="media-urls">
				{% if (o.media_type == 'image') { %}
				<?=__d('media', 'Original size')?>:<br/>
				<input type="text" class="form-control" id="media-url-orig" value="<?=Configure::read('baseURL.media')?>/media/router/index/{%=(o.object_type).toLowerCase()%}/{%=o.id%}/noresize/{%=o.file%}{%=o.ext%}" readonly="readonly" onfocus="this.select()"/>
				<?=__d('media', 'For editor')?>:<br/>
				<input type="text" class="form-control" id="media-url-editor" value="<?=Configure::read('baseURL.media')?>/media/router/index/{%=(o.object_type).toLowerCase()%}/{%=o.id%}/400x/{%=o.file%}{%=o.ext%}" readonly="readonly" onfocus="this.select()" />
				{% } %}
				<?=__d('media', 'For download')?>:<br/>
				<input type="text" class="form-control" id="media-url-download" value="{%=o.url_download%}" readonly="readonly" onfocus="this.select()" />
				<a href="{%=o.url_download%}" target="_blank"><?=__d('media', 'Download')?></a>
				</div>
				{% if (o.media_type == 'image') { %}
				<h4><?=__d('media', 'Resize & Rotate')?></h4>
				<div class="media-resize">
					<?=__d('media', 'Width')?> x <?=__d('media', 'Height')?>:
					<input type="text" class="form-control" id="media-w" value="{%=o.orig_w%}" onfocus="this.select()" onchange="mediaGrid.updateImageURL({%=o.id%})" /> x
					<input type="text" class="form-control" id="media-h" value="{%=o.orig_h%}" onfocus="this.select()" onchange="mediaGrid.updateImageURL({%=o.id%})" />
					<button type="button" class="btn" title="<?=__d('media', 'Refresh')?>"><i class="icon icon-refresh"></i></button>
					<button type="button" class="btn" onclick="mediaGrid.rotate({%=o.id%}, 1)" title="<?=__d('media', 'Rotate left')?>" style="margin-left: 20px;"><i class="icon icon-action-undo"></i></button>
					<button type="button" class="btn" onclick="mediaGrid.rotate({%=o.id%}, -1)" title="<?=__d('media', 'Rotate right')?>"><i class="icon icon-action-redo"></i></button>
				</div>
				<?=__d('media', 'Media URL')?>: <input type="text" class="form-control" id="media-url" value="" readonly="readonly" onfocus="this.select()" />

				{% } %}
			</script>
			
		</td>
	</tr>
	</table>
<script type="text/javascript">
var mediaGrid = null;
var mediaURL = null;
var mediaLocale = null;
$(function(){
	mediaURL = {
		upload: '<?=$this->Html->url(array('plugin' => 'media', 'controller' => 'ajax', 'action' => 'upload'))?>',
		move: '<?=$this->Html->url(array('plugin' => 'media', 'controller' => 'ajax', 'action' => 'move'))?>.json',
		list: '<?=$this->Html->url(array('plugin' => 'media', 'controller' => 'ajax', 'action' => 'getList', $object_type, $object_id))?>.json',
		delete: '<?=$this->Html->url(array('plugin' => 'media', 'controller' => 'ajax', 'action' => 'delete', $object_type, $object_id))?>/{$id}.json',
		setMain: '<?=$this->Html->url(array('plugin' => 'media', 'controller' => 'ajax', 'action' => 'setMain', $object_type, $object_id))?>/{$id}.json',
		rotate: '<?=$this->Html->url(array('plugin' => 'media', 'controller' => 'ajax', 'action' => 'rotate', $object_type, $object_id))?>/{$id}.json'
	};
	mediaLocale = {
		noFiles: '<?=__d('media', 'No media files found')?>',
		noData: '<?=__d('media', 'No media data')?>',
		continue: '<?=__d('media', 'Are your sure to continue?')?>',
		msgWarnInvalid: '<?=__d('media', 'All links on this media will be invalid')?>',
		msgSavedAsPng: '<?=__d('media', 'To preserve quality rotated image it will be saved as new PNG image')?>',
		msgOrigRemoved: '<?=__d('media', 'Original image will be removed')?>',
	}
});
</script>