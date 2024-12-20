<?php
class RevendasFabricantesForm extends TStandardForm
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
        $this->form = new BootstrapFormBuilder('form_Fabricantes');
        $this->form->setFormTitle('Fabricante');
        $this->form->enableClientValidation();

        // defines the database
        parent::setDatabase('revendas');

        // defines the active record
        parent::setActiveRecord('Fabricante');

        // create the form fields
        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $logo = new TFile('logo');

        $logo->setAllowedExtensions(['jpg']);
        $logo->enableImageGallery();
        $logo->setDisplayMode('file');
        $id->setEditable(false);

        // add the fields
        $this->form->addFields([new TLabel('ID')], [$id]);
        $this->form->addFields([new TLabel('Nome')], [$nome]);
        $this->form->addFields([new TLabel('Logo')], [$logo]);
        $id->setSize('30%');
        $nome->setSize('100%');
        $logo->setSize('100%');

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
                $object->logo = 'tmp/' . $object->logo;

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

            $object = new Fabricante;
            $object->id = $data->id;
            $object->nome = $data->nome;
            $object->logo = $data->logo;

            $this->form->validate();
            $object->store();
            if (!is_writable('tmp/' . $object->logo)) {
                throw new Exception(AdiantiCoreTranslator::translate('Permission denied') . ': tmp/' . $object->logo);
            }

            if ($object->logo) {
                $source_file = 'tmp/' . $object->logo;
                $target_file = 'tmp/images/' . $object->logo;
                $finfo = new finfo(FILEINFO_MIME_TYPE);

                if (file_exists($source_file) and $finfo->file($source_file) == 'image/jpeg') {
                    // move to the target directory
                    rename($source_file, $target_file);
                }
            }

            $this->form->setData($object);

            TTransaction::close();
            $pos_action = new TAction(['RevendasFabricantesList', 'onReload']);
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
