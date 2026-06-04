<?php include BASE_PATH . '/app/Views/layouts/header.php'; ?>

<!-- Hero Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-5xl md:text-7xl font-bold font-serif leading-tight mb-12">
            Cuci Lebih Pintar<br>Bukan Lebih Lama
        </h1>
        
        <div class="relative max-w-5xl mx-auto">
            <!-- Olive Accent Block behind image -->
            <div class="absolute inset-x-0 bottom-0 h-1/2 bg-olive-400 rounded-3xl -mx-4 md:-mx-8"></div>
            <!-- Main Hero Image -->
            <img src="assets/img/hero_main.png" alt="CrystalWash Vehicles" class="relative z-10 w-full rounded-2xl shadow-2xl object-cover h-[400px] md:h-[600px] border-4 border-white">
        </div>
    </div>
</section>

<!-- Marquee / Brands / Services List -->
<section class="py-8 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap justify-center gap-8 md:gap-16 text-sm text-gray-500 tracking-wider uppercase font-medium">
            <span>Quick Wash</span>
            <span>Full Wash</span>
            <span>Premium Wash</span>
            <span>Express Wash</span>
            <span>VIP Wash</span>
        </div>
    </div>
</section>

<!-- Benefit Section -->
<section id="benefit" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mb-16">
            <h2 class="text-3xl md:text-4xl font-serif font-bold mb-4">Pesan dalam detik, bersinar dalam menit</h2>
            <p class="text-gray-500 uppercase text-xs font-semibold tracking-widest">Layanan premium dengan presisi tinggi</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-16">
            <div>
                <h4 class="text-sm font-bold border-b border-gray-200 pb-2 mb-3">Hemat Waktu & Cepat</h4>
                <p class="text-xs text-gray-500 leading-relaxed">Seluruh proses dioptimalkan untuk menghemat waktu Anda. Kami bekerja efisien tanpa mengorbankan kualitas.</p>
            </div>
            <div>
                <h4 class="text-sm font-bold border-b border-gray-200 pb-2 mb-3">Layanan Premium</h4>
                <p class="text-xs text-gray-500 leading-relaxed">Kami memberikan detail perawatan terbaik, menggunakan alat dan bahan premium untuk hasil sempurna.</p>
            </div>
            <div>
                <h4 class="text-sm font-bold border-b border-gray-200 pb-2 mb-3">Produk Berkualitas</h4>
                <p class="text-xs text-gray-500 leading-relaxed">Hanya menggunakan bahan berkualitas untuk melindungi cat dan material kendaraan Anda.</p>
            </div>
            <div>
                <h4 class="text-sm font-bold border-b border-gray-200 pb-2 mb-3">Tim Profesional</h4>
                <p class="text-xs text-gray-500 leading-relaxed">Tim ahli kami menjamin hasil yang andal, tanpa cacat, dan luar biasa setiap saat.</p>
            </div>
        </div>

        <div class="w-full h-[400px] md:h-[500px] overflow-hidden rounded-3xl">
            <img src="assets/img/benefit_wash.png" alt="Car wash process" class="w-full h-full object-cover hover:scale-105 transition-transform duration-700">
        </div>
    </div>
</section>

<!-- Why Choose CrystalWash Section -->
<section id="service" class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-serif font-bold mb-6">Mengapa Memilih CrystalWash?</h2>
            <button class="bg-olive-100 text-olive-800 text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider mb-12">
                Lihat Layanan Kami
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-[1px] bg-gray-200 border border-gray-200 rounded-xl overflow-hidden">
            <!-- Cuci Dasar -->
            <div class="bg-white p-8">
                <h3 class="text-lg font-bold text-center mb-6">Cuci Dasar</h3>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> Cuci eksterior</li>
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> Semir ban</li>
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> Proses pengeringan</li>
                </ul>
            </div>
            <!-- Cuci Penuh -->
            <div class="bg-white p-8">
                <h3 class="text-lg font-bold text-center mb-6">Cuci Penuh</h3>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> Vakum eksterior & interior penuh</li>
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> Pembersihan mendalam kaca & jendela</li>
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> Detailing ban dan velg</li>
                </ul>
            </div>
            <!-- Detail Interior -->
            <div class="bg-white p-8">
                <h3 class="text-lg font-bold text-center mb-6">Detail Interior</h3>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> Vakum mendalam dan sampo</li>
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> Perawatan dashboard & panel</li>
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> Perawatan penghilang bau</li>
                </ul>
            </div>
            <!-- Detail Mesin -->
            <div class="bg-white p-8">
                <h3 class="text-lg font-bold text-center mb-6">Detail Mesin</h3>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> Pembersihan ruang mesin</li>
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> Cuci tekanan aman</li>
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> Perawatan & perlindungan plastik</li>
                </ul>
            </div>
            <!-- Cuci Kilat -->
            <div class="bg-white p-8">
                <h3 class="text-lg font-bold text-center mb-6">Cuci Kilat</h3>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> Layanan cepat 15 menit</li>
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> Proses busa tanpa sentuhan</li>
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> Sistem pengeringan blower udara</li>
                </ul>
            </div>
            <!-- Cuci Ramah Lingkungan -->
            <div class="bg-white p-8">
                <h3 class="text-lg font-bold text-center mb-6">Cuci Eco</h3>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> Sistem cuci tanpa air</li>
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> Produk premium yang dapat terurai</li>
                    <li class="flex items-start"><span class="text-olive-500 mr-2">✓</span> Proses ramah lingkungan</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Bottom Image Showcase -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="w-full h-[300px] md:h-[500px] overflow-hidden rounded-3xl shadow-xl">
            <img src="assets/img/showcase_moto.png" alt="Motorcycle foam wash" class="w-full h-full object-cover">
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-24 bg-white text-center">
    <div class="max-w-3xl mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-serif font-bold mb-4">Siap Membuat Kendaraan Anda Bersinar?</h2>
        <p class="text-gray-500 text-sm mb-10">Tim kami siap memberikan yang terbaik untuk mobil atau motor anda.</p>
        <a href="index.php?page=checkout" class="btn-primary">Pesan Sekarang!</a>
    </div>
</section>

<?php include BASE_PATH . '/app/Views/layouts/footer.php'; ?>
