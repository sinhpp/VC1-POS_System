
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) : ?>



<div class="container">
    <header>
        <h2>Edit Product</h2>
    </header>

    <main class="grid-container">
        <section class="general-info">
            <form action="/products/update/<?= htmlspecialchars($product['id']); ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">

                <h3>General Information</h3>
                <label>Name Product</label>
                <input type="text" placeholder="Enter product name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

                <p>Category</p>
                <select id="categorySelect" name="category" required>
                    <option value="Uniform" <?= $product['category'] == 'Uniform' ? 'selected' : '' ?>>Uniform</option>
                    <option value="T-shirt" <?= $product['category'] == 'T-shirt' ? 'selected' : '' ?>>T-shirt</option>
                    <option value="Sport Clothes" <?= $product['category'] == 'Sport Clothes' ? 'selected' : '' ?>>Sport Clothes</option>
                    <option value="Clothes" <?= $product['category'] == 'Clothes' ? 'selected' : '' ?>>Clothes</option>
                    <option value="Shoes" <?= $product['category'] == 'Shoes' ? 'selected' : '' ?>>Shoes</option>
                    <option value="Bag" <?= $product['category'] == 'Bag' ? 'selected' : '' ?>>Bag</option>
                    <option value="Shirt" <?= $product['category'] == 'Shirt' ? 'selected' : '' ?>>Shirt</option>
                    <option value="Nightwear" <?= $product['category'] == 'Nightwear' ? 'selected' : '' ?>>Nightwear</option>
                    <option value="Student Material" <?= $product['category'] == 'Student Material' ? 'selected' : '' ?>>Student Material</option>
                    <option value="Other" <?= $product['category'] == 'Other' ? 'selected' : '' ?>>Other</option>
                </select>

                <label>Description Product</label>
                <textarea placeholder="Enter product description" name="descriptions" required><?= htmlspecialchars($product['descriptions'] ?? '') ?></textarea>


                <div class="size-gender">
            <div class="size">
                <label>Size</label>
                <select name="size" id="size">
                    <option value="S" <?= $product['size'] == 'S' ? 'selected' : '' ?>>S</option>
                    <option value="M" <?= $product['size'] == 'M' ? 'selected' : '' ?>>M</option>
                    <option value="L" <?= $product['size'] == 'L' ? 'selected' : '' ?>>L</option>
                    <option value="XL" <?= $product['size'] == 'XL' ? 'selected' : '' ?>>XL</option>
                    <option value="XXL" <?= $product['size'] == 'XXL' ? 'selected' : '' ?>>XXL</option>
                </select>
            </div>

            <div class="gender">
                <label>Gender</label>
                <select name="gender" id="gender">
                    <option value="Men" <?= $product['gender'] == 'Men' ? 'selected' : '' ?>>Men</option>
                    <option value="Women" <?= $product['gender'] == 'Women' ? 'selected' : '' ?>>Women</option>
                </select>
            </div>
        </div>

        
                <div class="image-preview" id="imagePreview">
                    <section class="upload-img">
                     
                        <input type="file" id="fileUpload" name="image" accept="image/*">
                        <img src="<?= !empty($product['image']) ? '/' . htmlspecialchars($product['image']) : '' ?>" alt="Product Image" id="previewImg" style="max-width: 150px; <?= empty($product['image']) ? 'display: none;' : '' ?>">
                    </section>
                </div>
            </form>
        </section>

        <section class="pricing-stock">
            <h3>Pricing And Stocks</h3>
            <label>Base Pricing</label>
            <input type="number" placeholder="$0.00" name="price" value="<?= htmlspecialchars($product['price']) ?>" required min="0" step="0.01">

            <label>Stock</label>
            <input type="number" id="stockInput" placeholder="Enter stock quantity" name="stock" value="<?= htmlspecialchars($product['stock']) ?>" required min="0" step="1">

            <label>Stock Adjustment</label>
            <input type="number" id="addStockInput" placeholder="Enter stock adjustment" name="add_stock" required min="0" step="1">

            <label>Discount</label>
            <input type="number" placeholder="Enter discount" name="discount" value="<?= htmlspecialchars($product['discount'] ?? '') ?>" min="0" step="0.01">

            <label>Discount Type</label>
            <input type="text" placeholder="Enter discount type" name="discount_type" value="<?= htmlspecialchars($product['discount_type'] ?? '') ?>">

            <label>Barcode:</label>
            <input type="text" class="form-control" name="barcode" value="<?= htmlspecialchars($product['barcode'] ?? '') ?>"/>
        </section>

        
        <div class="actions">
            <button type="submit" class="add"><?= isset($product) ? 'Update Product' : '➕ Add Product' ?></button>
        </div>
    </main>
</div>

</form>
</section>
</main>
<script>
        document.getElementById('fileUpload').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const previewImg = document.getElementById('previewImg');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                previewImg.style.display = 'none';
            }
        });

        function selectSize(button, size) {
            const sizeButtons = document.querySelectorAll('.size-options button');
            sizeButtons.forEach(btn => btn.classList.remove('selected'));
            button.classList.add('selected');
            document.getElementById('size').value = size;
        }

        function selectGender(button, gender) {
            const genderButtons = document.querySelectorAll('.gender-options button');
            genderButtons.forEach(btn => btn.classList.remove('selected'));
            button.classList.add('selected');
            document.getElementById('gender').value = gender;
        }
    </script>
<style>


    body {
            font-family: Arial, sans-serif;
            display: block;

            justify-content: center;
            align-items: center;
            height: 100vh;
         
        }
        .input-container {
            display: flex;
            justify-content: space-between;
        
            width: 100%;
        }
        .input-group1 {
            position: relative;
            display: block;
            flex-wrap: wrap;
            align-items: stretch;
        }
        .input-group {
            position: relative;
     
            flex-wrap: wrap;
            align-items: stretch;
        }
        .input-container label,
        .input-container input {
            flex: 1; /* Makes both take equal space */
        }

        .input-container label {
            white-space: nowrap; /* Prevents label from breaking */
        }

        .right-input {
            text-align: right; /* Aligns the second input to the right */
        }
        .table {
            width: 80%;
            margin-left: 30px;
        }
        .table th, .table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .table-striped tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }
        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }
        .table-dark {
            background-color: #343a40;
            color: white;
        }
        .badge.bg-success { background-color: #28a745; color: white; }
        .badge.bg-danger { background-color: #dc3545; color: white; }
        .btn {
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn-warning { background-color: #ffc107; color: black; }
        .btn-danger { background-color: #dc3545; color: white; }
        .btn:hover { background-color: #495057; }
        

.container {
    max-width: 70%;
    height: auto;
    margin-left: 26%; /* Sidebar adjustment */
    background: white;
    position: relative;
    margin-top:6%;
    border-radius: 8px;
   
}

 h2 {
    font-size: 30px;

}

.actions button {
    padding: 8px 12px;
    border: none;
    cursor: pointer;
    margin-left: 10px;
    border-radius: 5px;
}

.save {
    background: gray;
    color: white;
}

.add {
    position: relative;
    width: 450px;
    margin-top:10px;
    margin-bottom:50px;

    background: green;
    color: white;
}

.grid-container {
    display: grid;
    grid-template-columns: 2fr 1.5fr;
    gap: 10px;
}

section {

    padding: 30px;
    border-radius: 8px;
    box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;}

h3 {
    margin-bottom: 10px;
}

input, select, textarea {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button {
    width: 100%;
    padding: 8px;
    background: green;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 5px;
}

button:hover {
    background: darkgreen;
}

.size-gender {
    display: flex;
    justify-content: space-between;
}

.size-options, .gender-options {
    display: flex;
    gap: 5px;
}

.size-options button, .gender-options button{
    padding: 5px;
    border: 1px solid #ccc;
    background: white;
    color: gray; /* Text color set to black */
    cursor: pointer;
    border-radius: 5px;
}
.size-options button {
    width: 45px;
}
.gender-options button{
    width: 70px;
}

.size-options .selected, .gender-options .selected {
    background: green;
    color: white; /* Keep selected text white for better contrast */
}

.upload-img input {
    display: none;
}

.image-preview {
    margin-top: 10px;
    width: 100%;
    height: 150px;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #eaeaea;
    border-radius: 5px;
    overflow: hidden;
}

.image-preview img {
    max-width: 100%;
    max-height: 100%;
}
</style>
<script>
        function selectGender(button) {
            document.querySelectorAll(".gender-options button").forEach(btn => btn.classList.remove("selected"));
            button.classList.add("selected");
        }

        function selectSize(button) {
            document.querySelectorAll(".size-options button").forEach(btn => btn.classList.remove("selected"));
            button.classList.add("selected");
        }

        document.querySelector("#fileUpload").addEventListener("change", function(event) {
            const file = event.target.files[0];
            const preview = document.querySelector("#previewImg");
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = "block";
            };

            if (file) {
                reader.readAsDataURL(file);
            }
        });
    </script>
<?php 
else: 
    $this->redirect("/"); 
endif;   
?>