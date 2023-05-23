<?php
    //define como é feita a conexao de uma query dentro de um metodo com o BD
    //abstração dos modelos, todas classes precisam extendela
    namespace MF\Model;

    abstract class Model{
        protected $db;

        public function __construct(\PDO $db){
            $this->db = $db;
        }
    }
?>