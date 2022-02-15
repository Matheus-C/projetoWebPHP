<?php

use App\Core\ModelBase;

class ModelUsuario extends ModelBase
{

    public function create($usuario) // cria o usuario e salva na tabela
    {
        try { // conexão com a base de dados
            $sql = "INSERT INTO usuarios(hashid,tipo,nome,email,senha) VALUES (?,?,?,?,?)";
            $conn = ModelUsuario::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $usuario->getHashid());
            $stmt->bindValue(2, $usuario->getTipo());
            $stmt->bindValue(3, $usuario->getNome());
            $stmt->bindValue(4, $usuario->getEmail());
            $senhaHash = password_hash($usuario->getSenha(), PASSWORD_ARGON2I);
            $stmt->bindValue(5, $senhaHash);
            $stmt->execute();
            $chaveGerada = $conn->lastInsertId();
            $conn = null;
            return $chaveGerada;
        } catch (PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function get($hashid) // puxa um determinado usuario da tabela pelo seu hashid
    {
        try {
            $sql = "SELECT * FROM USUARIOS WHERE hashid = ?";
            $conn = ModelUsuario::getConexao();
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $hashid);
            $stmt->execute();
            $conn = null;
            return  $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function getNome($id) // puxa o nome de um determinado usuario da tabela pelo seu hashid
    {
        try {
            $sql = "SELECT nome FROM USUARIOS WHERE hashid = ?";
            $conn = ModelUsuario::getConexao();
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
            $conn = null;
            $nome = $stmt->fetch();
            return $nome['nome'];
        } catch (PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function read() // puxa todos os usuários registrados
    {
        try {
            $sql = "SELECT * FROM USUARIOS";
            $conn = ModelUsuario::getConexao();
            $stmt = $conn->query($sql);
            $conn = null;
            return $stmt;
        } catch (\PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function update($usuario, $novaSenha = "") // atualiza os dados de um determinado usuario da tabela
    {
        try {
            if ($novaSenha == "") {
                $sql = "UPDATE usuarios SET nome = ?, email = ? WHERE hashid = ?";
                $conn = ModelUsuario::getConexao();

                $stmt = $conn->prepare($sql);
                $stmt->bindValue(1, $usuario->getNome());
                $stmt->bindValue(2, $usuario->getEmail());
                $stmt->bindValue(3, $usuario->getHashId());
                $stmt->execute();
            } else {
                $sql = "UPDATE usuarios SET nome = ?, email = ?, senha= ? WHERE hashid = ?";
                $conn = ModelUsuario::getConexao();

                $stmt = $conn->prepare($sql);
                $stmt->bindValue(1, $usuario->getNome());
                $stmt->bindValue(2, $usuario->getEmail());
                $senhaHash = password_hash($usuario->getSenha(), PASSWORD_ARGON2I);
                $stmt->bindValue(3, $senhaHash);
                $stmt->bindValue(4, $usuario->getHashId());
                $stmt->execute();
            }
            $conn = null;
        } catch (PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function apagaUsuario($hashId) // apaga um determinado usuario da tabela pelo seu hashid
    {
        try {
            $sql = "DELETE FROM usuarios WHERE hashid = ?";
            $conn = ModelUsuario::getConexao();

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $hashId);
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }


    public function getTotalUsuarios() // puxa o total de usuarios
    {
        try {
            $conn = ModelUsuario::getConexao();
            $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM usuarios");
            $stmt->execute();
            $conn = null;
            return $stmt->fetch();
        } catch (\PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function getUsuarioEmail($email) // puxa um determinado usuarios da tabela pelo seu email
    {

        try {
            $sql = "Select * from usuarios where email = ? limit 1";
            // obter a conecção e preparar o comando sql (PDO)
            $conn = ModelUsuario::getConexao();
            $stmt = $conn->prepare($sql);
            // passando parâmteros
            $stmt->bindValue(1, $email);
            $stmt->execute();
            if ($stmt->rowCount() > 0) :
                $resultset = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                return $resultset[0];
            else :
                return []; // retornado array vazio... não há registros no BD    
            endif;
        } catch (\PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function createHashID($id) // cria um hashid
    {
        try {
            $sql = "UPDATE usuarios SET hashid = ? WHERE id = ?";
            $conn = ModelUsuario::getConexao();
            $hashId = hash_hmac('sha256', $id, CSRF_TOKEN_SECRET);
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $hashId);
            $stmt->bindValue(2, $id);
            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function search($searchQuery, $searchArray) //função de busca da datatable
    {
        try {
            $conn = ModelUsuario::getConexao();
            $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM usuarios WHERE 1 " . $searchQuery);
            $stmt->execute($searchArray);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function construirDataTable($sql, $searchValue, $searchArray, $row, $rowperpage)
    { // função que constroi a datatable de acordo com os parametros passados
        try {
            $conn = ModelUsuario::getConexao();
            $stmt = $conn->prepare($sql);
            if ($searchValue != '') {
                foreach ($searchArray as $key => $search) {
                    $stmt->bindValue(':' . $key, $search, PDO::PARAM_STR);
                }
            }
            $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }
}
