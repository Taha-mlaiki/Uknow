<?php
require_once "../components/header.php";
require_once "./auth.php";
require_once "../../vendor/autoload.php";

use Models\Course;
use Models\Student;

$coursesCount = Course::countCourses();
$courseDistribution = Course::courseDistribution();
$best3Courses = Course::getBest3Courses();
$best3Students = Student::best3Students();

?>
<!-- Sidebar -->
<?php
require_once './components/sidebar.php'
?>

<!-- Main Content -->
<div class="md:ml-64 p-4">
    <!-- Header -->
    <div class="flex justify-between mt-14 md:mt-1 items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold">Course Dashboard</h1>
            <p class="text-gray-600">Welcome back, Admin!</p>
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

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600">Total Courses</p>
                    <h3 class="text-2xl font-bold"><?= $coursesCount ?></h3>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-book text-blue-600"></i>
                </div>
            </div>
        </div>

    </div>
    <div class="grid lg:grid-cols-2 max-h-[500px] overflow-y-auto gap-10">
        <div class="bg-white rounded-lg p-5">
            <h1 class="font-bold text-xl text-blue-700 mb-5">Courses distribution by category</h1>
            <?php foreach ($courseDistribution as $category) : ?>
                <div class="flex my-4 items-center justify-between">
                    <span class="font-bold text-md"><?= $category["category_name"] ?></span>
                    <span class="font-bold text-lg text-neutral-700"><?= $category["course_count"] ?></span>
                </div>

            <?php endforeach; ?>
        </div>
        <div class="bg-white rounded-lg  p-5">
            <h1 class="font-bold text-xl text-blue-700 mb-5">Best 3 courses</h1>
            <?php foreach ($best3Courses as $course) : ?>
                <div class="flex my-4 items-center justify-between">
                    <span class="font-bold text-md"><?= $course["title"] ?></span>
                    <span class="font-bold text-lg text-neutral-700"><?= $course["enrolled_users"] ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- Students Table -->
    <div class="bg-white rounded-lg shadow my-8">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold">All Students</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Courses enrolled</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="studentsList">
                    <?php foreach($best3Students as $student) :?>
                    <tr>
                        <td class="px-6 py-4"><?= $student["username"] ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?= $student["email"] ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?= $student["enrolled_courses"] ?></td>
                    </tr>
                    <?php endforeach ;?>
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