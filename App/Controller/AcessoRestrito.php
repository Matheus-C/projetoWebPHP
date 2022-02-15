<?php

namespace App\Controller;

use App\core\ControllerBase;
use App\core\Funcoes;
use App\Model\Carrinho; //classe que implementa o carrinho que vai ser salvo na session
use App\Model\Usuario;
use GUMP as Validador;

class AcessoRestrito extends ControllerBase
{
    protected $filters = [ // filtros para os campos e-mail, senha e captcha
        'email' => 'trim|sanitize_email',
        'senha' => 'trim|sanitize_string',
        'captcha' => 'trim|sanitize_string'
    ];

    protected $rules = [ // regras para os campos e-mail, senha e captcha
        'email' => 'required|min_len,8|max_len,255',
        'senha' => 'required',
        'captcha' => 'required|validar_CAPTCHA_CODE'
    ];


    function __construct()
    {
        session_start();
    }

    public function login() // função que busca a view da tela de login
    {
        // gera o CAPTCHA_CODE e guarda na sessão 
        $_SESSION['CAPTCHA_CODE'] = Funcoes::gerarCaptcha();
        $imagem = Funcoes::gerarImgCaptcha($_SESSION['CAPTCHA_CODE']);
        // gera o CSRF_token e guarda na sessão
        $_SESSION['CSRF_token'] = Funcoes::gerarTokenCSRF();
        $data = ['imagem' => $imagem];
        $data['titulo'] = "login";
        // chama a view
        $this->view('acessorestrito/login', $data);
    }

    public function logar() // função que aplica o login
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") :
            // validar captcha
            Validador::add_validator("validar_CAPTCHA_CODE", function ($field, $input) {
                return $input['captcha'] === $_SESSION['CAPTCHA_CODE'];
            }, 'Código de Segurança incorreto.');

            $validacao = new Validador("pt-br");
            //valida os dados de acordo com os filtros e regras
            $post_filtrado = $validacao->filter($_POST, $this->filters);
            $post_validado = $validacao->validate($post_filtrado, $this->rules);

            if ($post_validado === true) :  // verificar login

                if ($_POST['CSRF_token'] == $_SESSION['CSRF_token']) :

                    $senha_enviada = $_POST['senha'];

                    // gera uma senha fake
                    $senha_fake   = random_bytes(64);
                    $hash_senha_fake = password_hash($senha_fake, PASSWORD_ARGON2I);

                    // busca o usuario
                    $usuarioModel = $this->model('modelUsuario');
                    $usuario = $usuarioModel->getUsuarioEmail($_POST['email']);

                    if (!empty($usuario)) :
                        $senha_hash = $usuario['senha']; // achou o usuário usa hash do banco
                    else :
                        $senha_hash = $hash_senha_fake;  // não achou o usuário usa hash fake
                    endif;

                    if (password_verify($senha_enviada, $senha_hash)) :

                        // apagar CAPTCHA_CODE
                        unset($_SESSION['CAPTCHA_CODE']);

                        // regenerar a sessão
                        session_regenerate_id(true);

                        $_SESSION['hashid'] = $usuario['hashid'];
                        $_SESSION['nomeUsuario'] = $usuario['nome'];
                        $_SESSION['emailUsuario'] = $usuario['email'];
                        $_SESSION['tipoUser'] = $usuario['tipo'];
                        $_SESSION['carrinho'] = new carrinho();

                        Funcoes::redirect("home");

                    else :
                        $mensagem = ["Usuário e/ou Senha incorreta"];
                        $_SESSION['CAPTCHA_CODE'] = Funcoes::gerarCaptcha(); // guarda o captcha_code na sessão 
                        $imagem = Funcoes::gerarImgCaptcha($_SESSION['CAPTCHA_CODE']);
                        $_SESSION['CSRF_token'] = Funcoes::gerarTokenCSRF();
                        $data = [
                            'imagem' => $imagem,
                            'mensagens' => $mensagem,
                            'titulo' => "login"
                        ];

                        $this->view('acessorestrito/login', $data);
                    endif;

                else :  // falha CSRF_token"
                    die("Erro 404");
                endif;
            else : // erro de validação
                $mensagem = $validacao->get_errors_array();
                $_SESSION['CAPTCHA_CODE'] = Funcoes::gerarCaptcha(); // guarda o captcha_code na sessão 
                $imagem = Funcoes::gerarImgCaptcha($_SESSION['CAPTCHA_CODE']);
                $_SESSION['CSRF_token'] = Funcoes::gerarTokenCSRF();
                $data = [
                    'imagem' => $imagem,
                    'mensagens' => $mensagem,
                    'titulo' => "login"
                ];
                //tendo erro de validação retorna para a tela de login
                $this->view('acessorestrito/login', $data);
            endif;
        else : // não POST
            Funcoes::redirect();
        endif;
    }
    // função que busca a view da tela de registro
    public function registro()
    {
        // gera o CAPTCHA_CODE e guarda na sessão 
        $_SESSION['CAPTCHA_CODE'] = Funcoes::gerarCaptcha();
        $imagem = Funcoes::gerarImgCaptcha($_SESSION['CAPTCHA_CODE']);
        // gera o CSRF_token e guarda na sessão
        $_SESSION['CSRF_token'] = Funcoes::gerarTokenCSRF();
        $data = ['imagem' => $imagem];
        $data['titulo'] = "Registrar";
        // chama a view
        $this->view('acessorestrito/registrar', $data);
    }
    public function registrar()
    { // função que aplica o registro
        if ($_SERVER['REQUEST_METHOD'] == "POST") :
            // validar captcha
            Validador::add_validator("validar_CAPTCHA_CODE", function ($field, $input) {
                return $input['captcha'] === $_SESSION['CAPTCHA_CODE'];
            }, 'Código de Segurança incorreto.');

            $validacao = new Validador("pt-br");
            // valida os dados de acordo com os filtros e regras
            $post_filtrado = $validacao->filter($_POST, $this->filters);
            $post_validado = $validacao->validate($post_filtrado, $this->rules);

            if ($post_validado === true) :  // verificar login
                if ($_POST['CSRF_token'] == $_SESSION['CSRF_token']) :
                    $novoUsuario = new Usuario($_POST['tipoUser'], $_POST['nome'], $_POST['email'], $_POST['senha']);

                    $usuarioModel = $this->model('ModelUsuario');
                    $usuario = $usuarioModel->create($novoUsuario);
                    $usuarioModel->createHashID($usuario);
                    Funcoes::redirect("login");

                else :  // falha CSRF_token"
                    die("Erro 404");
                endif;
            else : // erro de validação
                $mensagem = $validacao->get_errors_array();
                $_SESSION['CAPTCHA_CODE'] = Funcoes::gerarCaptcha(); // guarda o captcha_code na sessão 
                $imagem = Funcoes::gerarImgCaptcha($_SESSION['CAPTCHA_CODE']);
                $_SESSION['CSRF_token'] = Funcoes::gerarTokenCSRF();
                $data = [
                    'imagem' => $imagem,
                    'mensagens' => $mensagem,
                    'titulo' => "registrar"
                ];
                //tendo erro de validação retorna para a tela de registro
                $this->view('acessorestrito/registrar', $data);
            endif;
        else : // não POST
            Funcoes::redirect();
        endif;
    }

    public function alterar()
    {
        // gera o CAPTCHA_CODE e guarda na sessão 
        $_SESSION['CAPTCHA_CODE'] = Funcoes::gerarCaptcha();
        $imagem = Funcoes::gerarImgCaptcha($_SESSION['CAPTCHA_CODE']);
        // gera o CSRF_token e guarda na sessão
        $_SESSION['CSRF_token'] = Funcoes::gerarTokenCSRF();
        $usuarioModel = $this->model('modelUsuario');
        $usuario = $usuarioModel->get($_SESSION['hashid']);
        $data = ['imagem' => $imagem];
        $data['titulo'] = "Alterar dados";
        $data['nome'] = $usuario['nome'];
        $data['email'] = $usuario['email'];
        // chama a view
        $this->view('acessorestrito/alterarDados', $data, "acessorestrito/alterarJs");
    }

    public function salvarAlteracao()
    { // função que salva as alterações no banco
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            // validar captcha
            Validador::add_validator("validar_CAPTCHA_CODE", function ($field, $input) {
                return $input['captcha'] === $_SESSION['CAPTCHA_CODE'];
            }, 'Código de Segurança incorreto.');
            //se for marcado para alterar senha, é adicionado regras e filtros no campo
            if (isset($_POST['pergunta-senha'])) {
                $filters['nova-senha'] = 'trim|sanitize_string';
                $rules['nova-senha'] = 'required';
            }
            $validacao = new Validador("pt-br");
            // valida os dados de acordo com os filtros e regras
            $post_filtrado = $validacao->filter($_POST, $this->filters);
            $post_validado = $validacao->validate($post_filtrado, $this->rules);

            if ($post_validado === true) {
                if ($_POST['CSRF_token'] == $_SESSION['CSRF_token']) {
                    $usuarioModel = $this->model('ModelUsuario');
                    $usuario = $usuarioModel->get($_SESSION['hashid']);
                    //verifica se o usuario atual é ele mesmo pedindo a senha para alteração dos dados
                    if (password_verify($_POST['senha'], $usuario['senha'])) {
                        $usuarioAlterado = new Usuario();
                        $usuarioAlterado->setHashid($_SESSION['hashid']);
                        $usuarioAlterado->setNome($_POST['nome']);
                        $usuarioAlterado->setEmail($_POST['email']);
                        //se for pedido a senha também é alterada
                        if (isset($_POST['pergunta-senha'])) {
                            $usuarioAlterado->setSenha($_POST['nova-senha']);
                            $usuarioModel->update($usuarioAlterado, $_POST);
                        } else {
                            $usuarioModel->update($usuarioAlterado);
                        }
                        $data['mensagens'] = 'Alterado com sucesso!!';
                        $data['titulo'] = 'Meus Itens';
                        $this->viewDataTable('Item/index', $data, "Item/itemJs");
                    } else { // senha errada, faz retornar a tela de alteração com mensagens de erro
                        $_SESSION['CAPTCHA_CODE'] = Funcoes::gerarCaptcha(); // guarda o captcha_code na sessão 
                        $imagem = Funcoes::gerarImgCaptcha($_SESSION['CAPTCHA_CODE']);
                        $_SESSION['CSRF_token'] = Funcoes::gerarTokenCSRF();
                        $data = [
                            'imagem' => $imagem,
                            'mensagens' => ['Senha Incorreta'],
                            'titulo' => "Alterar dados",
                            'nome' => $usuario['nome'],
                            'email' => $usuario['email']
                        ];
                        //tendo erro de validação retorna para a tela de alteração
                        $this->view('acessorestrito/alterarDados', $data, "acessorestrito/alterarJs");
                    }
                } else {  // falha CSRF_token"
                    die("Erro 404");
                }
            } else { // erro de validação
                $mensagem = $validacao->get_errors_array();
                $_SESSION['CAPTCHA_CODE'] = Funcoes::gerarCaptcha(); // guarda o captcha_code na sessão 
                $imagem = Funcoes::gerarImgCaptcha($_SESSION['CAPTCHA_CODE']);
                $_SESSION['CSRF_token'] = Funcoes::gerarTokenCSRF();
                $usuarioModel = $this->model('ModelUsuario');
                $usuario = $usuarioModel->get($_SESSION['hashid']);
                $data = [
                    'imagem' => $imagem,
                    'mensagens' => $mensagem,
                    'titulo' => "Alterar dados",
                    'nome' => $usuario['nome'],
                    'email' => $usuario['email']
                ];
                //tendo erro de validação retorna para a tela de alteração
                $this->view('acessorestrito/alterarDados', $data, "acessorestrito/alterarJs");
            }
        } else { // não POST
            Funcoes::redirect();
        }
    }

    public function logout() //aplica o logout
    {
        session_unset();
        session_destroy();
        Funcoes::redirect();
    }
}
