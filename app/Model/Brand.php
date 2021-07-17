<?php
App::uses('AppModel', 'Model');
App::uses('Article', 'Article.Model');
class Brand extends Article {
    protected $objectType = 'Brand';

    var $hasOne = array(
        'Media' => array(
            'foreignKey' => 'object_id',
            'conditions' => array('Media.media_type' => 'image', 'Media.object_type' => 'Brand', 'Media.main' => 1),
            'dependent' => true
        ),
        'Seo' => array(
            'className' => 'Seo.Seo',
            'foreignKey' => 'object_id',
            'conditions' => array('Seo.object_type' => 'Brand'),
            'dependent' => true
        )
    );

}
