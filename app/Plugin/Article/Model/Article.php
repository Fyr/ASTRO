<?php
App::uses('AppModel', 'Model');
class Article extends AppModel {
	public $useTable = 'articles';

	protected $objectType = '';

	/**
	 * Auto-add object type in find conditions
	 *
	 * @param array $query
	 * @return array
	 */
	public function beforeFind($query) {
		if ($this->objectType) {
			$query['conditions'][$this->objectType.'.object_type'] = $this->objectType;
		}
		return $query;
	}

	private function _getObjectConditions($objectType = '', $objectID = '') {
		$conditions = array();
		if ($objectType) {
			$conditions[$this->alias.'.object_type'] = $objectType;
		}
		if ($objectID) {
			$conditions[$this->alias.'.object_id'] = $objectID;
		}
		return compact('conditions');
	}

	public function getObjectOptions($objectType = '', $objectID = '', $order = 'sorting') {
		$conditions = array_values($this->_getObjectConditions($objectType, $objectID));
		return $this->find('list', compact('conditions', 'order'));
	}

	public function getObject($objectType = '', $objectID = '') {
		return $this->find('first', $this->_getObjectConditions($objectType, $objectID));
	}

	public function getObjectList($objectType = '', $objectID = '', $order = 'sorting') {
		$conditions = array_values($this->_getObjectConditions($objectType, $objectID));
		return $this->find('all', compact('conditions', 'order'));
	}

}
