<?php

use Adianti\Database\TRecord;
class Cliente extends TRecord
{
  const TABLENAME = 'clientes';
  const PRIMARYKEY = 'id';
  const IDPOLICY =  'max'; // {max, serial}

  public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('dt_nascimento');
        parent::addAttribute('documento');
        parent::addAttribute('tipo_pessoa');
        parent::addAttribute('endereco');
        parent::addAttribute('bairro');
        parent::addAttribute('numero');
        parent::addAttribute('complemento');
        parent::addAttribute('cep');
        parent::addAttribute('cidade');
        parent::addAttribute('fone');
        parent::addAttribute('email');
    }
}
