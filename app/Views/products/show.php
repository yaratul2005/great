<?php
// Override page meta if product has SEO fields (future), for now use Product Title
$page['meta_title'] = $product['title'] . " - " . ($globalSettings['site_name'] ?? 'Great Ten Technology');
$page['meta_description'] = substr(strip_tags($product['description']), 0, 160);

require_once '../app/Views/layouts/header.php'; 
?>

<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2">
                
                <!-- Image Gallery -->
                <div class="p-8 bg-gray-100 flex flex-col items-center justify-center">
                    <?php 
                        $mainImage = !empty($images) ? $images[0]['image_path'] : 'assets/placeholder.png'; // Use first as main
                         // Check if primary is set
                        foreach($images as $img) { if($img['is_primary']) $mainImage = $img['image_path']; }
                        // If no images, maybe use a default icon
                        if (empty($images)) $mainImage = null;
                    ?>
                    
                    <?php if ($mainImage): ?>
                        <div class="w-full h-96 mb-4 flex items-center justify-center bg-white rounded-lg shadow-inner overflow-hidden">
                            <img id="mainImage" src="<?= url('/' . $mainImage) ?>" class="max-h-full max-w-full object-contain hover:scale-105 transition duration-500">
                        </div>
                        
                        <!-- Thumbnails -->
                        <div class="grid grid-cols-5 gap-2 w-full">
                            <?php foreach($images as $img): ?>
                            <div class="cursor-pointer border-2 border-transparent hover:border-blue-500 rounded-md overflow-hidden h-20 bg-white flex items-center justify-center"
                                 onclick="document.getElementById('mainImage').src='<?= url('/' . $img['image_path']) ?>'">
                                <img src="<?= url('/' . $img['image_path']) ?>" class="max-h-full max-w-full object-contain">
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="w-full h-96 flex items-center justify-center text-gray-400 bg-gray-200 rounded-lg">
                            <span class="text-6xl">📦</span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Product Details -->
                <div class="p-10 flex flex-col">
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold uppercase tracking-wide">
                            <?= htmlspecialchars($product['type']) ?> Software
                        </span>
                        <span class="text-gray-500 text-sm">v<?= htmlspecialchars($product['version']) ?></span>
                    </div>
                    
                    <h1 class="text-4xl font-extrabold text-gray-900 mb-4 leading-tight"><?= htmlspecialchars($product['title']) ?></h1>
                    
                    <div class="flex items-baseline mb-6">
                        <span class="text-3xl font-bold text-gray-900">$<?= number_format($product['price'], 2) ?></span>
                        <span class="ml-2 text-sm text-gray-500">One-time payment</span>
                    </div>
                    
                    <div class="prose prose-blue text-gray-600 mb-8 flex-grow">
                        <?= nl2br(htmlspecialchars($product['description'])) ?>
                    </div>
                    
                    <div class="mt-auto">
                        <?php if ($product['price'] > 0): ?>
                            <a href="<?= url('/purchase?product_id=' . $product['id']) ?>" class="block w-full bg-blue-600 border border-transparent rounded-lg py-4 px-8 text-center font-bold text-white text-lg hover:bg-blue-700 transition transform hover:-translate-y-1 shadow-lg">
                                Buy Now & Instant Download
                            </a>
                        <?php else: ?>
                            <a href="#" class="block w-full bg-green-600 border border-transparent rounded-lg py-4 px-8 text-center font-bold text-white text-lg hover:bg-green-700 transition transform hover:-translate-y-1 shadow-lg">
                                Download Free
                            </a>
                        <?php endif; ?>
                        
                        <div class="mt-6 flex items-center justify-center space-x-6 text-sm text-gray-500">
                             <span class="flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg> Secure Payment</span>
                             <span class="flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg> Instant Delivery</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Back Link -->
        <div class="mt-8 text-center">
            <a href="<?= url('/') ?>" class="text-blue-600 hover:text-blue-800 font-medium">← Back to Home</a>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layouts/footer.php'; ?>
