<?php
ob_start();

use backend\bus\ProductBUS;
use backend\bus\SizeBUS;
use backend\bus\SizeItemsBUS;
use backend\bus\CategoriesBUS;
use backend\services\session;

$categoriesList = CategoriesBUS::getInstance()->getAllModels();
$size = SizeBUS::getInstance()->getAllModels();
$sizeItems = SizeItemsBUS::getInstance()->getAllModels();
$products = ProductBUS::getInstance()->getAllModels();

?>

<div id="header" style="  background-color: rgb(18, 15, 40); ">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE ?>/css/product.css" />
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE ?>/css/product_slider.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <?php layouts("header") ?>
</div>

<div id="content">
    <div class="carousel">
        <div class="list">
            <div class="item active">
                <img src="<?php echo _WEB_HOST_TEMPLATE ?>/images/680098.jpg" alt="">
                <div class="content">
                    <div class="author">My Shoes Store</div>
                    <div class="title">FILLO</div>
                    <div class="topic">Introduce</div>
                    <div class="des">
                        Nơi mà niềm đam mê thể thao và phong cách thời trang hiện đại hòa quyện.
                        Không chỉ là một điểm bán giày, mà còn là nguồn cảm hứng cho những người yêu thể thao.
                    </div>
                    <div class="buttons">
                        <button>SUBCRIBE</button>
                        <button>CONTACT US</button>
                    </div>
                </div>
            </div>

            <div class="item">
                <img src="<?php echo _WEB_HOST_TEMPLATE ?>/images/680102.jpg" alt="">
                <div class="content">
                    <div class="author">My Shoes Store</div>
                    <div class="title">FILLO</div>
                    <div class="topic">Introduce</div>
                    <div class="des">
                        Cam kết mang đến cho bạn những đôi giày chất lượng cao, từ các thương hiệu nổi tiếng như
                        Nike,
                        Adidas, và Puma, đến những thương hiệu mới nổi đầy sáng tạo.
                    </div>
                    <div class="buttons">
                        <button>SUBCRIBE</button>
                        <button>CONTACT US</button>
                    </div>
                </div>
            </div>

            <div class="item">
                <img src="<?php echo _WEB_HOST_TEMPLATE ?>/images/Air-Jordan-Shoes-Photo.jpg" alt="">
                <div class="content">
                    <div class="author">My Shoes Store</div>
                    <div class="title">FILLO</div>
                    <div class="topic">Introduce</div>
                    <div class="des">
                        Mỗi đôi giày tại cửa hàng đều được chọn lọc kỹ càng, đảm bảo sự thoải mái, độ bền và phong
                        cách.
                        Với đa dạng mẫu mã và màu sắc, bạn chắc chắn sẽ tìm thấy đôi giày phù hợp với mình.
                    </div>
                    <div class="buttons">
                        <button>SUBCRIBE</button>
                        <button>CONTACT US</button>
                    </div>
                </div>
            </div>

            <div class="item">
                <img src="<?php echo _WEB_HOST_TEMPLATE ?>/images/Air-Jordan-Shoes-Picture.jpg" alt="">
                <div class="content">
                    <div class="author">My Shoes Store</div>
                    <div class="title">FILLO</div>
                    <div class="topic">Introduce</div>
                    <div class="des">
                        Đội ngũ nhân viên thân thiện và chuyên nghiệp của chúng tôi luôn sẵn sàng tư vấn để bạn có
                        thể
                        chọn được đôi giày tốt nhất.
                        Chính sách đổi trả linh hoạt và dịch vụ sau bán hàng chu đáo sẽ làm bạn hài lòng.
                    </div>
                    <div class="buttons">
                        <button>SUBCRIBE</button>
                        <button>CONTACT US</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="thumbnail">
            <div class="item">
                <img src="<?php echo _WEB_HOST_TEMPLATE ?>/images/680102.jpg" alt="">
                <div class="content">
                    <div class="title">FILLO</div>
                    <div class="des">Tôn Chỉ</div>
                </div>
            </div>

            <div class="item">
                <img src="<?php echo _WEB_HOST_TEMPLATE ?>/images/Air-Jordan-Shoes-Photo.jpg" alt="">
                <div class="content">
                    <div class="title">FILLO</div>
                    <div class="des">Đặc Điểm</div>
                </div>
            </div>

            <div class="item">
                <img src="<?php echo _WEB_HOST_TEMPLATE ?>/images/Air-Jordan-Shoes-Picture.jpg" alt="">
                <div class="content">
                    <div class="title">FILLO</div>
                    <div class="des">Dịch Vụ</div>
                </div>
            </div>

            <div class="item active">
                <img src="<?php echo _WEB_HOST_TEMPLATE ?>/images/680098.jpg" alt="">
                <div class="content">
                    <div class="title">FILLO</div>
                    <div class="des">FILLO Slogan</div>
                </div>
            </div>
        </div>

        <div class="arrows">
            <button id="prev">
                < </button>
                    <button id="next"> > </button>
        </div>
    </div>

    <div class="con_product">
        <form method="POST">
            <div class="psearch">
                <input class="searchInput" type="text" name="searchbox" placeholder="Nhập sản phẩm bạn muốn tìm kiếm" required>
                <button class="custom-btn btn-14" name="searchBtn" id="searchBtnId" onchange="this.form.submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
        </form>

        <div class="container_filter_pagination">
            <div class="filter">
                <fieldset>
                    <legend>Category</legend>
                    <input type="radio" checked name="category" value="0">All products
                    <br>
                    <?php
                    foreach ($categoriesList as $category) {
                        echo '<input type="radio" name="category" value="' . $category->getId() . '">' . $category->getName() . '<br>';
                    }
                    ?>
                </fieldset>
                <fieldset>
                    <legend>Gender</legend>
                    <input type="radio" checked name="gender" value="-1">All Gender
                    <br>
                    <input type="radio" name="gender" value="0">Male
                    <br>
                    <input type="radio" name="gender" value="1">Female
                </fieldset>
                <fieldset>
                    <legend>Price</legend>
                    <label for="min_price">Minimum Price:</label>
                    <input type="number" name="min_price" min="0" placeholder="100000">
                    <br>
                    <label for="max_price">Maximum Price:</label>
                    <input type="number" name="max_price" min="<?php echo $_POST['min_price'] ?? '' ?>" placeholder="100000">
                </fieldset>
            </div>
            <div class="container_pagination">

                <div class="areaproduct">
                    <?php
                    if (isPost()) {
                        $filterAll = filter();
                        if (isset($filterAll['thisPage']) && isset($filterAll['limit'])) {
                            $thisPage = $filterAll['thisPage'];
                            $limit = $filterAll['limit'];
                            $beginGet = $limit * ($thisPage - 1);

                            $filterName = $filterAll['filterName'];
                            $filterCategory = $filterAll['filterCategory'];
                            $filterGender = $filterAll['filterGender'];
                            $filterPriceFrom = $filterAll['filterPriceFrom'];
                            $filterPriceTo = $filterAll['filterPriceTo'];

                            if (
                                ($filterName == "") &&
                                ($filterCategory == "") &&
                                ($filterGender == "") &&
                                ($filterPriceFrom == "") &&
                                ($filterPriceTo == "")
                            ) {
                                $totalQuantity = ProductBUS::getInstance()->countAllModels();
                                $listSP = ProductBUS::getInstance()->paginationTech($beginGet, $limit);
                                $listSPArray = array_map(function ($product) {
                                    return $product->toArray();
                                }, $listSP);
                                ob_end_clean();
                                header('Content-Type: application/json');
                                echo json_encode(['listProducts' => $listSPArray, 'thisPage' => $thisPage, 'limit' => $limit, 'totalQuantity' => $totalQuantity, 'beginGet' => $beginGet]);
                                exit;
                            } else {
                                $listSP = ProductBUS::getInstance()->multiFilter($beginGet, $limit, $filterName, $filterCategory, $filterGender, $filterPriceFrom, $filterPriceTo);
                                $totalQuantity = ProductBUS::getInstance()->countFilteredProducts($filterName, $filterCategory, $filterGender, $filterPriceFrom, $filterPriceTo);
                                $totalQuantity = isset($totalQuantity) ? $totalQuantity : 0;
                                $listSPArray = array_map(function ($product) {
                                    return $product->toArray();
                                }, $listSP);
                                ob_end_clean();
                                header('Content-Type: application/json');
                                echo json_encode(['listProducts' => $listSPArray, 'thisPage' => $thisPage, 'limit' => $limit, 'totalQuantity' => $totalQuantity, 'beginGet' => $beginGet]);
                                exit;
                            }
                        }
                    }
                    ?>
                </div>
                <div style="margin-bottom: 2rem;" class="areaPage">

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let thisPage = 1;
        let limit = 12;
        let areaProduct = document.querySelector('.areaproduct');
        let areaPage = document.querySelector('.areaPage');
        let pageIndexButtons;
        let productList = document.querySelectorAll('pitem');


        let searchInput = document.querySelector('.searchInput');
        let categoryRadios = document.querySelectorAll('input[name="category"]');
        let genderRadios = document.querySelectorAll('input[name="gender"]');
        let inputPriceFrom = document.querySelector('input[name="min_price"]');
        console.log(inputPriceFrom)
        let inputPriceTo = document.querySelector('input[name="max_price"]');

        let filterName = "";
        let filterCategory = "";
        let filterGender = "";
        let filterPriceFrom = "";
        let filterPriceTo = "";

        // Hàm tải dữ liệu cho trang hiện tại
        function loadData(thisPage, limit, filterName, filterCategory, filterGender, filterPriceFrom, filterPriceTo) {
            fetch('http://localhost/ShoesStore/frontend/?module=indexphp&action=product', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'thisPage=' + thisPage + '&limit=' + limit + '&filterName=' + filterName + '&filterCategory=' + filterCategory + '&filterGender=' + filterGender + '&filterPriceFrom=' + filterPriceFrom + '&filterPriceTo=' + filterPriceTo
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    areaProduct.innerHTML = toHTMLProductList(data.listProducts);
                    areaPage.innerHTML = toHTMLPagination(data.totalQuantity, data.thisPage, data.limit);
                    totalPage = Math.ceil(data.totalQuantity / data.limit);
                    console.log(filterGender)
                    changePageIndexLogic(totalPage, data.totalQuantity, data.limit);
                });
        }

        loadData(thisPage, limit, filterName, filterCategory, filterGender, filterPriceFrom, filterPriceTo);

        function toHTMLProductList(products) {
            let html = '';
            products.forEach(product => {
                html += `
                    <div class="pitem">
                        <div class="imgitem">
                            <img src="${product.image}" alt="">
                        </div>
                        <div class="content">
                            <div class="name">${product.name}</div>
                            <div class="price">${product.price}<sup>đ</sup></div>
                            <button class="see_product">
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <a href="?module=indexphp&action=singleproduct&id=${product.id}">SEE MORE</a>
                            </button>
                        </div>
                    </div>
                `;
            });
            return html;
        }

        function toHTMLPagination(totalQuantity, thisPage, limit) {
            buttonPrev = `<button class="custom-btn btn-7 prev" id="prevPage" name="prevPage"><span><</span></button>`;
            buttonNext = `<button class="custom-btn btn-7 next" id="nextPage" name="nextPage"><span>></span></button>`;
            pageIndexButtons = '';
            for (i = 1; i <= Math.ceil(totalQuantity / limit); i++) {
                if (i == thisPage) {
                    pageIndexButtons += `<button class="custom-btn pageIndex active"><span>${i}</span></button>`;
                } else {
                    pageIndexButtons += `<button class="custom-btn pageIndex btn-7 active"><span>${i}</span></button>`;
                }
            }
            return buttonPrev + pageIndexButtons + buttonNext;
        }

        function changePageIndexLogic(totalPage, totalQuantity, limit) {
            if (totalQuantity < limit && totalQuantity > 0) {
                document.getElementById('prevPage').classList.add('hideBtn');
                document.getElementById('nextPage').classList.add('hideBtn');
            } else if (totalQuantity > limit) {
                let pageIndexButtons = document.querySelectorAll('.pageIndex');

                if (thisPage == 1) {
                    document.getElementById('prevPage').classList.add('hideBtn');
                } else {
                    document.getElementById('prevPage').classList.remove('hideBtn');
                }

                if (thisPage == totalPage) {
                    document.getElementById('nextPage').classList.add('hideBtn');
                } else {
                    document.getElementById('nextPage').classList.remove('hideBtn');
                }

                document.getElementById('prevPage').addEventListener('click', function() {
                    thisPage--;
                    loadData(thisPage, limit, filterName, filterCategory, filterGender, filterPriceFrom, filterPriceTo);
                })

                document.getElementById('nextPage').addEventListener('click', function() {
                    thisPage++;
                    loadData(thisPage, limit, filterName, filterCategory, filterGender, filterPriceFrom, filterPriceTo);
                })

                pageIndexButtons.forEach(function(button) {
                    button.addEventListener('click', function() {
                        thisPage = parseInt(this.textContent);
                        loadData(thisPage, limit, filterName, filterCategory, filterGender, filterPriceFrom, filterPriceTo);
                    });
                });
            } else if (totalQuantity == 0) {
                document.getElementById('prevPage').classList.add('hideBtn');
                document.getElementById('nextPage').classList.add('hideBtn');
                areaPage.innerHTML = `
                <h1> Không tồn tại sản phẩm nào </h1>
                `
            }
        }

        searchInput.addEventListener('input', function() {
            filterName = searchInput.value;
            thisPage = 1;
            loadData(thisPage, limit, filterName, filterCategory, filterGender, filterPriceFrom, filterPriceTo);
        })

        categoryRadios.forEach(function(categoryRadio) {
            categoryRadio.addEventListener('click', function() {
                filterCategory = categoryRadio.value;
                thisPage = 1;
                loadData(thisPage, limit, filterName, filterCategory, filterGender, filterPriceFrom, filterPriceTo);
            });
        })

        genderRadios.forEach(function(genderRadio) {
            genderRadio.addEventListener('click', function() {
                filterGender = genderRadio.value;
                thisPage = 1;
                loadData(thisPage, limit, filterName, filterCategory, filterGender, filterPriceFrom, filterPriceTo);
            })
        })

        inputPriceFrom.addEventListener('change', function() {
            filterPriceFrom = inputPriceFrom.value;
            thisPage = 1;
            loadData(thisPage, limit, filterName, filterCategory, filterGender, filterPriceFrom, filterPriceTo);
        })

        inputPriceTo.addEventListener('change', function() {
            filterPriceTo = inputPriceTo.value;
            thisPage = 1;
            loadData(thisPage, limit, filterName, filterCategory, filterGender, filterPriceFrom, filterPriceTo);
        })

    });
</script>

<div id="footer">
    <?php layouts("footer") ?>
</div>