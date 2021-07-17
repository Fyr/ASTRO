<?php
App::uses('AppModel', 'Model');
// App::uses('Article', 'Article.Model');
class Tag extends AppModel {
    protected $objectType = 'Tag';

    var $hasOne = array(
        'Media' => array(
            'foreignKey' => 'object_id',
            'conditions' => array('Media.media_type' => 'image', 'Media.object_type' => 'Tag', 'Media.main' => 1),
            'dependent' => true
        ),
    );

}
