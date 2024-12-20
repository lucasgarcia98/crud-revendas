<?php

use Adianti\Database\TRecord;
class Cliente extends TRecord
{
  const TABLENAME = 'clientes';
  const PRIMARYKEY = 'id';
  const IDPOLICY =  'max'; // {max, serial}

  private $fabricante;
  private $acessorios = [];

  public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('placa');
        parent::addAttribute('ano');
        parent::addAttribute('cor');
        parent::addAttribute('km');
        parent::addAttribute('valor');
        parent::addAttribute('obs');
    }

    public function get_fabricante()
    {
        if (empty($this->fabricante) && !empty($this->fabricante_id))
        {
            $this->fabricante = new Fabricante($this->fabricante_id);
        }

        return $this->fabricante;
    }

    public function addVeiculoAcessorio(Acessorio $acessorio)
    {
        $object = new VeiculoAcessorio;
        $object->veiculos_id = $this->id;
        $object->acessorios_id = $acessorio->id;
        $object->store();
    }
}
