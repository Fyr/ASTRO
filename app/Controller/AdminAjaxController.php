<?php
App::uses('AppController', 'Controller');
App::uses('PAjaxController', 'Core.Controller');
App::uses('Task', 'Model');
class AdminAjaxController extends PAjaxController {
	public $name = 'AdminAjax';
	public $components = array('Core.PCAuth');
	public $uses = array('Task');

	public function getTaskStatus($id) {
		$task = $this->Task->getData($id);
		if ($task) {
			$task['progress'] = $this->Task->getProgressInfo($id);
			$subtask_id = $this->Task->getData($id, 'subtask_id');
			if ($subtask_id) {
				$subtask = $this->Task->getData($subtask_id);
				$subtask['progress'] = $this->Task->getProgressInfo($subtask_id);
				$task['subtask'] = $subtask;
			}
		}
		$this->setResponse($task);
	}

	public function taskAbort($id) {
		$task = $this->Task->getData($id);
		if ($task) {
			$this->Task->setStatus($id, Task::ABORT);
		}
		$this->setResponse(true);
	}
}
