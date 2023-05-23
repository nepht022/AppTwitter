<?php
    //arquivo da "classe" das rotas
    namespace MF\Init;

    abstract class Bootstrap{
        private $routes;//variavel da rota

        //cadastra uma rota
        abstract protected function initRoutes();

        //construtor que quando instanciado, cadastra uma rota e roda a rota da URL
        public function __construct(){
            $this->initRoutes();
            $this->run($this->getUrl());
        }

        public function getRoutes(){
            return $this->routes;
        }
        public function setRoutes(array $routes){
            $this->routes = $routes;
        }

        //roda uma rota
        protected function run($url){
            //para cada rota cadastrada...
            foreach($this->getRoutes() as $key => $route){
                /*echo '<pre>';
                print_r($route);
                echo '</pre>';*/

                //se a rota passada como parametro(url) for igual a alguma rota cadastrada...
                if($url == $route['route']){
                    //é criado um controller pra essa rota, o nome do seu action da rota cadastrada é recuperado... 
                    //e esse metodo action dentro do controller especifico é chamado
                    $class = "App\\Controller\\".$route['controller'];//=App/Controller/IndexController.php
                    $controller = new $class;//instanciando uma classe controller
                    $action = $route['action'];//recuperando o action da rota cadastrada
                    $controller->$action();//executando o metodo recuperado dentro da classe controller
                }
            }
        }

        protected function getUrl(){
            return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        }

    }
?>