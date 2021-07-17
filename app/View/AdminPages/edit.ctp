<?
    $id = $this->request->data($objectType.'.id');
    $title = __('Static content');
    $breadcrumbs = array(
        $title => 'javascript:;',
        $this->ObjectType->getTitle('index', $objectType) => array('action' => 'index'),
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
    $options = array();
    if ($this->request->data($objectType.'.featured')) {
        $options['readonly'] = true;
    }
    $tabs = array(
        __('General') => $this->Html->div('form-body',
            $this->PHForm->input('title', array('label' => array('class' => 'col-md-3 control-label', 'text' => __('Title'))))
            .$this->PHForm->input('slug', $options)
        ),
        __('Text') => $this->element('Article.edit_body', array('field' => 'body')),
        __('SEO') => $this->element('Seo.edit', array('object_type' => $objectType)),
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
