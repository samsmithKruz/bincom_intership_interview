<?php

class Controller
{

    public $model;
    public function model($model)
    {
        require_once '../app/models/' . $model . '.model.php';
        $this->model =  new $model();
    }
    public function view($view, $data = [])
    {
        if (file_exists('../app/views/' . $view . '.php')) {
            extract((array)$data);
            require_once '../app/views/' . $view . '.php';
        } else {
            require_once '../app/views/404.php';
        }
        if(isset($_SESSION[APP]->flashMessage)){
            unset($_SESSION[APP]->flashMessage);
        }
        exit();
    }
}
