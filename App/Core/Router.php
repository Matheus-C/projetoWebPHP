<?php

$route = new \CoffeeCode\Router\Router(URL_BASE);

/**
 * APP
 */
$route->namespace("App\Controller");

/**
 * parte publica
 */
$route->group(null);
$route->get("/", "Home:index"); // Homepage
$route->get("/home", "Home:index"); // Homepage
$route->get("/login", "AcessoRestrito:login"); // login GET
$route->post("/logar", "AcessoRestrito:logar"); // login POST
$route->get("/registro", "AcessoRestrito:registro"); // registro GET
$route->post("/registrar", "AcessoRestrito:registrar"); // registro POST

/**
 * parte restrita
 */

$route->get("/logout", "AcessoRestrito:logout"); // logout
$route->get("/alterar", "AcessoRestrito:alterar"); // alterar dados GET
$route->post("/alterar", "AcessoRestrito:salvarAlteracao"); // alterar dados POST

/**
 * parte restrita - Admin
 */

$route->get("/usuarios", "Admin:index"); // vai para a pagina da tabela de usuários
$route->post('/ajaxusuarios', "Admin:getAjaxUsuarios"); // requisição POST ajax para dataTable de usuarios
$route->get("/excluirusuario/{id}", "Admin:excluirUsuario"); // Função de remover usuário


/**
 * parte restrita - Itens
 */

$route->get("/painelitens", "ControllerItem:index"); // view tabela de itens do usuário logado
$route->post("/novoitem", "ControllerItem:novoItem"); // requisição POST para salvar o item registrado
$route->get("/registraritem", "ControllerItem:registrarItem"); // requisição GET para a view de registro
$route->post('/ajaxitens', "Home:getAjaxItens"); // requisição POST ajax para dataTable de itens à venda
$route->post('/ajaxgetitem', "ControllerItem:getAjaxItemAlterar"); // requisição POST ajax para puxar os dados do item para o formulário
$route->post('/gravaralteracaoitem', "ControllerItem:gravarAlteracaoItem"); // requisição POST para salvar os dados da alteração
$route->post('/meusitens', "ControllerItem:getAjaxItensPorUser"); //requisição POST ajax para dataTable de itens do usuário logado
$route->get("/excluiritem/{id}", "ControllerItem:excluirItem"); // Função de remover Item
$route->get("/visualizar/{hashid}", "ControllerItem:visualizarItem"); // Página de visualizar as informações do item mais claramente

/**
 * parte restrita - Carrinho
 */

$route->get('/adicionar/{id}/{quantidade}', "Carrinho:adicionarAoCarrinho"); // função que adiciona o item no carrinho
$route->get("/comprar", "Carrinho:comprar"); // chama a função de compra
$route->get("/carrinho", "Carrinho:index"); // chama a view do carrinho
$route->post("/carrinho", "Carrinho:getAjaxItensCarrinho"); // requisição POST ajax para dataTable com itens do carrinho
$route->get("/tirardocarrinho/{id}/{quantidade}/{preco}", "Carrinho:removeDoCarrinho"); // chama a função de remover do carrinho

/**
 * PROCESS
 */
$route->dispatch();
