import React, { useState, useEffect } from 'react';
import BookingStepper from './components/BookingStepper';
import Step1_PersonalData from './components/Step1_PersonalData';
import Step2_VehicleInfo from './components/Step2_VehicleInfo';
import Step3_ServiceSelection from './components/Step3_ServiceSelection';
import Step4_Payment from './components/Step4_Payment';
import SmartSidebar from './components/Sidebar/SmartSidebar';
import { Sparkles } from 'lucide-react';

function App() {
  const [currentStep, setCurrentStep] = useState(1);
  const [bookingData, setBookingData] = useState({
    name: '',
    phone: '',
    vehicleType: 'Car',
    plateNumber: '',
    service: null,
  });

  // Global Customer State
  const [isChecking, setIsChecking] = useState(false);
  const [customerData, setCustomerData] = useState(null);
  const [checkedPhone, setCheckedPhone] = useState('');

  const updateData = (newData) => {
    setBookingData((prev) => ({ ...prev, ...newData }));
  };

  const nextStep = () => setCurrentStep((prev) => Math.min(prev + 1, 4));
  const prevStep = () => setCurrentStep((prev) => Math.max(prev - 1, 1));
  const jumpToStep = (stepIndex) => setCurrentStep(stepIndex);

  // Phone check logic hosted globally
  useEffect(() => {
    const phone = bookingData.phone.replace(/[^0-9]/g, '');
    
    if (phone.length >= 10 && phone !== checkedPhone) {
      setCheckedPhone(phone);
      setIsChecking(true);
      
      const timer = setTimeout(() => {
        const mockCustomer = {
          name: 'Budi Santoso',
          level: 'Premium Member',
          visits: 12,
          lastVisit: '2 Minggu yang lalu',
          frequency: '2x sebulan',
          vehicle: { type: 'Car', plate: 'B 1234 CD' },
          favoriteService: { 
            id: 'premium', 
            name: 'Premium Detailing',
            price: 150000,
            icon: Sparkles // Reference to icon component will be handled in child
          },
          history: [
            { date: '12 Mei 2026', service: 'Premium Detailing', price: 150000 },
            { date: '28 Apr 2026', service: 'Basic Wash', price: 50000 },
            { date: '10 Apr 2026', service: 'Premium Detailing', price: 150000 },
          ]
        };
        
        setCustomerData(mockCustomer);
        if (!bookingData.name) updateData({ name: mockCustomer.name });
        setIsChecking(false);
      }, 1500);
      
      return () => clearTimeout(timer);
    } else if (phone.length < 10) {
      setCustomerData(null);
      setCheckedPhone('');
      setIsChecking(false);
    }
  }, [bookingData.phone, checkedPhone, bookingData.name]);

  const renderStep = () => {
    switch (currentStep) {
      case 1:
        return <Step1_PersonalData bookingData={bookingData} updateData={updateData} nextStep={nextStep} isChecking={isChecking} />;
      case 2:
        return <Step2_VehicleInfo bookingData={bookingData} updateData={updateData} nextStep={nextStep} prevStep={prevStep} />;
      case 3:
        return <Step3_ServiceSelection bookingData={bookingData} updateData={updateData} nextStep={nextStep} prevStep={prevStep} />;
      case 4:
        return <Step4_Payment bookingData={bookingData} updateData={updateData} prevStep={prevStep} />;
      default:
        return <Step1_PersonalData bookingData={bookingData} updateData={updateData} nextStep={nextStep} isChecking={isChecking} />;
    }
  };

  return (
    <div className="min-h-screen relative overflow-hidden bg-gradient-to-br from-cream via-white to-olive-50 selection:bg-olive-200 selection:text-olive-900">
      <div className="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-gradient-to-tr from-olive-200/50 to-olive-100/30 rounded-full blur-3xl animate-float pointer-events-none"></div>
      <div className="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-gradient-to-bl from-olive-300/40 to-olive-50/20 rounded-full blur-3xl animate-float pointer-events-none" style={{ animationDelay: '2s' }}></div>

      <div className="container mx-auto max-w-7xl px-4 py-8 md:py-12 relative z-10">
        <header className="mb-10 text-center md:text-left flex flex-col md:flex-row items-center justify-between">
          <div>
            <h1 className="font-serif text-4xl md:text-5xl font-bold text-gray-900 tracking-tight mb-2">
              CrystalWash <span className="text-olive-600">Premium</span>
            </h1>
            <p className="text-gray-500 font-medium">Smart Booking Dashboard.</p>
          </div>
          <div className="hidden md:flex items-center space-x-2 bg-white px-4 py-2 rounded-full shadow-sm border border-gray-100">
            <Sparkles className="w-5 h-5 text-olive-500" />
            <span className="text-sm font-bold text-gray-700">Layanan #1 di Kota Anda</span>
          </div>
        </header>

        <div className="flex flex-col lg:flex-row gap-8">
          {/* Main Booking Form */}
          <div className="flex-1 lg:max-w-2xl">
            <BookingStepper currentStep={currentStep} />
            <div className="min-h-[500px]">
              {renderStep()}
            </div>
          </div>

          {/* Smart Sidebar */}
          <div className="w-full lg:w-[450px] shrink-0 hidden md:block">
            <SmartSidebar 
              isChecking={isChecking} 
              customerData={customerData} 
              bookingData={bookingData}
              updateData={updateData}
              jumpToStep={jumpToStep}
              currentStep={currentStep}
            />
          </div>
        </div>
      </div>
    </div>
  );
}

export default App;
