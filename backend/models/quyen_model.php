<?php

namespace backend\models;

class QuyenModel
{
    private $maQuyen, $tenQuyen;

    public function __construct(
        $maQuyen, $tenQuyen
    ) {
        $this->maQuyen = $maQuyen;
        $this->tenQuyen = $tenQuyen;
    }

    public function getMaQuyen() {
        return $this->maQuyen;
    }

    public function setMaQuyen($maQuyen) {
        $this->maQuyen = $maQuyen;
    }

    public function getTenQuyen() {
        return $this->tenQuyen;
    }

    public function setTenQuyen($tenQuyen) {
        $this->tenQuyen = $tenQuyen;
    }
}

