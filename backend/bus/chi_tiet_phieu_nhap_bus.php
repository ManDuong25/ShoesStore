<?php

namespace backend\bus;

use backend\interfaces\BUSInterface;
use InvalidArgumentException;
use backend\dao\ChiTietPhieuNhapDAO;
use Exception;

class ChiTietPhieuNhapBUS
{
    private $ctpnList = array();
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new ChiTietPhieuNhapBUS();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->ctpnList = ChiTietPhieuNhapDAO::getInstance()->getAll();
    }

    public function getAllModels(): array
    {
        return $this->ctpnList;
    }

    public function refreshData(): void
    {
        $this->ctpnList = ChiTietPhieuNhapDAO::getInstance()->getAll();
    }

    public function getModelByIdPhieuNhap($id): array
    {
        return ChiTietPhieuNhapDAO::getInstance()->getByIdPhieuNhap($id);
    }

    public function addModel($ctpnModel)
    {
        $newCtpn = ChiTietPhieuNhapDAO::getInstance()->insert($ctpnModel);
        if ($newCtpn) {
            $this->ctpnList[] = $ctpnModel;
            $this->refreshData();
            return true;
        }
        return false;
    }

    public function updateModel($model)
    {
        $result = ChiTietPhieuNhapDAO::getInstance()->update($model);
        if ($result) {
            $index = array_search($model, $this->ctpnList);
            $this->ctpnList[$index] = $model;
            $this->refreshData();
            return true;
        }
        return false;
    }

    public function deleteModel($id)
    {
        $result = ChiTietPhieuNhapDAO::getInstance()->delete($id);
        if ($result) {
            $index = array_search($id, array_column($this->ctpnList, 'maCTPN'));
            unset($this->ctpnList[$index]);
            $this->refreshData();
            return true;
        }
        return false;
    }

    public function searchModel(string $condition, $columnNames): array
    {
        if (empty($condition)) {
            return [];
        }
        return ChiTietPhieuNhapDAO::getInstance()->search($condition, $columnNames);
    }

    public function getCTPNListByProductId($productId) {
        return ChiTietPhieuNhapDAO::getInstance()->getCTPNListByProductId($productId);
    }
}
