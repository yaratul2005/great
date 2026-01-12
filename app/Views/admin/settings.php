<?php require_once '../app/Views/layouts/header.php'; ?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Site Settings</h2>
            <p class="text-gray-500">Configure your payment gateway and site options.</p>
        </div>
        <a href="<?= url('/admin/dashboard') ?>" class="text-blue-600 hover:text-blue-800">← Back to Dashboard</a>
    </div>

    <?php if (isset($_GET['saved'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
        <strong class="font-bold">Success!</strong>
        <span class="block sm:inline">Settings have been saved.</span>
    </div>
    <?php endif; ?>

    <div class="bg-white shadow-xl rounded-lg overflow-hidden border border-gray-100 p-6">
        <form action="<?= url('/admin/settings/save') ?>" method="POST">
            
            <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b">General</h3>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Site Name</label>
                <input type="text" name="site_name" value="<?= htmlspecialchars($settings['site_name'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b">Stripe Payment Configuration</h3>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Stripe Publishable Key</label>
                <input type="text" name="stripe_publishable_key" value="<?= htmlspecialchars($settings['stripe_publishable_key'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="pk_test_...">
            </div>

            <div class="mb-4">
                 <label class="block text-gray-700 text-sm font-bold mb-2">Stripe Secret Key</label>
                 <input type="password" name="stripe_secret_key" value="<?= htmlspecialchars($settings['stripe_secret_key'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="sk_test_...">
                 <p class="text-xs text-gray-500 mt-1">Found in Stripe Dashboard -> Developers -> API Keys.</p>
            </div>

            <div class="mb-6">
                 <label class="block text-gray-700 text-sm font-bold mb-2">Stripe Webhook Secret</label>
                 <input type="text" name="stripe_webhook_secret" value="<?= htmlspecialchars($settings['stripe_webhook_secret'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="whsec_...">
                 <p class="text-xs text-gray-500 mt-1">Generated when you add an endpoint in Stripe Dashboard -> Webhooks.</p>
            </div>

            <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b">SMTP Configuration (Email)</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">SMTP Host</label>
                    <input type="text" name="smtp_host" value="<?= htmlspecialchars($settings['smtp_host'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="smtp.gmail.com">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">SMTP Port</label>
                    <input type="text" name="smtp_port" value="<?= htmlspecialchars($settings['smtp_port'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="587">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">SMTP Username</label>
                    <input type="text" name="smtp_user" value="<?= htmlspecialchars($settings['smtp_user'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">SMTP Password</label>
                    <input type="password" name="smtp_pass" value="<?= htmlspecialchars($settings['smtp_pass'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">From Email</label>
                <input type="email" name="smtp_from" value="<?= htmlspecialchars($settings['smtp_from'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="noreply@example.com">
            </div>

            <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b">SEO & Customization</h3>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Global Meta Description</label>
                <textarea name="site_description" rows="2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?= htmlspecialchars($settings['site_description'] ?? '') ?></textarea>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Meta Keywords (comma separated)</label>
                <input type="text" name="site_meta_keywords" value="<?= htmlspecialchars($settings['site_meta_keywords'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Google Analytics ID</label>
                    <input type="text" name="google_analytics_id" value="<?= htmlspecialchars($settings['google_analytics_id'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="G-XXXXXXXXXX">
                </div>
                <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2">Google Search Console Meta</label>
                     <input type="text" name="google_search_console" value="<?= htmlspecialchars($settings['google_search_console'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Verification Code only">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Custom HEAD Code (JS/CSS)</label>
                <textarea name="custom_head_code" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline font-mono"><?= htmlspecialchars($settings['custom_head_code'] ?? '') ?></textarea>
                <p class="text-xs text-gray-500 mt-1">Raw HTML/JS injected before &lt;/head&gt;. Use for Pixels, Chat widgets, etc.</p>
            </div>

            <div class="mb-6">
                 <label class="block text-gray-700 text-sm font-bold mb-2">Favicon URL</label>
                 <input type="text" name="site_favicon" value="<?= htmlspecialchars($settings['site_favicon'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="https://example.com/favicon.ico">
                 <p class="text-xs text-gray-500 mt-1">Enter a direct URL to an image/ico file.</p>
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition transform hover:scale-105">
                    Save Changes
                </button>
            </div>

        </form>
    </div>
</div>

<?php require_once '../app/Views/layouts/footer.php'; ?>
