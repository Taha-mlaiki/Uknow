<?php
require_once "../components/header.php";
require_once "./auth.php";
?>
<!-- Sidebar -->
<?php
    require_once './components/sidebar.php'
?>

<!-- Main Content -->
<div class="md:ml-64 p-4 mt-5 ">
    <!-- Header -->
    <div class="flex justify-between mt-14 md:mt-1 items-center mb-8">
        <div class="flex items-center space-x-2">
            <i class="fas fa-chalkboard-teacher text-2xl"></i>
            <h1 class="text-2xl font-bold">Teachers</h1>
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

    <!-- Teachers Table -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold">All Teachers</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="teachersList">
                    <tr>
                        <td colspan="4" class="px-6 py-4">
                            <p class="text-gray-900 text-center text-xl font-semibold">Loading...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // Utility function to create teacher row
    function createTeacherRow(teacher) {
        const statusClass = teacher.isActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
        const statusText = teacher.isActive ? 'Active' : 'Inactive';
        const actionIcon = teacher.isActive ? 'fa-ban' : 'fa-check';
        const actionColor = teacher.isActive ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900';
        const actionTitle = teacher.isActive ? 'Deactivate' : 'Activate';
        return `
            <tr>
                <td class="px-6 py-4">${teacher.username}</td>
                <td class="px-6 py-4 text-sm text-gray-500">${teacher.email}</td>
                <td class="px-6 py-4">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                        ${statusText}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm">
                    <button onclick="toggleTeacherStatus(${teacher.id}, ${teacher.isActive})" 
                            class="${actionColor} text-xl mx-8" 
                            title="${actionTitle}">
                        <i class="fas ${actionIcon}"></i>
                    </button>
                </td>
            </tr>
        `;
    }

    // Load teachers from server
    async function loadTeachers() {
        try {
            const res = await axios.get('http://www.localhost/uknow/Controllers/teacher/view.php')
            const teachers = res.data.teachers;
            const teachersList = document.getElementById('teachersList');
            teachersList.innerHTML = teachers.map(teacher => createTeacherRow(teacher)).join('');
        } catch (error) {
            console.error('Error loading teachers:', error);
            showToast('Error loading teachers', 'error');
        }
    }

    // Toggle teacher status
    async function toggleTeacherStatus(teacherId, currentStatus) {
        const action = currentStatus ? 'desactivate' : 'activate';
        const confirmMessage = `Are you sure you want to ${action} this teacher?`;
        if (confirm(confirmMessage)) {
            try {
                const res = await axios.post('http://www.localhost/uknow/Controllers/teacher/toggleStatus.php', {
                    id: teacherId,
                    status: !currentStatus
                });
                if (res.data.message) {
                    showToast(res.data.message);
                    loadTeachers(); 
                } else if (res.data.error) {
                    showToast(res.data.error, 'error');
                }
            } catch (error) {
                console.error(`Error ${action}ing teacher:`, error);
                showToast(`Error ${action}ing teacher`, 'error');
            }
        }
    }

    // User menu toggle
    const userMenu = document.getElementById('user-menu-button');
    userMenu.addEventListener("click", () => {
        document.getElementById("user-dropdown").classList.toggle("hidden")
    });

    // Initial load
    loadTeachers();
</script>

<?php
require_once "../components/footer.php";
?>