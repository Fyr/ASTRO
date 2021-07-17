<?php
App::uses('AppController', 'Controller');
App::uses('Brand', 'Model');
class BrandsController extends AppController {
	public $uses = array('Brand');

	public function index() {
		$this->paginate = array(
			'Brand' => array(
				'conditions' => array('published' => 1),
				'order' => array('modified' => 'desc'),
				'limit' => 6
			)
		);
		$this->set('aArticles', $this->paginate('Brand'));
	}

	public function view($id) {
		$article = $this->Brand->findById($id);
		$this->set('article', $article);
		$this->currMenu = 'Brands';
	}
}

