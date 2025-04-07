<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Category Selector</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
  <div class="max-w-5xl mx-auto">
    <h2 class="text-2xl font-bold mb-4">Select a Category</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

      <!-- Card Template -->
      <div class="bg-white shadow-md rounded-lg p-4 flex items-start space-x-4 hover:shadow-lg transition">
        <div class="bg-blue-100 text-blue-600 p-2 rounded-full">
          👕
        </div>
        <div>
          <h3 class="font-semibold text-lg">Clothes</h3>
          <p class="text-sm text-gray-600">Fashion items, outfits, and more.</p>
        </div>
      </div>

      <div class="bg-white shadow-md rounded-lg p-4 flex items-start space-x-4 hover:shadow-lg transition">
        <div class="bg-yellow-100 text-yellow-600 p-2 rounded-full">
          👜
        </div>
        <div>
          <h3 class="font-semibold text-lg">Bag</h3>
          <p class="text-sm text-gray-600">Handbags, backpacks, and travel bags.</p>
        </div>
      </div>

      <div class="bg-white shadow-md rounded-lg p-4 flex items-start space-x-4 hover:shadow-lg transition">
        <div class="bg-pink-100 text-pink-600 p-2 rounded-full">
          👟
        </div>
        <div>
          <h3 class="font-semibold text-lg">Shoes</h3>
          <p class="text-sm text-gray-600">Casual, formal, or sports footwear.</p>
        </div>
      </div>

      <div class="bg-white shadow-md rounded-lg p-4 flex items-start space-x-4 hover:shadow-lg transition">
        <div class="bg-green-100 text-green-600 p-2 rounded-full">
          🧸
        </div>
        <div>
          <h3 class="font-semibold text-lg">Toys</h3>
          <p class="text-sm text-gray-600">Toys and games for all ages.</p>
        </div>
      </div>

      <div class="bg-white shadow-md rounded-lg p-4 flex items-start space-x-4 hover:shadow-lg transition">
        <div class="bg-purple-100 text-purple-600 p-2 rounded-full">
          🎒
        </div>
        <div>
          <h3 class="font-semibold text-lg">Student Material</h3>
          <p class="text-sm text-gray-600">Books, pens, and school items.</p>
        </div>
      </div>

      <div class="bg-white shadow-md rounded-lg p-4 flex items-start space-x-4 hover:shadow-lg transition">
        <div class="bg-red-100 text-red-600 p-2 rounded-full">
          💍
        </div>
        <div>
          <h3 class="font-semibold text-lg">Jewelry</h3>
          <p class="text-sm text-gray-600">Rings, necklaces, and more.</p>
        </div>
      </div>

      <div class="bg-white shadow-md rounded-lg p-4 flex items-start space-x-4 hover:shadow-lg transition">
        <div class="bg-pink-200 text-pink-700 p-2 rounded-full">
          💄
        </div>
        <div>
          <h3 class="font-semibold text-lg">Make Up</h3>
          <p class="text-sm text-gray-600">Cosmetics and beauty products.</p>
        </div>
      </div>

      <div class="bg-white shadow-md rounded-lg p-4 flex items-start space-x-4 hover:shadow-lg transition">
        <div class="bg-gray-200 text-gray-700 p-2 rounded-full">
          📦
        </div>
        <div>
          <h3 class="font-semibold text-lg">Other</h3>
          <p class="text-sm text-gray-600">Miscellaneous category items.</p>
        </div>
      </div>

    </div>
  </div>
</body>
</html>
