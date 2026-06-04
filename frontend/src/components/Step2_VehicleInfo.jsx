import React from 'react';
import { Car, Bike } from 'lucide-react';
import { clsx } from 'clsx';

export default function Step2_VehicleInfo({ bookingData, updateData, nextStep, prevStep }) {
  const isCar = bookingData.vehicleType === 'Car';

  const handleSubmit = (e) => {
    e.preventDefault();
    if (bookingData.plateNumber && bookingData.vehicleType) {
      nextStep();
    }
  };

  return (
    <div className="glass-card p-8 animate-in fade-in slide-in-from-right-4 duration-500">
      <h2 className="font-serif text-3xl font-bold text-gray-900 mb-2">Informasi Kendaraan</h2>
      <p className="text-gray-500 mb-8">Pilih tipe kendaraan dan masukkan nomor plat.</p>
      
      <form onSubmit={handleSubmit} className="space-y-6">
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-3">Tipe Kendaraan</label>
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <button
              type="button"
              onClick={() => updateData({ vehicleType: 'Car', service: null })}
              className={clsx(
                "flex flex-col items-center justify-center p-6 rounded-2xl border-2 transition-all duration-300",
                isCar ? "border-olive-600 bg-olive-50 text-olive-800 ring-4 ring-olive-100/50 shadow-md transform -translate-y-1" : "border-gray-100 bg-white hover:border-olive-300 hover:bg-olive-50/50 hover:-translate-y-1 hover:shadow-lg text-gray-500"
              )}
            >
              <Car className={clsx("w-10 h-10 mb-3 transition-transform duration-300", isCar ? "scale-110 text-olive-600" : "")} />
              <span className="font-medium">Mobil</span>
            </button>
            
            <button
              type="button"
              onClick={() => updateData({ vehicleType: 'Motorcycle', service: null })}
              className={clsx(
                "flex flex-col items-center justify-center p-6 rounded-2xl border-2 transition-all duration-300",
                !isCar ? "border-olive-600 bg-olive-50 text-olive-800 ring-4 ring-olive-100/50 shadow-md transform -translate-y-1" : "border-gray-100 bg-white hover:border-olive-300 hover:bg-olive-50/50 hover:-translate-y-1 hover:shadow-lg text-gray-500"
              )}
            >
              <Bike className={clsx("w-10 h-10 mb-3 transition-transform duration-300", !isCar ? "scale-110 text-olive-600" : "")} />
              <span className="font-medium">Motor</span>
            </button>
          </div>
        </div>
        
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-2">Nomor Plat</label>
          <input 
            type="text" 
            name="plateNumber"
            value={bookingData.plateNumber}
            onChange={(e) => updateData({ plateNumber: e.target.value.toUpperCase() })}
            placeholder="Contoh: B 1234 ABC" 
            required
            className="w-full px-5 py-4 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-olive-500 focus:border-olive-500 transition-all outline-none uppercase"
          />
        </div>

        <div className="flex space-x-4 pt-2">
          <button 
            type="button" 
            onClick={prevStep}
            className="w-1/3 border-2 border-gray-200 text-gray-600 font-medium text-lg py-4 rounded-xl hover:bg-gray-50 transition-colors"
          >
            Kembali
          </button>
          <button 
            type="submit" 
            className="w-2/3 bg-olive-700 text-white font-medium text-lg py-4 rounded-xl hover:bg-olive-800 transition-colors shadow-lg shadow-olive-700/20"
          >
            Pilih Layanan
          </button>
        </div>
      </form>
    </div>
  );
}
