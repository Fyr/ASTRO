<?php
App::uses('AppController', 'Controller');
App::uses('AdminController', 'Controller');
App::uses('AdminContentController', 'Controller');
class AdminPagesController extends AdminContentController {
    public $name = 'AdminPages';
    public $uses = array('Page');

    public $paginate = array(
        'fields' => array('title', 'slug'),
        'order' => array('published' => 'desc'),
        'limit' => 20
    );
}
