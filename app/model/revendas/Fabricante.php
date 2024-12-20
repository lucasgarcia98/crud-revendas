<?php

use Adianti\Database\TRecord;
class Fabricante extends TRecord
{
  const TABLENAME = 'fabricantes';
  const PRIMARYKEY = 'id';
  const IDPOLICY =  'max'; // {max, serial}

  public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('logo');
    }
}


/**
 * 
 * TABELAS
fabricantes: nome, logo - input image


acessorios: descricao


cliente: nome, dt_nascimento, cpf - mascara, fisica/juridica - combo , endereco, bairro, numero, 
complemento, cep - mascara, cidade - combo, fone - mascara, email - mascara


veiculo: descricao, placa, ano, cor - combo, fabricante(fk), km, acessorios(fk, 1->n), valor - numeric, obs - textarea


venda: dt_venda, cliente(fk), veiculo(fk), valor - numeric, obs - textarea

 */