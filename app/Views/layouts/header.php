<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrystalWash - Wash Smarter Not Longer</title>
    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Fonts: Playfair Display & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-white text-dark font-sans flex flex-col min-h-screen">

    <!-- Navbar -->
    <?php if (!isset($hide_navbar) || !$hide_navbar): ?>
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="index.php?page=home" class="font-serif text-2xl font-bold text-olive-700 tracking-tight">CrystalWash</a>
                </div>
                
                <!-- Center Links -->
                <div class="hidden md:flex space-x-10">
                    <a href="index.php?page=home#about" class="text-sm font-medium text-gray-600 hover:text-olive-700 transition-colors">About</a>
                    <a href="index.php?page=home#benefit" class="text-sm font-medium text-gray-600 hover:text-olive-700 transition-colors">Benefit</a>
                    <a href="index.php?page=home#service" class="text-sm font-medium text-gray-600 hover:text-olive-700 transition-colors">Service</a>
                    <a href="index.php?page=home#contact" class="text-sm font-medium text-gray-600 hover:text-olive-700 transition-colors">Contact Us</a>
                </div>

                <!-- Right Button -->
                <div class="hidden md:flex items-center">
                    <a href="index.php?page=login" class="inline-flex items-center justify-center px-6 py-2 border border-transparent rounded-full shadow-sm text-sm font-medium text-white bg-olive-700 hover:bg-olive-600 transition-colors">
                        Login
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <!-- Main Content Wrapper -->
    <main class="flex-grow">
