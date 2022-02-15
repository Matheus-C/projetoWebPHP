<?php

namespace App\Controller;

use App\Core\ControllerBase;
use App\core\Funcoes;

class Admin extends ControllerBase
{

    function __construct() //verifica se o usuário está logado e se ele é um admin ou não
    {
        session_start();
        if (!Funcoes::usuarioLogado()) {
            Funcoes::redirect("home");
        } elseif ($_SESSION['tipoUser'] != 0) {
            Funcoes::redirect("home");
        }
    }

    public function index($numPag = 1) //carrega a view com o datatable
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') :
            $data = ['titulo' => "Admin"];
            $this->viewDataTable('Admin/index', $data, VIEW_ROOT . 'Admin/adminjs');
        else :
            Funcoes::redirect("home");
        endif;
    }


    // obtém todos os Usuarios
    public function getAjaxUsuarios()
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
            $searchQuery = " AND (nome LIKE :nome or email LIKE :email ) ";

            $searchArray = array(
                'nome' => "%$searchValue%",
                'email' => "%$searchValue%"
            );
        }

        $modelUsuario = $this->model("ModelUsuario");
        ## Total de registro sem filtragem
        $records = $modelUsuario->getTotalUsuarios();
        $totalRecords = $records['total'];
        $totalRecordComFiltro = $records['total'];
        if ($searchValue != '') {
            $records = $modelUsuario->search($searchQuery, $searchArray); //função que retorna o total com filtro da busca
            $totalRecords = $records['total'];
            $totalRecordComFiltro = $records['total'];
        }

        $data = [];
        $sql = "SELECT * FROM usuarios WHERE 1 " . $searchQuery . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT :limit,:offset";
        if ($searchValue == '') {
            $searchArray = [];
        } //chama função que retorna os usuários de acordo com os comandos da datatable
        $usuarios = $modelUsuario->construirDataTable($sql, $searchValue, $searchArray, $row, $rowperpage);
        foreach ($usuarios as $row) {
            $data[] = array(
                "id" => htmlentities(utf8_encode($row['hashid'])),
                "nome" => htmlentities(utf8_encode($row['nome'])),
                "tipo" => htmlentities(utf8_encode($row['tipo'])),
                "email" => htmlentities(utf8_encode($row['email'])),
                "acoes" => '<a href="#"  data-id="' . $row['hashid'] . '" data-nome="' . $row['nome'] . '" class="btn btn-outline-dark excluir" >excluir</a>'
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


    // exclusão do usuario
    public function excluirUsuario($data)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $id = $data['id'];
            if ($id == $_SESSION['hashid']) { //verifica se o usuário atual é o usuário sofrendo a tentativa de exclusão
                $data = [];
                $data['status'] = false;
                $data['mensagem'] = 'você não pode se excluir';
                echo json_encode($data);
                exit();
            } else {
                $itemModel = $this->model("ModelItem"); //instancia o model dos itens
                $temItem = $itemModel->getTotalItensPorUser($id); //verifica se existe algum item registrado por aquele user
                if (intval($temItem) > 0) {                       //se sim os itens serão excluídos
                    $itemModel->deleteItensPorUser($id);
                }
                $usuarioModel = $this->model("ModelUsuario"); //instancia o model dos usuarios
                $usuarioModel->apagaUsuario($id);

                $data = [];
                $data['status'] = true;
                echo json_encode($data);
                exit();
            }
        } else {
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error');
            echo "Processamento Inválido - Deveria ser GET";
            exit();
        }
    }
}
