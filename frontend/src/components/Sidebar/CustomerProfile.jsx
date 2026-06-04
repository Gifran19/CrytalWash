import React from 'react';
import { UserCheck, Clock, History, Car, Sparkles, Zap } from 'lucide-react';

export default function CustomerProfile({ customer, updateData, jumpToStep }) {
  const handleQuickBook = () => {
    updateData({
      vehicleType: customer.vehicle.type,
      plateNumber: customer.vehicle.plate,
      service: customer.favoriteService
    });
    jumpToStep(4);
  };

  return (
    <div className="glass-card overflow-hidden border-2 border-olive-200 shadow-xl relative group">
      <div className="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-olive-100/40 to-transparent rounded-bl-full pointer-events-none z-0"></div>
      
      <div className="p-6 relative z-10">
        <div className="flex items-start justify-between mb-6">
          <div className="flex items-center space-x-4">
            <div className="w-14 h-14 rounded-full bg-olive-100 border-2 border-white shadow-sm flex items-center justify-center text-olive-600 font-serif text-xl font-bold">
              {customer.name.charAt(0)}
            </div>
            <div>
              <div className="flex items-center space-x-1">
                <UserCheck className="w-4 h-4 text-olive-600" />
                <h3 className="font-serif text-lg font-bold text-gray-900">
                  Hai, {customer.name.split(' ')[0]}!
                </h3>
              </div>
              <div className="flex space-x-2 mt-1">
                <span className="bg-olive-100 text-olive-800 text-[10px] px-2 py-0.5 rounded-full font-bold border border-olive-200 tracking-wide uppercase">
                  {customer.level}
                </span>
                <span className="bg-orange-50 text-orange-700 text-[10px] px-2 py-0.5 rounded-full font-bold border border-orange-100 tracking-wide uppercase">
                  {customer.visits}x Visits
                </span>
              </div>
            </div>
          </div>
        </div>

        <div className="grid grid-cols-2 gap-3 mb-6">
          <div className="bg-white/60 p-3 rounded-xl border border-gray-100 shadow-sm">
            <div className="flex items-center text-gray-500 mb-1">
              <Clock className="w-3.5 h-3.5 mr-1" />
              <span className="text-[11px] font-semibold uppercase tracking-wider">Kunjungan Terakhir</span>
            </div>
            <p className="font-bold text-gray-900 text-sm">{customer.lastVisit}</p>
          </div>
          <div className="bg-white/60 p-3 rounded-xl border border-gray-100 shadow-sm">
            <div className="flex items-center text-gray-500 mb-1">
              <History className="w-3.5 h-3.5 mr-1" />
              <span className="text-[11px] font-semibold uppercase tracking-wider">Frekuensi</span>
            </div>
            <p className="font-bold text-gray-900 text-sm">{customer.frequency}</p>
          </div>
        </div>

        <div className="bg-gradient-to-r from-olive-50 to-white rounded-xl p-4 border border-olive-100 mb-6 shadow-sm">
          <p className="text-[10px] font-black text-olive-600 uppercase tracking-widest mb-2 flex items-center">
            <Sparkles className="w-3 h-3 mr-1" /> Rekomendasi Hari Ini
          </p>
          <div className="flex justify-between items-center">
            <div>
              <div className="flex items-center space-x-1.5 mb-1">
                <Car className="w-3.5 h-3.5 text-gray-400" />
                <span className="text-xs font-semibold text-gray-600">{customer.vehicle.plate}</span>
              </div>
              <p className="font-bold text-gray-900 text-sm">{customer.favoriteService.name}</p>
            </div>
            <div className="text-right">
              <p className="font-black text-olive-700">Rp {customer.favoriteService.price.toLocaleString('id-ID')}</p>
            </div>
          </div>
        </div>

        <button 
          onClick={handleQuickBook}
          type="button"
          className="w-full bg-gray-900 text-white font-medium py-3.5 rounded-xl hover:bg-gray-800 transition-all duration-300 shadow-lg shadow-gray-900/20 flex justify-center items-center group/btn"
        >
          <Zap className="w-4 h-4 mr-2 text-yellow-400 group-hover/btn:scale-125 transition-transform" />
          <span className="text-sm tracking-wide">Pesan dengan Cepat (1-Klik)</span>
        </button>
      </div>
    </div>
  );
}
