<?php

namespace backend\dao;

use Exception;
use backend\interfaces\DAOInterface;
use InvalidArgumentException;
use backend\models\PhieuNhapModel;
use backend\services\DatabaseConnection;

class PhieuNhapDAO implements DAOInterface
{
    private static $instance;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new PhieuNhapDAO();
        }
        return self::$instance;
    }

    public function readDatabase(): array
    {
        $phieuNhapList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM phieunhap");
        while ($row = $rs->fetch_assoc()) {
            $phieuNhapModel = $this->createPNModel($row);
            array_push($phieuNhapList, $phieuNhapModel);
        }
        return $phieuNhapList;
    }

    private function createPNModel($rs)
    {
        $maPhieuNhap = $rs['maNCC'];
        $userId = $rs['user_id'];
        $phieuNhapDate = $rs['phieuNhapDate'];
        $totalAmount = $rs['total_amount'];
        $maNCC = $rs['maNCC'];
        $trangThai = $rs['trangThai'];
        return new PhieuNhapModel($maPhieuNhap, $userId, $phieuNhapDate, $totalAmount, $maNCC, $trangThai);
    }

    public function getAll(): array
    {
        $phieuNhapList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM phieunhap");
        while ($row = $rs->fetch_assoc()) {
            $phieuNhapModel = $this->createPNModel($row);
            array_push($phieuNhapList, $phieuNhapModel);
        }
        return $phieuNhapList;
    }

    public function getById($id)
    {
        $query = "SELECT * FROM phieunhap WHERE maPhieuNhap = ?";
        $result = DatabaseConnection::executeQuery($query, $id);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row) {
                return $this->createPNModel($row);
            }
        }
        return null;
    }

    public function getActiveProducts()
    {
        $query = "SELECT * FROM phieunhap WHERE trangThai = 1";
        $rs = DatabaseConnection::executeQuery($query);
        $phieuNhapList = [];
        while ($row = $rs->fetch_assoc()) {
            $phieuNhapModel = $this->createPNModel($row);
            array_push($phieuNhapList, $phieuNhapModel);
        }
        return $phieuNhapList;
    }

    public function insert($data): int
    {
        $query = "INSERT INTO phieunhap (maPhieuNhap, user_id, phieuNhapDate, total_amount, maNCC, trangThai) VALUES (?, ?, ?, ?, ?, ?)";
        $args = [
            $data->getMaPhieuNhap(),
            $data->getUserId(),
            $data->getPhieuNhapDate(),
            $data->getTotalAmount(),
            $data->getMaNCC(),
            $data->getTrangThai()
        ];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function update($data): int
    {
        $query = "UPDATE phieunhap SET user_id = ?, phieuNhapDate = ?, total_amount = ?, maNCC = ?, trangThai = ? WHERE maPhieuNhap = ?";
        $args = [
            $data->getUserId(),
            $data->getPhieuNhapDate(),
            $data->getTotalAmount(),
            $data->getMaNCC(),
            $data->getTrangThai(),
            $data->getMaPhieuNhap()
        ];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function delete(int $id): int
    {
        $query = "UPDATE phieunhap SET trangThai = 0 WHERE maPhieuNhap = ?";
        return DatabaseConnection::executeUpdate($query, $id);
    }

    public function search(string $condition, $columnNames): array
    {
        if (empty($condition)) {
            return [];
        }
        $query = "";
        if ($columnNames === null || count($columnNames) === 0) {
            $query = "SELECT * FROM nha WHERE id LIKE ? OR name LIKE ? OR category_id LIKE ? OR price LIKE ? OR description LIKE ? OR image LIKE ? OR gender LIKE ? OR status LIKE ?";
            $args = array_fill(0, 7, "%" . $condition . "%");
        } else if (count($columnNames) === 1) {
            $column = $columnNames[0];
            $query = "SELECT * FROM products WHERE $column LIKE ?";
            $args = ["%" . $condition . "%"];
        } else {
            $query = "SELECT * FROM products WHERE " . implode(" LIKE ? OR ", $columnNames) . " LIKE ?";
            $args = array_fill(0, count($columnNames), "%" . $condition . "%");
        }
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $productList = [];
        while ($row = $rs->fetch_assoc()) {
            $productModel = $this->createPNModel($row);
            array_push($productList, $productModel);
        }
        if (count($productList) === 0) {
            return [];
        }
        return $productList;
    }
}
