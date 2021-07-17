<?php
App::uses('AppHelper', 'View/Helper');
App::uses('SiteRouter', 'Lib/Routing');
class ArticleVarsHelper extends AppHelper {
	public $helpers = array('Media');

	public function init($article, &$url, &$title, &$teaser = '', &$src = '', $size = 'noresize', &$id = '') {
		$objectType = $this->getObjectType($article);
		$id = $article[$objectType]['id'];
		
		$url = SiteRouter::url($article);
		$title = $article[$objectType]['title'];

		$teaser = nl2br($article[$objectType]['teaser']);

		$src = $this->Media->imageUrl($article, $size);
	}

	public function body($article) {
		$objectType = $this->getObjectType($article);
		return '<article>'.$article[$objectType]['body'].'</article>';
	}

	public function divideColumns($items, $cols) {
		$aCols = array();
		$col = 0;
		$count = 0;
		$total = ceil(count($items) / $cols) ;
		$i = 0;
		foreach($items as $key => $item) {
			$aCols[$col][$key] = $item;
			$count++;
			$i++;
			if ($count >= $total && $i < count($items)) {
				$col++;
				$total = ceil((count($items) - $i) / ($cols - $col));
				$count = 0;
			}
		}
		return $aCols;
	}

	public function list2array($list) {
		$list = str_replace(array('<br />', '<br>'), '', trim($list)); // почему-то иногда при добавлении записи в textarea есть <br>
		return explode("\n", str_replace("\r\n", "\n", trim($list)));
	}

}
