<?php

use Adianti\Database\TRecord;
class VeiculoAcessorio extends TRecord
{
  const TABLENAME = 'veiculos_acessorios';
  const PRIMARYKEY = 'id';
  const IDPOLICY =  'max'; // {max, serial}

  public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('veiculos_id');
        parent::addAttribute('acessorios_id');
    }
}
