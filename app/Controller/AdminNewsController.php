<?php
App::uses('AppController', 'Controller');
App::uses('AdminController', 'Controller');
App::uses('AdminContentController', 'Controller');
class AdminNewsController extends AdminContentController {
    public $name = 'AdminNews';
    public $uses = array('News');

    public $paginate = array(
        'conditions' => array(),
        'fields' => array('modified', 'title', 'slug', 'published', 'featured', 'Media.*'),
        'limit' => 10
    );
}
