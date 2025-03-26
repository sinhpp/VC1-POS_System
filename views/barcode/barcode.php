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

    <!-- Barcode Input -->
    <div class="mb-4">
      <input id="barcodeInput" type="text" class="px-4 py-2 border border-gray-300 rounded" placeholder="Scan or enter barcode" autofocus>
    </div>

    <div id="errorMessage" class="text-red-500 hidden mb-4">Invalid barcode! Please try again.</div>

    <!-- Product Table -->
    <div class="overflow-x-auto shadow-md rounded-lg bg-white mb-4">
      <table class="min-w-full table-auto border-collapse border border-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 border-b">Image</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 border-b">Product Name</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 border-b">Model</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 border-b">Barcode</th>
            <th class="px-6 py-3 text-right text-sm font-medium text-gray-500 border-b">Price</th>
            <th class="px-6 py-3 text-right text-sm font-medium text-gray-500 border-b">Total Quantity</th>
            <th class="px-6 py-3 text-right text-sm font-medium text-gray-500 border-b">POS Quantity</th>
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

    <!-- Add Product Form -->
    <div id="addProductForm" class="hidden mb-4 p-4 bg-white border border-gray-200 rounded">
      <input id="productName" type="text" placeholder="Product Name" class="block mb-2 px-4 py-2 border border-gray-300 rounded">
      <input id="productModel" type="text" placeholder="Model" class="block mb-2 px-4 py-2 border border-gray-300 rounded">
      <input id="productBarcode" type="text" placeholder="Barcode" class="block mb-2 px-4 py-2 border border-gray-300 rounded">
      <input id="productPrice" type="text" placeholder="Price" class="block mb-2 px-4 py-2 border border-gray-300 rounded">
      <input id="productTotalQuantity" type="number" placeholder="Total Quantity" class="block mb-2 px-4 py-2 border border-gray-300 rounded">
      <input id="productPosQuantity" type="number" placeholder="POS Quantity" class="block mb-2 px-4 py-2 border border-gray-300 rounded">
      <input id="productImage" type="text" placeholder="Image Path or URL" class="block mb-2 px-4 py-2 border border-gray-300 rounded">
      <button id="saveProductButton" class="bg-green-500 text-white px-4 py-2 rounded shadow hover:bg-green-600">Save Product</button>
    </div>
  </div>

  <script>
    const barcodeInput = document.getElementById('barcodeInput');
    const errorMessage = document.getElementById('errorMessage');
    const productList = document.getElementById('productList');
    const addProductButton = document.getElementById('addProductButton');
    const addProductForm = document.getElementById('addProductForm');
    const saveProductButton = document.getElementById('saveProductButton');

    // Dummy product data
    const products = [
      {
        name: "Beige Prom Dress",
        model: "Model 51",
        barcode: "123456789",
        price: "$101.00",
        totalQuantity: 86,
        posQuantity: 69,
        image: "https://via.placeholder.com/50",
      },
      {
        name: "Beige Ruffle Dress",
        model: "Model 68",
        barcode: "987654321",
        price: "$72.00",
        totalQuantity: 90,
        posQuantity: 12,
        image: "https://via.placeholder.com/50",
      }
    ];

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
        <td class="px-6 py-4 text-gray-700">${product.model}</td>
        <td class="px-6 py-4 text-gray-700">
          <canvas id="${barcodeCanvasId}"></canvas>
        </td>
        <td class="px-6 py-4 text-right text-gray-700">${product.price}</td>
        <td class="px-6 py-4 text-right text-gray-700">${product.totalQuantity}</td>
        <td class="px-6 py-4 text-right text-gray-700">${product.posQuantity}</td>
        <td class="px-6 py-4 text-center">
          <button class="bg-blue-500 text-white px-3 py-1 rounded shadow hover:bg-blue-600">Print</button>
        </td>
      `;

      productList.appendChild(row);
      barcodeInput.value = ''; // Clear input after product is added

      // Generate barcode
      JsBarcode(`#${barcodeCanvasId}`, product.barcode, {
        format: "CODE128",
        width: 2,
        height: 40,
        displayValue: true
      });
    }

    // Load initial products
    products.forEach(displayProduct);

    // Add Product Button Logic
    addProductButton.addEventListener('click', function () {
      addProductForm.classList.toggle('hidden');
    });

    // Save Product Logic
    saveProductButton.addEventListener('click', function () {
      const name = document.getElementById('productName').value;
      const model = document.getElementById('productModel').value;
      const barcode = document.getElementById('productBarcode').value;
      const price = document.getElementById('productPrice').value;
      const totalQuantity = parseInt(document.getElementById('productTotalQuantity').value, 10);
      const posQuantity = parseInt(document.getElementById('productPosQuantity').value, 10);
      const image = document.getElementById('productImage').value;

      if (name && model && barcode && price && totalQuantity >= 0 && posQuantity >= 0 && image) {
        const newProduct = { name, model, barcode, price, totalQuantity, posQuantity, image };
        products.push(newProduct); // Add product to array
        displayProduct(newProduct); // Display the new product in the table

        // Clear form
        document.getElementById('productName').value = '';
        document.getElementById('productModel').value = '';
        document.getElementById('productBarcode').value = '';
        document.getElementById('productPrice').value = '';
        document.getElementById('productTotalQuantity').value = '';
        document.getElementById('productPosQuantity').value = '';
        document.getElementById('productImage').value = '';
        addProductForm.classList.add('hidden');
      } else {
        alert('Please fill out all fields correctly.');
      }
    });
  </script>
</body>
</html>
