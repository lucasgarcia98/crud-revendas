<?php

use Adianti\Log\TLoggerSTD;
use Adianti\Validator\TMinValueValidator;
use Adianti\Widget\Form\TSpinner;
use Adianti\Widget\Wrapper\TDBMultiCombo;
class RevendasVendasForm extends TStandardForm
{
    protected $form; // form

    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct($param)
    {
        parent::__construct();

        parent::setTargetContainer('adianti_right_panel');

        // creates the form
        $this->form = new BootstrapFormBuilder('form_Venda');
        $this->form->setFormTitle('Veículo');
        $this->form->enableClientValidation();

        // defines the database
        parent::setDatabase('revendas');

        // defines the active record
        parent::setActiveRecord('Venda');

        // create the form fields
        $id = new TEntry('id');

        $dt_venda = new TEntry('dt_venda');
        $dt_venda->setMask('99/99/9999');
        $dt_venda->setValue(date('d/m/Y'));

        $valor = new TNumeric('valor', 2, ',', '.', true);
        $obs = new TText('obs');

        $cliente_id = new TDBCombo('cliente_id', 'revendas', 'Cliente', 'id', 'nome');
        $cliente_id->enableSearch();
        $cliente_id->setSize('100%');
        $veiculo_id = new TDBCombo('veiculo_id', 'revendas', 'Veiculo', 'id', 'descricao');
        $veiculo_id->enableSearch();
        $veiculo_id->setSize('100%');

        $id->setEditable(false);

        // add the fields
        $this->form->addFields([new TLabel('ID')], [$id]);
        $this->form->addFields([new TLabel('Data da Venda')], [$dt_venda]);
        $this->form->addFields([new TLabel('Valor')], [$valor]);
        $this->form->addFields([new TLabel('Obs')], [$obs]);
        $this->form->addFields([new TLabel('Cliente')], [$cliente_id]);
        $this->form->addFields([new TLabel('Veículo')], [$veiculo_id]);
        $id->setSize('30%');

        // validations
        $dt_venda->addValidation('dt_venda', new TRequiredValidator);

        // add form actions
        $btn = $this->form->addAction(_t('Save'), new TAction(array($this, 'onSave')), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';

        $this->form->addActionLink(_t('Clear'), new TAction(array($this, 'onEdit')), 'fa:eraser red');
        //$this->form->addActionLink(_t('Back'),new TAction(array('SystemProgramList','onReload')),'far:arrow-alt-circle-left blue');

        $this->form->addHeaderActionLink(_t('Close'), new TAction([$this, 'onClose']), 'fa:times red');

        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add($this->form);

        // add the container to the page
        parent::add($container);
    }

    /**
     * method onEdit()
     * Executed whenever the user clicks at the edit button da datagrid
     * @param  $param An array containing the GET ($_GET) parameters
     */
    public function onEdit($param)
    {
        try {
            if (isset($param['key'])) {
                $key = $param['key'];

                TTransaction::open($this->database);
                $class = $this->activeRecord;
                $object = new $class($key);

                $this->form->setData($object);

                TTransaction::close();

                return $object;
            } else {
                $this->form->clear(true);
            }
        } catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    /**
     * method onSave()
     * Executed whenever the user clicks at the save button
     */
    public function onSave()
    {
        try {

            TTransaction::open($this->database);

            $data = $this->form->getData();

            $object = new Venda();
            $object->fromArray((array) $data);

            $this->form->validate();
            $object->store();

            $this->form->setData($object);

            TTransaction::close();
            $pos_action = new TAction(['RevendasVendasList', 'onReload']);
            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'), $pos_action);

            return $object;
        } catch (Exception $e) // in case of exception
        {
            // get the form data
            $object = $this->form->getData($this->activeRecord);
            $this->form->setData($object);
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    /**
     * on close
     */
    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
    }

}
