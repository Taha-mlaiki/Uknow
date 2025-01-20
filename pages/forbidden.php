<?php
require_once "./components/header.php";
?>

<div class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full mx-auto mt-20 text-center">
        <!-- Error Icon -->
        <div class="mb-8">
            <div class="mx-auto w-24 h-24 bg-red-100 rounded-full flex items-center justify-center">
                <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m0 0v2m0-2h2m-2 0H10m10-6H4a2 2 0 01-2-2V6a2 2 0 012-2h16a2 2 0 012 2v3a2 2 0 01-2 2zM6 18h12a2 2 0 002-2v-3a2 2 0 00-2-2H6a2 2 0 00-2 2v3a2 2 0 002 2z">
                    </path>
                </svg>
            </div>
        </div>

        <!-- Error Message -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">403</h1>
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Access Forbidden</h2>
            <p class="text-gray-600 mb-8">
                Sorry, you don't have permission to access this page. Please contact your administrator
                if you believe this is an error.
            </p>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button onclick="window.history.back()"
                    class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    Go Back
                </button>
                <a href="/uknow/pages/"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Return Home
                </a>
            </div>
        </div>

        <!-- Help Text -->
        <p class="mt-8 text-gray-500">
            If you continue to experience issues, please contact
            <a href="mailto:support@example.com" class="text-blue-600 hover:underline">support@example.com</a>
        </p>
    </div>
</div>
<?php
require_once "./components/footer.php";
?>