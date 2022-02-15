<?php

namespace App\model;

class Usuario
{
    private $id;
    private $hashid;
    private $tipo; //tipos: 0=admin 1=usuario comum
    private $nome;
    private $senha;
    private $email;

    public function __construct($tipo = 0, $nome = "", $email = "", $senha = "")
    {
        $this->id = 0;
        $this->hashid = "";
        $this->tipo = $tipo;
        $this->nome = $nome;
        $this->email = $email;
        $this->senha = $senha;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getSenha()
    {
        return $this->senha;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getHashId()
    {
        return $this->hashid;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function setSenha($senha)
    {
        $this->senha = $senha;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setHashId($hashid)
    {
        $this->hashid = $hashid;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }
}
