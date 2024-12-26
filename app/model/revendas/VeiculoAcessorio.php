<?php

use Adianti\Database\TRecord;
class VeiculoAcessorio extends TRecord
{
  use SystemChangeLogTrait;
  const TABLENAME = 'veiculos_acessorios';
  const PRIMARYKEY = 'id';
  const IDPOLICY = 'max'; // {max, serial}

  public function __construct($id = NULL)
  {
    parent::__construct($id);
    parent::addAttribute('veiculos_id');
    parent::addAttribute('acessorios_id');
  }

  public function getAcessorios($acessorios_id)
  {
    return new Acessorio($acessorios_id);
  }

  public function getVeiculos($veiculos_id)
  {
    return new Veiculo($veiculos_id);
  }
}
