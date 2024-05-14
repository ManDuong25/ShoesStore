<?php

namespace backend\models;

class ProductModel
{
    private $id, $name, $categoryId, $price, $description, $image, $gender, $status, $giaNhap;

    public function __construct(
        $id,
        $name,
        $categoryId,
        $price,
        $description,
        $image,
        $gender,
        $status,
        $giaNhap
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->categoryId = $categoryId;
        $this->price = $price;
        $this->description = $description;
        $this->image = $image;
        $this->gender = $gender;
        $this->status = $status;
        $this->giaNhap = $giaNhap;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getCategoryId()
    {
        return $this->categoryId;
    }

    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getGiaNhap() {
        return $this->giaNhap;
    }

    public function setGiaNhap($giaNhap) {
        $this->giaNhap = $giaNhap;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'categoryId' => $this->categoryId,
            'price' => $this->price,
            'description' => $this->description,
            'image' => $this->image,
            'gender' => $this->gender,
            'status' => $this->status,
            'giaNhap' => $this->giaNhap,
        ];
    }

    public function toArrayDashBoard($categoryName)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'categoryName' => $categoryName,
            'price' => $this->price,
            'description' => $this->description,
            'image' => $this->image,
            'gender' => $this->gender,
            'status' => $this->status,
            'giaNhap' => $this->giaNhap,
        ];
    }
}
