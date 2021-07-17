<?php
App::uses('AppController', 'Controller');
App::uses('Media', 'Media.Model');
class ProductsController extends AppController {
	public $name = 'Products';
	public $uses = array('Media.Media', 'Product', 'Category', 'Subcategory', 'Tag', 'ProductTag');

	protected $aCategories, $aSubcategories, $aTags;

	public function beforeFilterLayout() {
		$order = 'Category.sorting';
		$this->aCategories = $this->Category->find('list', compact('order'));
		$order = 'Subcategory.sorting';
		$this->aSubcategories = $this->Subcategory->find('list', compact('order'));
		$order = 'Tag.sorting';
		$this->aTags = $this->Tag->find('list', compact('order'));

		$this->set(array(
			'aCategories' => $this->aCategories,
			'aSubcategories' => $this->aSubcategories,
			'aTags' => $this->aTags
		));

		$this->currMenu = 'Products';
		parent::beforeFilterLayout();
	}

	private function _getFilter() {
		$filter = array();
		$filterNames = array();

		if ($cat_id = $this->request->query('cat_id')) {
			$filter['Product.cat_id'] = $cat_id;
			$filterNames[__('Category')] = $this->aCategories[$cat_id];
		}
		if ($subcat_id = $this->request->query('subcat_id')) {
			$filter['Product.subcat_id'] = $subcat_id;
			$filterNames[__('Subcategory')] = $this->aSubcategories[$subcat_id];
		}
		if ($q = $this->request->query('q')) {
			$filter['OR'] = array('Product.title LIKE ' => "%$q%", 'Product.teaser LIKE ' => "%$q%");
		}
		if ($tag_id = $this->request->query('tag')) {
			$rowset = $this->ProductTag->findAllByTagId($tag_id);
			$filter['Product.id'] = Hash::extract($rowset, '{n}.ProductTag.product_id');
			$filterNames[__('Tags')] = $this->aTags[$tag_id];
		}
		$this->set(compact('filter', 'filterNames'));
		return $filter;
	}

	public function index() {
		$filter = $this->_getFilter();
		$this->paginate = array(
			'Product' => array(
				'conditions' => array_merge(array('Product.published' => 1), $filter),
				'order' => array('modified' => 'desc'),
				'limit' => 8
			)
		);
		$this->set('aArticles', $this->paginate('Product'));
		// $this->set('lDirectSearch', $this->request->query('q') && true);
	}

	public function view($id) {
		$article = $this->Product->findById($id);
		$aMedia = $this->Media->getList(
			array('object_type' => 'Product', 'object_id' => $id),
			array('created' => 'asc')
		);
		$aProducts = $this->Product->find('all', compact('conditions', 'order'));
		$this->set(compact('article', 'aMedia', 'aProducts'));
	}


}
