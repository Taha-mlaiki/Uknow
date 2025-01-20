<?php

require_once "../vendor/autoload.php";

use Models\Course as Course;


require_once "./components/header.php";
require_once "./components/navbar.php";
$courses = Course::get4PublishedCoures();
?>


<section class="relative overflow-hidden bg-gray-50">
    <!-- Abstract background elements -->
    <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-bl from-indigo-50 to-transparent -z-10"></div>
    <div class="absolute bottom-0 left-0 w-1/2 h-1/2 bg-gradient-to-tr from-violet-50 to-transparent -z-10"></div>

    <!-- Decorative circles -->
    <div class="absolute top-20 right-20 w-72 h-72 bg-indigo-600/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-20 left-20 w-72 h-72 bg-violet-600/5 rounded-full blur-3xl"></div>

    <!-- Main content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Text content -->
            <div class="text-center lg:text-left">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
                    <span class="bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">
                        Transform Your Future
                    </span>
                    <br>
                    <span class="text-gray-900">With Expert Learning</span>
                </h1>

                <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                    Access world-class education from industry experts and transform your career with cutting-edge courses designed for modern learners.
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <!-- Primary CTA -->
                    <button class="group relative px-8 py-4 bg-indigo-600 text-white rounded-full font-medium 
                            transform hover:scale-105 transition-all duration-200 
                            shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/40">
                        Get Started Now
                        <span class="absolute inset-0 w-full h-full rounded-full bg-white/20 animate-ping group-hover:opacity-0"></span>
                    </button>

                    <!-- Secondary CTA -->
                    <button class="px-8 py-4 border-2 border-indigo-600 text-indigo-600 rounded-full font-medium 
                            hover:bg-indigo-50 transform hover:scale-105 transition-all duration-200">
                        Explore Courses
                    </button>
                </div>

                <!-- Stats -->
                <div class="mt-12 grid grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="flex justify-center mb-2">
                            <i class="fas fa-users text-2xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">50K+</h3>
                        <p class="text-sm text-gray-600">Active Students</p>
                    </div>
                    <div class="text-center">
                        <div class="flex justify-center mb-2">
                            <i class="fas fa-book-open text-2xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">200+</h3>
                        <p class="text-sm text-gray-600">Expert Courses</p>
                    </div>
                    <div class="text-center">
                        <div class="flex justify-center mb-2">
                            <i class="fas fa-star text-2xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">4.9/5</h3>
                        <p class="text-sm text-gray-600">User Rating</p>
                    </div>
                </div>
            </div>

            <!-- Image/Illustration side -->
            <div class="relative lg:ml-12">
                <!-- Main image -->
                <div class="relative z">
                    <img src="https://placehold.co/600x500" alt="Learning Illustration"
                        class="rounded-2xl shadow-2xl w-full">
                </div>

                <!-- Floating cards -->
                <div class="absolute -left-8 -bottom-8 bg-white p-4 rounded-xl shadow-xl animate-float">
                    <div class="flex items-center space-x-3">
                        <div class="bg-indigo-100 p-3 rounded-lg">
                            <i class="fas fa-play-circle text-xl text-indigo-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Live Classes</p>
                            <p class="text-sm text-gray-500">Join Now</p>
                        </div>
                    </div>
                </div>

                <div class="absolute -right-8 top-8 bg-white p-4 rounded-xl shadow-xl animate-float-delayed">
                    <div class="flex items-center space-x-3">
                        <div class="bg-violet-100 p-3 rounded-lg">
                            <i class="fas fa-award text-xl text-violet-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Certified</p>
                            <p class="text-sm text-gray-500">Industry Standard</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<div class="container grid grid-cols-1 my-20 md:grid-cols-2 mt-20 lg:grid-cols-3 gap-8">
    <?php foreach ($courses as $course) : ?>
        <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
            <div class="relative">
                <img
                    src='../assets/uploads/<?= $course->getThumbnail(); ?>'
                    class="w-full h-80 aspect-video object-cover" />
            </div>

            <div class="p-6">

                <div class="flex flex-wrap gap-2 mb-4">
                    <?php foreach ($course->getTags() as $tag) : ?>
                        <span
                            class="px-3 py-1 bg-blue-50 text-blue-600 text-xs font-medium rounded-full">
                            <?= $tag->getName(); ?>
                        </span>
                    <?php endforeach; ?>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2"><?= $course->getTitle(); ?></h3>
                <div class="flex items-end justify-between w-full mt-5">
                    <div class="flex items-center">
                        <div>
                            <p class="text-xs text-gray-500"><?= $course->getUserName(); ?></p>
                            <p class="text-xs font-medium text-gray-900"><?= $course->getUserEmail(); ?></p>
                        </div>
                    </div>
                    <a href="./courseDetails.php?id=<?= $course->getId(); ?>">
                        <button class="flex items-center text-blue-500 hover:text-blue-600 text-sm font-medium">
                            View Course
                            <i class="fa-solid fa-chevron-right  ml-1"></i>
                        </button>
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="flex items-center justify-center ">
    <a href="./courses.php" class="bg-blue-500 text-white px-6 py-2 rounded-full font-medium text-center hover:bg-blue-700 transition-colors duration-200 shadow-lg shadow-indigo-600/20">View All Courses <i class="fa-solid fa-chevron-right ml-1"></i></a>
</div>

<div class="mt-10">
    <!-- Hero Section -->
    <div class="relative bg-blue-700 text-white">
        <div class="absolute inset-0">
            <img src="https://placehold.co/1920/600" alt="Education Background" class="w-full h-full object-cover opacity-20">
        </div>
        <div class="relative max-w-7xl mx-auto py-24 px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl md:text-5xl font-bold text-center mb-6">Transforming Education for Everyone</h1>
            <p class="text-xl text-center max-w-3xl mx-auto">Empowering learners and educators through innovative technology and collaborative learning experiences.</p>
        </div>
    </div>

    <!-- Mission Statement -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Our Mission</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">To create an accessible, engaging, and effective learning environment that connects passionate educators with eager learners worldwide.</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            <div class="text-center p-6 bg-white rounded-lg shadow-lg">
                <div class="text-4xl font-bold text-blue-600 mb-2">100K+</div>
                <div class="text-gray-600">Active Students</div>
            </div>
            <div class="text-center p-6 bg-white rounded-lg shadow-lg">
                <div class="text-4xl font-bold text-blue-600 mb-2">5K+</div>
                <div class="text-gray-600">Expert Instructors</div>
            </div>
            <div class="text-center p-6 bg-white rounded-lg shadow-lg">
                <div class="text-4xl font-bold text-blue-600 mb-2">1M+</div>
                <div class="text-gray-600">Course Enrollments</div>
            </div>
        </div>

        <!-- Core Values -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
            <div class="p-6 bg-white rounded-lg shadow-lg">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                    <i class="fas fa-graduation-cap text-2xl text-blue-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Quality Education</h3>
                <p class="text-gray-600">Delivering high-quality, curated content from industry experts and accomplished educators.</p>
            </div>
            <div class="p-6 bg-white rounded-lg shadow-lg">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                    <i class="fas fa-users text-2xl text-green-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Community First</h3>
                <p class="text-gray-600">Building a supportive community where learners and educators can connect and collaborate.</p>
            </div>
            <div class="p-6 bg-white rounded-lg shadow-lg">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                    <i class="fas fa-lightbulb text-2xl text-purple-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Innovation</h3>
                <p class="text-gray-600">Leveraging cutting-edge technology to create engaging and interactive learning experiences.</p>
            </div>
            <div class="p-6 bg-white rounded-lg shadow-lg">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-4">
                    <i class="fas fa-globe text-2xl text-yellow-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Accessibility</h3>
                <p class="text-gray-600">Making quality education accessible to everyone, anywhere in the world.</p>
            </div>
        </div>

        <!-- Story Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-16">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Our Story</h2>
                <p class="text-gray-600 mb-4">Founded in 2023, EduLearn emerged from a simple yet powerful idea: education should be accessible, engaging, and effective for everyone. Our journey began with a small team of passionate educators and technologists who believed in the transformative power of online learning.</p>
                <p class="text-gray-600 mb-4">Today, we've grown into a global platform that connects thousands of expert instructors with learners from every corner of the world. Our success is built on our commitment to innovation, quality, and community.</p>
                <p class="text-gray-600">We continue to evolve and improve our platform, incorporating the latest educational technologies and methodologies to provide the best possible learning experience for our community.</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <img src="https://placehold.co/300x400" alt="Team collaboration" class="rounded-lg shadow-lg">
                <img src="https://placehold.co/300x400" alt="Student learning" class="rounded-lg shadow-lg mt-8">
            </div>
        </div>

        <!-- Team Section -->
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Leadership Team</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-6 bg-white rounded-lg shadow-lg">
                    <img src="https://placehold.co/150x150" alt="CEO" class="w-32 h-32 rounded-full mx-auto mb-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-1">Sarah Chen</h3>
                    <p class="text-gray-600 mb-2">CEO & Co-founder</p>
                    <p class="text-gray-500 text-sm">Former EdTech executive with 15+ years of experience in education innovation.</p>
                </div>
                <div class="p-6 bg-white rounded-lg shadow-lg">
                    <img src="https://placehold.co/150x150" alt="CTO" class="w-32 h-32 rounded-full mx-auto mb-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-1">Marcus Rodriguez</h3>
                    <p class="text-gray-600 mb-2">CTO</p>
                    <p class="text-gray-500 text-sm">Tech leader with expertise in building scalable educational platforms.</p>
                </div>
                <div class="p-6 bg-white rounded-lg shadow-lg">
                    <img src="https://placehold.co/150x150" alt="COO" class="w-32 h-32 rounded-full mx-auto mb-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-1">Emily Thompson</h3>
                    <p class="text-gray-600 mb-2">Head of Education</p>
                    <p class="text-gray-500 text-sm">PhD in Education with focus on online learning methodologies.</p>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="text-center bg-blue-700 text-white rounded-lg p-12">
            <h2 class="text-3xl font-bold mb-4">Join Our Learning Community</h2>
            <p class="text-xl mb-8">Start your learning journey today and be part of our growing educational community.</p>
            <div class="flex justify-center gap-4">
                <button class="bg-white text-blue-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100">
                    Start Learning
                </button>
                <button class="border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-800">
                    Become an Instructor
                </button>
            </div>
        </div>
    </div>
</div>
<style>
    @keyframes float {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    @keyframes float-delayed {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    .animate-float {
        animation: float 6s ease-in-out infinite;
    }

    .animate-float-delayed {
        animation: float-delayed 6s ease-in-out infinite;
        animation-delay: 2s;
    }
</style>

<script>
    // Mobile menu toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.querySelector('.mobile-menu-button');
        const mobileMenu = document.querySelector('.mobile-menu');
        const menuIcon = mobileMenuButton.querySelector('[data-lucide="menu"]');

        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
            if (mobileMenu.classList.contains('hidden')) {
                menuIcon.setAttribute('data-lucide', 'menu');
            } else {
                menuIcon.setAttribute('data-lucide', 'x');
            }
        });
    });
</script>


<?php

require_once "./components/header.php";
?>