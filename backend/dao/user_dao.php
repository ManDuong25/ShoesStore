<?php

namespace backend\dao;

use Exception;
use backend\interfaces\DAOInterface;
use InvalidArgumentException;
use backend\models\UserModel;
use backend\services\DatabaseConnection;
use Symfony\Component\VarDumper\VarDumper;

class UserDAO implements DAOInterface
{
    private static $instance;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new UserDAO();
        }
        return self::$instance;
    }

    public function readDatabase(): array
    {
        $userList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM users");
        while ($row = $rs->fetch_assoc()) {
            $userModel = $this->createUserModel($row);
            array_push($userList, $userModel);
        }
        return $userList;
    }

    public function getByMaNhomQuyen($maNhomQuyen): array
    {
        $userList = [];
        $query = "SELECT * FROM users WHERE maNhomQuyen = ?";
        $rs = DatabaseConnection::executeQuery($query, $maNhomQuyen);
        while ($row = $rs->fetch_assoc()) {
            $userModel = $this->createUserModel($row);
            array_push($userList, $userModel);
        }
        return $userList;
    }


    private function createUserModel($rs)
    {
        $id = $rs['id'];
        $username = $rs['username'];
        $password = $rs['password'];
        $email = $rs['email'];
        $name = $rs['name'];
        $phone = $rs['phone'];
        $gender = $rs['gender'];
        $image = $rs['image'];
        $maNhomQuyen = $rs['maNhomQuyen'];
        $address = $rs['address'];
        $status = strtoupper($rs['status']);
        $forgotToken = $rs['forgotToken'];
        $activeToken = $rs['activeToken'];
        $create_at = $rs['create_at'];
        $update_at = $rs['update_at'];
        return new UserModel($id, $username, $password, $email, $name, $phone, $gender, $image, $maNhomQuyen, $status, $address, $forgotToken, $activeToken, $create_at, $update_at);
    }

    public function getAll(): array
    {
        $userList = [];
        $rs = DatabaseConnection::executeQuery("SELECT * FROM users");
        while ($row = $rs->fetch_assoc()) {
            $userModel = $this->createUserModel($row);
            array_push($userList, $userModel);
        }
        return $userList;
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM users WHERE id = ?";
        $result = DatabaseConnection::executeQuery($sql, $id);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row) {
                return $this->createUserModel($row);
            }
        }
        return null;
    }

    public function insert($user): int
    {
        $insertSql = "INSERT INTO users (username, password, email, name, phone, gender, image, maNhomQuyen, address, status, forgotToken, activeToken, create_at, update_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        if ($user->getGender() === "male") {
            $genderValue = "0";
        } elseif ($user->getGender() === "female") {
            $genderValue = "1";
        } else {
            $genderValue = "0";
        }
        $args = [
            $user->getUsername(),
            $user->getPassword(),
            $user->getEmail(),
            $user->getName(),
            $user->getPhone(),
            (int) $genderValue,
            $user->getImage(),
            $user->getMaNhomQuyen(),
            $user->getAddress(),
            strtolower($user->getStatus()),
            $user->getForgotToken(),
            $user->getActiveToken(),
            $user->getCreateAt(),
            $user->getUpdateAt()
        ];
        return DatabaseConnection::executeUpdate($insertSql, ...$args);
    }

    public function update($user): int
    {
        $updateSql = "UPDATE users SET username = ?, password = ?, email = ?, name = ?, phone = ?, gender = ?, image = ?, maNhomQuyen = ?, address = ?, status = ?, forgotToken = ?, activeToken = ?, create_at = ?, update_at = ? WHERE id = ?";
        $args = [
            $user->getUsername(),
            $user->getPassword(),
            $user->getEmail(),
            $user->getName(),
            $user->getPhone(),
            $user->getGender(),
            $user->getImage(),
            $user->getMaNhomQuyen(),
            $user->getAddress(),
            strtoupper($user->getStatus()),
            $user->getForgotToken(),
            $user->getActiveToken(),
            $user->getCreateAt(),
            $user->getUpdateAt(),
            $user->getId()
        ];
        return DatabaseConnection::executeUpdate($updateSql, ...$args);
    }

    public function delete(int $id): int
    {
        $deleteSql = "DELETE FROM users WHERE id = ?";
        return DatabaseConnection::executeUpdate($deleteSql, $id);
    }

    public function search(string $condition, $columnNames): array
    {
        if (empty($condition)) {
            throw new InvalidArgumentException("Search condition cannot be empty or null");
        }
        $query = "";
        if ($columnNames === null || count($columnNames) === 0) {
            $query = "SELECT * FROM users WHERE id LIKE ? OR username LIKE ? OR email LIKE ? OR name LIKE ? OR phone LIKE ? OR gender LIKE ? OR maNhomQuyen LIKE ? OR address LIKE ? OR status LIKE ?";
            $args = array_fill(0, 9, "%" . $condition . "%");
        } else if (count($columnNames) === 1) {
            $column = $columnNames[0];
            $query = "SELECT * FROM users WHERE $column LIKE ?";
            $args = ["%" . $condition . "%"];
        } else {
            $query = "SELECT * FROM users WHERE " . implode(" LIKE ? OR ", $columnNames) . " LIKE ?";
            $args = array_fill(0, count($columnNames), "%" . $condition . "%");
        }
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $userList = [];
        while ($row = $rs->fetch_assoc()) {
            $userModel = $this->createUserModel($row);
            array_push($userList, $userModel);
        }
        return $userList;
    }



    public function countAllModels()
    {
        $query = "SELECT COUNT(*) AS total FROM users;";
        $rs = DatabaseConnection::executeQuery($query);
        $row = $rs->fetch_assoc();
        return $row['total'];
    }

    public function paginationTech($from, $limit)
    {
        $userItemsList = [];
        $query = "SELECT * FROM users LIMIT ?, ?;";
        $args = [
            $from + 1,
            $limit
        ];
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        while ($row = $rs->fetch_assoc()) {
            $userItemModel = $this->createUserModel($row);
            array_push($userItemsList, $userItemModel);
        }
        return $userItemsList;
    }

    public function filterByEmail($from, $limit, $email): array
    {
        $userList = [];
        $query = "SELECT * FROM users WHERE email LIKE ? LIMIT ?, ?";
        $args = ["%" . $email . "%", $from, $limit];
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        while ($row = $rs->fetch_assoc()) {
            $userModel = $this->createUserModel($row);
            array_push($userList, $userModel);
        }
        return $userList;
    }

    public function countFilterByEmail($email): int
    {
        $query = "SELECT COUNT(*) AS total FROM users WHERE email LIKE ?";
        $args = ["%" . $email . "%"];
        $rs = DatabaseConnection::executeQuery($query, ...$args);
        $row = $rs->fetch_assoc();
        return (int) $row['total'];
    }
}
