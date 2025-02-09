<?php

use Adianti\Database\TRecord;
class Fabricante extends TRecord
{
  use SystemChangeLogTrait;
  const TABLENAME = 'fabricantes';
  const PRIMARYKEY = 'id';
  const IDPOLICY = 'max'; // {max, serial}

  public function __construct($id = NULL, $callObjectLoad = TRUE)
  {
    parent::__construct($id, $callObjectLoad);
    parent::addAttribute('nome');
    parent::addAttribute('logo');
  }
}
