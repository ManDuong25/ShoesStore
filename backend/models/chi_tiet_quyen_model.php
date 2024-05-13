<?php

namespace backend\models;

class ChiTietQuyenModel{
    private $maNhomQuyen, $maChucNang, $maQuyen;

    public function __construct($maNhomQuyen, $maChucNang, $maQuyen) {
        $this->maNhomQuyen = $maNhomQuyen;
        $this->maChucNang = $maChucNang;
        $this->maQuyen = $maQuyen;
    }

    public function getMaNhomQuyen() {
        return $this->maNhomQuyen;
    }

    public function setMaNhomQuyen($maNhomQuyen) {
        $this->maNhomQuyen = $maNhomQuyen;
    }

    public function getMaChucNang() {
        return $this->maChucNang;
    }

    public function setMaChucNang($maChucNang) {
        $this->maChucNang = $maChucNang;
    }

    public function getMaQuyen() {
        return $this->maQuyen;
    }
    
    public function setMaQuyen($maQuyen) {
        $this->maQuyen = $maQuyen;
    }
}