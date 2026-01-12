<?php require_once '../app/Views/layouts/header.php'; ?>

<!-- Hero Section -->
<section class="relative bg-white overflow-hidden">
    <div class="absolute inset-0 bg-hero-pattern opacity-50"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative pt-20 pb-32">
        <div class="text-center">
            <h1 class="text-4xl sm:text-6xl font-bold tracking-tight text-gray-900 mb-6">
                Premium PHP Tools & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Great Designs</span>
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-10">
                Kickstart your business with our secure, scalable, and beautifully designed scripts. License management, admin dashboards, and more included.
            </p>
            <div class="flex justify-center space-x-4">
                <a href="#products" class="bg-blue-600 text-white px-8 py-3 rounded-full hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition font-semibold">Explore Tools</a>
                <a href="<?= url('/register') ?>" class="bg-white text-gray-900 border border-gray-200 px-8 py-3 rounded-full hover:bg-gray-50 transition font-semibold">Join Now</a>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900">Why Choose Us?</h2>
            <p class="text-gray-500 mt-2">Built for developers and entrepreneurs.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <!-- Feature 1 -->
            <div class="p-8 rounded-2xl bg-gray-50 border border-gray-100 hover:shadow-xl transition duration-300">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-6 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h3 class="text-xl font-bold mb-3">Secure Licensing</h3>
                <p class="text-gray-600">Protect your intellectual property with our robust license key generation and validation API.</p>
            </div>
            <!-- Feature 2 -->
            <div class="p-8 rounded-2xl bg-gray-50 border border-gray-100 hover:shadow-xl transition duration-300">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-6 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg>
                </div>
                <h3 class="text-xl font-bold mb-3">Admin Dashboard</h3>
                <p class="text-gray-600">Manage products, view sales, and control licenses from a comprehensive admin panel.</p>
            </div>
            <!-- Feature 3 -->
            <div class="p-8 rounded-2xl bg-gray-50 border border-gray-100 hover:shadow-xl transition duration-300">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-6 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <h3 class="text-xl font-bold mb-3">High Performance</h3>
                <p class="text-gray-600">Optimized for speed. Lightweight PHP architecture ensures your apps fly on any hosting.</p>
            </div>
        </div>
    </div>
</section>

<!-- Products Placeholder -->
<section id="products" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900">Featured Products</h2>
            <p class="text-gray-500 mt-2">Latest additions to our collection.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php if (empty($products)): ?>
                <div class="col-span-3 text-center py-10 text-gray-500">
                    <p>No products available yet.</p>
                </div>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden group border border-gray-100 flex flex-col h-full">
                    <a href="<?= url('/product/' . $product['id']) ?>" class="block h-48 bg-gray-100 relative overflow-hidden flex items-center justify-center">
                        <!-- Real Image or Gradient -->
                        <?php 
                            // Quick hack: fetch primary image here or assume logic in controller? 
                            // For performance, better in controller, but for now let's try to see if we can use a helper or just placeholder.
                            // Let's use the placeholder gradient if no logic, but wait, we want to show images.
                            // I'll leave the gradient for now but make it clickable.
                        ?>
                        <div class="absolute inset-0 bg-gradient-to-tr from-blue-500 to-indigo-600 opacity-90 group-hover:opacity-100 group-hover:scale-105 transition duration-500"></div>
                        <div class="relative z-10 text-white font-bold text-3xl opacity-20">GT</div>
                        
                        <?php if ($product['type'] == 'paid'): ?>
                        <div class="absolute top-4 right-4 bg-white text-blue-600 text-xs font-bold px-2 py-1 rounded shadow">
                            $<?= number_format($product['price'], 2) ?>
                        </div>
                        <?php else: ?>
                        <div class="absolute top-4 right-4 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded shadow">
                            FREE
                        </div>
                        <?php endif; ?>
                    </a>
                    
                    <div class="p-6 flex flex-col flex-grow">
                        <div class="flex justify-between items-center mb-2">
                             <span class="text-xs font-medium text-gray-400">v<?= htmlspecialchars($product['version']) ?></span>
                        </div>
                        <a href="<?= url('/product/' . $product['id']) ?>">
                            <h3 class="text-xl font-bold mb-2 text-gray-900 hover:text-blue-600 transition"><?= htmlspecialchars($product['title']) ?></h3>
                        </a>
                        <p class="text-gray-600 text-sm mb-4 flex-grow"><?= htmlspecialchars($product['description']) ?></p>
                        <div class="mt-6">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($product['type'] == 'free'): ?>
                            <a href="#" onclick="alert('Free download logic here')" class="block w-full text-center bg-gray-900 text-white font-bold py-2 px-4 rounded hover:bg-gray-800 transition">
                                Get License (Free)
                            </a>
                        <?php else: ?>
                            <a href="<?= url('/product/' . $product['id']) ?>" class="block w-full text-center bg-indigo-600 text-white font-bold py-2 px-4 rounded hover:bg-indigo-700 transition shadow-lg transform hover:-translate-y-1">
                                View Details
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="<?= url('/product/' . $product['id']) ?>" class="block w-full text-center bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded hover:bg-gray-300 transition">
                            View Details
                        </a>
                    <?php endif; ?>
                </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once '../app/Views/layouts/footer.php'; ?>
