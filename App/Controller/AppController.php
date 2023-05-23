<?php
    //faz o controlle após o usuario estar logado
    namespace App\Controller;

    //recursos
    use MF\Controller\Action;
    use MF\Model\Container;

    class AppController extends Action{
        public function index(){
            //chama o metodo da classe Action para recuperar a view index
            $this->render('index');
        }

        //metodo para verificar se o usuario está autenticado/logado
        public function validaAutenticacao(){
            session_start();
            //se nao existe ou esta vazio o id e nome da sessao, ou seja, o usuario nao existe no BD ao tentar logar...
            if(!isset($_SESSION['id']) || $_SESSION['id'] == '' && !isset($_SESSION['nome']) || $_SESSION['nome'] == ''){
                //vai para a pagina inicial indicando erro
                header('Location: /?login=erro');  
            }
        }

        //metodo que recupera os dados do usuario logado
        public function recuperaInfoPerfil(){
            //chama o metodo de container que recupera uma classe e instancia uma conexao com o BD dela
            $usuario = Container::getModel('Usuario');
            //seta o id da variavel do usuario como o valor do id da sessao
            $usuario->__set('id', $_SESSION['id']);
            //metodos de recuperacao de dados do usuario no BD
            $this->view->info_usuario = $usuario->getInfoUsuario();
            $this->view->qt_tweets = $usuario->getQtTweets();
            $this->view->qt_seguindo = $usuario->getQtSeguindo();
            $this->view->qt_seguidores = $usuario->getQtSeguidores();
        }

        //metodo para a view da timeline exibindo todos tweets
        public function timeline(){
            $this->validaAutenticacao();
            $this->recuperaInfoPerfil();

            //chama o metodo de container que recupera uma classe e instancia uma conexao com o BD dela
            $tweet = Container::getModel('Tweet');
            //seta a variavel id_usuario do tweet do usuario da sessao como o id da sessao
            $tweet->__set('id_usuario', $_SESSION['id']);
            //chama o metodo que recupera todos os tweets
            $tweets = $tweet->getAll();
           
            $this->view->tweets = $tweets;//atribui os tweets recuperados a uma variavel criada dinamicamente
            //chama o metodo da classe Action para recuperar a view timeline
            $this->render('timeline');
        }

        //metodo para adicao de um tweet
        public function tweet(){
            $this->validaAutenticacao();

            //chama o metodo de container que recupera uma classe e instancia uma conexao com o BD dela
            $tweet = Container::getModel('Tweet');

            //seta o valor da variavel tweet pelo valor escrito no textarea do form enviado via post
            //seta o valor do id_usuario que tweetou pelo id da sessao do usuario autenticado
            $tweet->__set('tweet', $_POST['tweet']);
            $tweet->__set('id_usuario', $_SESSION['id']);
            //chama o metodo salvar da classe tweet
            $tweet->salvar();

            //volta para a pagina da timeline após o tweet ser adicionado ao BD
            header('Location: /timeline');
        }

        //metodo da view de pesquisa de usuarios para seguir
        public function quemSeguir(){
            $this->validaAutenticacao();
            $this->recuperaInfoPerfil();

            $pesquisa = isset($_GET['pesquisa']) ? $_GET['pesquisa'] : '';//verifica se houve pesquisa

            $usuarios = array();//array de usuarios
            //se houve pesquisa...
            if($pesquisa != ''){
                //chama o metodo de container que recupera uma classe e instancia uma conexao com o BD dela
                $usuario = Container::getModel('Usuario');
                //seta a variavel nome do usuario como o valor pesquisado
                //seta a variavel de id do usuario como o id da sessao
                $usuario->__set('nome', $pesquisa);
                $usuario->__set('id', $_SESSION['id']);
                //chama o metodo que recupera usuarios
                $usuarios = $usuario->getAll();
            }
            //atribui o array de usuarios recuperados a variavel criada dinamicamente
            $this->view->usuarios = $usuarios;
            //chama o metodo da classe Action para recuperar a view quem seguir
            $this->render('quemSeguir');
        }

        //metodo que controla a possibilidade de ação nos usuarios pesquisados
        public function acao(){
            $this->validaAutenticacao();

            $acao = isset($_GET['acao']) ? $_GET['acao'] : '';//verifica o tipo de acao passado via url
            //verifica se o id do usuario pesquisado foi passado via url
            $id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

            //chama o metodo de container que recupera uma classe e instancia uma conexao com o BD dela
            $usuario = Container::getModel('Usuario');
            //seta o id da variavel do usuario como o id do usuario da sessao
            $usuario->__set('id', $_SESSION['id']);

            //verifica o tipo da acao e chama o metodo em cima do id passado via url
            if($acao=='seguir'){
                $usuario->seguirUsuario($id_usuario_seguindo);
            }else if($acao=='deixar_de_seguir'){
                $usuario->deixarSeguirUsuario($id_usuario_seguindo);
            }

            //volta para a pagina quem seguir
            header('Location: /quem_seguir');
        }
    }
?>