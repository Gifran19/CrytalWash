import React from 'react';
import { Clock, Users, ShieldCheck } from 'lucide-react';

export default function BookingSummary({ bookingData }) {
  const isCar = bookingData.vehicleType === 'Car';
  const basePrice = isCar ? 50000 : 30000;
  const servicePrice = bookingData.service ? bookingData.service.price : 0;
  const total = basePrice + servicePrice;

  return (
    <div className="glass-card p-6 sticky top-8">
      <h3 className="font-serif text-xl font-bold text-gray-900 mb-6">Ringkasan Pesanan</h3>
      
      <div className="space-y-4 mb-6">
        {/* Queue Info Widget */}
        <div className="bg-olive-50 rounded-xl p-4 border border-olive-100 flex items-start space-x-3">
          <div className="bg-olive-100 p-2 rounded-lg text-olive-700">
            <Users className="w-5 h-5" />
          </div>
          <div>
            <p className="text-xs text-gray-500 mb-1">Status Antrean Saat Ini</p>
            <p className="font-semibold text-gray-900">3 Kendaraan</p>
          </div>
        </div>

        {/* Estimated Duration Widget */}
        <div className="bg-cream rounded-xl p-4 border border-gray-100 flex items-start space-x-3">
          <div className="bg-orange-50 p-2 rounded-lg text-orange-600">
            <Clock className="w-5 h-5" />
          </div>
          <div>
            <p className="text-xs text-gray-500 mb-1">Estimasi Waktu Pengerjaan</p>
            <p className="font-semibold text-gray-900">~45 Menit</p>
          </div>
        </div>
      </div>

      <hr className="border-gray-100 my-6" />

      <div className="space-y-3 text-sm">
        <div className="flex justify-between text-gray-600">
          <span>Tipe Kendaraan ({isCar ? 'Mobil' : 'Motor'})</span>
          <span>Rp {basePrice.toLocaleString('id-ID')}</span>
        </div>
        {bookingData.service && (
          <div className="flex justify-between text-gray-600">
            <span>{bookingData.service.name}</span>
            <span>Rp {servicePrice.toLocaleString('id-ID')}</span>
          </div>
        )}
        <div className="flex justify-between font-bold text-lg text-gray-900 pt-3 border-t border-gray-100">
          <span>Total Pembayaran</span>
          <span className="text-olive-700">Rp {total.toLocaleString('id-ID')}</span>
        </div>
      </div>

      <div className="mt-8 flex items-center justify-center space-x-2 text-xs text-gray-400">
        <ShieldCheck className="w-4 h-4 text-green-500" />
        <span>Pembayaran Aman & Terenkripsi</span>
      </div>
    </div>
  );
}
