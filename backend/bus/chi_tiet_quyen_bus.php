<?php

namespace backend\bus;

use InvalidArgumentException;
use backend\dao\ChiTietQuyenDAO;
use Exception;

class ChiTietQuyenBUS
{
    private $chiTietQuyenList = array();
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new ChiTietQuyenBUS();
        }
        return self::$instance;
    }

    public function getAllModels(): array
    {
        return $this->chiTietQuyenList = ChiTietQuyenDAO::getInstance()->getAll();
    }

    public function refreshData(): void
    {
        $this->chiTietQuyenList = ChiTietQuyenDAO::getInstance()->getAll();
    }

    public function getModelById($maNhomQuyen, $maQuyen, $maChucNang)
    {
        return ChiTietQuyenDAO::getInstance()->getById($maNhomQuyen, $maQuyen, $maChucNang);
    }

    public function addModel($chiTietQuyenModel)
    {
        $result = ChiTietQuyenDAO::getInstance()->insert($chiTietQuyenModel);
        if ($result) {
            $this->chiTietQuyenList[] = $chiTietQuyenModel;
            $this->refreshData();
            return true;
        }
        return false;
    }

    public function updateModel($model)
    {
        $result = ChiTietQuyenDAO::getInstance()->update($model);
        if ($result) {
            $index = array_search($model, $this->chiTietQuyenList);
            $this->chiTietQuyenList[$index] = $model;
            $this->refreshData();
            return true;
        }
        return false;
    }

    public function deleteModel($id)
    {
        $result = ChiTietQuyenDAO::getInstance()->delete($id);
        if ($result) {
            $index = array_search($id, array_column($this->chiTietQuyenList, 'maChucNang'));
            unset($this->chiTietQuyenList[$index]);
            $this->refreshData();
            return true;
        }
        return false;
    }

    public function getAllWithMaNhomQuyen($maNhomQuyen) {
        return ChiTietQuyenDAO::getInstance()->getAllWithMaNhomQuyen($maNhomQuyen);
    }

    public function getMaxNumberFromMaNhomQuyen() {
        return ChiTietQuyenDAO::getInstance()->getMaxNumberFromMaNhomQuyen();
    }
}
