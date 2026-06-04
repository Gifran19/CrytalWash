<h2 class="font-serif text-4xl font-bold text-gray-900 mb-8">Pesan Cucian Anda</h2>

<form action="index.php?action=auth_booking" method="POST" class="space-y-6">
    <input type="hidden" name="next_step" value="2">
    
    <div>
        <label class="block text-sm font-serif text-gray-900 mb-2">Nama</label>
        <input type="text" name="nama" placeholder="masukkan nama anda" required
            class="w-full px-5 py-3 border border-olive-400 rounded-2xl focus:ring-2 focus:ring-olive-700 focus:border-olive-700 transition-colors text-sm text-gray-600 placeholder-gray-400 bg-white">
    </div>
    
    <div>
        <label class="block text-sm font-serif text-gray-900 mb-2">WhatsApp</label>
        <input type="text" name="whatsapp" placeholder="masukkan nomor whatsapp anda" required
            class="w-full px-5 py-3 border border-olive-400 rounded-2xl focus:ring-2 focus:ring-olive-700 focus:border-olive-700 transition-colors text-sm text-gray-600 placeholder-gray-400 bg-white">
    </div>
    
    <div>
        <label class="block text-sm font-serif text-gray-900 mb-2">Email</label>
        <input type="email" name="email" placeholder="masukkan email anda" required
            class="w-full px-5 py-3 border border-olive-400 rounded-2xl focus:ring-2 focus:ring-olive-700 focus:border-olive-700 transition-colors text-sm text-gray-600 placeholder-gray-400 bg-white">
    </div>
    
    <div class="flex space-x-4 pt-4">
        <button type="button" onclick="window.history.back()" class="w-1/2 border border-olive-700 text-olive-700 font-serif text-lg py-3 rounded-full hover:bg-olive-50 transition-colors">
            Kembali
        </button>
        <button type="submit" class="w-1/2 bg-olive-700 text-white font-serif text-lg py-3 rounded-full hover:bg-olive-800 transition-colors shadow-md">
            Lanjut
        </button>
    </div>
</form>
