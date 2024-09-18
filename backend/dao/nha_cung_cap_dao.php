<?php

namespace backend\dao;

use Exception;
use backend\interfaces\DAOInterface;
use InvalidArgumentException;
use backend\models\NhaCungCapModel;
use backend\services\DatabaseConnection;

class NhaCungCapDAO implements DAOInterface
{
    private static $instance;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new NhaCungCapDAO();
        }
        return self::$instance;
    }

    public function readDatabase(): array
    {
        $nccList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM nhancungcap");
        while ($row = $rs->fetch_assoc()) {
            $nccModel = $this->createNCCModel($row);
            array_push($nccList, $nccModel);
        }
        return $nccList;
    }

    private function createNCCModel($rs)
    {
        $maNCC = $rs['maNCC'];
        $ten = $rs['ten'];
        $diaChi = $rs['diaChi'];
        $sdt = $rs['sdt'];
        $email = $rs['email'];
        $trangThai = $rs['trangThai'];
        return new NhaCungCapModel($maNCC, $ten, $diaChi, $sdt, $email, $trangThai);
    }

    public function getAll(): array
    {
        $nccList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM nhacungcap");
        while ($row = $rs->fetch_assoc()) {
            $nccModel = $this->createNCCModel($row);
            array_push($nccList, $nccModel);
        }
        return $nccList;
    }

    public function getAllActive(): array
    {
        $nccList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM nhacungcap WHERE trangThai = 1");
        while ($row = $rs->fetch_assoc()) {
            $nccModel = $this->createNCCModel($row);
            array_push($nccList, $nccModel);
        }
        return $nccList;
    }

    public function getById($id)
    {
        $query = "SELECT * FROM nhacungcap WHERE maNCC = ?";
        $result = DatabaseConnection::executeQuery($query, $id);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row) {
                return $this->createNCCModel($row);
            }
        }
        return null;
    }

    public function getActiveProducts()
    {
        $query = "SELECT * FROM nhaCungCap WHERE trangThai = 1";
        $rs = DatabaseConnection::executeQuery($query);
        $productList = [];
        while ($row = $rs->fetch_assoc()) {
            $productModel = $this->createNCCModel($row);
            array_push($productList, $productModel);
        }
        return $productList;
    }

    public function insert($data): int
    {
        $query = "INSERT INTO nhacungcap (ten, diaChi, sdt, email, trangThai) VALUES (?, ?, ?, ?, ?)";
        $args = [
            $data->getTen(),
            $data->getDiaChi(),
            $data->getSdt(),
            $data->getEmail(),
            $data->getTrangThai()
        ];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function update($data): int
    {
        $query = "UPDATE nhacungcap SET ten = ?, diaChi = ?, sdt = ?, email = ?, trangThai = ? WHERE maNCC = ?";
        $args = [
            $data->getTen(),
            $data->getDiaChi(),
            $data->getSdt(),
            $data->getEmail(),
            $data->getTrangThai(),
            $data->getMaNCC()
        ];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function delete(int $id): int
    {
        $query = "UPDATE nhacungcap SET trangThai = 0 WHERE maNCC = ?";
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
            $productModel = $this->createNCCModel($row);
            array_push($productList, $productModel);
        }
        if (count($productList) === 0) {
            return [];
        }
        return $productList;
    }


    public function countAllModels()
    {
        $query = "SELECT COUNT(*) AS total FROM nhacungcap;";
        $rs = DatabaseConnection::executeQuery($query);
        $row = $rs->fetch_assoc();
        return $row['total'];
    }

    public function paginationTech($from, $limit)
    {
        $userItemsList = [];
        $query = "SELECT * FROM nhacungcap LIMIT ?, ?;";
        $args = [
            $from,
            $limit
        ];
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        while ($row = $rs->fetch_assoc()) {
            $userItemModel = $this->createNCCModel($row);
            array_push($userItemsList, $userItemModel);
        }
        return $userItemsList;
    }

    public function filterByEmail($from, $limit, $email): array
    {
        $userList = [];
        $query = "SELECT * FROM nhacungcap WHERE email LIKE ? LIMIT ?, ?";
        $args = ["%" . $email . "%", $from, $limit];
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        while ($row = $rs->fetch_assoc()) {
            $userModel = $this->createNCCModel($row);
            array_push($userList, $userModel);
        }
        return $userList;
    }

    public function countFilterByEmail($email): int
    {
        $query = "SELECT COUNT(*) AS total FROM nhacungcap WHERE email LIKE ?";
        $args = ["%" . $email . "%"];
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $row = $rs->fetch_assoc();
        return (int) $row['total'];
    }
}
