<?php

namespace backend\dao;

use backend\models\StatisticModel;
use backend\models\OrdersModel;
use backend\models\CustomerStatisticModel;
use backend\models\ProductStatisticModel;
use backend\interfaces\DAOInterface;
use InvalidArgumentException;
use backend\services\DatabaseConnection;

class OrdersDAO implements DAOInterface
{
    private static $instance;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new OrdersDAO();
        }
        return self::$instance;
    }
    public function readDatabase(): array
    {
        $ordersList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM orders");
        while ($row = $rs->fetch_assoc()) {
            $ordersModel = $this->createOrdersModel($row);
            array_push($ordersList, $ordersModel);
        }
        return $ordersList;
    }
    private function createOrdersModel($rs)
    {
        $id = $rs['id'];
        $userId = $rs['user_id'];
        $orderDate = $rs['order_date'];
        $totalAmount = $rs['total_amount'];
        $customerName = $rs['customer_name'];
        $customerPhone = $rs['customer_phone'];
        $customerAddress = $rs['customer_address'];
        $status = $rs['status'];
        return new OrdersModel($id, $userId, $orderDate, $totalAmount, $customerName, $customerPhone, $customerAddress, $status);
    }

    public function getAll(): array
    {
        $ordersList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM orders");
        while ($row = $rs->fetch_assoc()) {
            $ordersModel = $this->createOrdersModel($row);
            array_push($ordersList, $ordersModel);
        }
        return $ordersList;
    }

    public function getById(int $id)
    {
        $query = "SELECT * FROM orders WHERE id = ?";
        $rs = DatabaseConnection::executeQuery($query, $id);
        if ($rs->num_rows > 0) {
            $row = $rs->fetch_assoc();
            if ($row) {
                return $this->createOrdersModel($row);
            }
        }
        return null;
    }

    public function getLastOrderId(): int
    {
        $sql = "SELECT MAX(id) as maxId FROM orders";
        $result = DatabaseConnection::getInstance()->getConnection()->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['maxId'];
        }
        return 0;
    }

    public function insert($ordersModel): int
    {
        $query = "INSERT INTO orders (user_id, order_date, total_amount, customer_name, customer_phone, customer_address, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $args = [
            $ordersModel->getUserId(),
            $ordersModel->getOrderDate(),
            $ordersModel->getTotalAmount(),
            $ordersModel->getCustomerName(),
            $ordersModel->getCustomerPhone(),
            $ordersModel->getCustomerAddress(),
            $ordersModel->getStatus()
        ];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function update($ordersModel): int
    {
        $query = "UPDATE orders SET user_id = ?, order_date = ?, total_amount = ?, customer_name = ?, customer_phone = ?, customer_address = ?, status = ? WHERE id = ?";
        $args = [
            $ordersModel->getUserId(),
            $ordersModel->getOrderDate(),
            $ordersModel->getTotalAmount(),
            $ordersModel->getCustomerName(),
            $ordersModel->getCustomerPhone(),
            $ordersModel->getCustomerAddress(),
            $ordersModel->getStatus(),
            $ordersModel->getId()
        ];
        return DatabaseConnection::executeUpdate($query, ...$args);
    }

    public function delete(int $id): int
    {
        $query = "DELETE FROM orders WHERE id = ?";
        return DatabaseConnection::executeUpdate($query, $id);
    }

    public function search(string $condition, $columnNames): array
    {
        if (empty($condition)) {
            throw new InvalidArgumentException("Search condition cannot be empty or null");
        }
        $query = "";
        if ($columnNames === null || count($columnNames) === 0) {
            $query = "SELECT * FROM orders WHERE id LIKE ? OR user_id LIKE ? OR order_date LIKE ? OR total_amount LIKE ? or customer_name LIKE ? or customer_phone LIKE ? or customer_address like ?";
            $args = array_fill(0, 5, "%" . $condition . "%");
        } else if (count($columnNames) === 1) {
            $column = $columnNames[0];
            $query = "SELECT * FROM orders WHERE $column LIKE ?";
            $args = ["%" . $condition . "%"];
        } else {
            $query = "SELECT * FROM orders WHERE " . implode(" LIKE ? OR ", $columnNames) . " LIKE ?";
            $args = array_fill(0, count($columnNames), "%" . $condition . "%");
        }
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $ordersList = [];
        while ($row = $rs->fetch_assoc()) {
            $ordersModel = $this->createOrdersModel($row);
            array_push($ordersList, $ordersModel);
        }
        return $ordersList;
    }

    public function getAllThongKeKH(): array
    {
        $thongKeList = [];
        $query = "SELECT
        u.id AS user_id,
        u.name AS customer_name,
        SUM(o.total_amount) AS total_purchase_amount
        FROM
            users u
        JOIN
            orders o ON u.id = o.user_id
        GROUP BY
            u.id, u.name
        ORDER BY
            total_purchase_amount DESC
        LIMIT 5;";
        $rs = DatabaseConnection::executeQuery($query);
        while ($row = $rs->fetch_assoc()) {
            $userId = $row['user_id'];
            $customerName = $row['customer_name'];
            $totalPurchaseAmount = $row['total_purchase_amount'];
            $thongKeModel = new CustomerStatisticModel($userId, $customerName, $totalPurchaseAmount);
            array_push($thongKeList, $thongKeModel);
        }
        return $thongKeList;
    }

    public function getByNgayTuNgayDenKH($ngayTu, $ngayDen): array
    {
        $thongKeList = [];
        $query = "SELECT
        u.id AS user_id,
        u.name AS customer_name,
        SUM(o.total_amount) AS total_purchase_amount
        FROM
            users u
        JOIN
            orders o ON u.id = o.user_id
        WHERE
            o.order_date >= ? AND o.order_date <= ?
        GROUP BY
            u.id, u.name
        ORDER BY
            total_purchase_amount DESC
        LIMIT 5;";
        $args = [
            $ngayTu,
            $ngayDen
        ];
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        while ($row = $rs->fetch_assoc()) {
            $userId = $row['user_id'];
            $customerName = $row['customer_name'];
            $totalPurchaseAmount = $row['total_purchase_amount'];
            $thongKeModel = new CustomerStatisticModel($userId, $customerName, $totalPurchaseAmount);
            array_push($thongKeList, $thongKeModel);
        }
        return $thongKeList;
    }

    public function getByNgayTuKH($ngayTu)
    {
        $thongKeList = [];
        $query = "SELECT
        u.id AS user_id,
        u.name AS customer_name,
        SUM(o.total_amount) AS total_purchase_amount
        FROM
            users u
        JOIN
            orders o ON u.id = o.user_id
        WHERE
            o.order_date >= ?
        GROUP BY
            u.id, u.name
        ORDER BY
            total_purchase_amount DESC
        LIMIT 5;";
        $rs = DatabaseConnection::executeQuery($query, $ngayTu);
        while ($row = $rs->fetch_assoc()) {
            $userId = $row['user_id'];
            $customerName = $row['customer_name'];
            $totalPurchaseAmount = $row['total_purchase_amount'];
            $thongKeModel = new CustomerStatisticModel($userId, $customerName, $totalPurchaseAmount);
            array_push($thongKeList, $thongKeModel);
        }
        return $thongKeList;
    }

    public function getByNgayDenKH($ngayDen)
    {
        $thongKeList = [];
        $query = "SELECT
        u.id AS user_id,
        u.name AS customer_name,
        SUM(o.total_amount) AS total_purchase_amount
        FROM
            users u
        JOIN
            orders o ON u.id = o.user_id
        WHERE
            o.order_date <= ?
        GROUP BY
            u.id, u.name
        ORDER BY
            total_purchase_amount DESC
        LIMIT 5;";
        $rs = DatabaseConnection::executeQuery($query, $ngayDen);
        while ($row = $rs->fetch_assoc()) {
            $userId = $row['user_id'];
            $customerName = $row['customer_name'];
            $totalPurchaseAmount = $row['total_purchase_amount'];
            $thongKeModel = new CustomerStatisticModel($userId, $customerName, $totalPurchaseAmount);
            array_push($thongKeList, $thongKeModel);
        }
        return $thongKeList;
    }

    public function getAllThongKeSP(): array
    {
        $thongKeList = [];
        $query = "SELECT
        p.id AS product_id,
        p.name AS product_name,
        SUM(oi.quantity) AS total_quantity_sold,
        SUM(oi.quantity * oi.price) AS total_revenue
    FROM
        orders o
    INNER JOIN
        order_items oi ON o.id = oi.order_id
    INNER JOIN
        products p ON oi.product_id = p.id
    GROUP BY
        p.id, p.name
    ORDER BY
        total_revenue DESC
    LIMIT 5;";
        $rs = DatabaseConnection::executeQuery($query);
        while ($row = $rs->fetch_assoc()) {
            $productId = $row['product_id'];
            $productName = $row['product_name'];
            $totalQuantitySold = $row['total_quantity_sold'];
            $totalAmount = $row['total_revenue'];
            $thongKeModel = new ProductStatisticModel($productId, $productName, $totalQuantitySold, $totalAmount);
            array_push($thongKeList, $thongKeModel);
        }
        return $thongKeList;
    }


    public function getByNgayTuNgayDenSP($ngayTu, $ngayDen): array
    {
        $thongKeList = [];
        $query = "SELECT
        p.id AS product_id,
        p.name AS product_name,
        SUM(oi.quantity) AS total_quantity_sold,
        SUM(oi.quantity * oi.price) AS total_revenue
    FROM
        orders o
    INNER JOIN
        order_items oi ON o.id = oi.order_id
    INNER JOIN
        products p ON oi.product_id = p.id
    WHERE
        o.order_date >= ? AND o.order_date < ? -- Thay đổi khoảng thời gian tại đây
    GROUP BY
        p.id, p.name
    ORDER BY
        total_revenue DESC
    LIMIT 5;";
        $args = [
            $ngayTu,
            $ngayDen
        ];
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        while ($row = $rs->fetch_assoc()) {
            $productId = $row['product_id'];
            $productName = $row['product_name'];
            $totalQuantitySold = $row['total_quantity_sold'];
            $totalAmount = $row['total_revenue'];
            $thongKeModel = new ProductStatisticModel($productId, $productName, $totalQuantitySold, $totalAmount);
            array_push($thongKeList, $thongKeModel);
        }
        return $thongKeList;
    }


    public function getByNgayTuSP($ngayTu)
    {
        $thongKeList = [];
        $query = "SELECT
        p.id AS product_id,
        p.name AS product_name,
        SUM(oi.quantity) AS total_quantity_sold,
        SUM(oi.quantity * oi.price) AS total_revenue
    FROM
        orders o
    INNER JOIN
        order_items oi ON o.id = oi.order_id
    INNER JOIN
        products p ON oi.product_id = p.id
    WHERE
        o.order_date >= ? 
    GROUP BY
        p.id, p.name
    ORDER BY
        total_revenue DESC
    LIMIT 5;";
        $rs = DatabaseConnection::executeQuery($query, $ngayTu);
        while ($row = $rs->fetch_assoc()) {
            $productId = $row['product_id'];
            $productName = $row['product_name'];
            $totalQuantitySold = $row['total_quantity_sold'];
            $totalAmount = $row['total_revenue'];
            $thongKeModel = new ProductStatisticModel($productId, $productName, $totalQuantitySold, $totalAmount);
            array_push($thongKeList, $thongKeModel);
        }
        return $thongKeList;
    }

    public function getByNgayDenSP($ngayDen)
    {
        $thongKeList = [];
        $query = "SELECT
        p.id AS product_id,
        p.name AS product_name,
        SUM(oi.quantity) AS total_quantity_sold,
        SUM(oi.quantity * oi.price) AS total_revenue
    FROM
        orders o
    INNER JOIN
        order_items oi ON o.id = oi.order_id
    INNER JOIN
        products p ON oi.product_id = p.id
    WHERE
        o.order_date <= ?
    GROUP BY
        p.id, p.name
    ORDER BY
        total_revenue DESC
    LIMIT 5;";
        $rs = DatabaseConnection::executeQuery($query, $ngayDen);
        while ($row = $rs->fetch_assoc()) {
            $productId = $row['product_id'];
            $productName = $row['product_name'];
            $totalQuantitySold = $row['total_quantity_sold'];
            $totalAmount = $row['total_revenue'];
            $thongKeModel = new ProductStatisticModel($productId, $productName, $totalQuantitySold, $totalAmount);
            array_push($thongKeList, $thongKeModel);
        }
        return $thongKeList;
    }

    public function getAllThongKeSPTheoLoai($category): array
    {
        $thongKeList = [];
        $query = "SELECT p.id AS product_id,
        p.name AS product_name,
        SUM(oi.quantity) AS total_quantity_sold,
        SUM(oi.quantity * oi.price) AS total_revenue
        FROM products p
        JOIN order_items oi ON p.id = oi.product_id
        JOIN orders o ON oi.order_id = o.id
        JOIN categories c ON p.category_id = c.id
        WHERE c.id = ?
        GROUP BY p.id, p.name
        ORDER BY total_revenue DESC
        LIMIT 5;";
        $rs = DatabaseConnection::executeQuery($query, $category);
        while ($row = $rs->fetch_assoc()) {
            $productId = $row['product_id'];
            $productName = $row['product_name'];
            $totalQuantitySold = $row['total_quantity_sold'];
            $totalAmount = $row['total_revenue'];
            $thongKeModel = new ProductStatisticModel($productId, $productName, $totalQuantitySold, $totalAmount);
            array_push($thongKeList, $thongKeModel);
        }
        return $thongKeList;
    }


    public function getByNgayTuNgayDenSPTheoLoai($ngayTu, $ngayDen, $category): array
    {
        $thongKeList = [];
        $query = "SELECT p.id AS product_id,
        p.name AS product_name,
        SUM(oi.quantity) AS total_quantity_sold,
        SUM(oi.quantity * oi.price) AS total_revenue
        FROM products p
        JOIN order_items oi ON p.id = oi.product_id
        JOIN orders o ON oi.order_id = o.id
        JOIN categories c ON p.category_id = c.id
        WHERE c.id = ?
        AND o.order_date BETWEEN ? AND ?
        GROUP BY p.id, p.name
        ORDER BY total_revenue DESC
        LIMIT 5;";
        $args = [
            $category,
            $ngayTu,
            $ngayDen
        ];
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        while ($row = $rs->fetch_assoc()) {
            $productId = $row['product_id'];
            $productName = $row['product_name'];
            $totalQuantitySold = $row['total_quantity_sold'];
            $totalAmount = $row['total_revenue'];
            $thongKeModel = new ProductStatisticModel($productId, $productName, $totalQuantitySold, $totalAmount);
            array_push($thongKeList, $thongKeModel);
        }
        return $thongKeList;
    }


    public function getByNgayTuSPTheoLoai($ngayTu, $category)
    {
        $thongKeList = [];
        $query = "SELECT p.id AS product_id,
        p.name AS product_name,
        SUM(oi.quantity) AS total_quantity_sold,
        SUM(oi.quantity * oi.price) AS total_revenue
        FROM products p
        JOIN order_items oi ON p.id = oi.product_id
        JOIN orders o ON oi.order_id = o.id
        JOIN categories c ON p.category_id = c.id
        WHERE c.id = ?
        AND o.order_date >= ?
        GROUP BY p.id, p.name
        ORDER BY total_revenue DESC
        LIMIT 5;";

        $args = [
            $category,
            $ngayTu,
        ];

        $rs = DatabaseConnection::executeQuery($query, ...$args);
        while ($row = $rs->fetch_assoc()) {
            $productId = $row['product_id'];
            $productName = $row['product_name'];
            $totalQuantitySold = $row['total_quantity_sold'];
            $totalAmount = $row['total_revenue'];
            $thongKeModel = new ProductStatisticModel($productId, $productName, $totalQuantitySold, $totalAmount);
            array_push($thongKeList, $thongKeModel);
        }
        return $thongKeList;
    }

    public function getByNgayDenSPTheoLoai($ngayDen, $category)
    {
        $thongKeList = [];
        $query = "SELECT p.id AS product_id,
        p.name AS product_name,
        SUM(oi.quantity) AS total_quantity_sold,
        SUM(oi.quantity * oi.price) AS total_revenue
        FROM products p
        JOIN order_items oi ON p.id = oi.product_id
        JOIN orders o ON oi.order_id = o.id
        JOIN categories c ON p.category_id = c.id
        WHERE c.id = ?
        AND o.order_date <= ?
        GROUP BY p.id, p.name
        ORDER BY total_revenue DESC
        LIMIT 5;";

        $args = [
            $category,
            $ngayDen,
        ];

        $rs = DatabaseConnection::executeQuery($query, ...$args);
        while ($row = $rs->fetch_assoc()) {
            $productId = $row['product_id'];
            $productName = $row['product_name'];
            $totalQuantitySold = $row['total_quantity_sold'];
            $totalAmount = $row['total_revenue'];
            $thongKeModel = new ProductStatisticModel($productId, $productName, $totalQuantitySold, $totalAmount);
            array_push($thongKeList, $thongKeModel);
        }
        return $thongKeList;
    }



    public function countAllModels()
    {
        $query = "SELECT COUNT(*) AS total FROM orders;";
        $rs = DatabaseConnection::executeQuery($query);
        $row = $rs->fetch_assoc();
        return $row['total'];
    }

    public function paginationTech($from, $limit)
    {
        $orderItemsList = [];
        $query = "
        SELECT 
            o.id,
            o.user_id,
            o.order_date,
            o.total_amount,
            o.customer_name,
            o.customer_phone,
            o.customer_address,
            o.status,
            pm.method_name AS payment_method
        FROM 
            orders o
        LEFT JOIN 
            payments p ON o.id = p.order_id
        LEFT JOIN 
            payment_methods pm ON p.method_id = pm.id
        LIMIT ?, ?;    
        ";
        $args = [
            $from,
            $limit
        ];
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        while ($row = $rs->fetch_assoc()) {
            $id = $row['id'];
            $userId = $row['user_id'];
            $orderDate = $row['order_date'];
            $totalAmount = $row['total_amount'];
            $customerName = $row['customer_name'];
            $customerPhone = $row['customer_phone'];
            $customerAddress = $row['customer_address'];
            $status = $row['status'];
            $paymentMethod = $row['payment_method'];

            $orderItem = [
                'id' => $id,
                'userId' => $userId,
                'orderDate' => $orderDate,
                'totalAmount' => $totalAmount,
                'customerName' => $customerName,
                'customerPhone' => $customerPhone,
                'customerAddress' => $customerAddress,
                'status' => $status,
                'paymentMethod' => $paymentMethod
            ];
            array_push($orderItemsList, $orderItem);
        }
        return $orderItemsList;
    }


    public function countFilteredModel($filterName, $dateFrom, $dateTo, $filterStatus)
    {
        $query = "SELECT COUNT(*) AS total FROM orders WHERE 1=1";
        $args = [];

        if (!empty($filterName)) {
            $query .= " AND customer_name LIKE ?";
            $args[] = "%" . $filterName . "%";
        }

        if (!empty($dateFrom)) {
            $query .= " AND order_date >= ?";
            $args[] = $dateFrom;
        }

        if (!empty($dateTo)) {
            $query .= " AND order_date <= ?";
            $args[] = $dateTo;
        }

        if (!empty($filterStatus)) {
            $query .= " AND status = ?";
            $args[] = $filterStatus;
        }

        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $row = $rs->fetch_assoc();
        return $row['total'];
    }


    public function multiFilterModel($from, $limit, $filterName, $dateFrom, $dateTo, $filterStatus)
    {
        $orderItemsList = [];
        $query = "
        SELECT 
            o.id,
            o.user_id,
            o.order_date,
            o.total_amount,
            o.customer_name,
            o.customer_phone,
            o.customer_address,
            o.status,
            pm.method_name AS payment_method
        FROM 
            orders o
        LEFT JOIN 
            payments p ON o.id = p.order_id
        LEFT JOIN 
            payment_methods pm ON p.method_id = pm.id
        WHERE 1=1
    ";
        $args = [];

        if (!empty($filterName)) {
            $query .= " AND o.customer_name LIKE ?";
            $args[] = "%" . $filterName . "%";
        }

        if (!empty($dateFrom)) {
            $query .= " AND o.order_date >= ?";
            $args[] = $dateFrom;
        }

        if (!empty($dateTo)) {
            $query .= " AND o.order_date <= ?";
            $args[] = $dateTo;
        }

        if (!empty($filterStatus)) {
            $query .= " AND o.status = ?";
            $args[] = $filterStatus;
        }

        $query .= " LIMIT ?, ?";
        $args[] = $from;
        $args[] = $limit;

        $rs = DatabaseConnection::executeQuery($query, ...$args);
        while ($row = $rs->fetch_assoc()) {
            $id = $row['id'];
            $userId = $row['user_id'];
            $orderDate = $row['order_date'];
            $totalAmount = $row['total_amount'];
            $customerName = $row['customer_name'];
            $customerPhone = $row['customer_phone'];
            $customerAddress = $row['customer_address'];
            $status = $row['status'];
            $paymentMethod = $row['payment_method'];

            $orderItem = [
                'id' => $id,
                'userId' => $userId,
                'orderDate' => $orderDate,
                'totalAmount' => $totalAmount,
                'customerName' => $customerName,
                'customerPhone' => $customerPhone,
                'customerAddress' => $customerAddress,
                'status' => $status,
                'paymentMethod' => $paymentMethod
            ];
            array_push($orderItemsList, $orderItem);
        }
        return $orderItemsList;
    }
}
