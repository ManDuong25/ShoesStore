<?php

namespace backend\bus;

use backend\interfaces\BUSInterface;
use InvalidArgumentException;
use backend\dao\PhieuNhapDAO;
use Exception;

class PhieuNhapBUS implements BUSInterface
{
    private $phieuNhapList = array();
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new PhieuNhapBUS();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->phieuNhapList = PhieuNhapDAO::getInstance()->getAll();
    }

    public function getAllModels(): array
    {
        return $this->phieuNhapList;
    }

    public function refreshData(): void
    {
        $this->phieuNhapList = PhieuNhapDAO::getInstance()->getAll();
    }

    public function getModelById($id)
    {
        return PhieuNhapDAO::getInstance()->getById($id);
    }

    public function getActiveProductOnly()
    {
        return PhieuNhapDAO::getInstance()->getActiveProducts();
    }

    public function addModel($phieuNhapModel)
    {
        $newPhieuNhap = PhieuNhapDAO::getInstance()->insert($phieuNhapModel);
        if ($newPhieuNhap) {
            $this->phieuNhapList[] = $phieuNhapModel;
            $this->refreshData();
            return true;
        }
        return false;
    }

    public function updateModel($model)
    {
        $result = PhieuNhapDAO::getInstance()->update($model);
        if ($result) {
            $index = array_search($model, $this->phieuNhapList);
            $this->phieuNhapList[$index] = $model;
            $this->refreshData();
            return true;
        }
        return false;
    }

    public function deleteModel($id)
    {
        $result = PhieuNhapDAO::getInstance()->delete($id);
        if ($result) {
            $index = array_search($id, array_column($this->phieuNhapList, 'maPhieuNhap'));
            unset($this->phieuNhapList[$index]);
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
        return PhieuNhapDAO::getInstance()->search($condition, $columnNames);
    }

    public function getMaxPhieuNhapId(): int
    {
        return PhieuNhapDAO::getInstance()->getMaxPhieuNhapId();
    }

    public function countAllModels() {
        return PhieuNhapDAO::getInstance()->countAllModels();
    }

    public function paginationTech($from, $limit) {
        return PhieuNhapDAO::getInstance()->paginationTech($from, $limit);
    }

    public function multiFilterModel($from, $limit, $filterName, $dateFrom, $dateTo, $filterStatus) {
        if (!empty($dateFrom)) {
            $dateFrom = date('Y-m-d', strtotime($dateFrom));
        }
        if (!empty($dateTo)) {
            $dateTo = date('Y-m-d', strtotime($dateTo));
        }
        return PhieuNhapDAO::getInstance()->multiFilterModel($from, $limit, $filterName, $dateFrom, $dateTo, $filterStatus);
    }

    public function countFilteredModel($filterName, $dateFrom, $dateTo, $filterStatus) {
        if (!empty($dateFrom)) {
            $dateFrom = date('Y-m-d', strtotime($dateFrom));
        }
        if (!empty($dateTo)) {
            $dateTo = date('Y-m-d', strtotime($dateTo));
        }
        return PhieuNhapDAO::getInstance()->countFilteredModel($filterName, $dateFrom, $dateTo, $filterStatus);
    }
}
