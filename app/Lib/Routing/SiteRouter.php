<?
App::uses('Router', 'Cake/Routing');
class SiteRouter extends Router {

	static public function getObjectType($article) {
		list($objectType) = array_keys($article);
		return $objectType;
	}

	static public function url($article) {
		$objectType = self::getObjectType($article);
		if ($objectType == 'Product') {
			$url = array(
				'controller' => 'products',
				'action' => 'view',
				$article['Product']['id']
				// $article['Product']['slug']
			);
		} elseif ($objectType == 'News') {
			$url = array(
				'controller' => 'news',
				'action' => 'view',
				$article['News']['id']
			);
		} elseif ($objectType == 'Brand') {
			$url = array(
				'controller' => 'brands',
				'action' => 'view',
				$article['Brand']['id']
			);
		} elseif ($objectType == 'Category') {
			$url = array(
				'controller' => 'products',
				'action' => 'index',
				'?' => array('cat_id' => $article['Category']['id'])
			);
		} elseif ($objectType == 'Subcategory') {
			$url = array(
				'controller' => 'products',
				'action' => 'index',
				'?' => array('cat_id' => $article['Subcategory']['parent_id'], 'subcat_id' => $article['Subcategory']['id'])
			);
		} elseif ($objectType == 'Page') {
			$action = 'view';
			if (in_array($article['Page']['slug'], array('museum', 'customers', 'exposition'))) {
				$action = 'about';
			} elseif (in_array($article['Page']['slug'], array('history-pdf', 'history'))) {
				$action = 'history';
			}
			$url = array(
				'controller' => 'pages',
				'action' => $action,
				$article['Page']['slug']
			);
		} else {
			$url = array(
				'controller' => 'articles',
				'action' => 'view',
				'objectType' => $objectType,
				'slug' => $article[$objectType]['id']
			);
		}
		return parent::url($url);
	}
	
}