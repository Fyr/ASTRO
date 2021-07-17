<?
	echo $this->Form->hidden('Seo.id', array('value' => Hash::get($this->request->data, 'Seo.id')));
	echo $this->Form->hidden('Seo.object_type', array('value' => $objectType));
	echo $this->PHForm->input('Seo.title', array('type' => 'text'));
	echo $this->PHForm->input('Seo.keywords', array('type' => 'text'));
	echo $this->PHForm->input('Seo.descr', array('type' => 'text'));
	