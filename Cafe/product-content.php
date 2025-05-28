<?php
// Read product data from product.json
$products = [];
$productFile = 'product.json';
if (file_exists($productFile)) {
    $jsonData = file_get_contents($productFile);
    $products = json_decode($jsonData, true);
}
?>
<?php
require_once 'config.php';
requireLogin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Products</title>
    <link rel="stylesheet" href="css/dashboard.css" />
    <link rel="icon" href="images/logo.jpg?=2" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <style>
        /* existing styles unchanged */
        .product-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
        }
        .product-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            width: 220px;
            padding: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: box-shadow 0.3s ease;
            position: relative;
        }
        .product-card:hover {
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        .product-card img {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .product-name {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 8px;
            text-align: center;
            color: #333;
        }
        .product-price {
            font-size: 1rem;
            color: #F279D2;
            margin-bottom: 12px;
        }
        .btn-add-cart, .btn-edit, .btn-delete {
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 700;
            width: 100%;
            margin-bottom: 5px;
            transition: background-color 0.3s ease;
        }
        .btn-add-cart {
            background-color: #F279D2;
            color: white;
        }
        .btn-add-cart:hover {
            background-color: #d66bbf;
        }
        .btn-edit {
            background-color: #4CAF50;
            color: white;
        }
        .btn-edit:hover {
            background-color: #3e8e41;
        }
        .btn-delete {
            background-color: #f44336;
            color: white;
        }
        .btn-delete:hover {
            background-color: #da190b;
        }
        /* Modal styles */
        #productModal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0; top: 0;
            width: 100%; height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }
        #productModalContent {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.3);
        }
        #productModalContent label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        #productModalContent input {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }
        #productModalContent .modal-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        #productModalContent button {
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            font-weight: 700;
            cursor: pointer;
        }
        #btnSaveProduct {
            background-color: #F279D2;
            color: white;
        }
        #btnSaveProduct:hover {
            background-color: #d66bbf;
        }
        #btnCancelProduct {
            background-color: #777;
            color: white;
        }
        #btnCancelProduct:hover {
            background-color: #555;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="logo"></div>
        <ul class="menu">
            <li>
                <a href="dashboard.html" class="sidebar-link">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="profile-content.php" class="sidebar-link">
                    <i class="fas fa-user"></i>
                    <span>Profile</span>
                </a>
            </li>
            <li>
                <a href="orderlist.php" class="sidebar-link">
                    <i class="fas fa-list"></i>
                    <span>Order History</span>
                </a>
            </li>
            <li class="active">
                <a href="product-content.php" class="sidebar-link">
                    <i class="fa-brands fa-product-hunt"></i>
                    <span>Products</span>
                </a>
            </li>
            <li>
                <a href="account-create.php" class="sidebar-link">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Add Account</span>
                </a>
            </li>
            <li class="logout">
                <a href="logout.php">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Logout</span>
                </a>
            </li>
            <li>
                <a href="home.php" class="sidebar-link">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="main-content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Primary</span>
                <h2>Products</h2>
            </div>
            <div class="user--info">
                <div class="search--box">
                    <i class="fa-solid fa-search"></i>
                    <input type="text" placeholder="Search products..." id="productSearch" />
                </div>
                <img src="images/logo.jpg" alt="Logo" />
            </div>
        </div>
        <div class="card--container">
            <button class="btn-add-cart btn-add" id="btnAddProduct" style="margin-bottom: 20px;">Add Product</button>
            <div class="product-list" id="productList">
                <?php foreach ($products as $product): ?>
                    <div class="product-card" data-name="<?php echo htmlspecialchars(strtolower($product['name'])); ?>">
                        <img src="images/<?php echo htmlspecialchars(basename($product['image'])); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                        <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
                        <div class="product-price">₱<?php echo number_format($product['price'], 2); ?></div>
                        <button class="btn-edit" data-id="<?php echo $product['id']; ?>">Edit</button>
                        <button class="btn-delete" data-id="<?php echo $product['id']; ?>">Delete</button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Modal for Add/Edit Product -->
    <div id="productModal">
        <div id="productModalContent">
            <h3 id="modalTitle">Add Product</h3>
            <form id="productForm" enctype="multipart/form-data">
                <input type="hidden" id="productId" />
                <label for="productName">Name</label>
                <input type="text" id="productName" name="name" required />
                <label for="productPrice">Price</label>
                <input type="number" id="productPrice" name="price" min="0.01" step="0.01" required />
                <label for="productImage">Image File</label>
                <input type="file" id="productImage" name="image" accept="image/*" />
                <div class="modal-buttons">
                    <button type="submit" id="btnSaveProduct">Save</button>
                    <button type="button" id="btnCancelProduct">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const productModal = document.getElementById('productModal');
        const productForm = document.getElementById('productForm');
        const modalTitle = document.getElementById('modalTitle');
        const btnCancelProduct = document.getElementById('btnCancelProduct');
        const productIdInput = document.getElementById('productId');
        const productNameInput = document.getElementById('productName');
        const productPriceInput = document.getElementById('productPrice');
        const productImageInput = document.getElementById('productImage');
        const productList = document.getElementById('productList');
        const productSearch = document.getElementById('productSearch');

        function showAddProductModal() {
            modalTitle.textContent = 'Add Product';
            productIdInput.value = '';
            productNameInput.value = '';
            productPriceInput.value = '';
            productImageInput.value = '';
            productModal.style.display = 'block';
        }

        function showEditProductModal(id) {
            const productCard = document.querySelector(`.btn-edit[data-id="${id}"]`).closest('.product-card');
            productIdInput.value = id;
            productNameInput.value = productCard.querySelector('.product-name').textContent;
            productPriceInput.value = parseFloat(productCard.querySelector('.product-price').textContent.replace('₱', ''));
            productImageInput.value = '';
            modalTitle.textContent = 'Edit Product';
            productModal.style.display = 'block';
        }

        function closeModal() {
            productModal.style.display = 'none';
        }

        function attachProductCardListeners() {
            document.querySelectorAll('.btn-edit').forEach(button => {
                button.addEventListener('click', () => {
                    showEditProductModal(button.getAttribute('data-id'));
                });
            });
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.getAttribute('data-id');
                    if (confirm('Are you sure you want to delete this product?')) {
                        deleteProduct(id);
                    }
                });
            });
        }

        function deleteProduct(id) {
            const formData = new FormData();
            formData.append('id', id);
            fetch('product-delete.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Product deleted successfully');
                    location.reload();
                } else {
                    alert('Failed to delete product: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                alert('Error deleting product');
                console.error(error);
            });
        }

        productForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const id = productIdInput.value;
            const name = productNameInput.value.trim();
            const price = parseFloat(productPriceInput.value);
            const imageFile = productImageInput.files[0];

            if (!name || price <= 0) {
                alert('Please fill in all fields with valid values.');
                return;
            }

            const formData = new FormData();
            formData.append('name', name);
            formData.append('price', price);
            if (imageFile) {
                formData.append('image', imageFile);
            }

            let url = 'product-add.php';
            if (id) {
                url = 'product-edit.php';
                formData.append('id', id);
            }

            fetch(url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Product saved successfully');
                    closeModal();
                    location.reload();
                } else {
                    alert('Failed to save product: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                alert('Error saving product');
                console.error(error);
            });
        });

        btnCancelProduct.addEventListener('click', () => {
            closeModal();
        });

        productSearch.addEventListener('input', () => {
            const filter = productSearch.value.toLowerCase().trim();
            document.querySelectorAll('.product-card').forEach(card => {
                const name = card.getAttribute('data-name');
                if (name && name.includes(filter)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });

        document.getElementById('btnAddProduct').addEventListener('click', showAddProductModal);

            });
        

        // Add "Add Product" button dynamically
        const addProductBtn = document.getElementById('btnAddProduct');
        addProductBtn.addEventListener('click', showAddProductModal);

        // Attach listeners on page load
        attachProductCardListeners();
    </script>
</body>
</html>
