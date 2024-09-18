<?php
namespace backend\models;
class SizeItemsModel
{
    private $id;
    private $productId;
    private $sizeId;
    private $quantity;
    private $importPrice;

    public function __construct($id, $productId, $sizeId, $quantity, $importPrice)
    {
        $this->id = $id;
        $this->productId = $productId;
        $this->sizeId = $sizeId;
        $this->quantity = $quantity;
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

    public function getImportPrice()
    {
        return $this->importPrice;
    }

    public function setImportPrice($importPrice)
    {
        return $this->importPrice = $importPrice;
    }
    public function toArray($productName, $productCategory, $size, $img) {
        return [
            'id' => $this->id,
            'productName' => $productName,  
            'category' => $productCategory,
            'size' => $size,
            'quantity' => $this->quantity,
            'image' => $img,
            'importPrice' => $this->importPrice,
        ];
    }
}
