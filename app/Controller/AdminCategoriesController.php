<?php
App::uses('AppController', 'Controller');
App::uses('AdminController', 'Controller');
App::uses('AdminContentController', 'Controller');
class AdminCategoriesController extends AdminContentController {
    public $name = 'AdminCategories';
    public $uses = array('Category');

    public $paginate = array(
        'conditions' => array('parent_id' => 0),
        'fields' => array('title', 'published', 'sorting', 'Media.*'),
        'order' => array('sorting' => 'asc'),
        'limit' => 20
    );
}
