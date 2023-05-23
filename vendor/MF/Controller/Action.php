<?php
    //arquivo da "classe" das views(página mostrada no browser)
    namespace MF\Controller;

    abstract class Action{
        protected $view;//variavel de uma view

        abstract public function index();//metodo que vai ser sobrescrito para chamar os outros metodos dessa classe

        public function __construct(){
            //cria uma classe generica
            $this->view = new \stdClass();
        }

        //metodo para capturar a view
        protected function render($view, $layout = 'layout'){
            $this->view->page = $view;//um atributo page criado dinamicamente para o nome da view passada como parametro
            //se o arquivo da view passada como parametro existe...
            if(file_exists('../App/View/'.$layout.'.phtml')){
                //faz a requisicao dessa view para ser mostrada
                require_once '../App/View/'.$layout.'.phtml';//todos os requires vem do diretorio publico da aplicação
            }else{
                $this->content();
            }
        }

        //outra forma de capturar a view, mais trabalhada
        protected function content(){
            //retorna o endereço do controller
            $classe = get_class($this);//retorna 'App\Controller\IndexController' por exemplo
            $classe = str_replace('App\\Controller\\', '', $classe);//após o metodo, fica 'IndexController'
            $classe = strtolower(str_replace('Controller', '', $classe));//após o metodo, fica 'index'
            require_once '../App/View/'.$classe.'/'.$this->view->page.'.phtml';//faz a requisicao dessa view para ser mostrada
        }
    }
?>