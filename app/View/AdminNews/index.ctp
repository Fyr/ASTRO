<?
    $title = __('Static content');
    $breadcrumbs = array(
        $title => 'javascript:;',
        $this->ObjectType->getTitle('index', $objectType) => ''
    );
    echo $this->element('AdminUI/breadcrumbs', compact('breadcrumbs'));
    echo $this->element('AdminUI/title', compact('title'));
    echo $this->Flash->render();

    $columns = $this->PHTableGrid->getDefaultColumns($objectType);
    $columns[$objectType.'.modified']['label'] = __('Date');
    $columns[$objectType.'.featured']['label'] = __('Home page');
    unset($columns['Media.*']);
    array_unshift($columns, array('key' => 'Media.image', 'label' => __('Photo'), 'format' => 'string', 'align' => 'center'));

    $row_actions = '../AdminNews/_row_actions';

    $rowset = $this->PHTableGrid->getDefaultRowset($objectType);
    foreach($rowset as &$row) {
        $src = $this->Media->imageUrl($row, '100x');
        $row['Media']['image'] = ($src) ? $this->Html->image($src, array('class' => 'admin-thumb')) : '';
    }
?>
<style>
    .table.dataTable > tbody > tr > td:first-child {
        text-align: center;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <?=$this->element('AdminUI/form_title', array('title' => $this->ObjectType->getTitle('index', $objectType)))?>
            <div class="portlet-body dataTables_wrapper">
                <div class="table-toolbar">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="btn-group">
                                <a class="btn green" href="<?=$this->Html->url(array('action' => 'edit', 0, $parent_id))?>">
                                    <i class="fa fa-plus"></i> <?=$this->ObjectType->getTitle('create', $objectType)?>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">

                        </div>
                    </div>
                </div>
                <?=$this->PHTableGrid->render($objectType, compact('row_actions', 'columns', 'rowset'))?>
            </div>
        </div>
    </div>
</div>
