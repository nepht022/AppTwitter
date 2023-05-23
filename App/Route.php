<?php
    //define as rotas ou seja páginas de acesso
    namespace App;

    use MF\Init\Bootstrap;

    class Route extends Bootstrap{
        //metodo com todas rotas cadastradas
        protected function initRoutes(){

            $routes['home'] = Array(
                'route' => '/',//url
                'controller' => 'IndexController',//arquivo
                'action' => 'index'//metodo
            );
            $routes['inscreverse'] = Array(
                'route' => '/inscreverse',//url
                'controller' => 'IndexController',//arquivo
                'action' => 'inscreverse'
            );//dispara a action inscreverse dentro de indecontroller
            $routes['registrar'] = Array(
                'route' => '/registrar',//url
                'controller' => 'IndexController',//arquivo
                'action' => 'registrar'
            );
            $routes['autenticar'] = Array(
                'route' => '/autenticar',//url
                'controller' => 'AuthController',//arquivo
                'action' => 'autenticar'
            );
            $routes['timeline'] = Array(
                'route' => '/timeline',//url
                'controller' => 'AppController',//arquivo
                'action' => 'timeline'
            );
            $routes['sair'] = Array(
                'route' => '/sair',//url
                'controller' => 'AuthController',//arquivo
                'action' => 'sair'
            );
            $routes['tweet'] = Array(
                'route' => '/tweet',//url
                'controller' => 'AppController',//arquivo
                'action' => 'tweet'
            );
            $routes['quem_seguir'] = Array(
                'route' => '/quem_seguir',//url
                'controller' => 'AppController',//arquivo
                'action' => 'quemSeguir'
            );
            $routes['acao'] = Array(
                'route' => '/acao',//url
                'controller' => 'AppController',//arquivo
                'action' => 'acao'
            );
            $this->setRoutes($routes);//seta as rotas do bootstrap como as rotas criadas no metodo init routes
        }
    }
?>