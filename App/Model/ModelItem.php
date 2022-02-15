<?php

use App\Core\ModelBase;

class ModelItem extends ModelBase
{
    public function create($item) // cria item e salva na tabela
    {
        try {
            $sql = "INSERT INTO itens(hashid,nome_item,descricao,id_vendedor,preco,quantidade) VALUES (?,?,?,?,?,?)";
            $conn = Modelitem::getConexao();
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(1, $item->getHashid());
            $stmt->bindValue(2, $item->getNomeItem());
            $stmt->bindValue(3, $item->getDescricao());
            $stmt->bindValue(4, $item->getIdVendedor());
            $stmt->bindValue(5, $item->getPreco());
            $stmt->bindValue(6, $item->getQuantidade());

            $stmt->execute();
            $chaveGerada = $conn->lastInsertId();
            $conn = null;
            return $chaveGerada;
        } catch (PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function apagaItem($hashid) // apaga um determinado item da tabela pelo seu hashid
    {
        try {
            $sql = "DELETE FROM itens WHERE hashid = ?";
            $conn = Modelitem::getConexao();
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(1, $hashid);

            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function updateitem($item) // atualiza um determinado item da tabela pelo seu hashid
    {
        try {
            $sql = "UPDATE itens SET nome_item = ?, descricao = ?, preco = ?, quantidade = ? WHERE hashid = ?";
            $conn = Modelitem::getConexao();
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(1, $item->getNomeItem());
            $stmt->bindValue(2, $item->getDescricao());
            $stmt->bindValue(3, $item->getPreco());
            $stmt->bindValue(4, $item->getQuantidade());
            $stmt->bindValue(5, $item->getHashid());

            $stmt->execute();
            $conn = null;
        } catch (PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function getitemPorHashId($hashid) // puxa um item pelo seu hashid
    {
        try {
            $sql = "SELECT * FROM itens WHERE hashid = ?";
            $conn = Modelitem::getConexao();
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(1, $hashid);

            $stmt->execute();
            $conn = null;
            return  $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function getItens() // puxa todos os itens da tabela
    {
        try {
            $sql = "SELECT * FROM itens";
            $conn = Modelitem::getConexao();
            $stmt = $conn->query($sql);

            $stmt->execute();
            $conn = null;
            return  $stmt;
        } catch (PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function getTotalItens() // total de itens cadastrados
    {
        try {
            $conn = Modelitem::getConexao();
            $stmt = $conn->prepare("SELECT count(*) as total FROM itens");
            $stmt->execute();
            $conn = null;
            return $stmt->fetch();
        } catch (\PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function getTotalItensPorUser($hashid) // total de itens cadastrados pelo usuário
    {
        try {
            $conn = Modelitem::getConexao();
            $sql = "SELECT count(*) as total FROM itens WHERE id_vendedor = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $hashid);
            $stmt->execute();
            $conn = null;
            return $stmt->fetch();
        } catch (\PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function deleteItensPorUser($id) // apaga todos os itens de um determinado usuário
    {
        try {
            $conn = Modelitem::getConexao();
            $sql = "DELETE FROM itens WHERE id_vendedor = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
            $conn = null;
            return $stmt->fetch();
        } catch (\PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function createHashID($id) // criação do hashid
    {
        try {
            $sql = "UPDATE itens SET hashid = ? WHERE id = ?";
            $conn = Modelitem::getConexao();
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
            $conn = ModelItem::getConexao();
            $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM itens WHERE 1 " . $searchQuery);
            $stmt->execute($searchArray);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function construirDataTable($sql, $searchValue, $searchArray, $row, $rowperpage, $hashid = '')
    { // função que constroi a datatable de acordo com os parametros passados
        try {
            $conn = ModelItem::getConexao();
            $stmt = $conn->prepare($sql);
            if ($searchValue != '') {
                foreach ($searchArray as $key => $search) {
                    $stmt->bindValue(':' . $key, $search, PDO::PARAM_STR);
                }
            }
            if ($hashid != "") {
                $stmt->bindValue(":hashid", $hashid);
            }
            $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function checarQuantidade($hashid, $quantCarrinho)
    { //verifica se há itens suficientes no estoque
        try {
            $conn = ModelItem::getConexao();
            $stmt = $conn->prepare("SELECT quantidade FROM itens WHERE hashid= ?");
            $stmt->bindValue(1, $hashid);
            $stmt->execute();
            $quantBD = $stmt->fetch();

            if (intval($quantBD['quantidade']) < $quantCarrinho) {
                return false;
            } else {
                return true;
            }
        } catch (\PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }

    public function reduzirQuantidade($hashid, $quantCarrinho)
    { //reduz quantidade no estoque
        try {
            $conn = ModelItem::getConexao();
            $stmt = $conn->prepare("SELECT quantidade FROM itens WHERE hashid= ?");
            $stmt->bindValue(1, $hashid);
            $stmt->execute();
            $quantBD = $stmt->fetch();

            $q = intval($quantBD['quantidade']) - $quantCarrinho;
            $stmt = $conn->prepare("UPDATE itens SET quantidade= ? WHERE hashid= ?");
            $stmt->bindValue(1, $q);
            $stmt->bindValue(2, $hashid);
            $stmt->execute();
            $conn = null;
            return true;
        } catch (\PDOException $e) {
            die('Query falhou: ' . $e->getMessage());
        }
    }
}
