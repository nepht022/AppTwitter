<?php
    //faz a autenticacao do usuario ao tentar logar
    namespace App\Controller;

    //recursos
    use MF\Controller\Action;
    use MF\Model\Container;

    class AuthController extends Action{
        public function index(){
            //chama o metodo da classe Action para recuperar a view index
            $this->render('index');
        }

        public function autenticar(){
            //chama o metodo de container que recupera uma classe e instancia uma conexao com o BD dela
            $usuario = Container::getModel('Usuario');
            //seta o email e senha das variaveis do usuario como os valores digitados no form de login
            $usuario->__set('email', $_POST['email']);
            $usuario->__set('senha', md5($_POST['senha']));
            $usuario->autenticar();
            
            //se as variaveis ID e Nome do usuario foram preenchidas, ou seja, ele existe no BD...
            if($usuario->__get('id') != '' && $usuario->__get('nome')!= ''){
                //inicia sessao e atrbui o ID e Nome da sessao como o ID e Nome do usuario
                //oque faz com que o usuario esteja logado, indo para a pagina privada timeline
                session_start();
                $_SESSION['id'] = $usuario->__get('id');
                $_SESSION['nome'] = $usuario->__get('nome');
                header('Location: /timeline');
            }else{//se nao...
                //volta para a pagina inicial mas indicando um erro
                header('Location: /?login=erro');
            }
        }

        public function sair(){
            //inicia a sessao, destroi ela e vai para a pagina inicial antes de logar
            session_start();
            session_destroy();
            header("Location: /");
        }
    }
?>