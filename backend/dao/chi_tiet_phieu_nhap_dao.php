<?php

namespace backend\dao;

use Exception;
use backend\interfaces\DAOInterface;
use InvalidArgumentException;
use backend\models\ChiTietPhieuNhapModel;
use backend\services\DatabaseConnection;

class ChiTietPhieuNhapDAO implements DAOInterface
{
    private static $instance;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new ChiTietPhieuNhapDAO();
        }
        return self::$instance;
    }

    public function readDatabase(): array
    {
        $nccList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM chitietphieunhap");
        while ($row = $rs->fetch_assoc()) {
            $nccModel = $this->createCTPNModel($row);
            array_push($nccList, $nccModel);
        }
        return $nccList;
    }

    private function createCTPNModel($rs)
    {
        $maCTPN = $rs['maCTPN'];
        $maPhieuNhap = $rs['maPhieuNhap'];
        $productId = $rs['product_id'];
        $sizeId = $rs['size_id'];
        $quantity = $rs['quantity'];
        $price = $rs['price'];
        return new ChiTietPhieuNhapModel($maCTPN, $maPhieuNhap, $productId, $sizeId, $quantity, $price);
    }

    public function getAll(): array
    {
        $ctpnList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM chitietphieunhap");
        while ($row = $rs->fetch_assoc()) {
            $ctpnModel = $this->createCTPNModel($row);
            array_push($ctpnList, $ctpnModel);
        }
        return $ctpnList;
    }

    public function getByIdPhieuNhap($id): array
    {
        $ctpnList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM chitietphieunhap WHERE maPhieuNhap = ?", $id);
        while ($row = $rs->fetch_assoc()) {
            $ctpnModel = $this->createCTPNModel($row);
            array_push($ctpnList, $ctpnModel);
        }
        return $ctpnList;
    }

    public function insert($data): int
    {
        $query = "INSERT INTO chitietphieunhap (maCTPN, maPhieuNhap, product_id, size_id, quantity, price) VALUES (?, ?, ?, ?, ?, ?)";
        $args = [
            $data->getMaCTPN(),
            $data->getMaPhieuNhap(),
            $data->getProductId(),
            $data->getSizeId(),
            $data->getQuantity(),
            $data->getPrice()
        ];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function update($data): int
    {
        $query = "UPDATE chitietphieunhap SET maPhieuNhap = ?, product_id = ?, size_id = ?, quantity = ?, price = ? WHERE maCTPN = ?";
        $args = [
            $data->getMaPhieuNhap(),
            $data->getProductId(),
            $data->getSizeId(),
            $data->getQuantity(),
            $data->getPrice(),
            $data->getMaCTPN()
        ];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function delete(int $id): int
    {
        $query = "DELETE FROM chitietphieunhap WHERE maCTPN = ?";
        return DatabaseConnection::executeUpdate($query, $id);
    }

    public function search(string $condition, $columnNames): array
    {
        if (empty($condition)) {
            return [];
        }
        $query = "";
        if ($columnNames === null || count($columnNames) === 0) {
            $query = "SELECT * FROM chitietphieunhap WHERE maCTPN LIKE ? OR maPhieuNhap LIKE ? OR product_id LIKE ? OR size_id LIKE ? OR quantity LIKE ? OR price LIKE ?";
            $args = array_fill(0, 6, "%" . $condition . "%");
        } else if (count($columnNames) === 1) {
            $column = $columnNames[0];
            $query = "SELECT * FROM chitietphieunhap WHERE $column LIKE ?";
            $args = ["%" . $condition . "%"];
        } else {
            $query = "SELECT * FROM chitietphieunhap WHERE " . implode(" LIKE ? OR ", $columnNames) . " LIKE ?";
            $args = array_fill(0, count($columnNames), "%" . $condition . "%");
        }
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $productList = [];
        while ($row = $rs->fetch_assoc()) {
            $productModel = $this->createCTPNModel($row);
            array_push($productList, $productModel);
        }
        if (count($productList) === 0) {
            return [];
        }
        return $productList;
    }

    public function getCTPNListByProductId($productId)
    {
        $query = "SELECT * FROM chitietphieunhap WHERE product_id = ?";
        $rs = DatabaseConnection::executeQuery($query, $productId);
        $orderItemsList = [];
        while ($row = $rs->fetch_assoc()) {
            $orderItemsModel = $this->createCTPNModel($row);
            array_push($orderItemsList, $orderItemsModel);
        }
        return $orderItemsList;
    }
}
