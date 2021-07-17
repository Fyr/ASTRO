<?php
App::uses('AppController', 'Controller');
App::uses('AdminController', 'Controller');
App::uses('AdminContentController', 'Controller');
class AdminBrandsController extends AdminContentController {
    public $name = 'AdminBrands';
    public $uses = array('Brand');

    public $paginate = array(
        'conditions' => array(),
        'fields' => array('created', 'title', 'published', 'featured', 'Media.*'),
        'limit' => 10
    );
}
