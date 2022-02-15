<?php

namespace App\Controller;

use App\Core\ControllerBase;
use App\core\Funcoes;
use GUMP as Validador;
use App\Model\Item;


class ControllerItem extends ControllerBase
{

    protected $filters = [ // filtros para os campos e-mail, senha e captcha
        'nome' => 'trim|sanitize_string',
        'descricao' => 'trim|sanitize_string',
        'preco' => 'trim|sanitize_numbers',
        'quantidade' => 'trim|sanitize_numbers',
        'captcha' => 'trim|sanitize_string'
    ];

    protected $rules = [
        'nome'    => 'required|min_len,2|max_len,40',
        'descricao'    => 'required|min_len,2|max_len,200',
        'preco' => 'required|numeric|min_numeric,0.01',
        'quantidade' => 'required|numeric|min_numeric,1',
        'captcha' => 'required|validar_CAPTCHA_CODE'
    ];

    function __construct()
    {
        session_start();
        if (!Funcoes::usuarioLogado()) :
            Funcoes::redirect("Home");
        endif;
    }

    public function index($numPag = 1) //função que busca a view da tela de itens do usuário
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') :
            $data = ['titulo' => "Meus itens"];


            $this->viewDataTable('item/index', $data, 'item/itemjs');
        else :
            Funcoes::redirect("Home");
        endif;
    }



    #-----------------------------------------------------------------------------------------
    public function getAjaxItensPorUser() // datatable puxa apenas os itens que o usuário postou
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
            $searchQuery = " AND (nome LIKE :nome or descricao LIKE :descricao ) ";

            $searchArray = array(
                'nome' => "%$searchValue%",
                'descricao' => "%$searchValue%"
            );
        } else {
            $searchArray = [];
        }
        $modelItem = $this->model("ModelItem");
        ## Total de registro sem filtragem
        $hashid = $_SESSION['hashid'];
        $records = $modelItem->getTotalItensPorUser($hashid);
        $totalRecords = $records['total'];
        $totalRecordComFiltro = $records['total'];
        if ($searchValue != '') {
            $records = $modelItem->search($searchQuery, $searchArray); //função que retorna o total com filtro da busca
            $totalRecords = $records['total'];
            $totalRecordComFiltro = $records['total'];
        }
        $data = [];
        $sql = "SELECT * FROM itens WHERE id_vendedor = :hashid AND " . 1  . $searchQuery . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT :limit,:offset";
        //chama função que retorna os itens de acordo com os comandos da datatable
        $itens = $modelItem->construirDataTable($sql, $searchValue, $searchArray, $row, $rowperpage, $hashid);
        foreach ($itens as $row) {
            $data[] = array(
                "nome_item" => htmlentities(utf8_encode($row['nome_item'])),
                "descricao" => htmlentities(utf8_encode($row['descricao'])),
                "quantidade" => htmlentities(utf8_encode($row['quantidade'])),
                "preco" => htmlentities(utf8_encode($row['preco'])),
                "acoes" => '<a href="#" data-id="' . $row['hashid'] . '"class="btn btn-outline-dark selecionar_editar">editar</a>
                 <a href="#"  data-id="' . $row['hashid'] . '" data-nomeItem="' . $row['nome_item'] . '" class="btn btn-outline-dark excluir" >Remover</a>'
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

    // obtém dados de um item para alteração
    public function getAjaxItemAlterar()
    {
        if ($_POST['CSRF_token'] == $_SESSION['CSRF_token']) {
            $_SESSION['CAPTCHA_CODE'] = Funcoes::gerarCaptcha();
            $imagem = Funcoes::gerarImgCaptcha($_SESSION['CAPTCHA_CODE']);
            $id = filter_var($_POST['hashid'], FILTER_SANITIZE_STRING);

            $itemModel = $this->model("modelItem");
            $item = $itemModel->getitemPorHashId($id);

            $_SESSION['CSRF_token'] = Funcoes::gerarTokenCSRF();
            ## Response - devolvendo os dados 
            $response = array(
                "hashid" => htmlentities(utf8_encode($item['hashid'])),
                "nome" => htmlentities(utf8_encode($item['nome_item'])),
                "descricao" => htmlentities(utf8_encode($item['descricao'])),
                "preco" => htmlentities(utf8_encode($item['preco'])),
                "quantidade" => htmlentities(utf8_encode($item['quantidade'])),
                "CSRF_token" => $_SESSION['CSRF_token'],
                "imagem" => $imagem,
                "status" => true
            );

            echo json_encode($response);
            exit();
        } else {
            ## Response - devolvendo erro 
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error');
            echo "Processamento Inválido - Deveria ser GET";
            exit();
        }
    }

    // gravar dados alterados de um item
    public function gravarAlteracaoItem()
    {

        if ($_POST['CSRF_token'] == $_SESSION['CSRF_token']) :

            $_SESSION['CSRF_token'] = Funcoes::gerarTokenCSRF();
            Validador::add_validator("validar_CAPTCHA_CODE", function ($field, $input) {
                return $input['captcha'] === $_SESSION['CAPTCHA_CODE'];
            }, 'Código de Segurança incorreto.');
            $validacao = new Validador("pt-br");

            $post_filtrado = $validacao->filter($_POST, $this->filters);
            $post_validado = $validacao->validate($post_filtrado, $this->rules);

            if ($post_validado === true) :  // verificar dados do item

                // criando um objeto item
                $item = new \App\model\Item();
                $item->setHashid($_POST['hashid']);
                $item->setNomeItem($_POST['nome']);
                $item->setDescricao($_POST['descricao']);
                $item->setQuantidade($_POST['quantidade']);
                $item->setPreco(floatval($_POST['preco']));

                $itemModel = $this->model("modelItem");

                $itemModel->updateitem($item);

                ## Response - devolvendo que foi deito o update
                $response = array(
                    "CSRF_token" => $_SESSION['CSRF_token'],
                    "status" => true
                );
                echo json_encode($response);
                exit();

            else :
                $_SESSION['CAPTCHA_CODE'] = Funcoes::gerarCaptcha();
                $imagem = Funcoes::gerarImgCaptcha($_SESSION['CAPTCHA_CODE']);
                $data['imagem'] = $imagem;
                ## Response - devolvendo erro de validação de dados
                $erros = $validacao->get_errors_array();
                $erros = implode("<br>", $erros);
                $response = array(
                    "erros" => $erros,
                    "CSRF_token" => $_SESSION['CSRF_token'],
                    "status" => false,
                    "imagem" => $imagem
                );

                echo json_encode($response);
                exit();

            endif;

        else :

            ## Response - devolvendo erro de processamento interno
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error');
            echo "Processamento Inválido - CSRF_token invalido";
            exit();

        endif;
    }

    // exclusão do item
    public function excluirItem($data)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $id = $data['id'];

            $itemModel = $this->model("modelItem");

            $itemModel->apagaItem($id);

            $data = [];
            $data['status'] = true;
            echo json_encode($data);
            exit();
        } else {
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error');
            echo "Processamento Inválido - Deveria ser GET";
            exit();
        }
    }

    public function registrarItem()
    { // puxa a view de registro de item
        // gera o CAPTCHA_CODE e guarda na sessão 
        $_SESSION['CAPTCHA_CODE'] = Funcoes::gerarCaptcha();
        $imagem = Funcoes::gerarImgCaptcha($_SESSION['CAPTCHA_CODE']);
        // gera o CSRF_token e guarda na sessão
        $_SESSION['CSRF_token'] = Funcoes::gerarTokenCSRF();
        $data = ['imagem' => $imagem];
        $data['titulo'] = "registrar item";
        // chama a view
        $this->view('Item/incluirItem', $data);
    }

    public function novoItem()
    { //registra um item
        if ($_SERVER['REQUEST_METHOD'] == "POST") {

            Validador::add_validator("validar_CAPTCHA_CODE", function ($field, $input) {
                return $input['captcha'] === $_SESSION['CAPTCHA_CODE'];
            }, 'Código de Segurança incorreto.');
            $validacao = new Validador("pt-br");

            $post_filtrado = $validacao->filter($_POST, $this->filters);
            $post_validado = $validacao->validate($post_filtrado, $this->rules);

            if ($post_validado === true) {
                if ($_POST['CSRF_token'] == $_SESSION['CSRF_token']) :
                    $item = new Item($_POST['nome'], $_POST['descricao'], $_POST['idVendedor'], floatval($_POST['preco']), $_POST['quantidade']);
                    $itemModel = $this->model("modelItem");
                    $idItem = $itemModel->create($item);
                    $itemModel->createHashID($idItem);
                    Funcoes::redirect("painelitens"); //caso seja bem sucedido retorna ao painel de itens

                else :  // falha CSRF_token"
                    die("Erro 404");
                endif;
            } else { // erro de validação
                $mensagem = $validacao->get_errors_array();
                $_SESSION['CAPTCHA_CODE'] = Funcoes::gerarCaptcha(); // guarda o captcha_code na sessão 
                $imagem = Funcoes::gerarImgCaptcha($_SESSION['CAPTCHA_CODE']);
                $_SESSION['CSRF_token'] = Funcoes::gerarTokenCSRF();
                $data = [
                    'imagem' => $imagem,
                    'mensagens' => $mensagem,
                    'titulo' => "registrar Item"
                ];
                //em caso de erro de validação retorna para a tela de registro
                $this->view('item/incluirItem', $data);
            }
        } else {
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error');
            echo "Processamento Inválido - Deveria ser POST";
            exit();
        }
    }
    public function visualizarItem($data)
    { // puxa a view de visualizar o item
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            $modelItem = $this->model("modelitem");
            $data = $modelItem->getitemPorHashId($data['hashid']);
            $data['titulo'] = $data['nome_item'];
            $this->view('item/visualizarItem', $data, 'item/visualizarJs');
        } else {
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error');
            echo "Processamento Inválido - Deveria ser POST";
            exit();
        }
    }
}
