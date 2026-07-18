<div class="mb-8">
    <h2 class="font-serif text-4xl font-bold text-olive-700 dark:text-olive-400 tracking-tight">Metode Pembayaran</h2>
    <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm">Pilih cara pembayaran yang paling nyaman untuk Anda.</p>
</div>

<form action="index.php?action=payment_gateway" method="POST" class="space-y-6">
    <?php csrf_field(); ?>
    
    <div class="space-y-4">
        <!-- COD (Cash) -->
        <label class="relative block cursor-pointer group">
            <input type="radio" name="payment_method" value="Cash" checked class="peer sr-only">
            <div class="rounded-2xl border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 transition-all duration-300 hover:border-olive-200 hover:shadow-md peer-checked:border-2 peer-checked:border-olive-700 dark:peer-checked:border-olive-500 peer-checked:bg-olive-50 dark:peer-checked:bg-gray-800/80 peer-checked:shadow-lg flex items-center shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-gray-50 dark:bg-gray-700 border border-gray-100 dark:border-gray-600 flex items-center justify-center mr-4 peer-checked:bg-white dark:peer-checked:bg-gray-900 peer-checked:border-olive-200 transition-colors">
                    <svg class="w-6 h-6 text-gray-500 dark:text-gray-400 peer-checked:text-olive-700 dark:peer-checked:text-olive-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div class="flex-grow">
                    <span class="font-bold text-gray-800 dark:text-gray-100 text-lg block group-hover:text-olive-700 dark:group-hover:text-olive-400 transition-colors">Cash (Bayar di Tempat)</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Bayar tunai kepada kasir atau petugas di lokasi</span>
                </div>
                <!-- Checkmark -->
                <div class="w-6 h-6 rounded-full bg-olive-700 dark:bg-olive-500 text-white items-center justify-center hidden peer-checked:flex shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
            </div>
        </label>
        
        <!-- QRIS -->
        <label class="relative block cursor-pointer group">
            <input type="radio" name="payment_method" value="QRIS" class="peer sr-only">
            <div class="rounded-2xl border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 transition-all duration-300 hover:border-olive-200 hover:shadow-md peer-checked:border-2 peer-checked:border-olive-700 dark:peer-checked:border-olive-500 peer-checked:bg-olive-50 dark:peer-checked:bg-gray-800/80 peer-checked:shadow-lg flex items-center shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-gray-50 dark:bg-gray-700 border border-gray-100 dark:border-gray-600 flex items-center justify-center mr-4 peer-checked:bg-white dark:peer-checked:bg-gray-900 peer-checked:border-olive-200 transition-colors">
                    <svg class="w-6 h-6 text-gray-500 dark:text-gray-400 peer-checked:text-olive-700 dark:peer-checked:text-olive-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                </div>
                <div class="flex-grow">
                    <span class="font-bold text-gray-800 dark:text-gray-100 text-lg block group-hover:text-olive-700 dark:group-hover:text-olive-400 transition-colors">QRIS (Bayar Sekarang)</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Scan QR Code dengan aplikasi m-banking atau e-wallet (OVO, GoPay, Dana)</span>
                </div>
                <!-- Checkmark -->
                <div class="w-6 h-6 rounded-full bg-olive-700 dark:bg-olive-500 text-white items-center justify-center hidden peer-checked:flex shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
            </div>
        </label>
    </div>
    
    <div class="flex space-x-4 pt-6 mt-8 border-t border-gray-100 dark:border-gray-700">
        <button type="button" onclick="window.history.back()" class="w-1/3 px-6 py-4 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 rounded-xl font-bold text-sm hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-300">
            Kembali
        </button>
        <button type="submit" class="w-2/3 px-6 py-4 bg-olive-700 text-white rounded-xl font-bold text-lg hover:bg-olive-800 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300">
            Pilih & Lanjutkan
        </button>
    </div>
</form>
