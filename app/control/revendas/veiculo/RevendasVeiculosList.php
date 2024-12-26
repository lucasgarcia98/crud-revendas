<?php

use Adianti\Control\TWindow;
use Adianti\Database\TTransaction;

class RevendasVeiculosList extends TStandardList
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

        parent::setDatabase('revendas');
        parent::setActiveRecord('Veiculo');
        parent::setDefaultOrder('id', 'asc');
        parent::addFilterField('id', '=', 'id');
        parent::addFilterField('descricao', 'like', 'descricao');
        parent::addFilterField('placa', 'like', 'placa');
        parent::addFilterField('ano', '=', 'ano');
        parent::addFilterField('cor', 'like', 'cor');
        parent::addFilterField('km', '=', 'km');
        parent::addFilterField('valor', '=', 'valor');
        parent::addFilterField('obs', 'like', 'obs');
        parent::addFilterField('fabricante_id', '=', 'fabricante_id');

        parent::setLimit(TSession::getValue(__CLASS__ . '_limit') ?? 10);

        parent::setAfterSearchCallback([$this, 'onAfterSearch']);

        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_name');
        $this->form->setFormTitle('Revendas');

        // create the form fields
        $descricao = new TEntry('descricao');
        $placa = new TEntry('placa');
        $cor = new TEntry('cor');
        $km = new TEntry('km');
        $valor = new TNumeric('valor', 2, ',', '.', true);
        $obs = new TText('obs');

        // add the fields
        $this->form->addFields([new TLabel('Descricao')], [$descricao]);
        $this->form->addFields([new TLabel('Placa')], [$placa]);
        $this->form->addFields([new TLabel('Cor')], [$cor]);
        $this->form->addFields([new TLabel('Km')], [$km]);
        $this->form->addFields([new TLabel('Valor')], [$valor]);
        $this->form->addFields([new TLabel('Obs')], [$obs]);

        $descricao->setSize('100%');
        $placa->setSize('100%');
        $cor->setSize('100%');
        $km->setSize('100%');
        $valor->setSize('100%');
        $obs->setSize('100%');

        // keep the form filled during navigation with session data
        $this->form->setData(TSession::getValue('Veiculos_filter_data'));

        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';

        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        //$this->datagrid->datatable = 'true';
        $this->datagrid->style = 'width: 100%';

        // creates the datagrid columns
        $column_id = $this->datagrid->addColumn(new TDataGridColumn('id', 'Id', 'center', 50));
        $column_descricao = $this->datagrid->addColumn(new TDataGridColumn('descricao', 'Descrição', 'left'));
        $column_placa = $this->datagrid->addColumn(new TDataGridColumn('placa', 'Placa', 'center'));
        $column_ano = $this->datagrid->addColumn(new TDataGridColumn('ano', 'Ano', 'center'));
        $column_cor = $this->datagrid->addColumn(new TDataGridColumn('cor', 'Cor', 'center'));
        $column_km = $this->datagrid->addColumn(new TDataGridColumn('km', 'Km', 'center'));
        $column_valor = $this->datagrid->addColumn(new TDataGridColumn('valor', 'Valor', 'right'));
        $column_obs = $this->datagrid->addColumn(new TDataGridColumn('obs', 'Obs', 'left'));
        $column_fabricante = $this->datagrid->addColumn(new TDataGridColumn('fabricante_id', 'Fabricante', 'left'));
        $column_acessorios = $this->datagrid->addColumn(new TDataGridColumn('acessorios', 'Acessórios', 'left'));

        // creates the datagrid column actions
        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);

        $order_descricao = new TAction(array($this, 'onReload'));
        $order_descricao->setParameter('order', 'descricao');
        $column_descricao->setAction($order_descricao);

        $order_placa = new TAction(array($this, 'onReload'));
        $order_placa->setParameter('order', 'placa');
        $column_placa->setAction($order_placa);

        $order_ano = new TAction(array($this, 'onReload'));
        $order_ano->setParameter('order', 'ano');
        $column_ano->setAction($order_ano);

        $order_cor = new TAction(array($this, 'onReload'));
        $order_cor->setParameter('order', 'cor');
        $column_cor->setAction($order_cor);

        $order_km = new TAction(array($this, 'onReload'));
        $order_km->setParameter('order', 'km');
        $column_km->setAction($order_km);

        $order_valor = new TAction(array($this, 'onReload'));
        $order_valor->setParameter('order', 'valor');
        $column_valor->setAction($order_valor);

        $order_obs = new TAction(array($this, 'onReload'));
        $order_obs->setParameter('order', 'obs');
        $column_obs->setAction($order_obs);

        $order_fabricante = new TAction(array($this, 'onReload'));
        $order_fabricante->setParameter('order', 'fabricante_id');
        $column_fabricante->setAction($order_fabricante);
        $column_fabricante->setTransformer(function ($value, $object, $row) {
            if ($value) {
                return $object->fabricante->nome;
            }

            return '';
        });

        $column_acessorios->setTransformer(function ($value, $object, $row) {
            $acessorios = $object->getVeiculosAcessorios();
            $str = '';

            if ($acessorios) {
                if (count($acessorios) > 1) {
                    foreach ($acessorios as $acessorio) {
                        $str .= $acessorio->descricao . ', ';
                    }
                } else {
                    $str = $acessorios[0]->descricao;
                }

            }

            $str = trim($str);
            $str = rtrim($str, ',');
            return $str;
        });
        // create EDIT action
        $action_edit = new TDataGridAction(array('RevendasVeiculosForm', 'onEdit'), ['register_state' => 'false']);
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

        $action_show = new TDataGridAction([$this, 'onView'], ["veiculo_id" => "{id}"]);
        $action_show->setButtonClass('btn btn-default');
        $action_show->setLabel('Visualizar');
        $action_show->setImage('far:eye green ');
        $action_show->setField('id');
        $this->datagrid->addAction($action_show);

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
        $form_search->add($descricao, true);
        $form_search->add($btnf, true);

        $panel->addHeaderWidget($form_search);

        $panel->addHeaderActionLink('', new TAction(['RevendasVeiculosForm', 'onEdit'], ['register_state' => 'false']), 'fa:plus');

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($panel);

        parent::add($container);
    }

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

    public static function onView($params)
    {
        $id = $params['id'] ?? NULL;
        if (!$id) {
            return;
        }

        try {
            TTransaction::open('revendas');

            $veiculo = new Veiculo($params['veiculo_id']);


            AdiantiCoreApplication::loadPage(
                (RevendasVeiculosView::class),
                'onShow',
                [
                    'register_state' => 'true',
                    'key' => $id,
                    'id' => $id,
                ]
            );

            TTransaction::close();
        } catch (Exception $e) {
            TTransaction::rollback();
            new TMessage('error', $e->getMessage());
        }

    }

}
