<?php require_once '../app/Views/layouts/header.php'; ?>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8 flex justify-between items-center">
        <h2 class="text-3xl font-bold text-gray-900"><?= $product ? 'Edit Product' : 'Create New Product' ?></h2>
        <a href="<?= url('/admin/dashboard') ?>" class="text-gray-600 hover:text-gray-900">Back</a>
    </div>

    <!-- Main Form -->
    <form action="<?= url(isset($product) ? '/admin/product/update' : '/admin/product/add') ?>" method="POST" enctype="multipart/form-data" class="bg-white shadow-xl rounded-lg overflow-hidden border border-gray-100 p-8">
        <?php if ($product): ?>
            <input type="hidden" name="id" value="<?= $product['id'] ?>">
        <?php endif; ?>
        
        <!-- Basic Info -->
        <h3 class="text-xl font-bold text-gray-800 mb-4">Basic Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($product['title'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Price ($)</label>
                <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($product['price'] ?? '0.00') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Type</label>
                <select name="type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="free" <?= ($product['type'] ?? '') == 'free' ? 'selected' : '' ?>>Free</option>
                    <option value="paid" <?= ($product['type'] ?? '') == 'paid' ? 'selected' : '' ?>>Paid</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Version</label>
                <input type="text" name="version" value="<?= htmlspecialchars($product['version'] ?? '1.0') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
            <textarea name="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
        </div>

        <!-- File Upload -->
        <div class="mb-8 p-4 bg-blue-50 rounded-lg border border-blue-100">
            <h3 class="text-lg font-bold text-blue-800 mb-2">Digital File</h3>
            <?php if (!empty($product['file_path'])): ?>
                <p class="text-sm text-gray-600 mb-2">Current File: <span class="font-mono"><?= htmlspecialchars(basename($product['file_path'])) ?></span></p>
            <?php endif; ?>
            
            <label class="block text-gray-700 text-sm font-bold mb-2"><?= $product ? 'Update File (Optional)' : 'Upload File' ?></label>
            <input type="file" name="file" class="block w-full text-sm text-gray-500
              file:mr-4 file:py-2 file:px-4
              file:rounded-full file:border-0
              file:text-sm file:font-semibold
              file:bg-blue-50 file:text-blue-700
              hover:file:bg-blue-100">
            <p class="text-xs text-gray-500 mt-1">Upload the .zip or .rar file for this product.</p>

            <div class="mt-4 border-t border-blue-200 pt-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">OR External Download Link</label>
                <input type="url" name="external_link" value="<?= htmlspecialchars($product['external_link'] ?? '') ?>" placeholder="https://mega.nz/..." class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <p class="text-xs text-gray-500 mt-1">If provided, users will be redirected to this link instead of downloading a local file.</p>
            </div>
        </div>

        <!-- Image Gallery -->
        <div class="mb-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Image Gallery</h3>
            
            <!-- Existing Images -->
            <?php if (!empty($images)): ?>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                <?php foreach($images as $img): ?>
                <div class="relative group">
                    <img src="<?= url('/' . $img['image_path']) ?>" class="w-full h-32 object-cover rounded shadow">
                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center rounded">
                        <a href="<?= url('/admin/product/delete-image?id=' . $img['id'] . '&product_id=' . $product['id']) ?>" 
                           class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700"
                           onclick="return confirm('Delete this image?')">Delete</a>
                    </div>
                    <?php if ($img['is_primary']): ?>
                        <span class="absolute top-1 left-1 bg-green-500 text-white text-xs px-2 py-0.5 rounded shadow">Main</span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Upload New Images -->
            <label class="block text-gray-700 text-sm font-bold mb-2">Add Images</label>
            <input type="file" name="images[]" multiple accept="image/*" class="block w-full text-sm text-gray-500
              file:mr-4 file:py-2 file:px-4
              file:rounded-full file:border-0
              file:text-sm file:font-semibold
              file:bg-green-50 file:text-green-700
              hover:file:bg-green-100">
            <p class="text-xs text-gray-500 mt-1">You can select multiple images (PNG, JPG).</p>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end space-x-4 border-t pt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transform transition hover:scale-105">
                <?= $product ? 'Save Changes' : 'Create Product' ?>
            </button>
        </div>
    </form>
</div>

<?php require_once '../app/Views/layouts/footer.php'; ?>
