import React from 'react';

export default function SkeletonSidebar() {
  return (
    <div className="space-y-6 animate-in fade-in duration-300">
      <div className="glass-card p-6 border-2 border-transparent">
        <div className="animate-pulse flex space-x-4">
          <div className="rounded-full bg-olive-100 h-14 w-14"></div>
          <div className="flex-1 space-y-3 py-1">
            <div className="h-4 bg-olive-100 rounded w-3/4"></div>
            <div className="flex space-x-2">
              <div className="h-3 bg-olive-100 rounded w-1/4"></div>
              <div className="h-3 bg-olive-100 rounded w-1/4"></div>
            </div>
          </div>
        </div>
        
        <div className="grid grid-cols-2 gap-3 mt-6">
          <div className="h-16 bg-gray-100 rounded-xl animate-pulse"></div>
          <div className="h-16 bg-gray-100 rounded-xl animate-pulse"></div>
        </div>

        <div className="mt-6 h-24 bg-olive-50 rounded-xl animate-pulse"></div>
        <div className="mt-6 h-12 bg-gray-200 rounded-xl animate-pulse"></div>
      </div>

      <div className="glass-card p-6">
        <div className="h-6 bg-gray-100 rounded w-1/3 mb-6 animate-pulse"></div>
        <div className="space-y-4">
          <div className="h-20 bg-gray-100 rounded-xl animate-pulse"></div>
          <div className="h-20 bg-gray-100 rounded-xl animate-pulse"></div>
        </div>
      </div>
    </div>
  );
}
