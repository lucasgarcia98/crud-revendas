<?php

use Adianti\Database\TRecord;
class Venda extends TRecord
{
  use SystemChangeLogTrait;
  const TABLENAME = 'vendas';
  const PRIMARYKEY = 'id';
  const IDPOLICY = 'max'; // {max, serial}

  private $cliente;
  private $veiculo;
  public function __construct($id = NULL)
  {
    parent::__construct($id);
    parent::addAttribute('dt_venda');
    parent::addAttribute('valor');
    parent::addAttribute('obs');
    parent::addAttribute('cliente_id');
    parent::addAttribute('veiculo_id');
  }

  public function set_cliente(Cliente $object)
  {
    $this->cliente = $object;
    $this->{'cliente_id'} = $object->id;
  }

  public function get_cliente()
  {
    if (empty($this->cliente) && !empty($this->cliente_id)) {
      $this->cliente = new Cliente($this->cliente_id);
    }

    return $this->cliente;
  }

  public function set_veiculo(Veiculo $object)
  {
    $this->veiculo = $object;
    $this->{'veiculo_id'} = $object->id;
  }

  public function get_veiculo()
  {
    if (empty($this->veiculo) && !empty($this->veiculo_id)) {
      $this->veiculo = new Veiculo($this->veiculo_id);
    }

    return $this->veiculo;
  }
}
