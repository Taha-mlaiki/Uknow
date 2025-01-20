<?php
require_once "./components/header.php";
?>

<div class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md mx-auto w-full text-center">
        <!-- Status Icon -->
        <div class="mb-8">
            <div class="mx-auto w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center">
                <svg class="w-12 h-12 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Account Inactive</h1>
            
            <div class="space-y-4 mb-8">
                <p class="text-gray-600">
                    Your account is currently inactive. To continue using our services, please contact our administration team for activation.
                </p>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-left">
                    <h2 class="font-semibold text-yellow-800 mb-2">What to do next:</h2>
                    <ol class="list-decimal list-inside text-yellow-700 space-y-2">
                        <li>Contact our administration team</li>
                        <li>Provide your account details</li>
                        <li>Wait for confirmation email</li>
                    </ol>
                </div>
            </div>

            <!-- Contact Options -->
            <div class="space-y-4">
                <div class="flex flex-col items-center justify-center gap-2">
                    <span class="text-gray-600">Contact Administration:</span>
                    <a href="mailto:admin@example.com" 
                        class="text-blue-600 hover:text-blue-700 font-medium">
                        admin@example.com
                    </a>
                </div>
                
                <div class="border-t border-gray-200 pt-4">
                    <span class="text-gray-600">Or call us at:</span>
                    <p class="font-medium text-gray-800">+1 (555) 123-4567</p>
                    <p class="text-sm text-gray-500 mt-1">
                        Monday - Friday, 9:00 AM - 5:00 PM
                    </p>
                </div>
            </div>
        </div>

        <!-- Additional Actions -->
        <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
            <button onclick="window.history.back()" 
                class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                Go Back
            </button>
            <a href="/uknow/pages/" 
                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Return Home
            </a>
        </div>

        <!-- Help Link -->
        <p class="mt-8 text-gray-500">
            Need help? Visit our 
            <a href="/help" class="text-blue-600 hover:underline">Help Center</a>
        </p>
    </div>
</div>
<?php
require_once "./components/footer.php";
?>