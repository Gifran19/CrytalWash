import React from 'react';
import { Sparkles, Droplets, CheckCircle2 } from 'lucide-react';
import { clsx } from 'clsx';

export default function Step3_ServiceSelection({ bookingData, updateData, nextStep, prevStep }) {
  const isCar = bookingData.vehicleType === 'Car';

  const services = [
    {
      id: 'basic',
      name: 'Basic Wash',
      price: isCar ? 50000 : 30000,
      icon: Droplets,
      features: ['Cuci Body', 'Vakum Interior', 'Semir Ban'],
      popular: false
    },
    {
      id: 'premium',
      name: 'Premium Detailing',
      price: isCar ? 150000 : 75000,
      icon: Sparkles,
      features: ['Cuci Body + Wax', 'Vakum Interior Total', 'Pembersihan Kaca', 'Semir Ban Premium'],
      popular: true
    }
  ];

  const handleSubmit = (e) => {
    e.preventDefault();
    if (bookingData.service) {
      nextStep();
    }
  };

  return (
    <div className="glass-card p-8 animate-in fade-in slide-in-from-right-4 duration-500">
      <h2 className="font-serif text-3xl font-bold text-gray-900 mb-2">Pilih Layanan</h2>
      <p className="text-gray-500 mb-8">Pilih paket layanan yang sesuai untuk kendaraan Anda.</p>
      
      <form onSubmit={handleSubmit} className="space-y-6">
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
          {services.map((service) => {
            const isSelected = bookingData.service?.id === service.id;
            const Icon = service.icon;

            return (
              <div 
                key={service.id}
                onClick={() => updateData({ service })}
                className={clsx(
                  "relative cursor-pointer rounded-2xl border-2 p-6 transition-all duration-300",
                  isSelected ? "border-olive-600 bg-olive-50 ring-4 ring-olive-100/50 shadow-md transform -translate-y-1" : "border-gray-100 bg-white hover:border-olive-300 hover:-translate-y-1 hover:shadow-xl"
                )}
              >
                {service.popular && (
                  <div className="absolute -top-3 right-4 bg-olive-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md">
                    Terpopuler
                  </div>
                )}
                
                <div className="flex justify-between items-start mb-4">
                  <div className={clsx("p-3 rounded-xl", isSelected ? "bg-olive-200 text-olive-800" : "bg-gray-100 text-gray-500")}>
                    <Icon className="w-6 h-6" />
                  </div>
                  {isSelected && <CheckCircle2 className="w-6 h-6 text-olive-600" />}
                </div>

                <h3 className="font-serif text-xl font-bold text-gray-900 mb-1">{service.name}</h3>
                <p className="text-2xl font-bold text-olive-700 mb-4">
                  Rp {service.price.toLocaleString('id-ID')}
                </p>

                <ul className="space-y-2 text-sm text-gray-600">
                  {service.features.map((feature, idx) => (
                    <li key={idx} className="flex items-center space-x-2">
                      <div className="w-1.5 h-1.5 rounded-full bg-olive-400"></div>
                      <span>{feature}</span>
                    </li>
                  ))}
                </ul>
              </div>
            );
          })}
        </div>

        <div className="flex space-x-4 pt-4">
          <button 
            type="button" 
            onClick={prevStep}
            className="w-1/3 border-2 border-gray-200 text-gray-600 font-medium text-lg py-4 rounded-xl hover:bg-gray-50 transition-colors"
          >
            Kembali
          </button>
          <button 
            type="submit" 
            disabled={!bookingData.service}
            className={clsx(
              "w-2/3 font-medium text-lg py-4 rounded-xl transition-all duration-300",
              bookingData.service ? "bg-olive-700 text-white hover:bg-olive-800 shadow-lg shadow-olive-700/20" : "bg-gray-200 text-gray-400 cursor-not-allowed"
            )}
          >
            Lanjut Pembayaran
          </button>
        </div>
      </form>
    </div>
  );
}
