<?php
require_once "../../vendor/autoload.php";
require_once "../components/header.php";
require_once "./auth.php";
use Models\Course as CourseModel;

// Initialize variables
$isEdit = false;
$course = null;
$courseId = $_GET['courseId'] ?? null;

if ($courseId) {
    $isEdit = true;
    $course = CourseModel::getCourseById($courseId);
    if (!$course[$courseId]) {
        $_SESSION['error'] = "Course not found";
        header("Location: ./courses.php");
        exit;
    }
}

$pageTitle = $isEdit ? "Edit Course: " . htmlspecialchars($course[$courseId]->getTitle()) : "Add New Course";
?>

<?php require_once './components/sidebar.php' ?>

<div class="md:ml-64 p-4">
    <div class="min-h-screen p-6 bg-gray-50">
        <div class="container max-w-screen-xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-6 md:p-8">
                <h1 class="text-2xl font-bold mb-8"><?= $pageTitle ?></h1>

                <!-- Course Form -->
                <form id="courseForm" class="space-y-6">
                    <?php if ($isEdit): ?>
                        <input type="hidden" id="courseId" value="<?= htmlspecialchars($courseId) ?>">
                    <?php endif; ?>

                    <!-- Thumbnail Section -->
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold">Course Thumbnail</h2>
                        <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg p-6 bg-gray-50">
                            <div id="thumbnailPreview" class="<?= ($isEdit && $course[$courseId]->getThumbnail()) ? '' : 'hidden' ?> mb-4">
                                <img src="../../assets/uploads/<?= $isEdit ? htmlspecialchars($course[$courseId]->getThumbnail()) : '' ?>"
                                    alt="Thumbnail preview" class="max-w-xs rounded-lg shadow-md">
                            </div>
                            <label class="cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                <span class="rounded-md border border-gray-300 bg-white py-2 px-4 hover:bg-gray-50">
                                    <?= $isEdit ? 'Change Thumbnail' : 'Upload Thumbnail' ?>
                                </span>
                                <input type="file" id="thumbnail" name="thumbnail" accept="image/*" class="sr-only">
                            </label>
                            <p class="mt-2 text-sm text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            <p class="mt-1 text-sm text-red-600 hidden" id="thumbnailError"></p>
                        </div>
                    </div>

                    <!-- Course Details Section -->
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold">Course Details</h2>

                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" id="title" name="title"
                                value="<?= $isEdit ? htmlspecialchars($course[$courseId]->getTitle()) : '' ?>"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="mt-1 text-sm text-red-600 hidden" id="titleError"></p>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="description" name="description" rows="4"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm resize-y min-h-[100px]"
                                placeholder="Write a brief description of your course..."><?= $isEdit ? htmlspecialchars($course[$courseId]->getDescription()) : '' ?></textarea>
                            <p class="mt-1 text-sm text-red-600 hidden" id="descriptionError"></p>
                        </div>
                    </div>

                    <!-- Content Type Selection -->
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold">Content Type</h2>
                        <div>
                            <select id="contentTypeSelect" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select content type</option>
                                <option value="video" <?= ($isEdit && $course[$courseId]->getVideo()) ? 'selected' : '' ?>>Upload Video</option>
                                <option value="document-write" <?= ($isEdit && $course[$courseId]->getDocument()) ? 'selected' : '' ?>>Write Document</option>
                            </select>
                        </div>

                        <!-- Video Section -->
                        <div id="videoUpload" class="<?= ($isEdit && $course[$courseId]->getVideo()) ? '' : 'hidden' ?> space-y-4">
                            <label class="block text-sm font-medium text-gray-700">Upload Video</label>
                            <?php if ($isEdit && $course[$courseId]->getVideo()): ?>
                                <div class="mb-2">
                                    <p class="text-sm text-gray-600">Current video: <?= htmlspecialchars($course[$courseId]->getVideo()) ?></p>
                                </div>
                            <?php endif; ?>
                            <input type="file" id="video" name="video" accept="video/*"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="mt-1 text-sm text-red-600 hidden" id="videoError"></p>
                        </div>

                        <!-- Document Section -->
                        <div id="documentWrite" class="<?= ($isEdit && $course[$courseId]->getDocument()) ? '' : 'hidden' ?> space-y-4">
                            <label class="block text-sm font-medium text-gray-700">Write Document</label>
                            <textarea id="documentEditor" name="documentEditor"></textarea>
                            <p class="mt-1 text-sm text-red-600 hidden" id="documentWriteError"></p>
                        </div>
                    </div>

                    <!-- Tags and Categories Section -->
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold">Tags and Categories</h2>

                        <div>
                            <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                            <select id="tags" name="tags" multiple class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <?php if ($isEdit): ?>
                                    <?php foreach ($course[$courseId]->getTags() as $tag): ?>
                                        <option selected value="<?= htmlspecialchars($tag->getId()) ?>">
                                            <?= htmlspecialchars($tag->getName()) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <p class="mt-1 text-sm text-red-600 hidden" id="tagsError"></p>
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                            <select id="category" name="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select a category</option>
                                <?php if ($isEdit && $course[$courseId]->getCategoryId()): ?>
                                    <option selected value="<?= htmlspecialchars($course[$courseId]->getCategoryId()) ?>">
                                        <?= htmlspecialchars($course[$courseId]->getCategoryName()) ?>
                                    </option>
                                <?php endif; ?>
                            </select>
                            <p class="mt-1 text-sm text-red-600 hidden" id="categoryError"></p>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4">
                        <button type="button" id="resetBtn"
                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Reset
                        </button>
                        <button type="submit"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <?= $isEdit ? 'Update Course' : 'Save Course' ?>
                        </button>
                    </div>
                </form>

                <!-- Loading Spinner -->
                <div id="loadingSpinner" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
                    <div class="animate-spin rounded-full h-32 w-32 border-b-2 border-white"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isEdit = <?= json_encode($isEdit) ?>;
        const initialDocument = <?= $isEdit ? json_encode($course[$courseId]->getDocument()) : 'null' ?>;

        // Initialize TinyMCE
        tinymce.init({
            selector: '#documentEditor',
            height: 500,
            menubar: false,
            plugins: 'lists link image table code',
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image | table | code',
            setup: function(editor) {
                // Set initial content when editor is initialized
                editor.on('init', function() {
                    if (initialDocument) {
                        editor.setContent(initialDocument);
                    }
                });
            }
        });

        // Initialize Choices.js
        const tagsChoices = new Choices('#tags', {
            removeItemButton: true,
            maxItemCount: 5,
            placeholder: true,
            placeholderValue: 'Select tags (max 5)'
        });

        const categoryChoices = new Choices('#category', {
            placeholder: true,
            placeholderValue: 'Select a category'
        });

        const contentTypeChoices = new Choices('#contentTypeSelect', {
            placeholder: true,
            placeholderValue: 'Select content type'
        });

        // Fetch tags and categories
        async function fetchTagsAndCategories() {
            try {
                const [tagsResponse, categoriesResponse] = await Promise.all([
                    axios.get("http://www.localhost/uknow/Controllers/tag/view.php"),
                    axios.get("http://www.localhost/uknow/Controllers/category/view.php")
                ]);


                const tags = tagsResponse.data.tags;
                const categories = categoriesResponse.data.categories;

                const selectedOptions = Array.from(document.querySelectorAll('#tags option')).filter(option => option.selected);
                
                const selectedValues = selectedOptions.map(option => Number(option.value));

                const availableTags = tags.filter(tag => !selectedValues.includes(tag.id));
                tagsChoices.setChoices(availableTags.map(tag => ({
                    value: tag.id,
                    label: tag.name
                })), 'value', 'label', true);

                categoryChoices.setChoices(categories.map(category => ({
                    value: category.id,
                    label: category.name
                })), 'value', 'label', true);
            } catch (error) {
                console.error('Error fetching tags and categories:', error);
                showToast('Failed to load tags and categories', 'error');
            }
        }

        fetchTagsAndCategories();

        // Handle content type selection
        const contentTypeSelect = document.getElementById('contentTypeSelect');
        const videoUpload = document.getElementById('videoUpload');
        const documentWrite = document.getElementById('documentWrite');

        contentTypeSelect.addEventListener('change', (e) => {
            videoUpload.classList.add('hidden');
            documentWrite.classList.add('hidden');

            switch (e.target.value) {
                case 'video':
                    videoUpload.classList.remove('hidden');
                    break;
                case 'document-write':
                    documentWrite.classList.remove('hidden');
                    break;
            }
        });

        // Handle thumbnail preview
        const thumbnailInput = document.getElementById('thumbnail');
        const thumbnailPreview = document.getElementById('thumbnailPreview');
        const thumbnailImage = thumbnailPreview.querySelector('img');

        thumbnailInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                if (!validateFileSize(file,20)) {
                    showError('thumbnail', 'File size should not exceed 2MB');
                    e.target.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = (e) => {
                    thumbnailImage.src = e.target.result;
                    thumbnailPreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        // Form submission
        const form = document.getElementById('courseForm');
        const loadingSpinner = document.getElementById('loadingSpinner');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (!validateForm()) return;

            loadingSpinner.classList.remove('hidden');
            const formData = new FormData();

            // Add course ID if editing
            if (isEdit) {
                formData.append('courseId', document.getElementById('courseId').value);
            }

            // Append form data
            formData.append('title', document.getElementById('title').value);
            formData.append('description', document.getElementById('description').value);
            formData.append('category', document.getElementById('category').value);

            const selectedTags = tagsChoices.getValue();
            formData.append('tags', JSON.stringify(selectedTags.map(tag => tag.value)));
            // Append files only if they're selected
            const thumbnail = document.getElementById('thumbnail').files[0];
            const video = document.getElementById('video').files[0];
            if (thumbnail) formData.append('thumbnail', thumbnail);
            if (video) formData.append('video', video);

            // Append document content if it exists
            const documentContent = tinymce.get('documentEditor').getContent();
            if (documentContent) {
                formData.append('document', documentContent);
            }
            let res = null;
            try {
                const endpoint = isEdit ?
                    "http://www.localhost/uknow/Controllers/course/update.php" :
                    "http://www.localhost/uknow/Controllers/course/create.php";

                const res = await axios.post(endpoint, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                if (res.data.success) {
                    showToast(res.data.success);
                    setTimeout(() => {
                        window.location.href = './courses.php';
                    }, 2000);
                }{
                    showToast(res.data.error, 'error');
                }
            } catch (error) {
                console.log(res);
                console.error('Error:', error);
                showToast('Failed to save course', 'error');
            } finally {
                loadingSpinner.classList.add('hidden');
            }
        });

        // Form reset handler
        const resetBtn = document.getElementById('resetBtn');
        resetBtn.addEventListener('click', () => {
            if (!confirm('Are you sure you want to reset the form? All unsaved changes will be lost.')) {
                return;
            }

            form.reset();
            if (tinymce.get('documentEditor')) {
                tinymce.get('documentEditor').setContent(isEdit ? initialDocument : '');
            }

            // Reset thumbnail preview
            if (!isEdit) {
                thumbnailPreview.classList.add('hidden');
            } else {
                thumbnailImage.src = `../../assets/uploads/${initialThumbnail}`;
            }

            // Reset choices
            tagsChoices.removeActiveItems();
            categoryChoices.setChoiceByValue('');
            contentTypeChoices.setChoiceByValue('');

            // Reset content type sections
            videoUpload.classList.add('hidden');
            documentWrite.classList.add('hidden');

            clearErrors();
        });

        // Form validation
        function validateForm() {
            clearErrors();
            let isValid = true;

            // Title validation
            const title = document.getElementById('title');
            if (!title.value.trim()) {
                showError('title', 'Title is required');
                isValid = false;
            } else if (title.value.length < 5) {
                showError('title', 'Title must be at least 5 characters long');
                isValid = false;
            }

            // Description validation
            const description = document.getElementById('description');
            if (!description.value.trim()) {
                showError('description', 'Description is required');
                isValid = false;
            } else if (description.value.length < 20) {
                showError('description', 'Description must be at least 20 characters long');
                isValid = false;
            }

            // Thumbnail validation
            const thumbnail = document.getElementById('thumbnail');
            if (!isEdit && !thumbnail.files[0]) {
                showError('thumbnail', 'Thumbnail is required');
                isValid = false;
            }

            // Content type validation
            const contentType = contentTypeSelect.value;
            if (!contentType) {
                showError('contentTypeSelect', 'Please select a content type');
                isValid = false;
            } else {
                switch (contentType) {
                    case 'video':
                        const video = document.getElementById('video');
                        if (!isEdit && !video.files[0]) {
                            showError('video', 'Video file is required');
                            isValid = false;
                        }
                        break;
                    case 'document-write':
                        const documentContent = tinymce.get('documentEditor').getContent();
                        if (!documentContent.trim()) {
                            showError('documentWrite', 'Document content is required');
                            isValid = false;
                        }
                        break;
                }
            }

            // Category validation
            const category = document.getElementById('category');
            if (!category.value) {
                showError('category', 'Category is required');
                isValid = false;
            }

            // Tags validation
            const selectedTags = tagsChoices.getValue();
            if (selectedTags.length === 0) {
                showError('tags', 'Please select at least one tag');
                isValid = false;
            } else if (selectedTags.length > 5) {
                showError('tags', 'Maximum 5 tags allowed');
                isValid = false;
            }

            return isValid;
        }

        // Error handling utilities
        function showError(fieldId, message) {
            const errorElement = document.getElementById(`${fieldId}Error`);
            if (errorElement) {
                errorElement.textContent = message;
                errorElement.classList.remove('hidden');
                // Smooth scroll to error
                errorElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        }

        function clearErrors() {
            const errorElements = document.querySelectorAll('[id$="Error"]');
            errorElements.forEach(element => {
                element.textContent = '';
                element.classList.add('hidden');
            });
        }

        // File size validation
        function validateFileSize(file, maxSizeMB = 2) {
            const fileSize = file.size / (1024 * 1024); // Convert to MB
            return fileSize <= maxSizeMB;
        }

        // Add file size validation to all file inputs
        ['thumbnail', 'video'].forEach(inputId => {
            const input = document.getElementById(inputId);
            input.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file && !validateFileSize(file)) {
                    showError(inputId, `File size should not exceed 2MB`);
                    e.target.value = ''; // Clear the input
                }
            });
        });

        // Toast notification function
        function showToast(message, type = 'success') {
            // You can implement your preferred toast notification here
            // Example using a simple alert (replace with your toast library)
            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 p-4 rounded-md ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } text-white`;
            toast.textContent = message;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    });
</script>

<?php require_once "../components/footer.php"; ?>