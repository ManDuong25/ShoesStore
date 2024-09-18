<?php
namespace backend\models;

class CustomerStatisticModel
{
    private $userId;
    private $customerName;
    private $totalPurchaseAmount;
    private $totalImportPrice;

    public function __construct($userId, $customerName, $totalPurchaseAmount, $totalImportPrice)
    {
        $this->userId = $userId;
        $this->customerName = $customerName;
        $this->totalPurchaseAmount = $totalPurchaseAmount;
        $this->totalImportPrice = $totalImportPrice;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setId($userId)
    {
        $this->userId = $userId;
    }

    public function getCustomerName()
    {
        return $this->customerName;
    }

    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;
    }

    public function getTotalPurchaseAmount()
    {
        return $this->totalPurchaseAmount;
    }

    public function setTotalPurchaseAmount($totalPurchaseAmount)
    {
        $this->totalPurchaseAmount = $totalPurchaseAmount;
    }

    public function getTotalImportPrice() {
        return $this->totalImportPrice;
    }

    public function setTotalImportPrice($totalImportPrice) {
        $this->totalImportPrice = $totalImportPrice;
    }

    public function toArray()
    {
        return [
            'userId' => $this->userId,
            'customerName' => $this->customerName,
            'totalPurchaseAmount' => $this->totalPurchaseAmount,
            'totalImportPrice' => $this->totalImportPrice,
        ];
    }
}
