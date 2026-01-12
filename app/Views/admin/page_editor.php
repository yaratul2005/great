<?php require_once '../app/Views/layouts/header.php'; ?>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900"><?= $page ? 'Edit Page' : 'Create New Page' ?></h2>
    </div>

    <form action="<?= url('/admin/pages/edit') ?>" method="POST" class="bg-white shadow-xl rounded-lg overflow-hidden border border-gray-100 p-8">
        <?php if ($page): ?>
            <input type="hidden" name="id" value="<?= $page['id'] ?>">
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Page Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($page['title'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Slug (URL Path)</label>
                <input type="text" name="slug" value="<?= htmlspecialchars($page['slug'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="e.g. about-us" required> 
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Page Content</label>
            <!-- Hidden Input to store data -->
            <input type="hidden" name="content" id="contentInput">
            
            <!-- Quill Editor Container -->
            <div id="editor-container" class="bg-white" style="height: 400px;">
                <?= $page['content'] ?? '' ?>
            </div>
        </div>

        <!-- Quill CSS & JS -->
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
        
        <script>
            var quill = new Quill('#editor-container', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        ['blockquote', 'code-block'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'script': 'sub'}, { 'script': 'super' }],
                        [{ 'color': [] }, { 'background': [] }],
                        ['link', 'image', 'video'],
                        ['clean']
                    ]
                }
            });

            // Sync content on form submit
            document.querySelector('form').onsubmit = function() {
                var content = document.querySelector('input[name=content]');
                content.value = quill.root.innerHTML;
            };
        </script>

        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b">SEO Settings</h3>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Meta Title</label>
            <input type="text" name="meta_title" value="<?= htmlspecialchars($page['meta_title'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Meta Description</label>
            <textarea name="meta_description" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?= htmlspecialchars($page['meta_description'] ?? '') ?></textarea>
        </div>

        <div class="flex items-center justify-end space-x-4">
            <a href="<?= url('/admin/pages') ?>" class="text-gray-600 hover:text-gray-900">Cancel</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline">
                Save Page
            </button>
        </div>
    </form>
</div>

<?php require_once '../app/Views/layouts/footer.php'; ?>
