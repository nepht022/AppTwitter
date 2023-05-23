<?php
    //faz o controle da inscrissao do usuario
    namespace App\Controller;

    //recursos
    use MF\Controller\Action;
    use MF\Model\Container;

    class IndexController extends Action{ 
        
        public function index(){   
            //atributo login criado dinamicamente
            $this->view->login = isset($_GET['login']) ? $_GET['login'] : '';//verifica se a pagina index está com erro, vindo de outra pagina    
            $this->render('index');//chama o metodo de Action que mostra a view index
        }
    
        //metodo chamado ao clicar no botao de inscrever-se na home
        public function inscreverse(){
            //cria um atributo dinamico para receber os dados do usuario
            $this->view->usuario = array(
                'nome' => '',
                'email' => '',
                'senha' => ''
            );
            $this->view->erroCadastro = false;
            //chama o metodo de Action que mostra a view inscreverse
            $this->render('inscreverse');
        }

        //metodo que registra um usuario
        public function registrar(){
            //chama o metodo de container que recupera uma classe e instancia uma conexao com o BD dela
            $usuario = Container::getModel('Usuario');
            //seta os valores das variaveis do usuario como os valores digitados no form de cadastro
            $usuario->__set('nome', $_POST['nome']);
            $usuario->__set('email', $_POST['email']);
            $usuario->__set('senha', md5($_POST['senha']));

            //se os campos foram preenchidos corretamente e nao ha um registro no bd com o email digitado...
            if($usuario->validarCadastro() && count($usuario->getUsuarioEmail()) == 0){
                $usuario->salvar();//chama o metodo que salva os dados do usuario no BD
                $this->render('cadastro');//chama o metodo de Action que mostra a view cadastro     
            }else{//se nao...
                //atribui os valores digitados no form aos valores do atributo dinamico
                $this->view->usuario = array(
                    'nome' => $_POST['nome'],
                    'email' => $_POST['email'],
                    'senha' => $_POST['senha']
                );
                //indica que houve erro
                $this->view->erroCadastro = true;
                $this->render('inscreverse');////chama o metodo de Action que mostra a view inscreverse novamente
            }
        }
    }
?>