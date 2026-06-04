import React from 'react';
import { Check } from 'lucide-react';
import { clsx } from 'clsx';

export default function BookingStepper({ currentStep }) {
  const steps = [
    { num: 1, label: 'Data Diri' },
    { num: 2, label: 'Kendaraan' },
    { num: 3, label: 'Layanan' },
    { num: 4, label: 'Pembayaran' },
  ];

  return (
    <div className="w-full mb-8">
      <div className="flex items-center justify-between relative">
        {/* Progress bar background */}
        <div className="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-olive-100 rounded-full z-0"></div>
        
        {/* Active progress bar */}
        <div 
          className="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-olive-600 rounded-full z-0 transition-all duration-500 ease-in-out"
          style={{ width: `${((currentStep - 1) / (steps.length - 1)) * 100}%` }}
        ></div>

        {steps.map((step) => {
          const isActive = currentStep === step.num;
          const isCompleted = currentStep > step.num;

          return (
            <div key={step.num} className="relative z-10 flex flex-col items-center">
              <div 
                className={clsx(
                  "w-10 h-10 rounded-full flex items-center justify-center font-serif text-sm font-semibold transition-all duration-300 shadow-sm",
                  isActive ? "bg-olive-600 text-white ring-4 ring-olive-100 scale-110" : 
                  isCompleted ? "bg-olive-600 text-white" : "bg-white text-gray-400 border-2 border-gray-200"
                )}
              >
                {isCompleted ? <Check className="w-5 h-5" /> : step.num}
              </div>
              <span className={clsx(
                "absolute -bottom-6 text-xs font-medium whitespace-nowrap transition-colors duration-300",
                isActive ? "text-olive-700 font-bold" : isCompleted ? "text-olive-600" : "text-gray-400"
              )}>
                {step.label}
              </span>
            </div>
          );
        })}
      </div>
    </div>
  );
}
