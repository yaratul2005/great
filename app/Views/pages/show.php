<?php require_once '../app/Views/layouts/header.php'; ?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden p-10 border border-gray-100">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-6"><?= htmlspecialchars($page['title']) ?></h1>
        
        <div class="prose prose-blue max-w-none text-gray-700 leading-relaxed">
            <!-- Allow specific HTML tags or just raw output if admin trusted. 
                 For safety we usually escape, but CMS implies HTML content. 
                 We will output raw content here assuming Admin is trusted. -->
            <?= $page['content'] ?>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layouts/footer.php'; ?>
