@extends('admin.layouts.app')
@section('title', 'Salary Details')
@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Salary Payment Details</h1>
            <p class="mt-1 text-sm text-gray-500">View salary payment information</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.salaries.edit', $salary) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            <a href="{{ route('admin.salaries.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
        </div>
    </div>

    <!-- Salary Details -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">Payment Information</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Staff Member</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $salary->user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $salary->user->email }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Payment Date</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $salary->payment_date->format('F d, Y') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Basic Salary</label>
                    <p class="text-lg font-semibold text-gray-900">₹{{ number_format($salary->basic_salary, 2) }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Payment Method</label>
                    <p class="text-lg font-semibold text-gray-900">{{ ucfirst($salary->payment_method) }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Allowances</label>
                    <p class="text-lg font-semibold text-green-600">+ ₹{{ number_format($salary->allowances, 2) }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Deductions</label>
                    <p class="text-lg font-semibold text-red-600">- ₹{{ number_format($salary->deductions, 2) }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Bonus</label>
                    <p class="text-lg font-semibold text-green-600">+ ₹{{ number_format($salary->bonus, 2) }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Net Salary</label>
                    <p class="text-2xl font-bold text-blue-600">₹{{ number_format($salary->net_salary, 2) }}</p>
                </div>
            </div>

            @if($salary->notes)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <label class="block text-sm font-medium text-gray-500 mb-1">Notes</label>
                <p class="text-gray-900">{{ $salary->notes }}</p>
            </div>
            @endif

            <div class="mt-6 pt-6 border-t border-gray-200">
                <label class="block text-sm font-medium text-gray-500 mb-1">Recorded By</label>
                <p class="text-gray-900">{{ $salary->createdBy ? $salary->createdBy->name : 'N/A' }}</p>
                <p class="text-sm text-gray-500">{{ $salary->created_at->format('M d, Y - g:i A') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
