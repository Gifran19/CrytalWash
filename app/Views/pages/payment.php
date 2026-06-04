<h2 class="font-serif text-3xl font-bold text-gray-900 mb-8">Metode Pembayaran</h2>

<form action="index.php?action=payment_gateway" method="POST" class="space-y-6">
    
    <div class="space-y-4">
        <!-- COD -->
        <label class="relative block cursor-pointer group">
            <input type="radio" name="payment_method" value="COD" checked class="peer sr-only" onchange="togglePaymentDetails()">
            <div class="rounded-xl border-2 border-gray-200 bg-white p-5 transition-all hover:border-olive-300 peer-checked:border-olive-600 peer-checked:bg-olive-50 peer-checked:ring-1 peer-checked:ring-olive-600 flex items-center">
                <span class="font-bold text-gray-900">Bayar di Tempat (COD)</span>
            </div>
        </label>
        
        <!-- Credit Card -->
        <label class="relative block cursor-pointer group">
            <input type="radio" name="payment_method" value="Credit" class="peer sr-only" onchange="togglePaymentDetails()">
            <div class="rounded-xl border-2 border-gray-200 bg-white p-5 transition-all hover:border-olive-300 peer-checked:border-olive-600 peer-checked:bg-olive-50 peer-checked:ring-1 peer-checked:ring-olive-600 flex items-center">
                <span class="font-bold text-gray-900">Kartu Kredit / Debit</span>
            </div>
        </label>
        
        <!-- Credit Card Details (Hidden by default) -->
        <div id="cc-details" class="hidden bg-gray-50 border border-gray-200 rounded-xl p-6 mt-2 space-y-4">
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Nama di Kartu</label>
                <input type="text" name="card_name" placeholder="John Doe" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-olive-500 focus:border-olive-500 text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Nomor Kartu</label>
                <input type="text" name="card_number" placeholder="1234 5678 9101 1121" maxlength="19" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-olive-500 focus:border-olive-500 text-sm">
            </div>
        </div>

        <!-- E-Wallet -->
        <label class="relative block cursor-pointer group">
            <input type="radio" name="payment_method" value="Ewallet" class="peer sr-only" onchange="togglePaymentDetails()">
            <div class="rounded-xl border-2 border-gray-200 bg-white p-5 transition-all hover:border-olive-300 peer-checked:border-olive-600 peer-checked:bg-olive-50 peer-checked:ring-1 peer-checked:ring-olive-600 flex items-center">
                <span class="font-bold text-gray-900">E-Wallet (QRIS/Dana/OVO)</span>
            </div>
        </label>

        <!-- E-Wallet Details (Hidden by default) -->
        <div id="ewallet-options" class="hidden bg-gray-50 border border-gray-200 rounded-xl p-6 mt-2 text-center">
            <p class="text-sm text-gray-600 mb-4 font-medium">Pilih Provider E-Wallet:</p>
            <div class="flex flex-wrap justify-center gap-3">
                <button type="button" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-semibold hover:border-olive-500 hover:text-olive-700 transition-colors ewallet-btn" onclick="selectEwallet('Dana')">Dana</button>
                <button type="button" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-semibold hover:border-olive-500 hover:text-olive-700 transition-colors ewallet-btn" onclick="selectEwallet('OVO')">OVO</button>
                <button type="button" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-semibold hover:border-olive-500 hover:text-olive-700 transition-colors ewallet-btn" onclick="selectEwallet('GoPay')">GoPay</button>
                <button type="button" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-semibold hover:border-olive-500 hover:text-olive-700 transition-colors ewallet-btn" onclick="selectEwallet('ShopeePay')">ShopeePay</button>
                <button type="button" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-semibold hover:border-olive-500 hover:text-olive-700 transition-colors ewallet-btn active-ewallet" onclick="selectEwallet('QRIS')">QRIS</button>
            </div>
            
            <input type="hidden" name="ewallet_provider" id="ewallet_provider" value="QRIS">
            
            <div class="mt-6 border border-dashed border-gray-300 rounded-lg p-4 bg-white inline-block">
                <img src="assets/img/qris-dummy.png" alt="QRIS Code" class="w-48 h-48 mx-auto opacity-50 grayscale">
                <p class="text-xs text-gray-400 mt-2">Scan dengan aplikasi <span id="provider_name" class="font-bold text-gray-600">QRIS</span> Anda</p>
            </div>
        </div>
    </div>
    
    <div class="flex space-x-4 mt-8">
        <button type="button" onclick="window.history.back()" class="w-1/3 bg-gray-100 text-gray-700 font-semibold py-3 rounded-lg hover:bg-gray-200 transition-colors">
            Kembali
        </button>
        <button type="submit" class="w-2/3 bg-olive-700 text-white font-semibold py-3 rounded-lg hover:bg-olive-600 transition-colors">
            Bayar Sekarang
        </button>
    </div>
</form>

<script>
function togglePaymentDetails() {
    const method = document.querySelector('input[name="payment_method"]:checked').value;
    const ccDetails = document.getElementById('cc-details');
    const ewalletOptions = document.getElementById('ewallet-options');
    
    // Hide all
    ccDetails.classList.add('hidden');
    ewalletOptions.classList.add('hidden');
    
    // Show selected
    if (method === 'Credit') {
        ccDetails.classList.remove('hidden');
    } else if (method === 'Ewallet') {
        ewalletOptions.classList.remove('hidden');
    }
}

function selectEwallet(provider) {
    document.getElementById('ewallet_provider').value = provider;
    document.getElementById('provider_name').innerText = provider;
    
    // Update active button style
    document.querySelectorAll('.ewallet-btn').forEach(btn => {
        btn.classList.remove('ring-2', 'ring-olive-500', 'border-olive-500', 'text-olive-700');
    });
    event.target.classList.add('ring-2', 'ring-olive-500', 'border-olive-500', 'text-olive-700');
}
</script>
