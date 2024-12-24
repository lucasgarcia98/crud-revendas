<?php

use Adianti\Database\TRecord;
class Veiculo extends TRecord
{
    const TABLENAME = 'veiculos';
    const PRIMARYKEY = 'id';
    const IDPOLICY = 'max'; // {max, serial}

    private $fabricante;
    private $veiculos_acessorios = [];
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
        parent::addAttribute('fabricante_id');
    }

    public function set_fabricante(Fabricante $object)
    {
        $this->fabricante = $object;
        $this->{'fabricante_id'} = $object->id;
    }

    public function get_fabricante()
    {
        if (empty($this->fabricante) && !empty($this->fabricante_id)) {
            $this->fabricante = new Fabricante($this->fabricante_id);
        }

        return $this->fabricante;
    }

    public function addAcessorio(Acessorio $acessorio)
    {
        $object = new VeiculoAcessorio;
        $object->veiculos_id = $this->id;
        $object->acessorios_id = $acessorio->id;
        $object->store();
    }

    public function getVeiculosAcessorios()
    {
        return parent::loadAggregate('Acessorio', 'VeiculoAcessorio', 'veiculos_id', 'acessorios_id', $this->id);
    }

    public function clearParts()
    {
        VeiculoAcessorio::where('veiculos_id', '=', $this->id)->delete();
    }
}
