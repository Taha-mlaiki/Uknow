<?php
require_once "../components/header.php";
require_once "./auth.php";

?>
<?php
require_once './components/sidebar.php'
?>

<!-- Main Content -->
<div class="md:ml-64 p-4">
    <!-- Header -->
    <div class="flex mt-14 md:mt-1 justify-between items-center mb-8">
        <div class="flex items-center space-x-2">
            <i class="fas fa-th-list text-2xl"></i>
            <h1 class="text-2xl font-bold">Categories</h1>
        </div>
        <div class="flex items-center space-x-4">
            <div class="relative w-fit">
                <button type="button" id="user-menu-button" class="flex text-sm bg-gray-800 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600 cursor-pointer" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
                    <span class="sr-only">Open user menu</span>
                    <img class="w-8 h-8 rounded-full" src="https://flowbite.com/docs/images/people/profile-picture-3.jpg" alt="user photo">
                </button>
                <!-- Dropdown menu -->
                <div class="z-50 hidden absolute top-10 right-0 my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600" id="user-dropdown">
                    <div class="px-4 py-3">
                        <span class="block text-sm text-gray-900 dark:text-white"><?= $_SESSION['user']["username"] ?></span>
                        <span class="block text-sm  text-gray-500 truncate dark:text-gray-400"><?= $_SESSION['user']["email"] ?></span>
                    </div>
                    <ul class="py-2" aria-labelledby="user-menu-button">
                        <li>
                            <a href="http://www.localhost/uknow/pages" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Home</a>
                        </li>
                        <form action="http://www.localhost/uknow/Controllers/auth/logout.php" method="POST">
                            <input type="hidden" name="signout">
                            <button type="submit" name="signout" class="block text-start w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Sign out</button>
                        </form>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Categories</h1>
        <button onclick="openCreateModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>Add Categories
        </button>
    </div>



    <!-- Recent Courses Table -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold">Categories</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 w-full text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category name</th>
                        <th class="px-6 py-3 pr-10 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="categoriesList">
                    <tr>
                        <td colspan="2" class="px-6 py-4">
                            <p class="text-gray-900 text-center text-xl font-semibold">Loading...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="max-w-4xl mx-auto">


    </div>
    <!-- Category modal -->
    <div id="categoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h2 class="text-lg font-semibold" id="modalTitle">Create Categories</h2>
                <form id="categoryForm" class="mt-4">
                    <input type="hidden" id="categoryId" value="">
                    <div id="categoryInputs" class="space-y-4">
                        <div class="flex items-center space-x-2">
                            <input type="text"
                                class="flex-1 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500"
                                name="categories[]"
                                placeholder="Category name">
                            <button type="button" class="text-red-500 hover:text-red-700" onclick="removeInput(this)">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <button id="add_input_btn" type="button"
                        onclick="addCategoryInput()"
                        class="mt-4 w-full border border-gray-300 rounded-lg px-4 py-2 hover:bg-gray-50">
                        <i class="fas fa-plus mr-2"></i>Add Another Category
                    </button>
                    <div class="flex justify-end space-x-4 mt-4">
                        <button type="button"
                            onclick="closeModal()"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Utility function to create category row
        function createCategoryRow(category) {
            return `
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">${category.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap space-x-2">
                        <button onclick="editCategory(${category.id}, '${category.name}')" 
                                class="text-blue-600 hover:text-blue-900">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteCategory(${category.id})" 
                                class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        }

        // Load categories from server
        async function loadCategories() {
            try {
                const res = await axios.get('http://www.localhost/uknow/Controllers/category/view.php')
                const categories = res.data.categories;
                const categoriesList = document.getElementById('categoriesList');
                categoriesList.innerHTML = categories.map(category => createCategoryRow(category)).join('');
            } catch (error) {
                console.error('Error loading categories:', error);
            }
        }

        // Modal management
        function openCreateModal() {
            document.getElementById('modalTitle').textContent = 'Create Categories';
            document.getElementById('categoryId').value = '';
            document.getElementById("add_input_btn").disabled = false;
            document.getElementById('categoryForm').reset();
            document.getElementById('categoryInputs').innerHTML = `
                <div class="flex items-center space-x-2">
                    <input type="text" 
                           class="flex-1 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500" 
                           name="categories[]" 
                           placeholder="Category name">
                    <button type="button" class="text-red-500 hover:text-red-700" onclick="removeInput(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            document.getElementById('categoryModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('categoryModal').classList.add('hidden');
        }

        // Input field management
        function addCategoryInput() {
            const container = document.getElementById('categoryInputs');
            const newInput = document.createElement('div');
            newInput.className = 'flex items-center space-x-2';
            newInput.innerHTML = `
                <input type="text" 
                       class="flex-1 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500" 
                       name="categories[]" 
                       placeholder="Category name">
                <button type="button" class="text-red-500 hover:text-red-700" onclick="removeInput(this)">
                    <i class="fas fa-times"></i>
                </button>
            `;
            container.appendChild(newInput);
        }

        function removeInput(button) {
            const inputsContainer = document.getElementById('categoryInputs');
            if (inputsContainer.children.length > 1) {
                button.parentElement.remove();
            }
        }

        // Edit category
        function editCategory(id, name) {
            document.getElementById('modalTitle').textContent = 'Update Category';
            document.getElementById("add_input_btn").disabled = true;
            document.getElementById('categoryId').value = id;
            document.getElementById('categoryInputs').innerHTML = `
                <div class="flex items-center space-x-2">
                    <input type="text" 
                           class="flex-1 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500" 
                           name="categories[]" 
                           value="${name}"
                           placeholder="Category name">
                </div>
            `;
            document.getElementById('categoryModal').classList.remove('hidden');
        }

        // Delete category
        async function deleteCategory(id) {
            if (confirm('Are you sure you want to delete this category?')) {
                try {
                    const res = await axios.post('http://www.localhost/uknow/Controllers/category/delete.php',{
                        id
                    })
                    if (res.data.message) {
                        showToast(res.data.message);
                        loadCategories();
                    }else if(res.data.error){
                        showToast(res.data.error, 'error');
                    }
                } catch (error) {
                    console.error('Error deleting category:', error);
                }
            }
        }

        // Form submission
        document.getElementById('categoryForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            // Validate inputs
            const inputs = document.querySelectorAll('input[name="categories[]"]');
            let hasEmpty = false;
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('border-red-500');
                    hasEmpty = true;
                } else {
                    input.classList.remove('border-red-500');
                }
            });

            if (hasEmpty) {
                showToast('Please fill all the fields ', 'error');
                return;
            }

            const categoryId = document.getElementById('categoryId').value;
            const categories = Array.from(inputs).map(input => input.value.trim());

            try {
                const path = categoryId ? 'update.php' : 'create.php';
                const res = await axios.post(`http://www.localhost/uknow/Controllers/category/${path}`, {
                    id: categoryId,
                    categories
                });
                if (res.data.message) {
                    showToast(res.data.message);
                    if(res.data.failed >= 0){
                        setTimeout(() => {
                            showToast(`${res.data.success} categories created successfully`);
                        }, 2000);
                        setTimeout(() => {
                            showToast(`${res.data.failed} categories failed to create`, res.data.failed > 0 ? "error":"success");
                        }, 2000);
                    }
                    closeModal();
                    loadCategories();
                    document.getElementById('categoryForm').reset();
                }
            } catch (error) {
                console.error('Error saving categories:', error);
            }
        });

        // Initial load
        loadCategories();
        const userMenu = document.getElementById('user-menu-button');
        userMenu.addEventListener("click", () => {
            document.getElementById("user-dropdown").classList.toggle("hidden")
        })
    </script>
</div>

<?php
require_once "../components/footer.php";
?>