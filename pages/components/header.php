<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/choices.js/10.2.0/choices.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/choices.js/10.2.0/choices.min.css">
    <link rel="stylesheet" href="http://www.localhost/uknow/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script defer>
    const showToast = (message, type = 'success') => {
        const toastContainer = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `p-4 rounded shadow text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
        toast.textContent = message;

        toastContainer.appendChild(toast);

        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.remove();
        }, 3000);
    };
</script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js" defer></script>
</head>
<body class="bg-gray-50">
<div id="toast-container" class="fixed top-5 right-5 space-y-3 z-50"></div>
    