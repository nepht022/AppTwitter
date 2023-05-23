<?php
    namespace App\Model;

    use MF\Model\Model;
    use PDOStatement;

    class Usuario extends Model{
        private $id;
        private $nome;
        private $email;
        private $senha;

        //getters e setters genericos
        public function __get($name){
            return $this->$name;
        }
        public function __set($name, $value){
            $this->$name = $value;
        }

        //insere um usuario no BD
        public function salvar(){
            $query = 'insert into usuarios(nome, email, senha)values(:nome, :email, :senha)';
            $stmt = $this->db->prepare($query);//verifica a query
            $stmt->bindValue(":nome", $this->__get('nome'));
            $stmt->bindValue(":email", $this->__get('email'));
            $stmt->bindValue(":senha", $this->__get('senha'));
            $stmt->execute();

            return $this;
        }

        //verifica se o cadastro de um usuario com valido
        public function validarCadastro(){
            $valido = true;
            //se o nome, email ou senha escolhidos na hora do cadastro for menor que 3 caracteres, o cadastro é invalidado
            if(strlen($this->__get('nome'))<3 or strlen($this->__get('email'))<3 or strlen($this->__get('senha'))<3){
                $valido = false;
            }
            return $valido;
        }

        //metodo que pesquisa um usuario no BD
        public function getUsuarioEmail(){//retorna os array com o mesmo email do digitado ja existentes no bd
            $query = 'select nome, email from usuarios where email = :email';
            $stmt = $this->db->prepare($query);//verifica a query
            $stmt->bindValue(":email", $this->__get('email'));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);//retorna um array associativo de todos elementos recuperados
        }

        //verifica se os dados digitados no form de login estao cadastrados no BD
        public function autenticar(){
            $query = 'select id, nome, email from usuarios where email = :email and senha = :senha';
            $stmt = $this->db->prepare($query);//verifica a query
            $stmt->bindValue(":email", $this->__get('email'));
            $stmt->bindValue(":senha", $this->__get('senha'));
            $stmt->execute();

            //atribui os valores consultados no BD obtidos a uma variavel
            $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);//retorna um resultado

            //se esse usuario na variavel tem um ID e Nome diferentes de vazio, seta o id e nome das variaveis do usuario...
            //como os valores obtidos no BD
            if(!empty($usuario['id']) && !empty($usuario['nome'])){
                $this->__set('id', $usuario['id']);
                $this->__set('nome', $usuario['nome']);
            }
            return $this;
        }

        //metodo que recupera todos os usuario com nome parecido com o pesquisado
        public function getAll(){
            $query = 'select u.id, u.nome, u.email, 
            (select count(*) from usuarios_seguidores as us where us.id_usuario=:id_usuario and us.id_usuario_seguindo=u.id) 
            as seguindo_sn from usuarios as u where u.nome like :nome and u.id != :id_usuario';
            $stmt = $this->db->prepare($query);//verifica a query
            $stmt->bindValue(":nome", '%'.$this->__get('nome').'%');
            $stmt->bindValue(":id_usuario", $this->__get('id'));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);//retorna varios resultados
        }

        //metodo para o usuario da sessao seguir um usuario
        public function seguirUsuario($id_usuario_seguindo){
            $query = 'insert into usuarios_seguidores(id_usuario, id_usuario_seguindo) values (:id_usuario, :id_usuario_seguindo)';
            $stmt = $this->db->prepare($query);//verifica a query
            $stmt->bindValue(":id_usuario", $this->__get('id'));
            $stmt->bindValue(":id_usuario_seguindo", $id_usuario_seguindo);
            $stmt->execute();

            return true;//retorna se teve sucesso
        }
        //metodo para o usuario da sessao deixar de seguir um usuario
        public function deixarSeguirUsuario($id_usuario_seguindo){
            $query = 'delete from usuarios_seguidores where id_usuario=:id_usuario and id_usuario_seguindo=:id_usuario_seguindo';
            $stmt = $this->db->prepare($query);//verifica a query
            $stmt->bindValue(":id_usuario", $this->__get('id'));
            $stmt->bindValue(":id_usuario_seguindo", $id_usuario_seguindo);
            $stmt->execute();
        
            return true;//retorna se teve sucesso
        }   

        //metodo que retorna o nome do usuario da sessao pelo BD
        public function getInfoUsuario(){
            $query = 'select nome from usuarios where id = :id_usuario';
            $stmt = $this->db->prepare($query);//verifica a query
            $stmt->bindValue(":id_usuario", $this->__get('id'));
            $stmt->execute();

            return $stmt->fetch(\PDO::FETCH_ASSOC);//retorna um resultado
        }
        //metodo que retorna a quantidade de tweets do usuario da sessao pelo BD
        public function getQtTweets(){
            $query = 'select count(*) as qt_tweets from tweets where id_usuario = :id_usuario';
            $stmt = $this->db->prepare($query);//verifica a query
            $stmt->bindValue(":id_usuario", $this->__get('id'));
            $stmt->execute();

            return $stmt->fetch(\PDO::FETCH_ASSOC);//retorna um resultado
        }
        //metodo que retorna a quantidade de usuarios que o usuario da sessao segue pelo BD
        public function getQtSeguindo(){
            $query = 'select count(*) as qt_seguindo from usuarios_seguidores where id_usuario = :id_usuario';
            $stmt = $this->db->prepare($query);//verifica a query
            $stmt->bindValue(":id_usuario", $this->__get('id'));
            $stmt->execute();

            return $stmt->fetch(\PDO::FETCH_ASSOC);//retorna um resultado
        }
        //metodo que retorna a quantidade de usuarios que seguem o usuario da sessao pelo BD
        public function getQtSeguidores(){
            $query = 'select count(*) as qt_seguidores from usuarios_seguidores where id_usuario_seguindo = :id_usuario';
            $stmt = $this->db->prepare($query);//verifica a query
            $stmt->bindValue(":id_usuario", $this->__get('id'));
            $stmt->execute();

            return $stmt->fetch(\PDO::FETCH_ASSOC);//retorna um resultado
        }
    }
?>