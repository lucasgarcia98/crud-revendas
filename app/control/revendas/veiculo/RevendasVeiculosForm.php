<?php

use Adianti\Log\TLoggerSTD;
use Adianti\Validator\TMinValueValidator;
use Adianti\Widget\Form\TSpinner;
use Adianti\Widget\Wrapper\TDBMultiCombo;
class RevendasVeiculosForm extends TStandardForm
{
    protected $form; // form

    private $fabricante_list = [];
    protected $acessorios_list;
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct($param)
    {
        parent::__construct();

        parent::setTargetContainer('adianti_right_panel');

        // creates the form
        $this->form = new BootstrapFormBuilder('form_Veiculo');
        $this->form->setFormTitle('Veículo');
        $this->form->enableClientValidation();

        // defines the database
        parent::setDatabase('revendas');

        // defines the active record
        parent::setActiveRecord('Veiculo');

        // create the form fields
        $id = new TEntry('id');

        $descricao = new TEntry('descricao');

        $placa = new TEntry('placa');
        $placa->setMaxLength(8);

        $ano = new TSpinner('ano');
        $ano->setRange(1900, date('Y') + 10, 1);
        $ano->addValidation('ano', new TMinValueValidator, ['min' => 1900]);
        $ano->setValue(date('Y'));

        $cor = new TCombo('cor');
        $itemsCor = ['Branco' => 'Branco', 'Preto' => 'Preto', 'Prata' => 'Prata', 'Vermelho' => 'Vermelho', 'Azul' => 'Azul', 'Verde' => 'Verde', 'Amarelo' => 'Amarelo', 'Laranja' => 'Laranja', 'Rosa' => 'Rosa', 'Roxo' => 'Roxo', 'Marrom' => 'Marrom', 'Bege' => 'Bege', 'Cinza' => 'Cinza', 'Dourado' => 'Dourado', 'Outra' => 'Outra'];
        $cor->addItems($itemsCor);
        $cor->enableSearch();
        $cor->setSize('100%');

        $km = new TSpinner('km');
        $valor = new TNumeric('valor', 2, ',', '.', true);

        $obs = new TText('obs');
        $obs->setSize('100%', 120);
        $fabricante_id = new TDBCombo('fabricante_id', 'revendas', 'Fabricante', 'id', 'nome');
        $acessorios = new TDBMultiCombo('acessorios', 'revendas', 'Acessorio', 'id', 'descricao');
        $acessorios->setSize('100%');

        $id->setEditable(false);

        // add the fields
        $this->form->addFields([new TLabel('ID')], [$id]);
        $this->form->addFields([new TLabel('Descricao')], [$descricao]);
        $this->form->addFields([new TLabel('Placa')], [$placa]);
        $this->form->addFields([new TLabel('Ano')], [$ano]);
        $this->form->addFields([new TLabel('Cor')], [$cor]);
        $this->form->addFields([new TLabel('Km')], [$km]);
        $this->form->addFields([new TLabel('Valor')], [$valor]);
        $this->form->addFields([new TLabel('Obs')], [$obs]);
        $this->form->addFields([new TLabel('Fabricante')], [$fabricante_id]);
        $this->form->addFields([new TLabel('Acessórios')], [$acessorios]);
        $id->setSize('30%');

        // validations
        $descricao->addValidation('descricao', new TRequiredValidator);

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

                $object->acessorios = array_column($object->getVeiculosAcessorios(), 'id');

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

            $object = new Veiculo();
            $object->fromArray((array) $data);

            $this->form->validate();
            $object->store();
            $object->clearParts();

            if (!empty($data->acessorios)) {
                foreach ($data->acessorios as $acessorio_id) {
                    $object->addAcessorio(new Acessorio($acessorio_id));
                }
            }

            $this->form->setData($object);

            TTransaction::close();
            $pos_action = new TAction(['RevendasVeiculosList', 'onReload']);
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
