 <?php
    session_start();
    $role = $_SESSION["user"]["role_id"] ?? null;
?>

 <header class="fixed w-full top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-200/50">
     <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
         <div class="flex items-center justify-between h-16">
             <!-- Logo -->
             <div class="flex-shrink-0 flex items-center">
                 <a href="/uknow/pages/" class="flex items-center space-x-2">
                     <i data-lucide="graduation-cap" class="h-8 w-8 text-indigo-600"></i>
                     <span class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">
                         <span class="text-black">U</span>Know
                     </span>
                 </a>
             </div>

             <!-- Desktop Navigation -->
             <div class="hidden md:flex items-center space-x-8">
                 <a href="./" class="text-gray-700 hover:text-indigo-600 transition-colors duration-200 relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-indigo-600 after:transition-all">
                     Home
                 </a>
                 <?php if ($role && $_SESSION['user']["role_id"] == 3): ?>
                     <a href="./admin/dashboard.php" class="text-gray-700 hover:text-indigo-600 transition-colors duration-200 relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-indigo-600 after:transition-all">
                         Dashboard
                     </a>
                 <?php endif; ?>
                 <?php if ($role && $_SESSION['user']["role_id"] == 2): ?>
                     <a href="/uknow/pages/teacher/dashboard.php" class="text-gray-700 hover:text-indigo-600 transition-colors duration-200 relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-indigo-600 after:transition-all">
                         Dashboard
                     </a>
                 <?php endif; ?>
                 <?php if ($role && $_SESSION['user']["role_id"] == 1) : ?>
                     <a href="./Mycourses.php" class="text-gray-700 hover:text-indigo-600 transition-colors duration-200 relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-indigo-600 after:transition-all">
                         My courses
                     </a>
                 <?php endif; ?>
                 <a href="./courses.php" class="text-gray-700 hover:text-indigo-600 transition-colors duration-200 relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-indigo-600 after:transition-all">
                     Courses
                 </a>
             </div>

             <!-- Sign Up Button -->
             <?php if (!$role) : ?>
                 <div class="hidden md:flex items-center space-x-4">
                     <a href="./login.php" class="text-gray-700 hover:text-indigo-600 transition-colors duration-200">
                         Login
                     </a>
                     <a href="./signup.php" class="bg-indigo-600 text-white px-6 py-2 rounded-full font-medium 
                        hover:bg-indigo-700 transform hover:scale-105 transition-all duration-200 
                        shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/40">
                         Sign Up
                     </a>
                 </div>
             <?php else : ?>
                 <div class="relative w-fit">
                     <button type="button" id="user-menu-button" class="flex text-sm bg-gray-800 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600 cursor-pointer" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
                         <span class="sr-only">Open user menu</span>
                         <img class="w-8 h-8 rounded-full" src="https://flowbite.com/docs/images/people/profile-picture-3.jpg" alt="user photo">
                     </button>
                     <!-- Dropdown menu -->
                     <div class="z-50 hidden absolute top-10  right-0 my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600" id="user-dropdown">
                         <div class="px-4 py-3">
                             <span class="block text-sm text-gray-900 dark:text-white"><?= $_SESSION['user']["username"] ?></span>
                             <span class="block text-sm  text-gray-500 truncate dark:text-gray-400"><?= $_SESSION['user']["email"] ?></span>
                         </div>
                         <ul class="py-2" aria-labelledby="user-menu-button">
                             <form action="http://www.localhost/uknow/Controllers/auth/logout.php" method="POST">
                                 <input type="hidden" name="signout">
                                 <button type="submit" name="signout" class="block text-start w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Sign out</button>
                             </form>
                         </ul>
                     </div>
                 </div>
             <?php endif; ?>


             <!-- Mobile Menu Button -->
             <div class="md:hidden flex items-center">
                 <button class="mobile-menu-button p-2 rounded-md text-gray-700 hover:text-indigo-600 focus:outline-none">
                     <i class="fa-solid fa-bars"></i>
                 </button>
             </div>
         </div>

         <!-- Mobile Menu -->
         <div class="md:hidden mobile-menu hidden">
             <div class="px-2 pt-2 pb-3 space-y-1">
                 <a href="#" class="block px-3 py-2 rounded-md text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 transition-colors duration-200">
                     Home
                 </a>
                 <a href="#" class="block px-3 py-2 rounded-md text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 transition-colors duration-200">
                     Courses
                 </a>
                 <a href="#" class="block px-3 py-2 rounded-md text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 transition-colors duration-200">
                     About
                 </a>
                 <a href="#" class="block px-3 py-2 rounded-md text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 transition-colors duration-200">
                     Contact
                 </a>
                 <div class="pt-4 flex flex-col space-y-2">
                     <a href="#" class="px-3 py-2 text-gray-700 hover:text-indigo-600 transition-colors duration-200">
                         Login
                     </a>
                     <a href="#" class="bg-indigo-600 text-white px-6 py-2 rounded-full font-medium text-center
                            hover:bg-indigo-700 transition-colors duration-200 shadow-lg shadow-indigo-600/20">
                         Sign Up
                     </a>
                 </div>
             </div>
         </div>
     </nav>
 </header>
 <div id="toast-container" class="fixed top-5 right-5 space-y-3 z-50"></div>
 <script>
     // set the target element that will be collapsed or expanded (eg. navbar menu)
     const userMenu = document.getElementById('user-menu-button');
     userMenu.addEventListener("click", () => {
         document.getElementById("user-dropdown").classList.toggle("hidden")
     })
 </script>