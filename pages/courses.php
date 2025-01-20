<?php
require_once "./components/header.php";
require_once "./components/navbar.php";
?>
<main class="my-20">
    <div class="container">
        <!-- Search Bar -->
        <div class="my-6">
            <div class="flex gap-4 max-w-xl">
                <input type="text" id="search_input" placeholder="Search courses..."
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                <button onclick="handleSearch()"
                    class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Search
                </button>
            </div>
        </div>

        <div id="courses_list" class="grid my-16 md:grid-cols-2 gap-10 lg:grid-cols-3"></div>

        <!-- Pagination -->
        <div class="flex items-center justify-center my-10">
            <ul class="flex items-center -space-x-px h-8 text-sm">
                <li>
                    <a id="previous_btn" onclick="fetchCoursesData(currentPage - 1)"
                        class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700">
                        <span class="sr-only">Previous</span>
                        <svg class="w-2.5 h-2.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4" />
                        </svg>
                    </a>
                </li>
                <div id="pagination_list" class="flex items-center -space-x-px h-8 text-sm"></div>
                <li>
                    <a id="next_btn" onclick="fetchCoursesData(currentPage + 1)"
                        class="flex cursor-not-allowed items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700">
                        <span class="sr-only">Next</span>
                        <svg class="w-2.5 h-2.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                        </svg>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    let courses_list = document.getElementById("courses_list");
    let coursesData = [];
    let filteredData = [];
    let pagination_list = document.getElementById("pagination_list");
    let search_input = document.getElementById("search_input");
    let currentPage = 1;
    const limit = 9;
    let totalPages = 1;
    let currentSearchTerm = '';

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

    // Improved search handler with debounce
    let searchTimeout;
    const handleSearch = () => {
        currentSearchTerm = search_input.value.trim();
        console.log('Searching for:', currentSearchTerm); // Debug log
        fetchCoursesData(1);
    }

    // Search event listeners
    search_input.addEventListener("keyup", handleSearch);
    search_input.addEventListener("search", handleSearch); // Handles clear button click

    const appendPagination = () => {
        if (totalPages <= 1) {
            pagination_list.innerHTML = "";
            return;
        }

        pagination_list.innerHTML = "";
        for (let i = 1; i <= totalPages; i++) {
            pagination_list.innerHTML += `
            <li>
                <a 
                    onclick="fetchCoursesData(${i})" 
                    class="flex items-center justify-center px-3 h-8 leading-tight cursor-pointer
                    ${currentPage == i 
                        ? "text-blue-600 border border-gray-300 bg-blue-50" 
                        : "text-gray-500 bg-white border border-gray-300"
                    } hover:bg-gray-100 hover:text-gray-700"
                >
                    ${i}
                </a>
            </li>
        `;
        }
    };

   

    const fetchCoursesData = async (clickedPage) => {
        try {
            if (clickedPage && clickedPage < 1 || (totalPages && clickedPage > totalPages)) return;

            setLoading(true);
            currentPage = clickedPage;

            // Log the request payload
            console.log('Sending request with:', {
                page: clickedPage || 1,
                limit,
                search: currentSearchTerm
            });

            const res = await axios.post('/uknow/Controllers/course/view.php', {
                page: clickedPage || 1,
                limit,
                search: currentSearchTerm,
            });

            // Log the response
            console.log('Received response:', res.data);

            if (res.data.courses && Array.isArray(res.data.courses)) {
                totalPages = res.data.totalPages;
                coursesData = res.data.courses;
                filteredData = coursesData;

                // Log the filtered data
                console.log('Filtered courses:', filteredData);

                appendPagination();
                appendData();
                updateNavigationButtons();
            } else {
                throw new Error('Invalid data format received');
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

    const updateNavigationButtons = () => {
        const nextBtn = document.getElementById("next_btn");
        const prevBtn = document.getElementById("previous_btn");

        if (nextBtn && prevBtn) {
            prevBtn.classList.toggle("cursor-not-allowed", currentPage <= 1);
            prevBtn.classList.toggle("opacity-50", currentPage <= 1);
            nextBtn.classList.toggle("cursor-not-allowed", currentPage >= totalPages);
            nextBtn.classList.toggle("opacity-50", currentPage >= totalPages);
        }
    };

    const appendData = () => {
        if (!filteredData.length) {
            courses_list.innerHTML = `
            <div class="col-span-3 text-center text-gray-500 py-10">
                No courses found${currentSearchTerm ? ` for "${currentSearchTerm}"` : ''}.
            </div>
        `;
            return;
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
    };

    // Initial load
    fetchCoursesData(1);
</script>
<?php require_once "./components/footer.php"; ?>