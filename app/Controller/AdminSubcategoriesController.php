<?php
App::uses('AppController', 'Controller');
App::uses('AdminController', 'Controller');
App::uses('AdminContentController', 'Controller');
class AdminSubcategoriesController extends AdminContentController {
    public $name = 'AdminSubcategories';
    public $uses = array('Subcategory');

    protected $parentModel = 'Category';

    public $paginate = array(
        'fields' => array('title', 'published', 'sorting'),
        'order' => array('sorting' => 'asc'),
        'limit' => 20
    );

}
