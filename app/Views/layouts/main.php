<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Great Ten Technology</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#000000', // Black
                        secondary: '#ffffff', // White
                        accent: '#3b82f6', // Blue-500
                        'accent-dark': '#1e40af', // Blue-800
                    },
                    backgroundImage: {
                        'hero-pattern': "linear-gradient(to right bottom, #ffffff, #f3f4f6, #3b82f6)",
                        'blue-gradient': "linear-gradient(90deg, #1e3a8a 0%, #3b82f6 100%)",
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
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-900 flex flex-col min-h-screen">
    
    <!-- Navbar -->
    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-black to-blue-600">Great Ten</a>
                </div>
                <div class="hidden sm:flex space-x-8">
                    <a href="/" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition">Home</a>
                    <a href="#features" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition">Features</a>
                    <a href="#products" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition">Products</a>
                </div>
                <div class="flex items-center space-x-4">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="/dashboard" class="text-gray-700 hover:text-blue-600 text-sm font-medium">Dashboard</a>
                        <a href="/logout" class="bg-gray-900 text-white px-4 py-2 rounded-full hover:bg-gray-800 transition text-sm">Logout</a>
                    <?php else: ?>
                        <a href="/login" class="text-gray-700 hover:text-blue-600 text-sm font-medium">Login</a>
                        <a href="/register" class="bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition text-sm">Get Started</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        <?php 
        // View content will be injected here by the Controller usually, 
        // but for this simple setup we are including views inside the layout or vice versa.
        // Actually, the Controller::view method includes the view file.
        // We should reverse this: The view file should include the layout.
        // Or we make the view file the content and include header/footer.
        ?>
        <!-- Content Placeholder -->
        {{content}}
    </main>

    <!-- Footer -->
    <footer class="bg-black text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Great Ten Technology</h3>
                    <p class="text-gray-400 text-sm">Empowering developers with premium tools and themes.</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Links</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="#" class="hover:text-white">About Us</a></li>
                        <li><a href="#" class="hover:text-white">Contact</a></li>
                        <li><a href="#" class="hover:text-white">Terms of Service</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Newsletter</h4>
                    <form class="flex">
                        <input type="email" placeholder="Your email" class="bg-gray-800 border-none rounded-l-md px-4 py-2 w-full text-white focus:ring-1 focus:ring-blue-500 outline-none">
                        <button class="bg-blue-600 px-4 py-2 rounded-r-md hover:bg-blue-700">Subscribe</button>
                    </form>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-800 pt-8 text-center text-gray-500 text-sm">
                &copy; 2024 Great Ten Technology. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>
