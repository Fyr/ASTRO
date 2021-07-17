<?php
App::uses('AppController', 'Controller');
App::uses('AdminController', 'Controller');
App::uses('AdminContentController', 'Controller');
class AdminTagsController extends AdminContentController {
    public $name = 'AdminTags';
    public $uses = array('Tag');

    public $paginate = array(
        'fields' => array('title', 'sorting', 'Media.*'),
        'order' => array('sorting' => 'asc'),
        'limit' => 20
    );
}
