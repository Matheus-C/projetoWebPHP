<?php

namespace App\Controller;

use App\Core\ControllerBase;
use App\core\Funcoes;

class Home extends ControllerBase
{

    function __construct()
    {
        session_start();
    }
    public function index() //puxa a view
    {
        $data = ['titulo' => "ShopNow"];
        $this->viewDataTable('home/index', $data, 'home/homejs');
    }

    // obtém todos os itens
    public function getAjaxItens()
    {

        // dados de controle enviados pelo DataTable
        $draw = $_POST['draw'];  // usado pelo DataTables para garantir que os retornos do Ajax das solicitações de processamento do lado do servidor sejam desenhados em sequência 
        $row = $_POST['start'];  // Indicador de primeiro registro de paginação
        $rowperpage = $_POST['length']; // número de registros por página
        $columnIndex = $_POST['order'][0]['column']; // Coluna à qual a ordenação deve ser aplicad
        $columnName = $_POST['columns'][$columnIndex]['data']; // nome da coluna
        $columnSortOrder = $_POST['order'][0]['dir']; // ordenação asc ou desc
        $searchValue = $_POST['search']['value']; // string de pesquisa (Search)

        ## Search 
        $searchQuery = " ";
        if ($searchValue != '') {
            $searchQuery = " AND (nome_item LIKE :nome or descricao LIKE :descricao ) ";

            $searchArray = array(
                'nome' => "%$searchValue%",
                'descricao' => "%$searchValue%"
            );
        } else {
            $searchArray = [];
        }
        $modelItem = $this->model("ModelItem");
        $modelUsuario = $this->model("ModelUsuario");
        ## Total de registro sem filtragem
        $records = $modelItem->getTotalItens();
        $totalRecords = $records['total'];
        $totalRecordComFiltro = $records['total'];
        if ($searchValue != '') {
            $records = $modelItem->search($searchQuery, $searchArray); //função que retorna o total com filtro da busca
            $totalRecords = $records['total'];
            $totalRecordComFiltro = $records['total'];
        }
        $data = [];
        $sql = "SELECT * FROM itens WHERE 1 " . $searchQuery . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT :limit,:offset";
        //chama função que retorna os itens de acordo com os comandos da datatable
        $itens = $modelItem->construirDataTable($sql, $searchValue, $searchArray, $row, $rowperpage);
        foreach ($itens as $row) {
            if (isset($_SESSION['hashid'])) {
                $data[] = array(
                    "nome_item" => htmlentities(utf8_encode($row['nome_item'])),
                    "descricao" => htmlentities(utf8_encode($row['descricao'])),
                    "vendedor" => htmlentities(utf8_encode($modelUsuario->getNome($row['id_vendedor']))),
                    "quantidade" => htmlentities(utf8_encode($row['quantidade'])),
                    "preco" => htmlentities(utf8_encode($row['preco'])),
                    "acoes" => '<a href="#" data-id="' . $row['hashid'] . '"class="btn btn-outline-dark adiciona_carrinho">Adicionar ao carrinho</a>
                    <a href="#"  data-id="' . $row['hashid'] . '" data-nomeItem="' . $row['nome_item'] . '" class="btn btn-outline-dark visualizar" >Visualizar</a>'
                );
            } else {
                $data[] = array(
                    "nome_item" => htmlentities(utf8_encode($row['nome_item'])),
                    "descricao" => htmlentities(utf8_encode($row['descricao'])),
                    "vendedor" => htmlentities(utf8_encode($modelUsuario->getNome($row['id_vendedor']))),
                    "quantidade" => htmlentities(utf8_encode($row['quantidade'])),
                    "preco" => htmlentities(utf8_encode($row['preco']))
                );
            }
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
}
