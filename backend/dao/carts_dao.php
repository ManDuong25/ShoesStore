<?php

namespace backend\dao;

use Exception;
use backend\interfaces\DAOInterface;
use InvalidArgumentException;
use backend\models\CartsModel;
use backend\services\DatabaseConnection;

class CartsDAO implements DAOInterface
{
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new CartsDAO();
        }
        return self::$instance;
    }
    public function readDatabase(): array
    {
        $cartsList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM carts");
        while ($row = $rs->fetch_assoc()) {
            $cartsModel = $this->createCartsModel($row);
            array_push($cartsList, $cartsModel);
        }
        return $cartsList;
    }
    private function createCartsModel($rs)
    {
        $id = $rs['id'];
        $userId = $rs['user_id'];
        $productId = $rs['product_id'];
        $quantity = $rs['quantity'];
        $sizeId = $rs['size_id'];
        $importPrice = $rs['import_price'];
        return new CartsModel($id, $userId, $productId, $quantity, $sizeId, $importPrice);
    }
    public function getAll(): array
    {
        $cartsList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM carts");
        while ($row = $rs->fetch_assoc()) {
            $cartsModel = $this->createCartsModel($row);
            array_push($cartsList, $cartsModel);
        }
        return $cartsList;
    }

    public function getById($id)
    {
        $query = "SELECT * FROM carts WHERE id = ?";
        $result = DatabaseConnection::executeQuery($query, $id);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row) {
                return $this->createCartsModel($row);
            }
        }
        return null;
    }

    public function insert($cartsModel): int
    {
        $query = "INSERT INTO carts (user_id, product_id, quantity, size_id, import_price) VALUES (?, ?, ?, ?, ?)";
        $args = [$cartsModel->getUserId(), $cartsModel->getProductId(), $cartsModel->getQuantity(), $cartsModel->getSizeId(), $cartsModel->getImportPrice()];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function update($cartsModel): int
    {
        $query = "UPDATE carts SET user_id = ?, product_id = ?, quantity = ?, size_id = ?, import_price = ? WHERE id = ?";
        $args = [$cartsModel->getUserId(), $cartsModel->getProductId(), $cartsModel->getQuantity(), $cartsModel->getSizeId(), $cartsModel->getImportPrice(), $cartsModel->getId()];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function delete($id): int
    {
        $query = "DELETE FROM carts WHERE id = ?";
        return DatabaseConnection::executeUpdate($query, $id);
    }

    public function search(string $condition, $columnNames): array
    {
        if (empty($condition)) {
            throw new InvalidArgumentException("Search condition cannot be empty or null");
        }
        $query = "";
        if ($columnNames === null || count($columnNames) === 0) {
            $query = "SELECT * FROM carts WHERE id LIKE ? OR user_id LIKE ? OR product_id LIKE ? OR quantity LIKE ? OR size_id LIKE ?";
            $args = array_fill(0,  5, "%" . $condition . "%");
        } else if (count($columnNames) === 1) {
            $column = $columnNames[0];
            $query = "SELECT * FROM carts WHERE $column LIKE ?";
            $args = ["%" . $condition . "%"];
        } else {
            $query = "SELECT * FROM carts WHERE " . implode(" LIKE ? OR ", $columnNames) . " LIKE ?";
            $args = array_fill(0, count($columnNames), "%" . $condition . "%");
        }
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $cartsList = [];
        while ($row = $rs->fetch_assoc()) {
            $cartsModel = $this->createCartsModel($row);
            array_push($cartsList, $cartsModel);
        }
        if (count($cartsList) === 0) {
            throw new Exception("No records found for the given condition: " . $condition);
        }
        return $cartsList;
    }


    public function getDetailedCartInfoByUserId($userId): array
    {
        $query = "
            SELECT 
                carts.id AS cart_id, 
                products.id AS product_id, 
                products.image, 
                products.name, 
                carts.import_price, 
                carts.quantity, 
                sizes.id AS size_id,
                sizes.name AS size_name 
            FROM carts
            INNER JOIN products ON carts.product_id = products.id
            INNER JOIN sizes ON carts.size_id = sizes.id
            WHERE carts.user_id = ?
    ";

        $rs = DatabaseConnection::executeQuery($query, $userId);
        $detailedCartsList = [];
        while ($row = $rs->fetch_assoc()) {
            $detailedCartsList[] = [
                'cart_id' => $row['cart_id'],
                'product_id' => $row['product_id'],
                'product_image' => $row['image'],
                'product_name' => $row['name'],
                'import_price' => $row['import_price'],
                'cart_quantity' => $row['quantity'],
                'size_id' => $row['size_id'],
                'size_name' => $row['size_name']
            ];
        }
        return $detailedCartsList;
    }

    public function getCartListByProductId($productId)
    {
        $query = "SELECT * FROM carts WHERE product_id = ?";
        $rs = DatabaseConnection::executeQuery($query, $productId);
        $orderItemsList = [];
        while ($row = $rs->fetch_assoc()) {
            $orderItemsModel = $this->createCartsModel($row);
            array_push($orderItemsList, $orderItemsModel);
        }
        return $orderItemsList;
    }
}
