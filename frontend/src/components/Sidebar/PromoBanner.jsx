import React from 'react';
import { Sparkles, ArrowRight } from 'lucide-react';

export default function PromoBanner() {
  return (
    <div className="relative overflow-hidden rounded-2xl shadow-lg group cursor-pointer">
      {/* Animated Gradient Background */}
      <div className="absolute inset-0 bg-gradient-to-r from-olive-700 via-olive-600 to-olive-800 transition-transform duration-700 group-hover:scale-105"></div>
      
      {/* Shine effect */}
      <div className="absolute inset-0 bg-gradient-to-tr from-white/0 via-white/20 to-white/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></div>

      <div className="relative p-6 text-white">
        <div className="flex items-center justify-between mb-4">
          <div className="bg-white/20 backdrop-blur-md px-3 py-1 rounded-full flex items-center">
            <Sparkles className="w-3 h-3 text-yellow-300 mr-1.5" />
            <span className="text-xs font-bold tracking-wider uppercase">Spesial Hari Ini</span>
          </div>
          <span className="text-3xl font-black text-yellow-300 opacity-90">-20%</span>
        </div>

        <h3 className="text-xl font-serif font-bold mb-1">Paket Premium Detailing</h3>
        <p className="text-olive-100 text-sm mb-4 leading-relaxed">
          Dapatkan perlindungan maksimal untuk kendaraan Anda dengan wax premium.
        </p>

        <div className="flex items-center text-sm font-semibold text-yellow-300 group-hover:text-yellow-200 transition-colors">
          <span>Klaim Promo Sekarang</span>
          <ArrowRight className="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" />
        </div>
      </div>
    </div>
  );
}
