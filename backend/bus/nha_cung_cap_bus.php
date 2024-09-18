<?php

namespace backend\bus;

use backend\interfaces\BUSInterface;
use backend\services\validation;
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

    public function getAllActive()
    {
        return NhaCungCapDAO::getInstance()->getAllActive();
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

    public function isEmailUsed($email)
    {
        $this->nhaCungCapList = NhaCungCapDAO::getInstance()->getAll();
        for ($i = 0; $i < count($this->nhaCungCapList); $i++) {
            if ($this->nhaCungCapList[$i]->getEmail() == $email) {
                return true;
            }
        }
        return false;
    }

    public function isPhoneNumberUsedToAdd($phone)
    {
        $this->nhaCungCapList = NhaCungCapDAO::getInstance()->getAll();
        for ($i = 0; $i < count($this->nhaCungCapList); $i++) {
            if ($this->nhaCungCapList[$i]->getSdt() == $phone) {
                return true;
            }
        }
        return false;
    }

    public function isPhoneNumberUsedToUpdate($phone, $nccIdUpdate)
    {
        $this->nhaCungCapList = NhaCungCapDAO::getInstance()->getAll();
        for ($i = 0; $i < count($this->nhaCungCapList); $i++) {
            if ($this->nhaCungCapList[$i]->getSdt() == $phone && !($this->nhaCungCapList[$i]->getMaNCC() == $nccIdUpdate)) {
                return true;
            }
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

    public function countAllModels()
    {
        return NhaCungCapDAO::getInstance()->countAllModels();
    }

    public function paginationTech($from, $limit)
    {
        return NhaCungCapDAO::getInstance()->paginationTech($from, $limit);
    }

    public function filterByEmail($from, $limit, $email)
    {
        return NhaCungCapDAO::getInstance()->filterByEmail($from, $limit, $email);
    }

    public function countFilterByEmail($email)
    {
        return NhaCungCapDAO::getInstance()->countFilterByEmail($email);
    }
}
