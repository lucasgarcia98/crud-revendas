<?php

use Adianti\Control\TPage;
class RevendasVeiculosView extends TPage
{
  protected $veiculo;

  public function __construct(array $params = NULL)
  {
    parent::__construct();
    TTransaction::open('revendas');
    parent::include_css('app/resources/revendas/revendas.css');
    $veiculo = new Veiculo($params['id']);
    $html = new THtmlRenderer('app/resources/revendas/veiculo_view.html');

    $acessorios = $veiculo->getVeiculosAcessorios();


    $html->enableSection('main', [
      'descricao' => $veiculo->descricao,
      'placa' => $veiculo->placa,
      'ano' => $veiculo->ano,
      'cor' => $veiculo->cor,
      'km' => $veiculo->km,
      'valor' => $veiculo->valor,
      'obs' => $veiculo->obs,
    ]);

    if ($veiculo->fabricante) {
      $html->enableSection('fabricante', [
        'nome_fabricante' => $veiculo->fabricante->nome,
        'logo_fabricante' => $veiculo->fabricante->logo ? 'tmp/' . $veiculo->fabricante->logo : ''
      ]);
    }

    if ($acessorios) {
      $stringAcessorios = '';

      if (count($acessorios) > 1) {
        foreach ($acessorios as $acessorio) {
          $stringAcessorios .= $acessorio->descricao . ', ';
        }
      } else {
        $stringAcessorios = $acessorios[0]->descricao;
      }

      $stringAcessorios = trim($stringAcessorios);
      $stringAcessorios = rtrim($stringAcessorios, ',');

      $html->enableSection('acessorios', [
        'lista_acessorios' => $stringAcessorios
      ]);
    }

    TTransaction::close();

    $container = $this->container($params, $html);

    parent::add($container);

  }

  public function container($params, ...$elements): TVBox
  {
    $container = new TVBox;
    $container->{'style'} = 'width: 100%';
    foreach ($elements as $element) {
      $container->add($element);
    }

    return $container;
  }


  public function onShow(array $params = NULL)
  {

    $key = (isset($params['key']) and $params['key']) ? $params['key'] : NULL;
    if ($key) {
      try {
        TTransaction::open('revendas');

        $this->veiculo = new Veiculo($key);


        TTransaction::close();
      } catch (Exception $e) {
        TTransaction::rollback();
        new TMessage('error', $e->getMessage());
      }
    }
  }
}