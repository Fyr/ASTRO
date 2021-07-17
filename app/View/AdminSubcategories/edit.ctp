<?
    $id = $this->request->data($objectType.'.id');
    $title = __('Catalog');
    $catTitle = Hash::get($parentArticle, 'Category.title');
    $subTitle = $this->ObjectType->getTitle('index', $objectType);
    $indexURL = array('controller' => 'AdminSubcategories', 'action' => 'index', Hash::get($parentArticle, 'Category.id'));
    $breadcrumbs = array(
        $title => 'javascript:;',
        $this->ObjectType->getTitle('index', 'Category') => array('controller' => 'AdminCategories', 'action' => 'index'),
        $catTitle => $indexURL,
        $subTitle => $indexURL,
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
            $this->element('AdminUI/checkboxes', array('checkboxes' => array('published')))
            .$this->PHForm->input('title')
            .$this->PHForm->input('sorting', array('class' => 'form-control input-small'))
        ),
        __('SEO') => $this->element('Seo.edit', array('object_type' => $objectType)),
    );

    echo $this->element('AdminUI/tabs', compact('tabs'));
    echo $this->element('AdminUI/form_actions', array('backURL' => $indexURL));
    echo $this->PHForm->end();
?>
        </div>
    </div>
</div>
