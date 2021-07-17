<?php
App::uses('AppModel', 'Model');
App::uses('Media', 'Media.Model');
class Subcategory extends AppModel {
    public $useTable = 'categories';

    public $belongsTo = array(
        'Category' => array(
            'className' => 'Category',
            'foreignKey' => 'parent_id',
            'dependent' => false
        )
    );

    public $hasOne = array(
        'Media' => array(
            'className' => 'Media.Media',
            'foreignKey' => 'object_id',
            'conditions' => array('Media.media_type' => 'image', 'Media.object_type' => 'Subcategory', 'Media.main' => 1),
            'dependent' => false
        )
    );

    public function beforeFind($query) {
        $query['conditions']['Subcategory.parent_id > '] = '0';
        return $query;
    }

}
