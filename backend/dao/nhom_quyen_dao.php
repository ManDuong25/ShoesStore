<?php

namespace backend\dao;

use backend\services\DatabaseConnection;
use backend\models\NhomQuyenModel;

class NhomQuyenDAO
{
    private static $instance;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new NhomQuyenDAO();
        }
        return self::$instance;
    }

    private function createNhomQuyenModel($rs)
    {
        $maNhomQuyen = $rs['maNhomQuyen'];
        $tenNhomQuyen = $rs['tenNhomQuyen'];
        $trangThai = $rs['trangThai'];
        return new NhomQuyenModel($maNhomQuyen, $tenNhomQuyen, $trangThai);
    }

    public function readDatabase(): array
    {
        $nhomQuyenList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM nhomquyen");
        while ($row = $rs->fetch_assoc()) {
            $nhomQuyenModel = $this->createNhomQuyenModel($row);
            array_push($nhomQuyenList, $nhomQuyenModel);
        }
        return $nhomQuyenList;
    }

    public function getAll(): array
    {
        $nhomQuyenList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM nhomquyen WHERE trangThai = 1 ORDER BY CAST(SUBSTRING(maNhomQuyen, 3) AS UNSIGNED) ASC;");
        while ($row = $rs->fetch_assoc()) {
            $nhomQuyenModel = $this->createNhomQuyenModel($row);
            array_push($nhomQuyenList, $nhomQuyenModel);
        }
        return $nhomQuyenList;
    }

    public function getById($maNhomQuyen)
    {
        $query = "SELECT * FROM nhomquyen WHERE maNhomQuyen = ?";
        $result = DatabaseConnection::executeQuery($query, $maNhomQuyen);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row) {
                return $this->createNhomQuyenModel($row);
            }
        }
        return null;
    }

    public function insert($data): int
    {
        $query = "INSERT INTO nhomquyen (maNhomQuyen, tenNhomQuyen, trangThai) VALUES (?, ?, ?)";
        $args = [
            $data->getMaNhomQuyen(),
            $data->getTenNhomQuyen(),
            1
        ];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function update($data): int
    {
        $query = "UPDATE nhomQuyen SET tenNhomQuyen = ?, trangThai = ? WHERE maNhomQuyen = ?";
        $args = [
            $data->getTenNhomQuyen(),
            $data->getTrangThai(),
            $data->getMaNhomQuyen()
        ];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function delete($maNhomQuyen): int
    {
        $query = "UPDATE nhomquyen SET nhomquyen.trangThai = 0 WHERE maNhomQuyen = ?";
        return DatabaseConnection::executeUpdate($query, $maNhomQuyen);
    }

    public function getNhomQuyenByTenNhomQuyen($tenNhomQuyen) {
        $query = "SELECT * from nhomquyen WHERE tenNhomQuyen = ?";
        $result = DatabaseConnection::executeQuery($query, $tenNhomQuyen);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row) {
                return $this->createNhomQuyenModel($row);
            }
        }
        return null;
    }
}
