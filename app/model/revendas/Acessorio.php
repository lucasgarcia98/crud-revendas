<?php

use Adianti\Database\TRecord;
class Acessorio extends TRecord
{
  const TABLENAME = 'acessorios';
  const PRIMARYKEY = 'id';
  const IDPOLICY =  'max'; // {max, serial}

  public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
    }
}
