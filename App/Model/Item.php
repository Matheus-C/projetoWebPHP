<?php

namespace App\model;

class item
{
    private $id;
    private $hashid;
    private $nomeItem;
    private $descricao;
    private $idVendedor;
    private $preco;
    private $quantidade;

    public function __construct($nomeItem = "", $descricao = "", $idVendedor = "", $preco = 0.00, $quantidade = 0, $hashid = "")
    {
        $this->id = 0;
        $this->hashid = $hashid;
        $this->nomeItem = $nomeItem;
        $this->descricao = $descricao;
        $this->idVendedor = $idVendedor;
        $this->preco = $preco;
        $this->quantidade = $quantidade;
    }

    function getId()
    {
        return $this->id;
    }

    function getHashid()
    {
        return $this->hashid;
    }

    function getNomeItem()
    {
        return $this->nomeItem;
    }

    function getDescricao()
    {
        return $this->descricao;
    }

    function getIdVendedor()
    {
        return $this->idVendedor;
    }

    function getPreco()
    {
        return $this->preco;
    }

    function getQuantidade()
    {
        return $this->quantidade;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function setHashid($hashid)
    {
        $this->hashid = $hashid;
    }

    function setNomeItem($nomeItem)
    {
        $this->nomeItem = $nomeItem;
    }

    function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    function setIdVendedor($idVendedor)
    {
        $this->idVendedor = $idVendedor;
    }

    function setPreco($preco)
    {
        $this->preco = $preco;
    }

    function setquantidade($quantidade)
    {
        $this->quantidade = $quantidade;
    }
}
