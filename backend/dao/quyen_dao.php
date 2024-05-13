<?php

namespace backend\dao;

use backend\services\DatabaseConnection;
use backend\models\QuyenModel;

class QuyenDAO
{
    private static $instance;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new QuyenDAO();
        }
        return self::$instance;
    }

    private function createQuyenModel($rs)
    {
        $maQuyen = $rs['maQuyen'];
        $tenQuyen = $rs['tenQuyen'];
        return new QuyenModel($maQuyen, $tenQuyen);
    }

    public function readDatabase(): array
    {
        $quyenList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM quyen");
        while ($row = $rs->fetch_assoc()) {
            $quyenModel = $this->createQuyenModel($row);
            array_push($quyenList, $quyenModel);
        }
        return $quyenList;
    }

    public function getAll(): array
    {
        $quyenList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM quyen ORDER BY CAST(SUBSTRING(maQuyen, 2) AS UNSIGNED) ASC;");
        while ($row = $rs->fetch_assoc()) {
            $quyenModel = $this->createQuyenModel($row);
            array_push($quyenList, $quyenModel);
        }
        return $quyenList;
    }

    public function getById($maQuyen)
    {
        $query = "SELECT * FROM quyen WHERE maQuyen = ?";
        $result = DatabaseConnection::executeQuery($query, $maQuyen);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row) {
                return $this->createQuyenModel($row);
            }
        }
        return null;
    }

    public function insert($data): int
    {
        $query = "INSERT INTO quyen (maQuyen, tenQuyen) VALUES (?, ?)";
        $args = [
            $data->getMaQuyen(),
            $data->getTenQuyen(),
        ];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function update($data): int
    {
        $query = "UPDATE quyen SET tenQuyen = ? WHERE maQuyen = ?";
        $args = [
            $data->getTenQuyen(),
            $data->getMaQuyen(),
        ];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function delete($maQuyen): int
    {
        $query = "DELETE FROM quyen WHERE maQuyen = ?";
        return DatabaseConnection::executeUpdate($query, $maQuyen);
    }
}
