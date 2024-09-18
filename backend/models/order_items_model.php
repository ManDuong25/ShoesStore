<?php
namespace backend\models;
class OrderItemsModel
{
    private $id, $orderId, $productId, $sizeId, $quantity, $price, $importPrice;

    public function __construct($id, $orderId, $productId, $sizeId, $quantity, $price, $importPrice)
    {
        $this->id = $id;
        $this->orderId = $orderId;
        $this->productId = $productId;
        $this->sizeId = $sizeId;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->importPrice = $importPrice;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function setProductId($productId)
    {
        $this->productId = $productId;
    }

    public function getSizeId()
    {
        return $this->sizeId;
    }

    public function setSizeId($sizeId)
    {
        $this->sizeId = $sizeId;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
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
            'id' => $this->id,
            'orderId' => $this->orderId,
            'productId' => $this->productId,
            'sizeId' => $this->sizeId,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'importPrice' => $this->importPrice,
        ];
    }
}
