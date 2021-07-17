<?php
App::uses('AdminController', 'Controller');
class AdminContentController extends AdminController {
    public $name = 'AdminContent';
    public $uses = array('Article');

	public $paginate = array(
		'conditions' => array(),
		'fields' => array('created', 'title', 'slug', 'published', 'featured', 'sorting'),
		'order' => array('sorting' => 'asc'),
		'limit' => 20
	);

	protected $parent_id = '', $parentModel = 'Article', $parentArticle = array();

	public function beforeRender() {
		parent::beforeRender();
		$this->set('objectType', $this->getModel());
		$this->set('parent_id', $this->parent_id);

		$model = $this->loadModel($this->parentModel);
		$this->parentArticle = ($this->parent_id) ? $model->findById($this->parent_id) : array();
		$this->set('parentArticle', $this->parentArticle);
	}

	protected function getModel() {
		list($plugin, $model) = pluginSplit($this->uses[0]);
		return $model;
	}

    public function index($parent_id = '') {
		if ($parent_id) {
			$this->parent_id = $parent_id;
			$this->paginate['conditions'][$this->getModel().'.parent_id'] = $parent_id;
		}
		foreach($this->paginate as &$field) {
			$field = str_replace('$lang', Configure::read('Config.language'), $field);
		}
		return $this->PCTableGrid->paginate($this->getModel());
    }

	protected function beforeSave($id) {
	}

	protected function afterSave($id) {
	}
    
	public function edit($id = 0, $parent_id = '') {
		$model = $this->getModel();
		if ($this->request->is(array('put', 'post'))) {
			if ($id) {
				$this->request->data($model.'.id', $id);
			} else {
				$this->request->data($model.'.object_type', $model);
				if ($parent_id) {
					$this->request->data($model . '.parent_id', $parent_id);
				}
			}
			$this->beforeSave($id);
			if ($this->{$model}->saveAll($this->request->data)) {
				$this->Flash->success(__('Record has been successfully saved'));
				$id = $this->{$model}->id;
				$this->afterSave($id);

				if ($this->request->data('apply')) {
					$route = array('action' => 'index');
					$parent_id = Hash::get($this->{$model}->findById($id), $model.'.parent_id');
					if ($parent_id) {
						$route[] = $parent_id;
					}
				} else {
					$route = array('action' => 'edit', $id);
				}
				return $this->redirect($route);
			}
		} else {
			$this->request->data = $this->{$model}->findById($id);
			if ($id) {
				$this->parent_id = $this->request->data($model.'.parent_id');
			} else {
				$this->parent_id = $parent_id;
				$this->request->data($model.'.parent_id', $parent_id);
				$this->request->data($model.'.sorting', '0');
			}
		}
	}

	public function delete($id) {
		$model = $this->getModel();
		$this->Flash->success(__('Record has been successfully deleted'));
		$this->{$model}->delete($id);
		return $this->redirect(array('action' => 'index'));
	}
}
