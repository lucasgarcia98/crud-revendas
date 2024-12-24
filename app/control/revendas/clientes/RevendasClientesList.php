<?php

class RevendasClientesList extends TStandardList
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
        parent::setActiveRecord('Cliente');   // defines the active record
        parent::setDefaultOrder('id', 'asc');         // defines the default order
        parent::addFilterField('id', '=', 'id'); // filterField, operator, formField
        parent::addFilterField('nome', 'like', 'nome'); // filterField, operator, formField
        parent::addFilterField('dt_nascimento', 'like', 'dt_nascimento');
        parent::addFilterField('documento', 'like', 'documento');
        parent::addFilterField('tipo_pessoa', 'like', 'tipo_pessoa');
        parent::addFilterField('endereco', 'like', 'endereco');
        parent::addFilterField('bairro', 'like', 'bairro');
        parent::addFilterField('numero', 'like', 'numero');
        parent::addFilterField('complemento', 'like', 'complemento');
        parent::addFilterField('cep', 'like', 'cep');
        parent::addFilterField('cidade', 'like', 'cidade');
        parent::addFilterField('fone', 'like', 'fone');
        parent::addFilterField('email', 'like', 'email');

        parent::setLimit(TSession::getValue(__CLASS__ . '_limit') ?? 10);

        parent::setAfterSearchCallback([$this, 'onAfterSearch']);

        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_name');
        $this->form->setFormTitle('Revendas');

        // create the form fields
        $nome = new TEntry('nome');
        // add the fields
        $this->form->addFields([new TLabel('Nome')], [$nome]);
        $nome->setSize('100%');

        // keep the form filled during navigation with session data
        $this->form->setData(TSession::getValue('Clientes_filter_data'));

        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';

        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        //$this->datagrid->datatable = 'true';
        $this->datagrid->style = 'width: 100%';

        // creates the datagrid columns
        $column_id = $this->datagrid->addColumn(new TDataGridColumn('id', 'Id', 'center', 50));
        $column_name = $this->datagrid->addColumn(new TDataGridColumn('nome', 'Nome', 'left'));
        $column_dt_nascimento = $this->datagrid->addColumn(new TDataGridColumn('dt_nascimento', 'Data de Nascimento', 'left'));
        $column_documento = $this->datagrid->addColumn(new TDataGridColumn('documento', 'Documento', 'left'));
        $column_tipo_pessoa = $this->datagrid->addColumn(new TDataGridColumn('tipo_pessoa', 'Tipo de Pessoa', 'left'));
        $column_endereco = $this->datagrid->addColumn(new TDataGridColumn('endereco', 'Endereço', 'left'));
        $column_bairro = $this->datagrid->addColumn(new TDataGridColumn('bairro', 'Bairro', 'left'));
        $column_numero = $this->datagrid->addColumn(new TDataGridColumn('numero', 'Número', 'left'));
        $column_complemento = $this->datagrid->addColumn(new TDataGridColumn('complemento', 'Complemento', 'left'));
        $column_cep = $this->datagrid->addColumn(new TDataGridColumn('cep', 'CEP', 'left'));
        $column_cidade = $this->datagrid->addColumn(new TDataGridColumn('cidade', 'Cidade', 'left'));
        $column_fone = $this->datagrid->addColumn(new TDataGridColumn('fone', 'Fone', 'left'));
        $column_email = $this->datagrid->addColumn(new TDataGridColumn('email', 'E-mail', 'left'));

        $column_name->enableAutoHide(500);

        // creates the datagrid column actions
        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);

        $order_name = new TAction(array($this, 'onReload'));
        $order_name->setParameter('order', 'nome');
        $column_name->setAction($order_name);

        $order_dt_nascimento = new TAction(array($this, 'onReload'));
        $order_dt_nascimento->setParameter('order', 'dt_nascimento');
        $column_dt_nascimento->setAction($order_dt_nascimento);

        $order_documento = new TAction(array($this, 'onReload'));
        $order_documento->setParameter('order', 'documento');
        $column_documento->setAction($order_documento);

        $order_tipo_pessoa = new TAction(array($this, 'onReload'));
        $order_tipo_pessoa->setParameter('order', 'tipo_pessoa');
        $column_tipo_pessoa->setAction($order_tipo_pessoa);

        $order_endereco = new TAction(array($this, 'onReload'));
        $order_endereco->setParameter('order', 'endereco');
        $column_endereco->setAction($order_endereco);

        $order_bairro = new TAction(array($this, 'onReload'));
        $order_bairro->setParameter('order', 'bairro');
        $column_bairro->setAction($order_bairro);

        $order_numero = new TAction(array($this, 'onReload'));
        $order_numero->setParameter('order', 'numero');
        $column_numero->setAction($order_numero);

        $order_complemento = new TAction(array($this, 'onReload'));
        $order_complemento->setParameter('order', 'complemento');
        $column_complemento->setAction($order_complemento);

        $order_cep = new TAction(array($this, 'onReload'));
        $order_cep->setParameter('order', 'cep');
        $column_cep->setAction($order_cep);

        $order_cidade = new TAction(array($this, 'onReload'));
        $order_cidade->setParameter('order', 'cidade');
        $column_cidade->setAction($order_cidade);

        $order_fone = new TAction(array($this, 'onReload'));
        $order_fone->setParameter('order', 'fone');
        $column_fone->setAction($order_fone);

        $order_email = new TAction(array($this, 'onReload'));
        $order_email->setParameter('order', 'email');
        $column_email->setAction($order_email);

        // create EDIT action
        $action_edit = new TDataGridAction(array('RevendasClientesForm', 'onEdit'), ['register_state' => 'false']);
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

        $panel->addHeaderActionLink('', new TAction(['RevendasClientesForm', 'onEdit'], ['register_state' => 'false']), 'fa:plus');

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
        if (!empty(TSession::getValue(get_class($this) . '_filter_data'))) {
            $obj = new stdClass;

            foreach (TSession::getValue(get_class($this) . '_filter_data') as $key => $value) {
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
