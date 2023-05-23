<?php
    //inicia a conexao com o banco de dados
    namespace App;

    use PDOException;

    class Connection{
        public static function getDb(){//nao precisa ser instanciada
            try{
                //tenta iniciar a conexao, passando host, nome do banco, tipo, usuario e senha
                $conexao = new \PDO("mysql:host=localhost;dbname=twitter_clone;charset=utf8", "root", "");//abrindo conexao com o BD
                return $conexao;
            }catch(PDOException $e){
                //tratanto erro
            }
        }
    }
?>