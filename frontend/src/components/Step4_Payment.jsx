import React, { useState } from 'react';
import { Wallet, QrCode, CreditCard, ShieldCheck } from 'lucide-react';
import { clsx } from 'clsx';

export default function Step4_Payment({ bookingData, updateData, prevStep }) {
  const [paymentMethod, setPaymentMethod] = useState('qris');
  const [isProcessing, setIsProcessing] = useState(false);

  const handlePayment = (e) => {
    e.preventDefault();
    setIsProcessing(true);
    // Simulate API call
    setTimeout(() => {
      alert('Pembayaran Berhasil! Pesanan Anda telah dikonfirmasi.');
      setIsProcessing(false);
      window.location.reload(); // Reset for demo purposes
    }, 2000);
  };

  return (
    <div className="glass-card p-8 animate-in fade-in slide-in-from-right-4 duration-500">
      <h2 className="font-serif text-3xl font-bold text-gray-900 mb-2">Pembayaran</h2>
      <p className="text-gray-500 mb-8">Pilih metode pembayaran untuk menyelesaikan pesanan Anda.</p>
      
      <div className="flex space-x-2 bg-gray-100 p-1 rounded-xl mb-6">
        <button
          onClick={() => setPaymentMethod('qris')}
          className={clsx(
            "flex-1 py-3 text-sm font-medium rounded-lg transition-all duration-300 flex items-center justify-center space-x-2",
            paymentMethod === 'qris' ? "bg-white text-olive-800 shadow-sm" : "text-gray-500 hover:text-gray-700"
          )}
        >
          <QrCode className="w-4 h-4" />
          <span>QRIS</span>
        </button>
        <button
          onClick={() => setPaymentMethod('transfer')}
          className={clsx(
            "flex-1 py-3 text-sm font-medium rounded-lg transition-all duration-300 flex items-center justify-center space-x-2",
            paymentMethod === 'transfer' ? "bg-white text-olive-800 shadow-sm" : "text-gray-500 hover:text-gray-700"
          )}
        >
          <Wallet className="w-4 h-4" />
          <span>Transfer Bank</span>
        </button>
      </div>

      <div className="bg-white border border-gray-100 rounded-2xl p-8 mb-8 text-center flex flex-col items-center">
        {paymentMethod === 'qris' ? (
          <div className="animate-in fade-in duration-500 flex flex-col items-center">
            <div className="w-48 h-48 bg-gray-100 rounded-xl mb-4 flex items-center justify-center border-2 border-dashed border-gray-300">
              <QrCode className="w-16 h-16 text-gray-400" />
              <span className="sr-only">QR Code Placeholder</span>
            </div>
            <p className="text-sm text-gray-600 mb-2">Scan QRIS menggunakan aplikasi E-Wallet atau M-Banking Anda.</p>
            <div className="flex items-center space-x-2 text-olive-600 bg-olive-50 px-4 py-2 rounded-full text-xs font-bold">
              <ShieldCheck className="w-4 h-4" />
              <span>Verifikasi Otomatis</span>
            </div>
          </div>
        ) : (
          <div className="animate-in fade-in duration-500 flex flex-col items-center w-full">
            <CreditCard className="w-16 h-16 text-olive-300 mb-4" />
            <p className="text-sm text-gray-600 mb-4">Transfer sesuai nominal ke rekening berikut:</p>
            <div className="bg-gray-50 border border-gray-200 w-full rounded-xl p-4 flex flex-col space-y-2">
              <div className="flex justify-between items-center text-sm">
                <span className="text-gray-500">Bank</span>
                <span className="font-bold text-gray-900">BCA</span>
              </div>
              <div className="flex justify-between items-center text-sm">
                <span className="text-gray-500">No. Rekening</span>
                <span className="font-bold text-gray-900">1234 5678 90</span>
              </div>
              <div className="flex justify-between items-center text-sm">
                <span className="text-gray-500">Atas Nama</span>
                <span className="font-bold text-gray-900">PT Crystal Wash</span>
              </div>
            </div>
          </div>
        )}
      </div>

      <div className="flex space-x-4">
        <button 
          type="button" 
          onClick={prevStep}
          disabled={isProcessing}
          className="w-1/3 border-2 border-gray-200 text-gray-600 font-medium text-lg py-4 rounded-xl hover:bg-gray-50 transition-colors disabled:opacity-50"
        >
          Kembali
        </button>
        <button 
          onClick={handlePayment}
          disabled={isProcessing}
          className="w-2/3 bg-olive-700 text-white font-medium text-lg py-4 rounded-xl hover:bg-olive-800 transition-colors shadow-lg shadow-olive-700/20 disabled:opacity-70 flex items-center justify-center"
        >
          {isProcessing ? (
            <div className="w-6 h-6 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
          ) : (
            'Bayar Sekarang'
          )}
        </button>
      </div>
    </div>
  );
}
