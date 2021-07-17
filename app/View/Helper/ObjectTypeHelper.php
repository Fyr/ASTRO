<?php
App::uses('AppHelper', 'View/Helper');
class ObjectTypeHelper extends AppHelper {
    public $helpers = array('Html');
    
    private function _getTitles() {
        $aTitles = array(
            'index' => array(
                'Article' => __('Articles'),
                'Page' => __('Static pages'),
                'News' => __('News'),
                'Brand' => __('Brands'),
                'Tag' => __('Tags'),
                'Category' => __('Categories'),
                'Subcategory' => __('Subcategories'),
                'Product' => __('Products'),
            ),
            'create' => array(
                'Article' => __('Create article'),
                'Page' => __('Create static page'),
                'News' => __('Create news article'),
                'Brand' => __('Create brand'),
                'Tag' => __('Create tag'),
                'Category' => __('Create category'),
                'Subcategory' => __('Create subcategory'),
                'Product' => __('Create product'),
            ),
            'edit' => array(
                'Article' => __('Edit article'),
                'Page' => __('Edit static page'),
                'News' => __('Edit news article'),
                'Brand' => __('Edit brand'),
                'Tag' => __('Edit tag'),
                'Category' => __('Edit category'),
                'Subcategory' => __('Edit subcategory'),
                'Product' => __('Edit product'),
            ),
            'view' => array(
            	'Article' => __('View article'),
            	'News' => __('View news article'),
            	'Product' => __('View product'),
            )
        );
        return $aTitles;
    }
    
    public function getTitle($action, $objectType) {
        $aTitles = $this->_getTitles();
        return (isset($aTitles[$action][$objectType])) ? $aTitles[$action][$objectType] : $aTitles[$action]['Article'];
    }
    
    public function getBaseURL($objectType, $objectID = '') {
        return $this->Html->url(array('action' => 'index', $objectType, $objectID));
    }
}