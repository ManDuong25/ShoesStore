<?php

namespace backend\bus;

use backend\interfaces\BUSInterface;
use InvalidArgumentException;
use backend\dao\NhaCungCapDAO;
use Exception;

class NhaCungCapBUS implements BUSInterface
{
    private $nhaCungCapList = array();
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new NhaCungCapBUS();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->nhaCungCapList = NhaCungCapDAO::getInstance()->getAll();
    }

    public function getAllModels(): array
    {
        return $this->nhaCungCapList;
    }

    public function refreshData(): void
    {
        $this->nhaCungCapList = NhaCungCapDAO::getInstance()->getAll();
    }

    public function getModelById($id)
    {
        return NhaCungCapDAO::getInstance()->getById($id);
    }

    public function getActiveProductOnly()
    {
        return NhaCungCapDAO::getInstance()->getActiveProducts();
    }

    public function addModel($nhaCungCapModel)
    {
        $newNhaCungCap = NhaCungCapDAO::getInstance()->insert($nhaCungCapModel);
        if ($newNhaCungCap) {
            $this->nhaCungCapList[] = $nhaCungCapModel;
            $this->refreshData();
            return true;
        }
        return false;
    }

    public function updateModel($model)
    {
        $result = NhaCungCapDAO::getInstance()->update($model);
        if ($result) {
            $index = array_search($model, $this->nhaCungCapList);
            $this->nhaCungCapList[$index] = $model;
            $this->refreshData();
            return true;
        }
        return false;
    }

    public function deleteModel($id)
    {
        $result = NhaCungCapDAO::getInstance()->delete($id);
        if ($result) {
            $index = array_search($id, array_column($this->nhaCungCapList, 'maNCC'));
            unset($this->nhaCungCapList[$index]);
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
        return NhaCungCapDAO::getInstance()->search($condition, $columnNames);
    }


}
