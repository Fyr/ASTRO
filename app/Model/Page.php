<?php
App::uses('AppModel', 'Model');
App::uses('Article', 'Article.Model');
class Page extends Article {
    protected $objectType = 'Page';

    var $hasOne = array(
        'Media' => array(
            'className' => 'Media.Media',
            'foreignKey' => 'object_id',
            'conditions' => array('Media.media_type' => 'image', 'Media.object_type' => 'Page', 'Media.main' => 1),
            'dependent' => false
        ),
        'Seo' => array(
            'className' => 'Seo.Seo',
            'foreignKey' => 'object_id',
            'conditions' => array('Seo.object_type' => 'Page'),
            'dependent' => true
        )
    );
}
