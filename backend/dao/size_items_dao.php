<?php

namespace backend\dao;

use Exception;
use backend\interfaces\DAOInterface;
use InvalidArgumentException;
use backend\models\SizeItemsModel;
use backend\services\DatabaseConnection;
class SizeItemsDAO implements DAOInterface
{
    private static $instance;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new SizeItemsDAO();
        }
        return self::$instance;
    }

    public function readDatabase(): array
    {
        $sizeItemList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM size_items");
        while ($row = $rs->fetch_assoc()) {
            $sizeItemModel = $this->createSizeItemModel($row);
            array_push($sizeItemList, $sizeItemModel);
        }
        return $sizeItemList;
    }

    public function getAll(): array
    {
        $sizeItemList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM size_items");
        while ($row = $rs->fetch_assoc()) {
            $sizeItemModel = $this->createSizeItemModel($row);
            array_push($sizeItemList, $sizeItemModel);
        }
        return $sizeItemList;
    }

    public function getById(int $id)
    {
        $query = "SELECT * FROM size_items WHERE id = ?";
        $result = DatabaseConnection::executeQuery($query, $id);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row) {
                return $this->createSizeItemModel($row);
            }
        }
        return null;
    }

    public function insert($sizeItem): int
    {
        $insertSql = "INSERT INTO size_items (product_id, size_id, quantity) VALUES (?, ?, ?)";
        $args = [
            $sizeItem->getProductId(),
            $sizeItem->getSizeId(),
            $sizeItem->getQuantity(),
        ];
        return DatabaseConnection::executeUpdate($insertSql, ...$args);
    }

    public function update($sizeItem): int
    {
        $updateSql = "UPDATE size_items SET product_id = ?, size_id = ?, quantity = ? WHERE id = ?";
        $args = [
            $sizeItem->getProductId(),
            $sizeItem->getSizeId(),
            $sizeItem->getQuantity(),
            $sizeItem->getId(),
        ];
        return DatabaseConnection::executeUpdate($updateSql, ...$args);
    }

    private function createSizeItemModel($rs)
    {
        $id = $rs['id'];
        $productId = $rs['product_id'];
        $sizeId = $rs['size_id'];
        $quantity = $rs['quantity'];

        return new SizeItemsModel($id, $productId, $sizeId, $quantity);
    }


    public function delete($id): int
    {
        $deleteSql = "DELETE FROM size_items WHERE id = ?";
        return DatabaseConnection::executeUpdate($deleteSql, $id);
    }

    public function search(string $condition, $columnNames): array
    {
        if (empty($condition)) {
            throw new InvalidArgumentException("Search condition cannot be empty or null");
        }
        $query = "";
        if ($columnNames === null || count($columnNames) === 0) {
            $query = "SELECT * FROM size_items WHERE id LIKE ? OR product_id LIKE ? OR size_id LIKE ? OR quantity LIKE ?";
            $args = array_fill(0, 4, "%" . $condition . "%");
        } else if (count($columnNames) === 1) {
            $column = $columnNames[0];
            $query = "SELECT * FROM size_items WHERE $column LIKE ?";
            $args = ["%" . $condition . "%"];
        } else {
            $query = "SELECT * FROM size_items WHERE " . implode(" LIKE ? OR ", $columnNames) . " LIKE ?";
            $args = array_fill(0, count($columnNames), "%" . $condition . "%");
        }
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $sizeItemList = [];
        while ($row = $rs->fetch_assoc()) {
            $sizeItemModel = $this->createSizeItemModel($row);
            array_push($sizeItemList, $sizeItemModel);
        }
        return $sizeItemList;
    }

    public function countAllModels()
    {
        $query = "SELECT COUNT(*) AS total FROM size_items;";
        $rs = DatabaseConnection::executeQuery($query);
        $row = $rs->fetch_assoc();
        return $row['total'];
    }

    public function paginationTech($from, $limit)
    {
        $sizeItemsList = [];
        $query = "SELECT * FROM size_items LIMIT ?, ?;";
        $args = [
            $from,
            $limit
        ];
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        while ($row = $rs->fetch_assoc()) {
            $sizeItemModel = $this->createSizeItemModel($row);
            array_push($sizeItemsList, $sizeItemModel);
        }
        return $sizeItemsList;
    }


    public function filterByName($from, $limit, $name)
    {
        $inventoryList = [];
        $query = "
        SELECT 
            p.image AS Image, 
            p.name AS ProductName, 
            c.name AS Category, 
            s.name AS Size, 
            si.quantity AS Quantity,
            si.id AS Id
        FROM 
            products AS p
        INNER JOIN 
            size_items AS si ON p.id = si.product_id
        INNER JOIN 
            categories AS c ON p.category_id = c.id
        INNER JOIN 
            sizes AS s ON si.size_id = s.id
        WHERE 
            LOWER(p.name) LIKE CONCAT('%', LOWER(?), '%')
        LIMIT ?, ?;
        ";
        $args = [
            $name,
            $from,
            $limit
        ];
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        while ($row = $rs->fetch_assoc()) {
            $image = $row['Image'];
            $productName = $row['ProductName'];
            $category = $row['Category'];
            $size = $row['Size'];
            $quantity = $row['Quantity'];
            $id = $row['Id'];

            // Thêm các giá trị vào mảng kết quả
            $inventoryItem = [
                'image' => $image,
                'productName' => $productName,
                'category' => $category,
                'size' => $size,
                'quantity' => $quantity,
                'id' => $id
            ];
            array_push($inventoryList, $inventoryItem);
        }
        return $inventoryList;
    }


    public function countFilterByName($name)
    {
        $query = "
        SELECT 
            COUNT(*) AS Total
        FROM 
            products AS p
        INNER JOIN 
            size_items AS si ON p.id = si.product_id
        WHERE 
            LOWER(p.name) LIKE LOWER(?)";
        $args = ["%$name%"];
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $row = $rs->fetch_assoc();
        return $row['Total'];
    }
}
