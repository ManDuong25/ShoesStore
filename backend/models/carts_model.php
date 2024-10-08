<?php

namespace backend\models;

class CartsModel
{
    private $id, $userId, $productId, $quantity, $sizeId, $importPrice;
    public function __construct($id, $userId, $productId, $quantity, $sizeId, $importPrice)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->sizeId = $sizeId;
        $this->importPrice = $importPrice;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getSizeId()
    {
        return $this->sizeId;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function setProductId($productId)
    {
        $this->productId = $productId;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function setSizeId($sizeId)
    {
        $this->sizeId = $sizeId;
    }

    public function getImportPrice()
    {
        return $this->importPrice;
    }

    public function setImportPrice($importPrice)
    {
        $this->importPrice = $importPrice;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'userId' => $this->getUserId(),
            'productId' => $this->getProductId(),
            'quantity' => $this->getQuantity(),
            'sizeId' => $this->getSizeId(),
            'importPrice' => $this->getImportPrice()
        ];
    }
}
