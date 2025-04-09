<?php
// require_once __DIR__.'/Database/Database.php';
// require_once __DIR__.'/Controllers/BarcodeController.php';

// $db = new Database(); // Your existing database class
// $controller = new BarcodeController($db);

// $action = $_GET['action'] ?? '';

// header('Content-Type: application/json');

// switch ($action) {
//     case 'scan':
//         $barcode = $_GET['code'] ?? '';
//         echo json_encode($controller->scan($barcode));
//         break;
        
//     case 'checkout':
//         $items = json_decode($_POST['items'], true);
//         $paymentType = $_POST['payment_type'] ?? '';
//         echo json_encode($controller->checkout($items, $paymentType));
//         break;
        
//     default:
//         echo json_encode(['success' => false, 'message' => 'Invalid action']);
// }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Products</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="container mx-auto my-8 p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold text-gray-700">POS Products</h1>
            <button id="massPrintButton" class="bg-green-500 text-white px-4 py-2 rounded shadow hover:bg-green-600">
                Mass Print Barcodes
            </button>
        </div>

        <!--Barcode Input -->
        <div class="mb-4">
            <input id="barcodeInput" type="text" class="px-4 py-2 border border-gray-300 rounded w-full" placeholder="Scan or enter barcode" autofocus>
        </div>

        <div id="errorMessage" class="text-red-500 hidden mb-4">Invalid barcode! Please try again.</div>

        <!-- Product Table -->
        <div class="overflow-x-auto shadow-md rounded-lg bg-white mb-4">
            <table class="min-w-full table-auto border-collapse border border-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 border-b">Image</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 border-b">Name</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 border-b">Brand</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 border-b">Barcode</th>
                        <th class="px-6 py-3 text-right text-sm font-medium text-gray-500 border-b">Price</th>
                        <th class="px-6 py-3 text-right text-sm font-medium text-gray-500 border-b">Stock</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 border-b">Category</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 border-b">Discount</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 border-b">Size</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 border-b">Gender</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 border-b">Action</th>
                    </tr>
                </thead>
                <tbody id="productList">
                    <!-- Product rows will be dynamically populated here -->
                </tbody>
            </table>
        </div>

        <!-- Add Product Button -->
        <div class="mb-4">
            <button id="addProductButton" class="bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-600">Add Product</button>
        </div>

        <!-- Layout 1: Modern Split Card -->
        <div id="addProductForm1" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center">
            <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-2xl transform transition-all duration-300 scale-95">
                <h3 class="text-xl font-bold mb-6 text-gray-800 text-center">Add New Product</h3>
                <div class="flex space-x-6">
                    <!-- Left Section -->
                    <div class="w-1/2 space-y-4">
                        <div>
                            <div class="flex space-x-2 mb-2">
                                <button id="toggleUrl1" class="flex-1 bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">URL</button>
                                <button id="toggleFile1" class="flex-1 bg-gray-300 text-gray-700 px-2 py-1 rounded hover:bg-gray-400">Upload</button>
                            </div>
                            <input id="productImageUrl1" type="text" placeholder="Image URL" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-300">
                            <input id="productImageFile1" type="file" accept="image/*" class="w-full px-3 py-2 border rounded-lg hidden">
                        </div>
                        <input id="productName1" type="text" placeholder="Product Name" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-300">
                        <input id="productBrand1" type="text" placeholder="Brand" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-300">
                        <input id="productPrice1" type="number" step="0.01" placeholder="Price" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-300">
                        <input id="productCategory1" type="text" placeholder="Category" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-300">
                        <input id="productStock1" type="number" placeholder="Stock" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-300">
                    </div>
                    <!-- Right Section -->
                    <div class="w-1/2 space-y-4">
                        <input id="productSize1" type="text" placeholder="Size" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-300">
                        <select id="productGender1" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-300">
                            <option value="unisex">Unisex</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                        <textarea id="productDescription1" placeholder="Description" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-300 h-24"></textarea>
                        <input id="productDiscount1" type="number" step="0.01" placeholder="Discount" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-300">
                        <select id="productDiscountType1" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-300">
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed Amount</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex space-x-4">
                    <input id="productBarcode1" type="text" placeholder="Barcode" class="flex-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-300">
                    <button id="saveProductButton1" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200">Save</button>
                    <button id="cancelButton1" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition duration-200">Cancel</button>
                </div>
            </div>
        </div>

        <!-- Layout 2: Split Slide-in -->
        <div id="addProductForm2" class="hidden fixed inset-y-0 right-0 w-full max-w-3xl bg-white shadow-2xl transform translate-x-full transition-transform duration-300">
            <div class="p-6 h-full flex">
                <!-- Left Section -->
                <div class="w-1/2 pr-6 border-r border-gray-200">
                    <h3 class="text-lg font-bold mb-6 text-gray-800">Basic Info</h3>
                    <div class="space-y-6">
                        <div>
                            <div class="flex space-x-2 mb-2">
                                <button id="toggleUrl2" class="flex-1 bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">URL</button>
                                <button id="toggleFile2" class="flex-1 bg-gray-300 text-gray-700 px-2 py-1 rounded hover:bg-gray-400">Upload</button>
                            </div>
                            <input id="productImageUrl2" type="text" placeholder="Image URL" class="w-full px-4 py-2 border-b-2 border-gray-300 focus:border-blue-500 outline-none transition-colors">
                            <input id="productImageFile2" type="file" accept="image/*" class="w-full px-4 py-2 border-b-2 border-gray-300 hidden">
                        </div>
                        <input id="productName2" type="text" placeholder="Product Name" class="w-full px-4 py-2 border-b-2 border-gray-300 focus:border-blue-500 outline-none transition-colors">
                        <input id="productBrand2" type="text" placeholder="Brand" class="w-full px-4 py-2 border-b-2 border-gray-300 focus:border-blue-500 outline-none transition-colors">
                        <input id="productPrice2" type="number" step="0.01" placeholder="Price" class="w-full px-4 py-2 border-b-2 border-gray-300 focus:border-blue-500 outline-none transition-colors">
                        <input id="productCategory2" type="text" placeholder="Category" class="w-full px-4 py-2 border-b-2 border-gray-300 focus:border-blue-500 outline-none transition-colors">
                        <input id="productStock2" type="number" placeholder="Stock" class="w-full px-4 py-2 border-b-2 border-gray-300 focus:border-blue-500 outline-none transition-colors">
                    </div>
                </div>
                <!-- Right Section -->
                <div class="w-1/2 pl-6">
                    <h3 class="text-lg font-bold mb-6 text-gray-800">Details</h3>
                    <div class="space-y-6">
                        <input id="productSize2" type="text" placeholder="Size" class="w-full px-4 py-2 border-b-2 border-gray-300 focus:border-blue-500 outline-none transition-colors">
                        <select id="productGender2" class="w-full px-4 py-2 border-b-2 border-gray-300 focus:border-blue-500 outline-none transition-colors">
                            <option value="unisex">Unisex</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                        <textarea id="productDescription2" placeholder="Description" class="w-full px-4 py-2 border-b-2 border-gray-300 focus:border-blue-500 outline-none transition-colors h-24"></textarea>
                        <input id="productDiscount2" type="number" step="0.01" placeholder="Discount" class="w-full px-4 py-2 border-b-2 border-gray-300 focus:border-blue-500 outline-none transition-colors">
                        <select id="productDiscountType2" class="w-full px-4 py-2 border-b-2 border-gray-300 focus:border-blue-500 outline-none transition-colors">
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed Amount</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 p-6 bg-white border-t flex justify-between">
                <input id="productBarcode2" type="text" placeholder="Barcode" class="w-2/3 px-4 py-2 border rounded-full focus:ring-2 focus:ring-blue-300">
                <div class="space-x-4">
                    <button id="saveProductButton2" class="bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 transition duration-200">Save</button>
                    <button id="cancelButton2" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-full hover:bg-gray-300 transition duration-200">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const barcodeInput = document.getElementById('barcodeInput');
        const errorMessage = document.getElementById('errorMessage');
        const productList = document.getElementById('productList');
        const addProductButton = document.getElementById('addProductButton');
        const addProductForm1 = document.getElementById('addProductForm1');
        const addProductForm2 = document.getElementById('addProductForm2');
        const saveProductButton1 = document.getElementById('saveProductButton1');
        const saveProductButton2 = document.getElementById('saveProductButton2');
        const cancelButton1 = document.getElementById('cancelButton1');
        const cancelButton2 = document.getElementById('cancelButton2');

        const products = [];

        // Choose which layout to use (uncomment one)
        const activeForm = addProductForm1; // Layout 1
        // const activeForm = addProductForm2; // Layout 2

        // Image input toggle for Layout 1
        const toggleUrl1 = document.getElementById('toggleUrl1');
        const toggleFile1 = document.getElementById('toggleFile1');
        const productImageUrl1 = document.getElementById('productImageUrl1');
        const productImageFile1 = document.getElementById('productImageFile1');

        toggleUrl1.addEventListener('click', () => {
            productImageUrl1.classList.remove('hidden');
            productImageFile1.classList.add('hidden');
            toggleUrl1.classList.add('bg-blue-500', 'text-white');
            toggleUrl1.classList.remove('bg-gray-300', 'text-gray-700');
            toggleFile1.classList.add('bg-gray-300', 'text-gray-700');
            toggleFile1.classList.remove('bg-blue-500', 'text-white');
        });

        toggleFile1.addEventListener('click', () => {
            productImageUrl1.classList.add('hidden');
            productImageFile1.classList.remove('hidden');
            toggleFile1.classList.add('bg-blue-500', 'text-white');
            toggleFile1.classList.remove('bg-gray-300', 'text-gray-700');
            toggleUrl1.classList.add('bg-gray-300', 'text-gray-700');
            toggleUrl1.classList.remove('bg-blue-500', 'text-white');
        });

        // Image input toggle for Layout 2
        const toggleUrl2 = document.getElementById('toggleUrl2');
        const toggleFile2 = document.getElementById('toggleFile2');
        const productImageUrl2 = document.getElementById('productImageUrl2');
        const productImageFile2 = document.getElementById('productImageFile2');

        toggleUrl2.addEventListener('click', () => {
            productImageUrl2.classList.remove('hidden');
            productImageFile2.classList.add('hidden');
            toggleUrl2.classList.add('bg-blue-500', 'text-white');
            toggleUrl2.classList.remove('bg-gray-300', 'text-gray-700');
            toggleFile2.classList.add('bg-gray-300', 'text-gray-700');
            toggleFile2.classList.remove('bg-blue-500', 'text-white');
        });

        toggleFile2.addEventListener('click', () => {
            productImageUrl2.classList.add('hidden');
            productImageFile2.classList.remove('hidden');
            toggleFile2.classList.add('bg-blue-500', 'text-white');
            toggleFile2.classList.remove('bg-gray-300', 'text-gray-700');
            toggleUrl2.classList.add('bg-gray-300', 'text-gray-700');
            toggleUrl2.classList.remove('bg-blue-500', 'text-white');
        });

        // Barcode scan handler
        barcodeInput.addEventListener('input', function () {
            const barcodeValue = barcodeInput.value.trim();
            if (barcodeValue) {
                const product = products.find(p => p.barcode === barcodeValue);
                if (product) {
                    displayProduct(product);
                    errorMessage.classList.add('hidden');
                } else {
                    errorMessage.classList.remove('hidden');
                }
            }
        });

        // Display product details in table
        function displayProduct(product) {
            const row = document.createElement('tr');
            row.classList.add('border-b', 'hover:bg-gray-100');
            const barcodeCanvasId = `barcode-${product.barcode}`;
            
            row.innerHTML = `
                <td class="px-6 py-4">
                    <img src="${product.image}" alt="Product Image" class="w-12 h-12 rounded">
                </td>
                <td class="px-6 py-4 text-gray-700">${product.name}</td>
                <td class="px-6 py-4 text-gray-700">${product.brand}</td>
                <td class="px-6 py-4 text-gray-700">
                    <canvas id="${barcodeCanvasId}"></canvas>
                </td>
                <td class="px-6 py-4 text-right text-gray-700">$${parseFloat(product.price).toFixed(2)}</td>
                <td class="px-6 py-4 text-right text-gray-700">${product.stock}</td>
                <td class="px-6 py-4 text-gray-700">${product.category}</td>
                <td class="px-6 py-4 text-gray-700">
                    ${product.discount ? (product.discountType === 'percentage' ? `${product.discount}%` : `$${product.discount}`) : 'N/A'}
                </td>
                <td class="px-6 py-4 text-gray-700">${product.size || 'N/A'}</td>
                <td class="px-6 py-4 text-gray-700">${product.gender}</td>
                <td class="px-6 py-4 text-center">
                    <button class="bg-blue-500 text-white px-3 py-1 rounded shadow hover:bg-blue-600">Print</button>
                </td>
            `;

            productList.appendChild(row);
            barcodeInput.value = '';

            JsBarcode(`#${barcodeCanvasId}`, product.barcode, {
                format: "CODE128",
                width: 2,
                height: 40,
                displayValue: true
            });
        }

        // Add Product Button Logic
        addProductButton.addEventListener('click', function () {
            activeForm.classList.remove('hidden');
            if (activeForm === addProductForm2) {
                setTimeout(() => activeForm.classList.remove('translate-x-full'), 10);
            } else {
                setTimeout(() => activeForm.querySelector('.scale-95').classList.remove('scale-95'), 10);
            }
        });

        // Cancel buttons
        cancelButton1.addEventListener('click', () => addProductForm1.classList.add('hidden'));
        cancelButton2.addEventListener('click', () => {
            addProductForm2.classList.add('translate-x-full');
            setTimeout(() => addProductForm2.classList.add('hidden'), 300);
        });

        // Save Product Logic (works for both layouts)
        function saveProductHandler(layoutNum) {
            const imageUrl = document.getElementById(`productImageUrl${layoutNum}`).value;
            const imageFile = document.getElementById(`productImageFile${layoutNum}`).files[0];
            let image;

            if (imageUrl && !imageFile) {
                image = imageUrl;
            } else if (imageFile && !imageUrl) {
                image = URL.createObjectURL(imageFile); // Temporary URL for display
            } else if (!imageUrl && !imageFile) {
                alert('Please provide either an image URL or upload an image.');
                return;
            } else {
                alert('Please choose only one image source (URL or file upload).');
                return;
            }

            const fields = {
                image,
                name: document.getElementById(`productName${layoutNum}`).value,
                brand: document.getElementById(`productBrand${layoutNum}`).value,
                barcode: document.getElementById(`productBarcode${layoutNum}`).value,
                price: parseFloat(document.getElementById(`productPrice${layoutNum}`).value),
                stock: parseInt(document.getElementById(`productStock${layoutNum}`).value),
                category: document.getElementById(`productCategory${layoutNum}`).value,
                description: document.getElementById(`productDescription${layoutNum}`).value,
                discount: parseFloat(document.getElementById(`productDiscount${layoutNum}`).value) || 0,
                discountType: document.getElementById(`productDiscountType${layoutNum}`).value,
                size: document.getElementById(`productSize${layoutNum}`).value,
                gender: document.getElementById(`productGender${layoutNum}`).value
            };

            if (fields.name && fields.brand && fields.barcode && fields.price && fields.stock >= 0 && fields.category) {
                products.push(fields);
                displayProduct(fields);

                // Clear form and hide
                Object.keys(fields).forEach(key => {
                    const element = document.getElementById(`product${key.charAt(0).toUpperCase() + key.slice(1)}${layoutNum}`);
                    if (element.tagName === 'SELECT') element.value = element.options[0].value;
                    else if (element.type === 'file') element.value = '';
                    else element.value = '';
                });
                document.getElementById(`productImageUrl${layoutNum}`).classList.remove('hidden');
                document.getElementById(`productImageFile${layoutNum}`).classList.add('hidden');
                document.getElementById(`toggleUrl${layoutNum}`).classList.add('bg-blue-500', 'text-white');
                document.getElementById(`toggleUrl${layoutNum}`).classList.remove('bg-gray-300', 'text-gray-700');
                document.getElementById(`toggleFile${layoutNum}`).classList.add('bg-gray-300', 'text-gray-700');
                document.getElementById(`toggleFile${layoutNum}`).classList.remove('bg-blue-500', 'text-white');

                if (layoutNum === 1) {
                    addProductForm1.classList.add('hidden');
                } else {
                    addProductForm2.classList.add('translate-x-full');
                    setTimeout(() => addProductForm2.classList.add('hidden'), 300);
                }
            } else {
                alert('Please fill out all required fields correctly.');
            }
        }

        saveProductButton1.addEventListener('click', () => saveProductHandler(1));
        saveProductButton2.addEventListener('click', () => saveProductHandler(2));
    </script>
</body>
</html>
