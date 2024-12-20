<?php

class RevendasAcessoriosList extends TStandardList
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;
    protected $transformCallback;

    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();

        parent::setDatabase('revendas');            // defines the database
        parent::setActiveRecord('Acessorio');   // defines the active record
        parent::setDefaultOrder('id', 'asc');         // defines the default order
        parent::addFilterField('id', '=', 'id'); // filterField, operator, formField
        parent::addFilterField('descricao', 'like', 'descricao'); // filterField, operator, formField

        parent::setLimit(TSession::getValue(__CLASS__ . '_limit') ?? 10);

        parent::setAfterSearchCallback([$this, 'onAfterSearch']);

        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_name');
        $this->form->setFormTitle('Revendas');

        // create the form fields
        $nome = new TEntry('descricao');

        // add the fields
        $this->form->addFields([new TLabel('Nome')], [$nome]);
        $nome->setSize('100%');

        // keep the form filled during navigation with session data
        $this->form->setData(TSession::getValue('Acessorios_filter_data'));

        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';

        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        //$this->datagrid->datatable = 'true';
        $this->datagrid->style = 'width: 100%';

        // creates the datagrid columns
        $column_id =  new TDataGridColumn('id', 'Id', 'center', 50);
        $column_name =  new TDataGridColumn('descricao', 'Nome', 'left');
        
        $column_name->enableAutoHide(500);
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_name);

        // creates the datagrid column actions
        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);

        $order_name = new TAction(array($this, 'onReload'));
        $order_name->setParameter('order', 'descricao');
        $column_name->setAction($order_name);

        // create EDIT action
        $action_edit = new TDataGridAction(array('RevendasAcessoriosForm', 'onEdit'), ['register_state' => 'false']);
        $action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel(_t('Edit'));
        $action_edit->setImage('far:edit blue ');
        $action_edit->setField('id');
        $this->datagrid->addAction($action_edit);

        // create DELETE action
        $action_del = new TDataGridAction(array($this, 'onDelete'));
        $action_del->setButtonClass('btn btn-default');
        $action_del->setLabel(_t('Delete'));
        $action_del->setImage('far:trash-alt red ');
        $action_del->setField('id');
        $this->datagrid->addAction($action_del);

        // create the datagrid model
        $this->datagrid->createModel();

        // create the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup;
        $panel->add($this->datagrid)->style = 'overflow-x:auto';
        $panel->addFooter($this->pageNavigation);

        $btnf = TButton::create('find', [$this, 'onSearch'], '', 'fa:search');
        $btnf->style = 'height: 37px; margin-right:4px;';

        $form_search = new TForm('form_search_name');
        $form_search->style = 'display:flex;justify-content: space-between;align-items: center;';
        $form_search->add($nome, true);
        $form_search->add($btnf, true);

        $panel->addHeaderWidget($form_search);

        $panel->addHeaderActionLink('', new TAction(['RevendasAcessoriosForm', 'onEdit'], ['register_state' => 'false']), 'fa:plus');
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($panel);

        parent::add($container);
    }

    /**
     *
     */
    public function onAfterSearch($datagrid, $options)
    {
        if (!empty(TSession::getValue(get_class($this).'_filter_data')))
        {
            $obj = new stdClass;

            foreach(TSession::getValue(get_class($this).'_filter_data') as $key => $value)
            {
                $obj->$key = $value;
            }

            TForm::sendData('form_search_name', $obj);
        }
    }

    /**
     *
     */
    public static function onChangeLimit($param)
    {
        TSession::setValue(__CLASS__ . '_limit', $param['limit']);
        AdiantiCoreApplication::loadPage(__CLASS__, 'onReload');
    }

    /**
     *
     */
    public static function onShowCurtainFilters($param = null)
    {
        try {
            // create empty page for right panel
            $page = new TPage;
            $page->setTargetContainer('adianti_right_panel');
            $page->setProperty('override', 'true');
            $page->setPageName(__CLASS__);

            $btn_close = new TButton('closeCurtain');
            $btn_close->onClick = "Template.closeRightPanel();";
            $btn_close->setLabel("Fechar");
            $btn_close->setImage('fas:times');

            // instantiate self class, populate filters in construct 
            $embed = new self;
            $embed->form->addHeaderWidget($btn_close);

            // embed form inside curtain
            $page->add($embed->form);
            $page->setIsWrapped(true);
            $page->show();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }

}
