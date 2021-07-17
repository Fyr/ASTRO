<?
App::uses('AppController', 'Controller');
class RouterController extends AppController {
	var $name = 'Router';
	var $layout = false;
	var $uses = false;
	var $autoRender = false;

	public function beforeFilter() {
		$this->Auth->allow('index');
	}
	
	public function beforeRender() {
	}
	
	public function index($type, $id, $size, $filename) {
		App::uses('MediaPath', 'Media.Vendor');
		$this->PHMedia = new MediaPath();
		
		$aFName = $this->PHMedia->getFileInfo($filename);
		$fname = $this->PHMedia->getFileName($type, $id, $size, $filename);

		$fname = ($size == 'noresize') ? str_replace($filename, 'noresize.'.$aFName['ext'], $fname) : $fname;
		if (file_exists($fname) && filesize($fname)) {
			header('Content-type: image/'.$aFName['ext']);
			echo file_get_contents($fname);
			exit;
		}

		App::uses('Image', 'Media.Vendor');
		$image = new Image();
		
		$aSize = $this->PHMedia->getSizeInfo($size);
		$method = $this->PHMedia->getResizeMethod($size);
		$origImg = $this->PHMedia->getFileName($type, $id, null, $aFName['fname'].'.'.$aFName['orig_ext']);
		if ($method == 'thumb') {
			$thumb = $this->PHMedia->getFileName($type, $id, null, 'thumb.png');
			if (file_exists($thumb)) {
				$origImg = $thumb;
			}
		}

		$image->load($origImg);
		if ($aSize) {
			$method = $this->PHMedia->getResizeMethod($size);
			$image->{$method}($aSize['w'], $aSize['h']);
		}

		// накладываем водяной знак только для картинок с шириной > 400px и только для продуктов и событий
		if ($watermark = Configure::read('media.watermark')) {
			if (($type == 'product' && $image->getSizeX() > 200) || ($type == 'news' && $image->getSizeX() > 400)) {

				if ($image->getSizeX() > $image->getSizeY() && $image->getSizeX() > 1200) {
					$image->resize(1200, null);
				} else {
					// TODO: resize also portrait image to fit screen
				}

				$logo = new Image();
				$logo->load($watermark);
				$logoY = $image->getSizeY() / 20; // при высоте картинки в 400px лого должно быть 20px

				// лого должно быть не менее 20px и не более 40px по высоте
				if ($logoY > 40) {
					$logoY = 40;
				} elseif ($logoY < 20) {
					$logoY = 20;
				}
				$logoX = floor($logoY * $logo->getSizeX() / $logo->getSizeY());
				$logo->resize($logoX, null);

				imagealphablending($image->getImage(), false);
				imagesavealpha($image->getImage(), true);
				for ($x = 0; $x < $image->getSizeX(); $x += $logo->getSizeX()) {
					imagecopymerge($image->getImage(), $logo->getImage(),
						$x, $image->getSizeY() - $logo->getSizeY() * 2,
						0, 0,
						min($logo->getSizeX(), $image->getSizeX() - $x), $logo->getSizeY(),
						40
					);
				}
			}
		}

		if ($aFName['ext'] == 'jpg') {
			$image->outputJpg($fname);
			$image->outputJpg();
		} elseif ($aFName['ext'] == 'png') {
			$image->outputPng($fname);
			$image->outputPng();
		} else {
			$image->outputGif($fname);
			$image->outputGif();
		}
		exit;
	}
	
}
