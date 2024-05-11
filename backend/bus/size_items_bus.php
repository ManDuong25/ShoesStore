<?php
namespace backend\bus;

use backend\interfaces\BUSInterface;
use backend\dao\SizeItemsDAO;
use Exception;

class SizeItemsBUS implements BUSInterface
{
    private $sizeItemsList = array();
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new SizeItemsBUS();
        }
        return self::$instance;
    }
    private function __construct()
    {
        $this->refreshData();
    }
    public function getAllModels(): array
    {
        return $this->sizeItemsList;
    }
    public function refreshData(): void
    {
        $this->sizeItemsList = SizeItemsDAO::getInstance()->getAll();
    }
    public function getModelById(int $id)
    {
        return SizeItemsDAO::getInstance()->getById($id);
    }
    public function addModel($sizeItemsModel)
    {
        $this->validateModel($sizeItemsModel);
        $result = SizeItemsDAO::getInstance()->insert($sizeItemsModel);
        if ($result) {
            $this->sizeItemsList[] = $sizeItemsModel;
            $this->refreshData();
        }
        return $result;
    }

    public function updateModel($sizeItemsModel)
    {
        $this->validateModel($sizeItemsModel);
        $result = SizeItemsDAO::getInstance()->update($sizeItemsModel);
        if ($result) {
            $index = array_search($sizeItemsModel, $this->sizeItemsList);
            $this->sizeItemsList[$index] = $sizeItemsModel;
            $this->refreshData();
        }
        return $result;
    }

    public function deleteModel($sizeItemsModel)
    {
        $result = SizeItemsDAO::getInstance()->delete($sizeItemsModel->getId());
        if ($result) {
            $index = array_search($sizeItemsModel, $this->sizeItemsList);
            unset($this->sizeItemsList[$index]);
            $this->refreshData();
        }
        return $result;
    }

    public function searchModel(string $value, array $columns)
    {
        return SizeItemsDAO::getInstance()->search($value, $columns);
    }

    public function validateModel($sizeItemsModel): void
    {
        if ($sizeItemsModel->getSizeId() == null) {
            throw new Exception("Size ID is required");
        }
        if ($sizeItemsModel->getProductId() == null) {
            throw new Exception("Product ID is required");
        }
        if (trim($sizeItemsModel->getQuantity()) === "") {
            throw new Exception("Quantity is required");
        }
        if ($sizeItemsModel->getQuantity() < 0) {
            throw new Exception("Invalid quantity value");
        }
    }

    public function getModelBySizeIdAndProductId($sizeId, $productId)
    {
        foreach ($this->sizeItemsList as $sizeItems) {
            if ($sizeItems->getSizeId() == $sizeId && $sizeItems->getProductId() == $productId) {
                return $sizeItems;
            }
        }
        return null;
    }

    public function getModelByProductId($productId)
    {
        $result = [];
        foreach ($this->sizeItemsList as $sizeItems) {
            if ($sizeItems->getProductId() == $productId) {
                $result[] = $sizeItems;
            }
        }
        return $result;
    }
    
    public function countAllModels() {
        return SizeItemsDAO::getInstance()->countAllModels();
    }

    public function paginationTech($from, $limit) {
        return SizeItemsDAO::getInstance()->paginationTech($from, $limit);
    }

    public function filterByName($from, $limit, $name) {
        return SizeItemsDAO::getInstance()->filterByName($from, $limit, $name);
    }
    
    public function countFilterByName($name) {
        return SizeItemsDAO::getInstance()->countFilterByName($name);
    }
}