<?php

namespace backend\models;

class ChucNangModel {
    private $maChucNang, $tenChucNang;
    
    public function __construct($maChucNang, $tenChucNang) {
        $this->maChucNang = $maChucNang;
        $this->tenChucNang = $tenChucNang;
    }

    public function getMaChucNang() {
        return $this->maChucNang;
    }

    public function setMaChucNang($maChucNang) {
        $this->maChucNang = $maChucNang;
    }

    public function getTenChucNang() {
        return $this->tenChucNang;
    }

    public function setTenChucNang($tenChucNang) {
        $this->tenChucNang = $tenChucNang;
    }
}