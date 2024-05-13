<?php

namespace backend\bus;

use InvalidArgumentException;
use backend\dao\ChucNangDAO;
use Exception;

class ChucNangBUS
{
    private $chucNangList = array();
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new ChucNangBUS();
        }
        return self::$instance;
    }

    public function getAllModels(): array
    {
        return $this->chucNangList = ChucNangDAO::getInstance()->getAll();
    }

    public function refreshData(): void
    {
        $this->chucNangList = ChucNangDAO::getInstance()->getAll();
    }

    public function getModelById($id)
    {
        return ChucNangDAO::getInstance()->getById($id);
    }

    public function addModel($chucNangModel)
    {
        $result = ChucNangDAO::getInstance()->insert($chucNangModel);
        if ($result) {
            $this->chucNangList[] = $chucNangModel;
            $this->refreshData();
            return true;
        }
        return false;
    }

    public function updateModel($model)
    {
        $result = ChucNangDAO::getInstance()->update($model);
        if ($result) {
            $index = array_search($model, $this->chucNangList);
            $this->chucNangList[$index] = $model;
            $this->refreshData();
            return true;
        }
        return false;
    }

    public function deleteModel($id)
    {
        $result = ChucNangDAO::getInstance()->delete($id);
        if ($result) {
            $index = array_search($id, array_column($this->chucNangList, 'maChucNang'));
            unset($this->chucNangList[$index]);
            $this->refreshData();
            return true;
        }
        return false;
    }
}
