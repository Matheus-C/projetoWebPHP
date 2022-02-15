<?php
// Configuração do BD
define('HOST', 'localhost'); // onde está o banco de dados
define('DB', 'php');      // nome da base de dados
define('USUARIO', 'root');   // usuário da base de dados
define('SENHA', '');         // senha usuário da base de dados

//Constante que indica a URL básica da aplicação
define("URL_BASE", "http://shopnow.test");

define("MODEL_ROOT", "C:/wamp64/www/devwebphp/App/Model/");

define("VIEW_ROOT", "C:/wamp64/www/devwebphp/App/View/");

define('PUBLIC_ROOT', 'C:/wamp64/www/devwebphp/public/');

//Constante que indica a URL básica da css
define("URL_CSS", URL_BASE . "/css/");

//Constante que indica a URL básica da css
define("URL_JS", URL_BASE . "/js/");

//Constante usada para gerar CSRF Token
define('CSRF_TOKEN_SECRET', 'iyHS4##SiPcV9tIZ');

// Caminho para a imagem Captcha
define('DIR_IMG_CAPTCHA', "C:/wamp64/www/devwebphp/App/writable/");

// datatable
define('PATH_URL_DATATABLE_LANG', URL_BASE . '/datatables/Portuguese-Brasil.json');
define('PATH_URL_DATATABLE_bootstrap4', URL_BASE . '/datatables/DataTables-1.10.20/css/dataTables.bootstrap4.min.css');
define('PATH_URL_DATATABLE_Responsive', URL_BASE . '/datatables/Responsive-2.2.3/css/responsive.bootstrap4.min.css');

define('PATH_URL_DATATABLE_dataTablesminjs', URL_BASE . '/datatables/DataTables-1.10.20/js/jquery.dataTables.min.js');
define('PATH_URL_DATATABLE_dataTablesbootstrap4minjs', URL_BASE . '/datatables/DataTables-1.10.20/js/dataTables.bootstrap4.min.js');
define('PATH_URL_DATATABLE_dataTablesresponsiveminjs', URL_BASE . '/datatables/Responsive-2.2.3/js/dataTables.responsive.min.js');
define('PATH_URL_DATATABLE_responsivebootstrap4minjs', URL_BASE . '/datatables/Responsive-2.2.3/js/responsive.bootstrap4.min.js');


/**
 * @param string|null $uri
 * @return string
 */

function url(string $uri = null): string
{
    if ($uri) {
        return URL_BASE . "/{$uri}";
    }

    return URL_BASE;
}
