import React from 'react';
import BookingSummary from '../BookingSummary';
import CustomerProfile from './CustomerProfile';
import BookingTimeline from './BookingTimeline';
import LiveQueueWidget from './LiveQueueWidget';
import PromoBanner from './PromoBanner';
import SkeletonSidebar from './SkeletonSidebar';

export default function SmartSidebar({ isChecking, customerData, bookingData, updateData, jumpToStep, currentStep }) {
  
  if (isChecking) {
    return <SkeletonSidebar />;
  }

  // If customer is found, show the Smart Profile + Timeline + Live Queue
  if (customerData) {
    return (
      <div className="space-y-6 animate-in fade-in slide-in-from-right-8 duration-700">
        <CustomerProfile 
          customer={customerData} 
          updateData={updateData} 
          jumpToStep={jumpToStep} 
        />
        <BookingTimeline history={customerData.history} />
        {currentStep > 1 && <BookingSummary bookingData={bookingData} />}
      </div>
    );
  }

  // Default state for new customers or before phone is entered
  return (
    <div className="space-y-6 animate-in fade-in duration-500">
      <LiveQueueWidget />
      <PromoBanner />
      {currentStep > 1 && <BookingSummary bookingData={bookingData} />}
    </div>
  );
}
