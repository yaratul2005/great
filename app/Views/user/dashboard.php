<?php require_once '../app/Views/layouts/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">My Dashboard</h1>
            <p class="text-gray-500">Manage your products and active licenses.</p>
        </div>
        <div class="space-x-4">
             <a href="<?= url('/invoices') ?>" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-200 transition">
                📜 My Orders
            </a>
             <a href="<?= url('/profile') ?>" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-200 transition">
                👤 My Profile
            </a>
            <a href="<?= url('/logout') ?>" class="text-red-500 hover:text-red-700">Logout</a>
        </div>
    </div>

    <?php if (empty($licenses)): ?>
    <div class="text-center py-20 bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="text-gray-400 mb-4">
            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900">No products found</h3>
        <p class="text-gray-500 mt-2 mb-6">You haven't purchased any tools or themes yet.</p>
        <a href="<?= url('/#products') ?>" class="bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 transition">Browse Store</a>
    </div>
    <?php else: ?>
    
    <div class="grid grid-cols-1 gap-6">
        <?php foreach ($licenses as $license): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col md:flex-row">
            <div class="md:w-1/4 bg-gradient-to-br from-gray-50 to-gray-100 p-6 flex flex-col justify-center items-center text-center border-r border-gray-100">
                 <div class="w-12 h-12 bg-white rounded-lg shadow-sm flex items-center justify-center mb-3 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <h3 class="font-bold text-gray-900"><?= htmlspecialchars($license['product_title']) ?></h3>
                <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded mt-2">v<?= htmlspecialchars($license['version']) ?></span>
            </div>
            
            <div class="p-6 flex-grow">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">License Key</label>
                        <div class="flex items-center space-x-2">
                            <code class="bg-gray-100 px-3 py-2 rounded text-sm text-blue-600 font-mono select-all w-full md:w-auto"><?= htmlspecialchars($license['license_key']) ?></code>
                            <button onclick="navigator.clipboard.writeText('<?= $license['license_key'] ?>')" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Status</label>
                        <?php if ($license['status'] === 'active'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <?= ucfirst($license['status']) ?>
                            </span>
                        <?php endif; ?>
                        
                        <div class="mt-4">
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Domain Restriction</label>
                            <span class="text-sm text-gray-600"><?= $license['domain_restriction'] ? htmlspecialchars($license['domain_restriction']) : 'None' ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 pt-6 border-t border-gray-100 flex justify-between items-center">
                    <p class="text-xs text-gray-400">Purchased on <?= date('M d, Y', strtotime($license['created_at'])) ?></p>
                    
                    <?php if (!empty($license['external_link'])): ?>
                        <a href="<?= htmlspecialchars($license['external_link']) ?>" target="_blank" rel="noopener noreferrer" class="bg-indigo-50 text-indigo-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-indigo-100 transition flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            Download (External)
                        </a>
                    <?php elseif (!empty($license['file_path'])): ?>
                        <a href="<?= url('/' . $license['file_path']) ?>" download class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-100 transition flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download File
                        </a>
                    <?php else: ?>
                        <span class="text-gray-400 text-sm italic">Download unavailable</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <?php endif; ?>
</div>

<?php require_once '../app/Views/layouts/footer.php'; ?>
