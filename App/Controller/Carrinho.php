<?php

namespace App\Controller;

use App\Model\Carrinho as car;
use App\Model\Item;
use App\Core\ControllerBase;
use App\core\Funcoes;


class Carrinho extends ControllerBase
{
    function __construct()
    {
        session_start();
        if (!Funcoes::usuarioLogado()) :
            Funcoes::redirect("Home");
        endif;
    }

    public function index($numPag = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') :
            $data = ['titulo' => "Carrinho"];
            $this->viewDataTable('Carrinho/index', $data, 'Carrinho/carrinhojs');
        else :
            Funcoes::redirect("Home");
        endif;
    }

    public function getAjaxItensCarrinho()
    {

        // dados de controle enviados pelo DataTable
        $draw = $_POST['draw'];  // usado pelo DataTables para garantir que os retornos do Ajax das solicitações de processamento do lado do servidor sejam desenhados em sequência 
        $itensNoCarrinho = $_SESSION["carrinho"]->getItens();
        ## Total de registro sem filtragem
        if ($itensNoCarrinho == []) {
            $records = "0";
        } else {
            $records = count($itensNoCarrinho);
        }
        $totalRecords = $records;
        $totalRecordComFiltro = $records;
        $data = [];
        foreach ($itensNoCarrinho as $item) {
            $data[] = array(
                "nomeItem" => htmlentities(utf8_encode($item->getNomeItem())),
                "quantidade" => htmlentities(utf8_encode($item->getQuantidade())),
                "preco" => htmlentities(utf8_encode($item->getPreco())),
                "acoes" => '<a href="#" data-preco="' . $item->getPreco() . '" data-quantidade="' . $item->getQuantidade() . '" data-id="' . $item->getHashid() . '" data-nomeItem="' . $item->getNomeItem() . '" class="btn btn-outline-dark remover" >Remover</a>'
            );
        }
        $_SESSION['CSRF_token'] = Funcoes::gerarTokenCSRF();
        ## Response - devolvendo os dados 
        $response = array(
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordComFiltro,
            "data" => $data,
            "CSRF_token" => $_SESSION['CSRF_token']
        );

        echo json_encode($response);
        exit();
    }

    //adiciona itens ao carrinho
    public function adicionarAoCarrinho($data)
    {
        $quantidade = $data["quantidade"];
        if ($data["quantidade"] == "") { //se a quantidade não for informada será considerada como 1
            $quantidade = 1;
        }
        $hashid = $data["id"]; //hashid do item
        $itensNoCarrinho = $_SESSION['carrinho']->getItens(); //puxa os itens do carrinho
        if (isset($itensNoCarrinho[$hashid])) { //verifica se o item já existe no carrinho
            $quantCarrinho = $itensNoCarrinho[$hashid]->getQuantidade(); //se já, puxa a quantidade de itens
        } else {
            $quantCarrinho = 0;
        }
        $modelItem = $this->model("modelitem");
        $itemPar = $modelItem->getitemPorHashId($hashid); //puxa a quantidade no estoque
        if (intval($itemPar["quantidade"]) < $quantCarrinho + $quantidade) { //verifica se a quantidadeno estoque bate com a quantidade pedida
            $data = [];                                                   //se não é mostrada uma mensagem de erro
            $data['status'] = false;
            $data['mensagem'] = "Essa quantidade ultrapassa o estoque";
            echo json_encode($data);
            exit();
        } else { //se há itens suficientes no estoque é adicionado no carrinho
            $item = new Item($itemPar['nome_item'], $itemPar['descricao'], $itemPar['id_vendedor'], $itemPar['preco'], $quantidade, $itemPar['hashid']);
            $_SESSION["carrinho"]->adiciona($item);
        }
        $data = [];
        $data['status'] = true;
        echo json_encode($data);
        exit();
    }

    public function removeDoCarrinho($data)
    {
        $_SESSION["carrinho"]->remove($data["id"], $data["preco"], $data['quantidade']); //remove os itens

        $data = [];
        $data['status'] = true;
        $data['preco'] = $_SESSION["carrinho"]->getTotal();
        echo json_encode($data);
        exit();
    }

    public function comprar()
    { //realiza a compra
        $modelItem = $this->model("ModelItem");
        if ($_SESSION["carrinho"]->getItens() == []) {
            $data['status'] = false;
            $data['mensagem'] = "Não há itens no carrinho.";
            echo json_encode($data);
            exit();
        }
        foreach ($_SESSION["carrinho"]->getItens() as $item) { //para cada item verifica se tem quantidade suficiente no estoque
            $temItem = $modelItem->checarQuantidade($item->getHashid(), $item->getQuantidade());
            if (!$temItem) {
                $data['status'] = false;
                $data['mensagem'] = "Não há itens suficientes no estoque - " . $item->getNomeItem();
                echo json_encode($data);
                exit();
            }
        }
        foreach ($_SESSION["carrinho"]->getItens() as $item) { //se não houve problemas é chamada a função que reduz a quantidade de itens em estoque
            $modelItem->reduzirQuantidade($item->getHashid(), $item->getQuantidade());
        }
        #após a compra ser efetuada o carrinho é zerado.
        $_SESSION["carrinho"] = new car();
        $data = [];
        $data['preco'] = $_SESSION["carrinho"]->getTotal();
        $data['status'] = true;
        echo json_encode($data);
        exit();
    }
}
