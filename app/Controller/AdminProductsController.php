<?php
App::uses('AppController', 'Controller');
App::uses('AdminController', 'Controller');
App::uses('AdminContentController', 'Controller');
class AdminProductsController extends AdminContentController {
    public $name = 'AdminProducts';
    public $uses = array('Product', 'Category', 'Subcategory', 'Brand', 'Tag');
    // public $helpers = array('Text', 'Media');

    public $paginate = array(
        'conditions' => array(),
        'fields' => array('created', 'cat_id', 'subcat_id', 'brand_id', 'title', 'Product.id AS tags', 'published', 'featured', 'Media.*'),
        'recursive' => 2,
        'order' => array('created' => 'desc'),
        'limit' => 20
    );

    public function beforeRender() {
        parent::beforeRender();
        $this->set('aCategories', $this->Category->find('list', array(
            'order' => 'Category.title'
        )));
        $this->set('aSubcategories', $this->Subcategory->find('all', array(
            'fields' => array('id', 'parent_id', 'title', 'Category.id', 'Category.title'),
            'order' => 'Subcategory.title'
        )));
        $this->set('aBrands', $this->Brand->find('list', array(
            'order' => 'Brand.title'
        )));
        $this->set('aTags', $this->Tag->find('list', array(
            'order' => 'Tag.sorting'
        )));
    }

}
