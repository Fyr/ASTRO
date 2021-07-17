<?php
App::uses('AppHelper', 'View/Helper');
class MediaHelper extends AppHelper {
	private $MediaPath;
	
	public function __construct(View $view, $settings = array()) {
		parent::__construct($view, $settings);
		
		App::uses('MediaPath', 'Media.Vendor');
		$this->MediaPath = new MediaPath();
	}
	
	public function imageUrl($mediaRow, $size = 'noresize') {
		if (!(isset($mediaRow['Media']) && $mediaRow['Media'] && isset($mediaRow['Media']['id']) && $mediaRow['Media']['id']) ) {
			return '/img/nophoto.jpg';
		}
		$media = $mediaRow['Media'];
		return $this->MediaPath->getImageUrl($media['object_type'], $media['id'], $size, $media['file'].$media['ext'].'.png');
	}
}
