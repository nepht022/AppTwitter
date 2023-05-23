<?php
    namespace App\Model;

    use MF\Model\Model;
    use PDOStatement;

    class Tweet extends Model{
        private $id;
        private $id_usuario;
        private $tweet;
        private $data;

        //getters e setter genericos
        public function __get($name){
            return $this->$name;
        }
        public function __set($name, $value){
            $this->$name = $value;
        }

        //salva um tweet no BD
        public function salvar(){
            $query = 'insert into tweets (id_usuario, tweet)values(:id_usuario, :tweet)';
            $stmt = $this->db->prepare($query);//verifica a query
            $stmt->bindValue(":id_usuario", $this->__get('id_usuario'));
            $stmt->bindValue(":tweet", $this->__get('tweet'));
            $stmt->execute();

            return $this;
        }
        //metodo que recupera dados dos tweets
        //recupera os tweets do usuario da sessao e os tweets de quem ele segue
        public function getAll(){
            $query = '
            select u.nome, t.id, t.id_usuario, t.tweet, DATE_FORMAT(t.data, "%d/%m/%Y %H:%i") as data
            from tweets as t 
            left join usuarios as u on (t.id_usuario = u.id)
            where t.id_usuario = :id_usuario
            or t.id_usuario in (select id_usuario_seguindo from usuarios_seguidores where id_usuario = :id_usuario) 
            order by t.data desc
            ';
            $stmt = $this->db->prepare($query);//verifica a query
            $stmt->bindValue(":id_usuario", $this->__get('id_usuario'));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);//retorna varios resultados
        }
    }
?>