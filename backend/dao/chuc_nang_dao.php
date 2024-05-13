<?php

namespace backend\dao;

use backend\services\DatabaseConnection;
use backend\models\ChucNangModel;

class ChucNangDAO
{
    private static $instance;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new ChucNangDAO();
        }
        return self::$instance;
    }

    private function createChucNangModel($rs)
    {
        $maChucNang = $rs['maChucNang'];
        $tenChucNang = $rs['tenChucNang'];
        return new ChucNangModel($maChucNang, $tenChucNang);
    }

    public function readDatabase(): array
    {
        $chucNangList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM chucnang");
        while ($row = $rs->fetch_assoc()) {
            $chucNangModel = $this->createChucNangModel($row);
            array_push($chucNangList, $chucNangModel);
        }
        return $chucNangList;
    }

    public function getAll(): array
    {
        $chucNangList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM chucnang ORDER BY CAST(SUBSTRING(maChucNang, 3) AS UNSIGNED) ASC;");
        while ($row = $rs->fetch_assoc()) {
            $chucNangModel = $this->createChucNangModel($row);
            array_push($chucNangList, $chucNangModel);
        }
        return $chucNangList;
    }

    public function getById($maChucNang)
    {
        $query = "SELECT * FROM chucnang WHERE maChucNang = ?";
        $result = DatabaseConnection::executeQuery($query, $maChucNang);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row) {
                return $this->createChucNangModel($row);
            }
        }
        return null;
    }

    public function insert($data): int
    {
        $query = "INSERT INTO chucnang (maChucNang, tenChucNang) VALUES (?, ?)";
        $args = [
            $data->getMaChucNang(),
            $data->getTenChucNang(),
        ];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }


    public function update($data): int
    {
        $query = "UPDATE chucnang SET tenChucNang = ? WHERE maChucNang = ?";
        $args = [
            $data->getTenChucNang(),
            $data->getMaChucNang(),
        ];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function delete($maChucNang): int
    {
        $query = "DELETE FROM chucnang WHERE maChucNang = ?";
        return DatabaseConnection::executeUpdate($query, $maChucNang);
    }
}
