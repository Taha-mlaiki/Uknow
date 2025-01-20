<?php
require_once "../components/header.php";
?>
<!-- Sidebar -->
<?php
require_once './components/sidebar.php';
require_once "./auth.php";
?>

<!-- Main Content -->
<div class="md:ml-64 p-4">
    <!-- Header -->
    <div class="flex justify-between mt-14 md:mt-1 items-center mb-8">
        <div class="flex items-center space-x-2">
            <i class="fas fa-book text-2xl"></i>
            <h1 class="text-2xl font-bold">Courses</h1>
        </div>
        <div class="flex items-center space-x-4">
            <!-- Add Course Button -->
            <a href="./createCourse.php" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                Add New Course
            </a>

            <!-- User Menu -->
            <div class="relative w-fit">
                <button type="button" id="user-menu-button" class="flex text-sm bg-gray-800 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600 cursor-pointer" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
                    <span class="sr-only">Open user menu</span>
                    <img class="w-8 h-8 rounded-full" src="https://flowbite.com/docs/images/people/profile-picture-3.jpg" alt="user photo">
                </button>
                <!-- Dropdown menu -->
                <div class="z-50 hidden absolute top-10 right-0 my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600" id="user-dropdown">
                    <div class="px-4 py-3">
                        <span class="block text-sm text-gray-900 dark:text-white"><?= $_SESSION['user']["username"] ?></span>
                        <span class="block text-sm text-gray-500 truncate dark:text-gray-400"><?= $_SESSION['user']["email"] ?></span>
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



    <!-- Courses Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold">My Courses</h2>
        </div>
        <div class="relative">
            <!-- Table Container with shadow indicators -->
            <div class="relative rounded-lg shadow">
                <!-- Scroll shadows -->
                <div class="absolute left-0 top-0 bottom-0 w-4 bg-gradient-to-r from-white to-transparent pointer-events-none z-10"></div>
                <div class="absolute right-0 top-0 bottom-0 w-4 bg-gradient-to-l from-white to-transparent pointer-events-none z-10"></div>

                <!-- Table Wrapper -->
                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full align-middle">
                        <table class="min-w-full divide-y divide-gray-200" id="coursesTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-[200px]">
                                        Course Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-[300px]">
                                        Description
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-[150px]">
                                        Category
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-[200px]">
                                        Tags
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-[120px]">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="coursesTableBody">
                                <!-- Example row structure -->
                               <tr>
                                <th colspan="5">
                                    <p class="text-gray-900 text-center text-xl font-semibold">Loading...</p>
                                </th>
                               </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Course</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Are you sure you want to delete this course? This action cannot be undone.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="deleteConfirm" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Delete
                    </button>
                    <button id="deleteCancel" class="ml-2 px-4 py-2 bg-gray-100 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // State management
        let courses = [];
        let currentFilters = {
            search: '',
            category: '',
            sort: 'newest'
        };
        let courseToDelete = null;

        // DOM Elements
        const coursesTableBody = document.getElementById('coursesTableBody');
        const deleteModal = document.getElementById('deleteModal');
        const deleteConfirm = document.getElementById('deleteConfirm');
        const deleteCancel = document.getElementById('deleteCancel');

        // Fetch courses data
        async function fetchCourses() {
            try {
                // Replace this with your actual API endpoint
                const res = await axios.get('/uknow/Controllers/course/teacherCourses.php');
                const data = res.data.courses;
                courses = data;
                renderCourses();
            } catch (error) {
                console.error('Error fetching courses:', error);
            }
        }

        // Render courses table
        function renderCourses() {

            coursesTableBody.innerHTML = courses.map(course => `
        <tr>
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <img src="${course.thumbnail ? `/uknow/assets/uploads/${course.thumbnail}` : ''}" 
                             class="h-16 aspect-video object-cover rounded-md bg-gray-100" 
                             alt="Course thumbnail" />
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900 line-clamp-2">${course.title}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="text-sm text-gray-500 line-clamp-2">${course.description}</div>
            </td>
            <td class="px-6 py-4">
                <div class="text-sm text-gray-500 line-clamp-1">${course.category_name}</div>
            </td>
            <td class="px-6 py-4">
                <div class="flex flex-wrap gap-2 gap-1">
                    ${course.tags.map(tag => `
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            ${tag.name}
                        </span>
                    `).join('')}
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="flex space-x-3">
                    <button onclick="updateCourse(${course.id})" 
                            class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="showDeleteModal(${course.id})" 
                            class="text-red-600 hover:text-red-900 transition-colors duration-200">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
        }
        // Update course handler
        window.updateCourse = function(courseId) {
            window.location.href = `./createCourse.php?courseId=${courseId}`;
        };

        // Delete course handlers
        window.showDeleteModal = function(courseId) {
            courseToDelete = courseId;
            deleteModal.classList.remove('hidden');
        };

        deleteConfirm.addEventListener('click', async () => {
            if (courseToDelete) {
                try {
                    // Replace with your actual delete API endpoint
                    const res =  await axios.post(`/uknow/Controllers/course/delete.php`,{
                        courseId:courseToDelete
                    });
                    if(res.data.success){
                        showToast(res.data.success);
                        courses = courses.filter(course => course.id !== courseToDelete);
                        renderCourses();
                    }else {
                        showToast(res.data.error, 'error');
                    }
                } catch (error) {
                    console.error('Error deleting course:', error);
                } finally {
                    deleteModal.classList.add('hidden');
                    courseToDelete = null;
                }
            }
        });

        deleteCancel.addEventListener('click', () => {
            deleteModal.classList.add('hidden');
            courseToDelete = null;
        });

        // Initialize user menu toggle
        const userMenu = document.getElementById('user-menu-button');
        userMenu.addEventListener("click", () => {
            document.getElementById("user-dropdown").classList.toggle("hidden");
        });

        // Initialize the page
        fetchCourses();
    });
</script>

<?php
require_once "../components/footer.php";
?>