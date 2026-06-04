import React from 'react';
import { Calendar, CheckCircle2 } from 'lucide-react';

export default function BookingTimeline({ history }) {
  if (!history || history.length === 0) return null;

  return (
    <div className="glass-card p-6">
      <h3 className="font-serif text-lg font-bold text-gray-900 mb-6 flex items-center">
        <Calendar className="w-5 h-5 mr-2 text-olive-600" />
        Riwayat Pesanan
      </h3>

      <div className="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-200 before:to-transparent">
        {history.map((item, index) => (
          <div key={index} className="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
            {/* Timeline dot */}
            <div className="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-olive-100 text-olive-600 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 absolute left-0 md:left-1/2 -translate-x-1/2 z-10 transition-transform duration-300 group-hover:scale-110 group-hover:bg-olive-600 group-hover:text-white group-hover:border-olive-100">
              <CheckCircle2 className="w-5 h-5" />
            </div>
            
            {/* Content Card */}
            <div className="w-[calc(100%-3rem)] md:w-[calc(50%-2.5rem)] ml-14 md:ml-0 bg-white/70 backdrop-blur border border-gray-100 p-4 rounded-xl shadow-sm hover:shadow-md transition-shadow group-hover:border-olive-200 group-hover:bg-olive-50/50">
              <div className="flex justify-between items-center mb-1">
                <span className="text-xs font-bold text-gray-400 uppercase tracking-wider">{item.date}</span>
                <span className="text-xs font-black text-olive-700">Rp {item.price.toLocaleString('id-ID')}</span>
              </div>
              <h4 className="font-bold text-gray-900 text-sm">{item.service}</h4>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
