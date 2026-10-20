@extends('admin.layouts.app')

@section('title', 'Reports')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Reports</h1>
            <p class="mt-1 text-sm text-gray-500">View financial and operational reports</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <a href="{{ route('admin.reports.daily') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
        <div class="flex items-center">
            <div class="bg-green-100 p-3 rounded-full mr-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">Daily Report</h3>
                <p class="text-gray-500">View daily financial summary</p>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.reports.monthly') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
        <div class="flex items-center">
            <div class="bg-green-100 p-3 rounded-full mr-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">Monthly Report</h3>
                <p class="text-gray-500">View monthly financial summary</p>
            </div>
        </div>
    </a>
</div>
@endsection
