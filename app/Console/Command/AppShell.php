<?php
/**
 * AppShell file
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 2.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Shell', 'Console');

/**
 * Application Shell
 *
 * Add your application-wide methods in the class below, your shells
 * will inherit them.
 *
 * @package       app.Console.Command
 */
class AppShell extends Shell {
    public $id = 0, $user_id = 0, $params = array(); // нужно для BkgServiceShell

	public function execute() {
		// перекрываемый метод для выполнения фон.задачи
	}

	public function cleanup() {
		// перекрываемый метод выполняемый после выполнения фон.задачи
	}

	public function loadModel($modelClass = null, $id = null) {
		if ($modelClass === null) {
			$modelClass = $this->modelClass;
		}

		$this->uses = ($this->uses) ? (array)$this->uses : array();
		if (!in_array($modelClass, $this->uses, true)) {
			$this->uses[] = $modelClass;
		}

		list($plugin, $modelClass) = pluginSplit($modelClass, true);

		$this->{$modelClass} = ClassRegistry::init(array(
			'class' => $plugin . $modelClass, 'alias' => $modelClass, 'id' => $id
		));
		if (!$this->{$modelClass}) {
			throw new MissingModelException($modelClass);
		}
		return $this->{$modelClass};
	}

    public function sqlLog() {
		if (!class_exists('ConnectionManager') || Configure::read('debug') < 2) {
			return false;
		}
		$noLogs = !isset($sqlLogs);
		if ($noLogs) {
			$sources = ConnectionManager::sourceList();

			$sqlLogs = array();
			foreach ($sources as $source) {
				$db = ConnectionManager::getDataSource($source);
				if (!method_exists($db, 'getLog')) {
					continue;
				}
				$sqlLogs[$source] = $db->getLog();
			}
		}

		if ($noLogs || isset($_forced_from_dbo_)) {
			foreach ($sqlLogs as $source => $logInfo) {
				$text = $logInfo['count'] > 1 ? 'queries' : 'query';
				foreach ($logInfo['log'] as $k => $i) {
					$i += array('error' => '');
					if (!empty($i['params']) && is_array($i['params'])) {
						$bindParam = $bindType = null;
						if (preg_match('/.+ :.+/', $i['query'])) {
							$bindType = true;
						}
						foreach ($i['params'] as $bindKey => $bindVal) {
							if ($bindType === true) {
								$bindParam .= h($bindKey) . " => " . h($bindVal) . ", ";
							} else {
								$bindParam .= h($bindVal) . ", ";
							}
						}
						$i['query'] .= " , params[ " . rtrim($bindParam, ', ') . " ]";
					}
					$sql = $i['query'];
					foreach (array(' FROM ', ' LEFT JOIN ', ' JOIN ', ' GROUP BY ', ' ORDER BY ', ' LIMIT ', ' WHERE ') as $stmt) {
						$sql = str_ireplace($stmt, "\r\n" . trim($stmt) . ' ', $sql);
					}
					foreach (array('SELECT', 'INSERT', 'UPDATE', 'DELETE') as $stmt) {
						// $sql = str_ireplace($stmt, "<b>".$stmt."</b>", $sql);
					}

					$i['query'] = $sql;
					fdebug(sprintf('%s; %sN %d, err:%s, Affected: %d, rows: %d, took: %d %s',
						$i['query'],
						"\r\n",
						$k + 1,
						$i['error'],
						$i['affected'],
						$i['numRows'],
						$i['took'],
						"\r\n\r\n"
					), 'sql.log');
				}
			}
		}
    }
}
