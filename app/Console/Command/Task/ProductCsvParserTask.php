<?php
App::uses('Shell', 'Console');
App::uses('AppShell', 'Console/Command');
App::uses('Product', 'Model');
App::uses('Media', 'Media.Model');
App::uses('CsvReader', 'Vendor');
App::uses('Image', 'Media.Vendor');
App::uses('Path', 'Core.Vendor');

class ProductCsvParserTask extends AppShell {
    public $uses = array('Product', 'Media.Media', 'Category', 'Subcategory');

    private $_xdata = array(
        'products' => array('no_image' => 0, 'image' => 0, 'image_3d' => 0, 'video' => 0, 'total' => 0, 'error' => 0),
        'images' => 0, 'images_3d' => 0,
        'video' => 0, 'errors' => 0
    );

    public function execute() {
        @unlink(Configure::read('ProductCSVParser.log'));
        $this->Task->setProgress($this->id, 0, ($this->params['clear_data']) ? 3 : 2); // 3 subtasks
        $this->Task->setStatus($this->id, Task::RUN);

        try {
            $this->Product->trxBegin();
            if ($this->params['clear_data']) {
                $this->_clearMedia(); // subtask 1
            }
            $aData = $this->_readCsv($this->params['csv_file']); // subtask 2
            $aID = $this->_updateProducts($aData['data']); // subtask 3
            $this->Product->trxCommit();
        } catch (Exception $e) {
            $this->Product->trxRollback();
            @unlink($this->params['csv_file']);
            throw new Exception($e->getMessage());
        }

        @unlink($this->params['csv_file']);
        $this->Task->setData($this->id, 'xdata', $this->_xdata);
        $this->Task->setProgress($this->id, 3);
        $this->Task->setStatus($this->id, Task::DONE);
    }

    private function _clearMedia() {
        $total = $this->Media->find('count', array('conditions' => array('object_type' => 'Product')));

        $subtask_id = $this->Task->add(0, 'ProductCsvParser_clearMedia', null, $this->id);
        $this->Task->setData($this->id, 'subtask_id', $subtask_id);
        $this->Task->setProgress($subtask_id, 0, $total);
        $this->Task->setStatus($subtask_id, Task::RUN);
        $this->Task->saveStatus($this->id);
        $progress = $this->Task->getProgressInfo($this->id);

        $this->Product->deleteAll(array('Product.id > 0'));
        $aRows = $this->Media->find('all', array('conditions' => array('object_type' => 'Product')));
        foreach($aRows as $i => $media) {
            $status = $this->Task->getStatus($this->id);
            if ($status == Task::ABORT) {
                $this->Task->setStatus($subtask_id, Task::ABORTED);
                $this->Task->setStatus($this->id, Task::ABORTED);
                throw new Exception(__('Processing was aborted by user'));
            }

            // main cycle
            $this->Media->delete($media['Media']['id']);

            $this->Task->setProgress($subtask_id, $i + 1);
            $_progress = $this->Task->getProgressInfo($subtask_id);
            $this->Task->setProgress($this->id, $progress['progress'] + $_progress['percent'] * 0.01);
        }

        $this->Task->setStatus($subtask_id, Task::DONE);
        $this->Task->setProgress($this->id, $progress['progress'] + 1);
        $this->Task->saveStatus($this->id);
    }

    private function _readCsv($file) {
        $subtask_id = $this->Task->add(0, 'ProductCsvParser_readCsv', null, $this->id);
        $this->Task->setData($this->id, 'subtask_id', $subtask_id);
        $this->Task->saveStatus($this->id);
        $progress = $this->Task->getProgressInfo($this->id);

        $aData = CsvReader::parse($this->params['csv_file'], array(
            'Task' => $this->Task,
            'task_id' => $this->id,
            'subtask_id' => $subtask_id
        ));

        $this->Task->setProgress($this->id, $progress['progress'] + 1);
        $this->Task->saveStatus($this->id);
        return $aData;
    }

    private function _getCategoryId($aCategories, $row) {
        foreach($aCategories as $cat_id => $cat) {
            $key = 'cat_'.$cat_id;
            if ($row[$key]) {
                return $cat_id;
            }
        }
        return null;
    }

    private function _getFilesByNum($aFiles, $num) {
        $files = array();
        $mask = 'kp-'.$num;
        $len = strlen($mask);
        foreach($aFiles['files'] as $file) {
            if ($file === $mask.'.jpg' || substr($file, 0, $len + 1) === $mask.'-') {
                $files[] = Configure::read('ProductCSVParser.photo_path').$file;
            }
        }
        return $files;
    }

    /**
     * Return all media files for product specified by $row
     * @param $aFiles - array of all file names (to search by file mask)
     * @param $row - row of imported product
     * @return array - array of existing files for product
     */
    private function _getMediaNames($aFiles, $row) {
        /* As file names differs, we have to get pure KP number, then seek file by mask
         *
         * Examples of file names:
         * ÊÏ 0001: kp-1.jpg
         * ÊÏ 0236: kp-236.jpg
         * ÊÏ 0256 àá: kp-256.jpg
         * ÊÏ 1033 àá: kp-1033-a.jpg, kp-1033-b.jpg
         * ÊÏ 1105: kp-1105.jpg, kp-1105-a.jpg
         * ÊÏ 1107/1-2: kp-1107.jpg, kp-1107-1.jpg, kp-1107-2.jpg
         * ÊÏ 1975 àá: kp-1975-a.jpg, kp-1975-b.jpg
         */
        // $id_num = mb_convert_encoding($row['id_num'], 'cp1251', 'utf8');
        // get pure number
        list($kp, $num) = explode(' ', $row['id_num']);
        list($num) = explode('/', $num);
        $num = intval($num);

        $fnames = array();
        try {
            if ($row['img'] == 1) { // only 1 image
                $fnames = $this->_getFilesByNum($aFiles, $num);
                if (!$fnames) {
                    $fname = 'kp-'.$num.'.jpg';
                    fdebug(__("No photo for item `%s`: `%s`", $row['id_num'], $fname)."\r\n", Configure::read('ProductCSVParser.log'));
                }
            } elseif ($row['img'] == 3) { // 3D images in folder
                $folder = 'kp'.$num;
                $aPath = Path::dirContent(Configure::read('ProductCSVParser.photo_path_3d').$folder);
                if (!isset($aPath['files'])) {
                    throw new Exception(__("No 3D-photo for item `%s`: `%s`", $row['id_num'], $folder));
                }
                $fnames = array();
                foreach($aPath['files'] as $fname) {
                    $fnames[] = $aPath['path'].$fname;
                }
            }

            // check media size
            foreach($fnames as $fname) {
                $img = new Image();
                $_fname = ($row['img'] == 3) ? $folder.DS.basename($fname) : basename($fname);
                if (!$img->load($fname)) {
                    throw new Exception(__("Could not load media as image for item `%s`: `%s`", $row['id_num'], $_fname));
                }
                $w = $img->getSizeX();
                $h = $img->getSizeY();
                if (max($w / $h, $h / $w) > 3) {
                    // throw new Exception(__("Incorrect image size for item `%s`: `%s` (%d x %d)", $row['id_num'], $_fname, $w, $h));
                    fdebug(__("Incorrect image size for item `%s`: `%s` (%d x %d)", $row['id_num'], $_fname, $w, $h)."\r\n", Configure::read('ProductCSVParser.log'));
                }
            }
        } catch (Exception $e) {
            $this->_xdata['products']['error']++;
            fdebug($e->getMessage()."\r\n", Configure::read('ProductCSVParser.log'));
            return array();
        }
        return $fnames;
    }

    private function _updateProducts($aRows) {
        $subtask_id = $this->Task->add(0, 'ProductCsvParser_updateProducts', null, $this->id);
        $this->Task->setData($this->id, 'subtask_id', $subtask_id);
        $this->Task->setProgress($subtask_id, 0, count($aRows));
        $this->Task->setStatus($subtask_id, Task::RUN);
        $this->Task->saveStatus($this->id);
        $progress = $this->Task->getProgressInfo($this->id);

        $aCategories = $this->Category->find('all', array('order' => array('Category.id' => 'ASC')));
        $aCategories = Hash::combine($aCategories, '{n}.Category.id', '{n}.Category');

        $aSubcategories = $this->Subcategory->find('all', array('order' => array('Subcategory.sorting' => 'ASC')));
        $aSubcategories = Hash::combine($aSubcategories, '{n}.Subcategory.sorting', '{n}.Subcategory', '{n}.Subcategory.parent_id');

        $aFiles = Path::dirContent(Configure::read('ProductCSVParser.photo_path'));
        $aID = array();
        try {
            foreach($aRows as $line => $row) {
                $status = $this->Task->getStatus($this->id);
                if ($status == Task::ABORT) {
                    $this->Task->setStatus($subtask_id, Task::ABORTED);
                    $this->Task->setStatus($this->id, Task::ABORTED);
                    throw new Exception(__('Processing was aborted by user'));
                }

                $cat_id = $this->_getCategoryId($aCategories, $row);
                if (!$cat_id) {
                    throw new Exception('Category not found (Line %d)');
                }

                $subcat_i = $row['cat_'.$cat_id];
                if (!(isset($aSubcategories[$cat_id]) && isset($aSubcategories[$cat_id][$subcat_i]))) {
                    throw new Exception('Subcategory not found (Line %d)');
                }
                $subcat = $aSubcategories[$cat_id][$subcat_i];
                $row['title'] = trim($row['title']);

                if (mb_strlen($row['title']) > 40) {
                    fdebug(__('Too long name for `%s`: `%s`', $row['id_num'], $row['title'])."\r\n", Configure::read('ProductCSVParser.log'));
                }

                $data = array(
                    'title' => $row['title'], // (mb_strlen($row['title']) > 40) ? mb_substr($row['title'], 0, 40).'...' :
                    'teaser' => $row['title'],
                    'body' => trim($row['body']),
                    'id_num' => trim($row['id_num']),
                    'location' => trim($row['location']),
                    'author' => trim($row['author']),
                    'creation_date' => trim($row['creation_date']),
                    'cat_id' => $cat_id,
                    'subcat_id' => $subcat['id'],
                    'published' => $this->params['publish_all']
                );
                $this->Product->clear();
                if (!$this->Product->save($data)) {
                    throw new Exception(__("Cannot save item `%s` (Line %d)", $line + 3, $row['id_num']));
                }

                if ($row['img'] == 1 || $row['img'] == 3) {
                    if ($fnames = $this->_getMediaNames($aFiles, $row)) {
                        foreach ($fnames as $fname) {
                            $media = array(
                                'media_type' => 'image',
                                'object_type' => 'Product',
                                'object_id' => $this->Product->id,
                                'orig_fname' => basename($fname),
                                'real_name' => $fname,
                                'file' => ($row['img'] == 3) ? '3D_image' : 'image',
                                'ext' => '.jpg'
                            );
                            $this->Media->uploadMedia($media);
                            if ($row['img'] == 3) {
                                $this->_xdata['images_3d']++;
                            } else {
                                $this->_xdata['images']++;
                            }
                        }

                        if ($row['img'] == 3) {
                            $this->_xdata['products']['image_3d']++;
                        } else {
                            $this->_xdata['products']['image']++;
                        }
                    }
                } elseif ($row['img'] == 2) {
                    $this->_xdata['products']['error']++;
                    fdebug(__('Video not found for item `%s`', $row['id_num'])."\r\n", Configure::read('ProductCSVParser.log'));
                } else {
                    $this->_xdata['products']['no_image']++;
                }
                $this->_xdata['products']['total']++;

                $this->Task->setProgress($subtask_id, $line + 1);
                $_progress = $this->Task->getProgressInfo($subtask_id);
                $this->Task->setProgress($this->id, $progress['progress'] + $_progress['percent'] * 0.01);
            }
        } catch (Exception $e) {
            throw new Exception(__($e->getMessage(), $line + 3));
        }

        $this->Task->setStatus($subtask_id, Task::DONE);
        $this->Task->setProgress($this->id, $progress['progress'] + 1);
        $this->Task->saveStatus($this->id);
        return $aID;
    }
}
