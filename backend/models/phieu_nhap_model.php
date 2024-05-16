<?php

namespace backend\models;

class PhieuNhapModel
{
    private $maPhieuNhap, $userId, $phieuNhapDate, $totalAmount, $maNCC, $trangThai;

    public function __construct($maPhieuNhap, $userId, $phieuNhapDate, $totalAmount, $maNCC, $trangThai)
    {
        $this->maPhieuNhap = $maPhieuNhap;
        $this->userId = $userId;
        $this->phieuNhapDate = $phieuNhapDate;
        $this->totalAmount = $totalAmount;
        $this->maNCC = $maNCC;
        $this->trangThai = $trangThai;
    }
    
    // Getters
    public function getMaPhieuNhap() {
        return $this->maPhieuNhap;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getPhieuNhapDate() {
        return $this->phieuNhapDate;
    }

    public function getTotalAmount() {
        return $this->totalAmount;
    }

    public function getMaNCC() {
        return $this->maNCC;
    }

    public function getTrangThai() {
        return $this->trangThai;
    }

    // Setters
    public function setMaPhieuNhap($maPhieuNhap) {
        $this->maPhieuNhap = $maPhieuNhap;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setPhieuNhapDate($phieuNhapDate) {
        $this->phieuNhapDate = $phieuNhapDate;
    }

    public function setTotalAmount($totalAmount) {
        $this->totalAmount = $totalAmount;
    }

    public function setMaNCC($maNCC) {
        $this->maNCC = $maNCC;
    }

    public function setTrangThai($trangThai) {
        $this->trangThai = $trangThai;
    }

    // toArray method
    public function toArray() {
        return [
            'maPhieuNhap' => $this->maPhieuNhap,
            'userId' => $this->userId,
            'phieuNhapDate' => $this->phieuNhapDate,
            'totalAmount' => $this->totalAmount,
            'maNCC' => $this->maNCC,
            'trangThai' => $this->trangThai
        ];
    }
}
