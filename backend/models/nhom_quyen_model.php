<?php

namespace backend\models;

class NhomQuyenModel {
    private $maNhomQuyen, $tenNhomQuyen, $trangThai;

    public function __construct($maNhomQuyen, $tenNhomQuyen, $trangThai) {
        $this->maNhomQuyen = $maNhomQuyen;
        $this->tenNhomQuyen = $tenNhomQuyen;
        $this->trangThai = $trangThai;
    }

    public function getMaNhomQuyen() {
        return $this->maNhomQuyen;
    }

    public function setMaNhomQuyen($maNhomQuyen) {
        $this->maNhomQuyen = $maNhomQuyen; 
    }

    public function getTenNhomQuyen() {
        return $this->tenNhomQuyen;
    }

    public function setTenNhomQuyen($tenNhomQuyen) {
        $this->tenNhomQuyen = $tenNhomQuyen;
    }

    public function getTrangThai() {
        return $this->trangThai;
    }

    public function setTrangThai($trangThai) {
        $this->trangThai = $trangThai;
    }

    public function toArray() {
        return array(
            'maNhomQuyen' => $this->maNhomQuyen,
            'tenNhomQuyen' => $this->tenNhomQuyen,
            'trangThai' => $this->trangThai
        );
    }
}