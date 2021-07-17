<?
App::uses('AppModel', 'Model');
class Contact extends AppModel {
	public $useTable = false;
	
	public $validate = array(
		'username' => array(
			'checkNotEmpty' => array(
				'rule' => 'notBlank',
				'message' => 'Field is mandatory',
			),
		),
		'phone' => array(
			'checkNotEmpty' => array(
				'rule' => 'notBlank',
				'message' => 'Field is mandatory',
			),
			'checkNameLen' => array(
				'rule' => array('minLength', 12),
				'message' => 'The number must be at least 12 characters.'
			),
		),
		'email' => 'email',
		'subj' => array(
			'checkNotEmpty' => array(
				'rule' => 'notBlank',
				'message' => 'Field is mandatory',
			),
		),
	);

}
