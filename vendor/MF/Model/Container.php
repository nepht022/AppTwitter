<?php
    //instancia um modelo e a classe de conexao com o banco
    namespace MF\Model;

    use App\Connection;

    class Container{
        //recupera a classe do parametro e instancia uma conexao com o BD pra essa classe
        public static function getModel($model){
            $class = "\\App\\Model\\".ucfirst($model);
            $conn = Connection::getDb();//instancia da conexao

            return new $class($conn);
        }
    }
?>