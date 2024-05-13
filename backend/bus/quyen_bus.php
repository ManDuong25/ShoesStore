<?php

namespace backend\bus;

use InvalidArgumentException;
use backend\dao\QuyenDAO;
use Exception;

class QuyenBUS
{
    private $quyenList = array();
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new QuyenBUS();
        }
        return self::$instance;
    }

    public function getAllModels(): array
    {
        return $this->quyenList = QuyenDAO::getInstance()->getAll();
    }

    public function refreshData(): void
    {
        $this->quyenList = QuyenDAO::getInstance()->getAll();
    }

    public function getModelById($id)
    {
        return QuyenDAO::getInstance()->getById($id);
    }

    public function addModel($quyenModel)
    {
        $result = QuyenDAO::getInstance()->insert($quyenModel);
        if ($result) {
            $this->quyenList[] = $quyenModel;
            $this->refreshData();
            return true;
        }
        return false;
    }

    public function updateModel($model)
    {
        $result = QuyenDAO::getInstance()->update($model);
        if ($result) {
            $index = array_search($model, $this->quyenList);
            $this->quyenList[$index] = $model;
            $this->refreshData();
            return true;
        }
        return false;
    }

    public function deleteModel($id)
    {
        $result = QuyenDAO::getInstance()->delete($id);
        if ($result) {
            $index = array_search($id, array_column($this->quyenList, 'maQuyen'));
            unset($this->quyenList[$index]);
            $this->refreshData();
            return true;
        }
        return false;
    }
}
