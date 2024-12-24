<?php
class RevendasClientesForm extends TStandardForm
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
        $this->form = new BootstrapFormBuilder('form_Clientes');
        $this->form->setFormTitle('Cliente');
        $this->form->enableClientValidation();

        // defines the database
        parent::setDatabase('revendas');

        // defines the active record
        parent::setActiveRecord('Cliente');

        // create the form fields
        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $dt_nascimento = new TEntry('dt_nascimento');
        $dt_nascimento->setMask('99/99/9999');
        $dt_nascimento->setValue(date('d/m/Y'));
        $documento = new TEntry('documento');
        $tipo_pessoa = new TCombo('tipo_pessoa');
        $itemsTipoPessoa = ['Física' => 'Física', 'Jurídica' => 'Jurídica'];
        $tipo_pessoa->addItems($itemsTipoPessoa);
        $tipo_pessoa->setSize('100%');
        $tipo_pessoa->setValue('Física');
        $endereco = new TEntry('endereco');
        $bairro = new TEntry('bairro');
        $numero = new TSpinner('numero');
        $complemento = new TEntry('complemento');
        $cep = new TEntry('cep');
        $cep->setMask('99999-999');
        $cidade = new TEntry('cidade');
        $fone = new TEntry('fone');
        $fone->setMask('(99) 99999-9999');
        $email = new TEntry('email');

        $id->setEditable(false);

        // add the fields
        $this->form->addFields([new TLabel('ID')], [$id]);
        $this->form->addFields([new TLabel('Nome')], [$nome]);
        $this->form->addFields([new TLabel('Data de Nascimento')], [$dt_nascimento]);
        $this->form->addFields([new TLabel('Documento')], [$documento]);
        $this->form->addFields([new TLabel('Tipo de Pessoa')], [$tipo_pessoa]);
        $this->form->addFields([new TLabel('Endereço')], [$endereco]);
        $this->form->addFields([new TLabel('Bairro')], [$bairro]);
        $this->form->addFields([new TLabel('Número')], [$numero]);
        $this->form->addFields([new TLabel('Complemento')], [$complemento]);
        $this->form->addFields([new TLabel('CEP')], [$cep]);
        $this->form->addFields([new TLabel('Cidade')], [$cidade]);
        $this->form->addFields([new TLabel('Fone')], [$fone]);
        $this->form->addFields([new TLabel('E-mail')], [$email]);

        $id->setSize('30%');
        $nome->setSize('100%');
        $dt_nascimento->setSize('100%');
        $documento->setSize('100%');
        $tipo_pessoa->setSize('100%');
        $endereco->setSize('100%');
        $bairro->setSize('100%');
        $numero->setSize('100%');
        $complemento->setSize('100%');
        $cep->setSize('100%');
        $cidade->setSize('100%');
        $fone->setSize('100%');
        $email->setSize('100%');
        $email->addValidation('email', new TEmailValidator);

        // validations
        $nome->addValidation('nome', new TRequiredValidator);

        // add form actions
        $btn = $this->form->addAction(_t('Save'), new TAction(array($this, 'onSave')), 'far:save');
        $btn->class = 'btn btn-sm btn-primary';

        $this->form->addActionLink(_t('Clear'), new TAction(array($this, 'onEdit')), 'fa:eraser red');
        //$this->form->addActionLink(_t('Back'),new TAction(array('SystemProgramList','onReload')),'far:arrow-alt-circle-left blue');

        $this->form->addHeaderActionLink(_t('Close'), new TAction([$this, 'onClose']), 'fa:times red');

        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml','SystemProgramList'));
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

            $object = new Cliente;
            $object->fromArray((array) $data);

            $this->form->validate();
            $object->store();
            $this->form->setData($object);

            TTransaction::close();
            $pos_action = new TAction(['RevendasClientesList', 'onReload']);
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
