<?php

class View extends Controller{
    public function __construct()
    {
        
    }
    public function index(){
        
        $this->view("view");
    }
}