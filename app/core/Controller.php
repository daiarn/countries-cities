<?php

error_reporting(E_ERROR | E_PARSE);

include '../database.php';

class Controller
{
    public function model($model)
    {
        require_once '../app/models/' . $model . '.php';
        return new $model;
    }

    public function view($view, $data = [])
    {
        require_once '../app/views/' . $view . '.php';
    }
}