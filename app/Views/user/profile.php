<?php require_once '../app/Views/layouts/header.php'; ?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Account Settings</h1>
            <p class="text-gray-500">Manage your profile information and security.</p>
        </div>
        <a href="<?= url('/dashboard') ?>" class="text-gray-600 hover:text-gray-900">← Back to Dashboard</a>
    </div>

    <!-- Messages -->
    <?php if (isset($_GET['success'])): ?>
        <div class="bg-green-50 text-green-700 p-4 rounded-md mb-6 border border-green-200">
            <?= $_GET['success'] == 'password_updated' ? 'Password changed successfully.' : 'Profile updated successfully.' ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="bg-red-50 text-red-700 p-4 rounded-md mb-6 border border-red-200">
            Failed to update profile. Please try again.
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- Profile Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Personal Information</h2>
            <form action="<?= url('/profile/update') ?>" method="POST">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2">
                </div>
                <!-- Role (Read Only) -->
                <div class="mb-4">
                     <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                     <input type="text" value="<?= ucfirst($user['role']) ?>" disabled class="w-full bg-gray-50 rounded-md border-gray-300 shadow-sm text-gray-500 border p-2 cursor-not-allowed">
                </div>
                
                <div class="mt-6">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition w-full">Save Changes</button>
                </div>
            </form>
        </div>

        <!-- Security -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Security</h2>
            <form action="<?= url('/profile/password') ?>" method="POST">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input type="password" name="new_password" required minlength="6" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2">
                </div>
                
                <div class="mt-6">
                    <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-md hover:bg-gray-800 transition w-full">Update Password</button>
                </div>
            </form>
            
            <div class="mt-8 pt-6 border-t border-gray-100">
                <h3 class="text-sm font-bold text-gray-900 mb-2">Two-Factor Authentication</h3>
                 <p class="text-xs text-gray-500 mb-3">Add an extra layer of security to your account.</p>
                 <button disabled class="w-full border border-gray-300 text-gray-400 px-4 py-2 rounded-md cursor-not-allowed bg-gray-50 text-sm">Coming Soon</button>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layouts/footer.php'; ?>
