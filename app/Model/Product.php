<?php
App::uses('AppModel', 'Model');
App::uses('Category', 'Model');
App::uses('Media', 'Media.Model');
class Product extends AppModel {
    public $validate = array(
        'title' => array(
            'checkNotEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Field is mandatory',
            )
        ),
    );

    public $belongsTo = array(
        'ProductCategory' => array(
            'className' => 'Category',
            'foreignKey' => 'cat_id',
            'dependent' => false
        ),
        'ProductSubcategory' => array(
            'className' => 'Subcategory',
            'foreignKey' => 'subcat_id',
            'dependent' => false
        ),
        'ProductBrand' => array(
            'className' => 'Brand',
            'foreignKey' => 'brand_id',
            'dependent' => false
        )
    );

    public $hasOne = array(
        'Media' => array(
            'className' => 'Media.Media',
            'foreignKey' => 'object_id',
            'conditions' => array('Media.media_type' => 'image', 'Media.object_type' => 'Product', 'Media.main' => 1),
            'dependent' => true
        ),
        'Seo' => array(
            'className' => 'Seo.Seo',
            'foreignKey' => 'object_id',
            'conditions' => array('Seo.object_type' => 'Product'),
            'dependent' => true
        )
    );
    public $hasMany = array(
        'ProductTag' => array(
            'dependent' => true
        )
    );

    public function saveAll($data, $options = array()) {
        if (isset($data['ProductTag']) && $data['ProductTag']) {
            $tagData = array();
            foreach($data['ProductTag'] as $tag_id => $isOn) {
                if ($isOn) {
                    $tagData[] = compact('tag_id');
                }
            }
            $data['ProductTag'] = $tagData;
            if (isset($data['Product']['id']) && $data['Product']['id']) {
                $this->ProductTag = $this->loadModel('ProductTag');
                $this->ProductTag->deleteAll(array('product_id' => $data['Product']['id']));
            }
        }
        return parent::saveAll($data, $options);
    }
}
