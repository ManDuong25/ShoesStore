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
        $maPhieuNhap = $rs['maPhieuNhap'];
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
        $query = "DELETE FROM phieunhap WHERE maPhieuNhap = ?";
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

    public function getMaxPhieuNhapId(): int
    {
        $query = "SELECT MAX(maPhieuNhap) AS max_id FROM phieunhap";
        $result = DatabaseConnection::executeQuery($query);
        $row = $result->fetch_assoc();
        return isset($row['max_id']) ? (int)$row['max_id'] : 0;
    }

    public function countAllModels()
    {
        $query = "SELECT COUNT(*) AS total FROM phieunhap;";
        $rs = DatabaseConnection::executeQuery($query);
        $row = $rs->fetch_assoc();
        return $row['total'];
    }

    public function paginationTech($from, $limit)
    {
        $phieuNhapList = [];
        $query = "SELECT * FROM phieunhap LIMIT ?, ?;";
        $args = [
            $from,
            $limit
        ];
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        while ($row = $rs->fetch_assoc()) {
            $phieuNhapModel = $this->createPNModel($row);
            array_push($phieuNhapList, $phieuNhapModel);
        }
        return $phieuNhapList;
    }


    public function multiFilterModel($from, $limit, $filterName, $dateFrom, $dateTo, $filterStatus)
    {
        $phieuNhapList = [];
        $query = "SELECT phieunhap.* FROM phieunhap
          JOIN users ON phieunhap.user_id = users.id
          WHERE 1=1";
        $args = [];

        if (!empty($filterName)) {
            $query .= " AND users.name LIKE ?";
            $filterParam = "%" . $filterName . "%";
            array_push($args, $filterParam);
        }

        if ($filterStatus !== null && $filterStatus != "") {
            $query .= " AND phieunhap.trangThai = ?";
            array_push($args, $filterStatus);
        }

        if (!empty($dateFrom)) {
            $query .= " AND phieunhap.phieuNhapDate >= ?";
            array_push($args, $dateFrom);
        }

        if (!empty($dateTo)) {
            $query .= " AND phieunhap.phieuNhapDate <= ?";
            array_push($args, $dateTo);
        }



        $query .= " LIMIT ?, ?";
        array_push($args, $from, $limit);

        $rs = DatabaseConnection::executeQuery($query, ...$args);
        while ($row = $rs->fetch_assoc()) {
            $phieuNhapModel = $this->createPNModel($row);
            array_push($phieuNhapList, $phieuNhapModel);
        }

        return $phieuNhapList;
    }

    public function countFilteredModel($filterName, $dateFrom, $dateTo, $filterStatus)
    {
        $query = "SELECT COUNT(*) AS total FROM phieunhap
          JOIN users ON phieunhap.user_id = users.id
          WHERE 1=1";
        $args = [];

        if (!empty($filterName)) {
            $query .= " AND users.name LIKE ?";
            $filterParam = "%" . $filterName . "%";
            array_push($args, $filterParam);
        }

        if ($filterStatus !== null && $filterStatus != "") {
            $query .= " AND phieunhap.trangThai = ?";
            array_push($args, $filterStatus);
        }

        if (!empty($dateFrom)) {
            $query .= " AND phieunhap.phieuNhapDate >= ?";
            array_push($args, $dateFrom);
        }

        if (!empty($dateTo)) {
            $query .= " AND phieunhap.phieuNhapDate <= ?";
            array_push($args, $dateTo);
        }

        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $row = $rs->fetch_assoc();
        return $row['total'];
    }
}
