<?php
require_once "./components/header.php";
require_once "./components/navbar.php";
session_start();
$userId = isset($_SESSION["user"]["id"]) ? $_SESSION["user"]["id"] : null ;
if (!$userId) {
    header("location: /uknow/pages/forbidden.php");
    exit();
}

?>
<main class="my-20">
    <input type="hidden" id="userId" value="<?= $userId  ?>">

    <div class="container">
        <h1 class="font-bold text-2xl">My Courses</h1>

        <div id="courses_list" class="grid my-16 md:grid-cols-2 gap-10 lg:grid-cols-3"></div>

    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    let courses_list = document.getElementById("courses_list");
    let coursesData = [];
    let filteredData = [];


    // Add loading state handler
    const setLoading = (isLoading) => {
        if (isLoading) {
            courses_list.innerHTML = `
            <div class="col-span-3 flex justify-center items-center py-10">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
            </div>
        `;
        }
    };

    const fetchCoursesData = async () => {
        try {
            setLoading(true);
            const userData = document.getElementById("userId").value
            const res = await axios.post('/uknow/Controllers/enrollement/myCourses.php', {
                userId: userData
            });
            if (res.data.courses && Array.isArray(res.data.courses)) {
                filteredData = res.data.courses;
                appendData();
            }
        } catch (error) {
            console.error('Error fetching courses:', error);
            courses_list.innerHTML = `
            <div class="col-span-3 text-center text-red-500 py-10">
                Error loading courses. Please try again later.
            </div>
        `;
        } finally {
            setLoading(false);
        }
    };


    const appendData = () => {
        if (filteredData.length <= 0) {
            courses_list.innerHTML = `
            <div class="col-span-3 mt-20 text-center text-gray-500 py-10">
                No courses Available
            </div>
        `;
        return ;
        }
        courses_list.innerHTML = filteredData.map(course => `
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                <div class="relative">
                    <img
                        src='../assets/uploads/${course.thumbnail}'
                        alt="${course.title}"
                        onerror="this.src='../assets/images/default-course.jpg'"
                        class="w-full h-80 aspect-video object-cover"
                    />
                </div>
    
                <div class="p-6">
                    <div class="flex flex-wrap gap-2 mb-4">
                        ${course.tags ? course.tags.split(',').map(tag => `
                            <span class="px-3 py-1 bg-blue-50 text-blue-600 text-xs font-medium rounded-full">
                                ${tag.trim()}
                            </span>
                        `).join('') : ''}   
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">${course.title}</h3>
                    <div class="flex items-end justify-between w-full mt-5">
                        <div class="flex items-center">
                            <div>
                                <p class="text-xs text-gray-500">${course.user_name}</p>
                                <p class="text-xs font-medium text-gray-900">${course.user_email}</p>
                            </div>
                        </div>
                        <a href="./courseDetails.php?id=${course.id}">
                            <button class="flex items-center text-blue-500 hover:text-blue-600 text-sm font-medium">
                                View Course
                                <i class="fa-solid fa-chevron-right ml-1"></i>
                            </button>      
                        </a>
                    </div>
                </div>
            </div>
        `).join('');
    }



    // Initial load
    fetchCoursesData();
</script>
<?php require_once "./components/footer.php"; ?>