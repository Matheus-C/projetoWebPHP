<?php

namespace App\model;

class carrinho
{
    private $itens; //array de de objetos do tipo item
    private $total; // valor total dos itens no carrinho

    public function __construct()
    {
        $this->itens = [];
        $this->total = 0.00;
    }

    public function getItens()
    {
        return $this->itens;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function setItens($itens)
    {
        $this->itens = $itens;
    }

    public function setTotal($total)
    {
        $this->total = $total;
    }
    //$item-> objeto do tipo item que vem com a quantidade pedida pelo usuário
    public function adiciona($item)
    {
        $itensNoCarrinho = $this->getItens();
        if (isset($itensNoCarrinho[$item->getHashid()])) {
            $quantidadeT = $item->getQuantidade() + $itensNoCarrinho[$item->getHashid()]->getQuantidade();
            $itensNoCarrinho[$item->getHashid()]->setQuantidade($quantidadeT);
            $this->setTotal($this->getTotal() + ($item->getQuantidade() * $item->getPreco()));
        } else {
            $itensNoCarrinho += array($item->getHashid() => $item);
            $this->setItens($itensNoCarrinho);
            $this->setTotal($this->getTotal() + ($itensNoCarrinho[$item->getHashid()]->getQuantidade() * $itensNoCarrinho[$item->getHashid()]->getPreco()));
        }
    }

    public function remove($id, $preco, $quantidade)
    {
        $itensNoCarrinho = $this->getItens();
        unset($itensNoCarrinho[$id]);
        $this->setItens($itensNoCarrinho);
        $this->setTotal($this->getTotal() - ($preco * $quantidade));
    }
}
