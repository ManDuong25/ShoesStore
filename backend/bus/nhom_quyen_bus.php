<?php

namespace backend\bus;

use InvalidArgumentException;
use backend\dao\NhomQuyenDAO;
use Exception;

class NhomQuyenBUS
{
    private $nhomQuyenList = array();
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new NhomQuyenBUS();
        }
        return self::$instance;
    }

    public function getAllModels(): array
    {
        return $this->nhomQuyenList = NhomQuyenDAO::getInstance()->getAll();
    }

    public function refreshData(): void
    {
        $this->nhomQuyenList = NhomQuyenDAO::getInstance()->getAll();
    }

    public function getModelById($id)
    {
        return NhomQuyenDAO::getInstance()->getById($id);
    }

    public function isTenNhomQuyenExists($tenNhomQuyen)
    {
        $this->nhomQuyenList = NhomQuyenDAO::getInstance()->getAll();
        foreach ($this->nhomQuyenList as $nhomQuyenModel) {
            if ($nhomQuyenModel->getTenNhomQuyen() === $tenNhomQuyen) {
                return true;
            }
        }
        return false;
    }

    public function addModel($nhomQuyenModel)
    {
        if ($this->isTenNhomQuyenExists($nhomQuyenModel->getTenNhomQuyen())) {
            return false;
        } 
        $result = NhomQuyenDAO::getInstance()->insert($nhomQuyenModel);
        if ($result) {
            $this->nhomQuyenList[] = $nhomQuyenModel;
            $this->refreshData();
            return true;
        }
        return false;
    }

    public function updateModel($model)
    {
        $result = NhomQuyenDAO::getInstance()->update($model);
        if ($result) {
            $index = array_search($model, $this->nhomQuyenList);
            $this->nhomQuyenList[$index] = $model;
            $this->refreshData();
            return true;
        }
        return false;
    }

    public function deleteModel($id)
    {
        $result = NhomQuyenDAO::getInstance()->delete($id);
        if ($result) {
            $index = array_search($id, array_column($this->nhomQuyenList, 'maNhomQuyens'));
            unset($this->nhomQuyenList[$index]);
            $this->refreshData();
            return true;
        }
        return false;
    }

    public function getNhomQuyenByTenNhomQuyen($tenNhomQuyen) {
        return NhomQuyenDAO::getInstance()->getNhomQuyenByTenNhomQuyen($tenNhomQuyen);
    }
}