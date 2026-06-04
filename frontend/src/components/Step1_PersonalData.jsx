import React from 'react';
import { Loader2, ChevronRight, Zap } from 'lucide-react';

export default function Step1_PersonalData({ bookingData, updateData, nextStep, isChecking }) {
  const handleChange = (e) => {
    updateData({ [e.target.name]: e.target.value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    if (bookingData.name && bookingData.phone) {
      nextStep();
    }
  };

  return (
    <div className="glass-card p-8 md:p-10 animate-in fade-in slide-in-from-bottom-4 duration-500 shadow-xl border border-white/60">
      <h2 className="font-serif text-3xl font-bold text-gray-900 mb-2">Identitas Pemesan</h2>
      <p className="text-gray-500 mb-8">Masukkan nomor WhatsApp Anda. Kami akan otomatis memuat riwayat kendaraan Anda jika Anda pernah memesan sebelumnya.</p>
      
      <form onSubmit={handleSubmit} className="space-y-6">
        {/* Floating Label Input for Phone */}
        <div className="relative">
          <input 
            type="tel" 
            id="phone"
            name="phone"
            value={bookingData.phone}
            onChange={handleChange}
            placeholder=" " 
            required
            className="block px-5 pb-3 pt-6 w-full text-lg text-gray-900 bg-white/50 rounded-xl border border-gray-200 appearance-none focus:outline-none focus:ring-2 focus:ring-olive-500 focus:border-olive-500 peer transition-all font-medium tracking-wide shadow-sm"
          />
          <label 
            htmlFor="phone" 
            className="absolute text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] left-5 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 peer-focus:text-olive-600 pointer-events-none"
          >
            Nomor WhatsApp (Contoh: 0812...)
          </label>
          
          {isChecking && (
            <div className="absolute right-4 top-1/2 -translate-y-1/2">
              <Loader2 className="w-5 h-5 text-olive-500 animate-spin" />
            </div>
          )}
        </div>

        {/* Floating Label Input for Name */}
        <div className="relative transition-all duration-500 opacity-100 mt-6">
          <input 
            type="text" 
            id="name"
            name="name"
            value={bookingData.name}
            onChange={handleChange}
            placeholder=" " 
            required
            className="block px-5 pb-3 pt-6 w-full text-lg text-gray-900 bg-white/50 rounded-xl border border-gray-200 appearance-none focus:outline-none focus:ring-2 focus:ring-olive-500 focus:border-olive-500 peer transition-all shadow-sm"
          />
          <label 
            htmlFor="name" 
            className="absolute text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] left-5 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 peer-focus:text-olive-600 pointer-events-none"
          >
            Nama Lengkap
          </label>
        </div>

        <button 
          type="submit" 
          disabled={isChecking}
          className="w-full mt-8 bg-gray-900 text-white font-medium text-lg py-4 rounded-xl hover:bg-olive-800 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-1 flex justify-center items-center group disabled:opacity-70 disabled:hover:translate-y-0"
        >
          <span>Lanjut ke Info Kendaraan</span>
          <ChevronRight className="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" />
        </button>
      </form>
    </div>
  );
}
