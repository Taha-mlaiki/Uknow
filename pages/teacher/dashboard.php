<?php
require_once "../components/header.php";
require_once "../../vendor/autoload.php";
require_once "./auth.php";
use Models\Teacher;

    $studentCount = Teacher::getStudentsCount();

?>
<?php
require_once './components/sidebar.php';
?>

<!-- Main Content -->
<div class="md:ml-64 p-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold">Course Dashboard</h1>
            <p class="text-gray-600">Welcome back, Professor!</p>
        </div>
        <div class="flex items-center space-x-4">
            <a href="./createCourse.php">
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>Add New Course
                </button>
            </a>
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

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600">Total Courses</p>
                    <h3 class="text-2xl font-bold">24</h3>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-book text-blue-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600">Active Students</p>
                    <h3 class="text-2xl font-bold"><?= $studentCount ? $studentCount : 0 ?></h3>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-users text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600">Completion Rate</p>
                    <h3 class="text-2xl font-bold">87%</h3>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-chart-pie text-yellow-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600">Average Rating</p>
                    <h3 class="text-2xl font-bold">4.8</h3>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-star text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Courses Table -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold">Recent Courses</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Students</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-2 rounded">
                                    <i class="fas fa-laptop-code text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">Web Development Basics</div>
                                    <div class="text-sm text-gray-500">Technology</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">64 Students</td>
                        <td class="px-6 py-4">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: 75%"></div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Active
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <button class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-edit"></i></button>
                            <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="bg-purple-100 p-2 rounded">
                                    <i class="fas fa-paint-brush text-purple-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">Digital Design</div>
                                    <div class="text-sm text-gray-500">Design</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">48 Students</td>
                        <td class="px-6 py-4">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: 45%"></div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Draft
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <button class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-edit"></i></button>
                            <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
<script>
    // set the target element that will be collapsed or expanded (eg. navbar menu)
    const userMenu = document.getElementById('user-menu-button');
    userMenu.addEventListener("click", () => {
        document.getElementById("user-dropdown").classList.toggle("hidden")
    })
</script>
<?php
require_once "../components/footer.php";
?>