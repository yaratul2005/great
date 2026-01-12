<?php require_once '../app/Views/layouts/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Admin Dashboard</h2>
            <div class="space-x-4">
                 <a href="<?= url('/admin/logs') ?>" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition shadow">
                    📊 Live Traffic
                </a>
                <a href="<?= url('/admin/pages') ?>" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition shadow">
                    📄 Pages
                </a>
                 <a href="<?= url('/admin/settings') ?>" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition shadow">
                    ⚙️ Settings
                </a>
                <button onclick="document.getElementById('manual-license-modal').classList.remove('hidden')" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition shadow">
                    🔑 Issue License
                </button>
                <a href="<?= url('/admin/product/edit') ?>" class="bg-gray-900 text-white px-4 py-2 rounded-md hover:bg-gray-800 transition shadow">
                    + Add Product
                </a>
                 <a href="<?= url('/admin/settings') ?>#smtp" onclick="window.location.href='<?= url('/admin/settings') ?>'" class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700 transition shadow">
                    📧 SMTP Setup
                </a>
            </div>
        </div>
    

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-gray-500 text-sm font-medium">Total Users</h3>
            <p class="text-2xl font-bold text-gray-900 mt-2">1,240</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-gray-500 text-sm font-medium">Total Products</h3>
            <p class="text-2xl font-bold text-gray-900 mt-2"><?= count($products) ?></p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-gray-500 text-sm font-medium">Active Licenses</h3>
            <p class="text-2xl font-bold text-gray-900 mt-2">856</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-gray-500 text-sm font-medium">Revenue</h3>
            <p class="text-2xl font-bold text-gray-900 mt-2">$12,450</p>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="text-lg font-bold text-gray-900">Product Management</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Version</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                         <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($product['title']) ?></div>
                                    <div class="text-sm text-gray-500"><?= htmlspecialchars(substr($product['description'], 0, 30)) ?>...</div>
                                </div>
                            </div>
                        </td>
                         <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                v<?= htmlspecialchars($product['version']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            $<?= number_format($product['price'], 2) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= ucfirst($product['type']) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button onclick='openIntegration("<?= htmlspecialchars($product['title']) ?>", "<?= $product['api_secret'] ?>")' class="text-indigo-600 hover:text-indigo-900 mr-3">Integration</button>
                            <a href="<?= url('/admin/product/edit?id=' . $product['id']) ?>" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                            <a href="<?= url('/admin/product/delete?id=' . $product['id']) ?>" class="text-red-600 hover:text-red-900 ml-4" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<!-- Add Product Modal Removed (Replaced by Editor Page) -->

<!-- Integration Modal -->
<div id="integration-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-[800px] shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="int-title">Integration Code</h3>
            <p class="text-sm text-gray-500 mb-4">Copy this code into your software (e.g., config.php or function.php) to verify licenses.</p>
            
            <div class="bg-gray-800 text-green-400 p-4 rounded text-sm overflow-x-auto font-mono mb-6">
                <pre id="int-code"></pre>
            </div>

            <div class="flex items-center justify-end mt-4">
                <button type="button" onclick="document.getElementById('integration-modal').classList.add('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function openIntegration(title, secret) {
    const apiUrl = "<?= url('/api/validate') ?>";
    const code = `
function verify_great_ten_license($license_key) {
    $api_url = "${apiUrl}";
    $product_secret = "${secret}"; // UNIQUE SECRET FOR: ${title}
    $domain = $_SERVER['HTTP_HOST'];

    $url = $api_url . "?key=" . urlencode($license_key) . "&product_secret=" . $product_secret . "&domain=" . urlencode($domain);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL check for localhost/testing
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $data = json_decode($response, true);
    
    if ($data && isset($data['valid']) && $data['valid'] === true) {
        return true; 
    }
    
    return false; // Invalid or Expired
}

// Usage:
// if (verify_great_ten_license("USER_ENTERED_KEY_HERE")) { ... } else { die("Invalid License"); }
    `;
    
    document.getElementById('int-title').textContent = "Integration: " + title;
    document.getElementById('int-code').textContent = code;
    document.getElementById('integration-modal').classList.remove('hidden');
}
</script>

<!-- Manual License Modal -->
<div id="manual-license-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Issue Manual License</h3>
            <form class="mt-4 text-left" action="<?= url('/admin/license/generate') ?>" method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">User Email</label>
                    <input type="email" name="email" placeholder="customer@example.com" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <p class="text-xs text-gray-500 mt-1">User must already be registered.</p>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Product</label>
                    <select name="product_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <?php foreach ($products as $product): ?>
                            <option value="<?= $product['id'] ?>"><?= htmlspecialchars($product['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex items-center justify-between mt-6">
                     <button type="button" onclick="document.getElementById('manual-license-modal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">Cancel</button>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Generate Key
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layouts/footer.php'; ?>
