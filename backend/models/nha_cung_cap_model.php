<?php

namespace backend\models;

class NhaCungCapModel
{
    private $maNCC, $ten, $diaChi, $sdt, $email, $trangThai;

    public function __construct(
        $maNCC,
        $ten,
        $diaChi,
        $sdt,
        $email,
        $trangThai
    ) {
        $this->maNCC = $maNCC;
        $this->ten = $ten;
        $this->diaChi = $diaChi;
        $this->sdt = $sdt;
        $this->email = $email;
        $this->trangThai = $trangThai;
    }

    // Getters
    public function getMaNCC()
    {
        return $this->maNCC;
    }

    public function getTen()
    {
        return $this->ten;
    }

    public function getDiaChi()
    {
        return $this->diaChi;
    }

    public function getSdt()
    {
        return $this->sdt;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getTrangThai()
    {
        return $this->trangThai;
    }

    // Setters
    public function setMaNCC($maNCC)
    {
        $this->maNCC = $maNCC;
    }

    public function setTen($ten)
    {
        $this->ten = $ten;
    }

    public function setDiaChi($diaChi)
    {
        $this->diaChi = $diaChi;
    }

    public function setSdt($sdt)
    {
        $this->sdt = $sdt;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setTrangThai($trangThai)
    {
        $this->trangThai = $trangThai;
    }

    // toArray method
    public function toArray()
    {
        return [
            'maNCC' => $this->maNCC,
            'ten' => $this->ten,
            'diaChi' => $this->diaChi,
            'sdt' => $this->sdt,
            'email' => $this->email,
            'trangThai' => $this->trangThai
        ];
    }
}
