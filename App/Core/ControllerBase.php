<?php

namespace App\Core;

class ControllerBase
{

    // método que carrega o model associado ao controller
    public function model($model)
    {

        if (file_exists(MODEL_ROOT . $model . '.php')) :
            require_once MODEL_ROOT . $model . '.php';
            return new $model;
        else :
            echo "Model não existe";
        endif;
    }

    // método que carrega a view associado ao controller
    // e os dados que serão exibidos
    public function view($view, $data = [], $js = null)
    {
        require_once VIEW_ROOT . 'template.php';
    }

    public function viewDataTable($view, $data = [], $js = null)
    {
        require_once VIEW_ROOT . 'templateDataTable.php';
    }

    public function teste($data = [], $js = null)
    {
        require_once VIEW_ROOT . 'teste.php';
    }
}
