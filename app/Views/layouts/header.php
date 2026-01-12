<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <?php
    $globalSettings = [];
    try {
        if (!isset($globalSettings) || empty($globalSettings)) {
            $settingModel = new \App\Models\Setting();
            // Check connection first if possible, or just try
            $globalSettings = $settingModel->getAll();
        }
    } catch (\Throwable $e) {
        // Fallback if DB fails
        $globalSettings = [
            'site_name' => 'Great Ten Info',
            'site_description' => 'Site is under maintenance',
        ];
        // echo "<!-- DB Error: " . $e->getMessage() . " -->"; 
    }
    
    // Page Specific Overrides
    $pageTitle = isset($page['meta_title']) && $page['meta_title'] ? $page['meta_title'] : ($globalSettings['site_name'] ?? 'Great Ten Technology');
    $pageDesc = isset($page['meta_description']) && $page['meta_description'] ? $page['meta_description'] : ($globalSettings['site_description'] ?? '');
    $pageKeywords = $globalSettings['site_meta_keywords'] ?? '';
    ?>
    
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDesc) ?>">
    <?php if ($pageKeywords): ?><meta name="keywords" content="<?= htmlspecialchars($pageKeywords) ?>"><?php endif; ?>
    
    <?php if (!empty($globalSettings['site_favicon'])): ?>
    <link rel="icon" href="<?= htmlspecialchars($globalSettings['site_favicon']) ?>">
    <?php endif; ?>

    <!-- Custom Head Code -->
    <?= $globalSettings['custom_head_code'] ?? '' ?>
    
    <!-- Using version 3.4.1 explicitly -->
    <script src="https://cdn.tailwindcss.com?v=3.4.1"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#000000',
                        secondary: '#ffffff',
                        accent: '#3b82f6',
                        'accent-dark': '#1e40af',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-900 flex flex-col min-h-screen">
    
    <!-- Navbar -->
    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex-shrink-0 flex items-center">
                    <a href="<?= url('/') ?>" class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-black to-blue-600">Great Ten</a>
                </div>
                <div class="hidden sm:flex space-x-8">
                    <a href="<?= url('/') ?>" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition">Home</a>
                    <a href="<?= url('/#features') ?>" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition">Features</a>
                    <a href="<?= url('/#products') ?>" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition">Products</a>
                </div>
                <div class="flex items-center space-x-4">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="<?= url('/dashboard') ?>" class="text-gray-700 hover:text-blue-600 text-sm font-medium">Dashboard</a>
                        <a href="<?= url('/logout') ?>" class="bg-gray-900 text-white px-4 py-2 rounded-full hover:bg-gray-800 transition text-sm">Logout</a>
                    <?php else: ?>
                        <a href="<?= url('/login') ?>" class="text-gray-700 hover:text-blue-600 text-sm font-medium">Login</a>
                        <a href="<?= url('/register') ?>" class="bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition text-sm">Get Started</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
