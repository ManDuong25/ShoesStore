<?php

namespace backend\dao;

use backend\services\DatabaseConnection;
use backend\models\ChiTietQuyenModel;

class ChiTietQuyenDAO
{
    private static $instance;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new ChiTietQuyenDAO();
        }
        return self::$instance;
    }

    private function createChiTietQuyenModel($rs)
    {
        $maNhomQuyen = $rs['maNhomQuyen'];
        $maChucNang = $rs['maChucNang'];
        $maQuyen = $rs['maQuyen'];
        return new ChiTietQuyenModel($maNhomQuyen, $maChucNang, $maQuyen);
    }

    public function readDatabase(): array
    {
        $chiTietQuyenList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * from chitietquyen ORDER BY CAST(SUBSTRING(maNhomQuyen, 3) AS UNSIGNED) ASC;");
        while ($row = $rs->fetch_assoc()) {
            $chiTietQuyenModel = $this->createChiTietQuyenModel($row);
            array_push($chiTietQuyenList, $chiTietQuyenModel);
        }
        return $chiTietQuyenList;
    }

    public function getAll(): array
    {
        $chiTietQuyenList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * from chitietquyen ORDER BY CAST(SUBSTRING(maNhomQuyen, 3) AS UNSIGNED) ASC;");
        while ($row = $rs->fetch_assoc()) {
            $chiTietQuyenModel = $this->createChiTietQuyenModel($row);
            array_push($chiTietQuyenList, $chiTietQuyenModel);
        }
        return $chiTietQuyenList;
    }

    public function getById($maNhomQuyen, $maQuyen, $maChucNang)
    {
        $query = "SELECT * from chitietquyen where maNhomQuyen = ? and maQuyen = ? and maChucNang = ?";
        $result = DatabaseConnection::executeQuery($query, $maNhomQuyen);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row) {
                return $this->createChiTietQuyenModel($row);
            }
        }
        return null;
    }

    public function insert($data): int
    {
        $query = "INSERT INTO chitietquyen (maNhomQuyen, maChucNang, maQuyen) VALUES (?, ?, ?)";
        $args = [
            $data->getMaNhomQuyen(),
            $data->getMaChucNang(),
            $data->getMaQuyen()
        ];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function update($data): int
    {
        $query = "UPDATE chitietquyen SET maQuyen = ? maChucNang = ? WHERE maNhomQuyen = ?";
        $args = [
            $data->getMaQuyen(),
            $data->getMaChucNang(),
            $data->getMaNhomQuyen()
        ];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function delete($maNhomQuyen): int
    {
        $query = "DELETE FROM chitietquyen WHERE maNhomQuyen = ?";
        return DatabaseConnection::executeUpdate($query, $maNhomQuyen);
    }

    public function getAllWithMaNhomQuyen($maNhomQuyen)
    {
        $chiTietQuyenList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * from chitietquyen where maNhomQuyen = ? ORDER BY CAST(SUBSTRING(maNhomQuyen, 3) AS UNSIGNED) ASC;", $maNhomQuyen);
        while ($row = $rs->fetch_assoc()) {
            $chiTietQuyenModel = $this->createChiTietQuyenModel($row);
            array_push($chiTietQuyenList, $chiTietQuyenModel);
        }
        return $chiTietQuyenList;
    }

    public function getMaxNumberFromMaNhomQuyen(): int
    {
        $query = "SELECT CAST(SUBSTRING(maNhomQuyen, 3) AS UNSIGNED) AS max_number FROM nhomquyen ORDER BY max_number DESC LIMIT 1";
        $result = DatabaseConnection::executeQuery($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return (int)$row['max_number'];
        }
        return 0;
    }
}
