<?
	$id = $this->request->data($objectType.'.id');
	$title = $this->ObjectType->getTitle('index', $objectType);
	$breadcrumbs = array(
		__('Static content') => 'javascript:;',
		$title => array('action' => 'index'),
		__('Edit') => ''
	);
	echo $this->element('AdminUI/breadcrumbs', compact('breadcrumbs'));
	echo $this->element('AdminUI/title', compact('title'));
	echo $this->Flash->render();
?>

<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">

<?
	echo $this->element('AdminUI/form_title', array('title' => $this->ObjectType->getTitle($id ? 'edit' : 'create', $objectType)));
	echo $this->PHForm->create($objectType);

	$tabs = array(
		__('General') => $this->Html->div('form-body',
			$this->element('AdminUI/checkboxes')
			.$this->element('Article.edit_title')
			.$this->element('Article.edit_slug')
			.$this->PHForm->input('teaser')
			.$this->PHForm->input('sorting', array('class' => 'form-control input-small'))
		),
		__('Text') => $this->element('Article.edit_body')
	);

	if ($id) {
		$tabs[__('Media')] = $this->element('Media.edit', array('object_type' => $objectType, 'object_id' => $id));
	}

	echo $this->element('AdminUI/tabs', compact('tabs'));
	echo $this->element('AdminUI/form_actions');
	echo $this->PHForm->end();
?>
		</div>
	</div>
</div>
