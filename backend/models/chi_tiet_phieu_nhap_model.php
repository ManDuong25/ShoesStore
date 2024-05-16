<?php

namespace backend\models;

class ChiTietPhieuNhapModel
{
    private $maCTPN, $maPhieuNhap, $productId, $sizeId, $quantity, $price;

    public function __construct($maCTPN, $maPhieuNhap, $productId, $sizeId, $quantity, $price)
    {
        $this->maCTPN = $maCTPN;
        $this->maPhieuNhap = $maPhieuNhap;
        $this->productId = $productId;
        $this->sizeId = $sizeId;
        $this->quantity = $quantity;
        $this->price = $price;
    }

    public function getMaCTPN() {
        return $this->maCTPN;
    }

    public function getMaPhieuNhap() {
        return $this->maPhieuNhap;
    }

    public function getProductId() {
        return $this->productId;
    }

    public function getSizeId() {
        return $this->sizeId;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function getPrice() {
        return $this->price;
    }

    // Setters
    public function setMaCTPN($maCTPN) {
        $this->maCTPN = $maCTPN;
    }

    public function setMaPhieuNhap($maPhieuNhap) {
        $this->maPhieuNhap = $maPhieuNhap;
    }

    public function setProductId($productId) {
        $this->productId = $productId;
    }

    public function setSizeId($sizeId) {
        $this->sizeId = $sizeId;
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    // toArray method
    public function toArray() {
        return [
            'maCTPN' => $this->maCTPN,
            'maPhieuNhap' => $this->maPhieuNhap,
            'productId' => $this->productId,
            'sizeId' => $this->sizeId,
            'quantity' => $this->quantity,
            'price' => $this->price
        ];
    }
}
