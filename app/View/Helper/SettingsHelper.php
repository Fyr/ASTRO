<?php
App::uses('AppHelper', 'View/Helper');

class SettingsHelper extends AppHelper {

	public function read($option) {
		return Configure::read('Settings.'.$option);
	}
}
