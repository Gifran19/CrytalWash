import React from 'react';
import { Users, Clock } from 'lucide-react';

export default function LiveQueueWidget() {
  return (
    <div className="glass-card p-6 border-l-4 border-l-orange-500 overflow-hidden relative group">
      <div className="absolute -right-4 -top-4 w-24 h-24 bg-orange-100 rounded-full blur-2xl opacity-50 group-hover:opacity-100 transition-opacity"></div>
      
      <div className="flex items-center justify-between mb-4 relative z-10">
        <h3 className="font-bold text-gray-900 flex items-center">
          <span className="relative flex h-3 w-3 mr-2">
            <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
            <span className="relative inline-flex rounded-full h-3 w-3 bg-orange-500"></span>
          </span>
          Live Queue Status
        </h3>
        <span className="text-xs font-semibold text-orange-600 bg-orange-50 px-2 py-1 rounded-md">Real-time</span>
      </div>

      <div className="grid grid-cols-2 gap-4 relative z-10">
        <div className="bg-white/60 p-4 rounded-xl border border-gray-100">
          <div className="flex items-center text-gray-500 mb-2">
            <Users className="w-4 h-4 mr-1.5" />
            <span className="text-xs font-medium">Antrean Saat Ini</span>
          </div>
          <div className="flex items-baseline space-x-1">
            <span className="text-2xl font-bold text-gray-900">3</span>
            <span className="text-sm font-medium text-gray-500">Kendaraan</span>
          </div>
        </div>

        <div className="bg-white/60 p-4 rounded-xl border border-gray-100">
          <div className="flex items-center text-gray-500 mb-2">
            <Clock className="w-4 h-4 mr-1.5" />
            <span className="text-xs font-medium">Estimasi Waktu</span>
          </div>
          <div className="flex items-baseline space-x-1">
            <span className="text-2xl font-bold text-gray-900">~45</span>
            <span className="text-sm font-medium text-gray-500">Menit</span>
          </div>
        </div>
      </div>
    </div>
  );
}
