<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Restaurant & Hotel Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .prose img { max-width: 100%; height: auto; }
        .prose ul { list-style-type: disc; margin-left: 1.5rem; }
        .prose ol { list-style-type: decimal; margin-left: 1.5rem; }
        .prose p { margin-bottom: 0.5rem; }
        .prose h1, .prose h2, .prose h3 { font-weight: bold; margin-top: 1rem; margin-bottom: 0.5rem; }
        .prose a { color: #2563eb; text-decoration: underline; }
        .modal {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background-color: white;
            border-radius: 1rem;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white flex flex-col">
            <div class="p-4 border-b border-gray-700">
                <h1 class="text-xl font-bold">Admin Panel</h1>
                <p class="text-sm text-gray-400">Restaurant & Hotel</p>
            </div>
            <nav class="flex-1 p-4 overflow-y-auto">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 p-2 rounded hover:bg-gray-800 {{ request()->is('admin') ? 'bg-gray-800' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.pos.index') }}" class="flex items-center gap-2 p-2 rounded hover:bg-gray-800 {{ request()->is('admin/pos*') ? 'bg-gray-800' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            POS System
                        </a>
                    </li>
                    <li class="pt-4 pb-2 text-xs text-gray-500 uppercase">Restaurant</li>
                    <li>
                        <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-2 p-2 rounded hover:bg-gray-800 {{ request()->is('admin/categories*') ? 'bg-gray-800' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            Categories
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.items.index') }}" class="flex items-center gap-2 p-2 rounded hover:bg-gray-800 {{ request()->is('admin/items*') ? 'bg-gray-800' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            Menu Items
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-2 p-2 rounded hover:bg-gray-800 {{ request()->is('admin/orders*') ? 'bg-gray-800' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            Orders
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.tables.index') }}" class="flex items-center gap-2 p-2 rounded hover:bg-gray-800 {{ request()->is('admin/tables*') ? 'bg-gray-800' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            Tables
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.table-bookings.index') }}" class="flex items-center gap-2 p-2 rounded hover:bg-gray-800 {{ request()->is('admin/table-bookings*') ? 'bg-gray-800' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Table Bookings
                        </a>
                    </li>
                    <li class="pt-4 pb-2 text-xs text-gray-500 uppercase">Hotel</li>
                    <li>
                        <a href="{{ route('admin.rooms.index') }}" class="flex items-center gap-2 p-2 rounded hover:bg-gray-800 {{ request()->is('admin/rooms*') ? 'bg-gray-800' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            Rooms
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.room-bookings.index') }}" class="flex items-center gap-2 p-2 rounded hover:bg-gray-800 {{ request()->is('admin/room-bookings*') ? 'bg-gray-800' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Room Bookings
                        </a>
                    </li>
                    <li class="pt-4 pb-2 text-xs text-gray-500 uppercase">Management</li>
                    <li>
                        <a href="{{ route('admin.inventories.index') }}" class="flex items-center gap-2 p-2 rounded hover:bg-gray-800 {{ request()->is('admin/inventories*') ? 'bg-gray-800' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            Inventory
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.inventory-transactions.index') }}" class="flex items-center gap-2 p-2 rounded hover:bg-gray-800 {{ request()->is('admin/inventory-transactions*') ? 'bg-gray-800' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
                            Stock Transactions
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.banners.index') }}" class="flex items-center gap-2 p-2 rounded hover:bg-gray-800 {{ request()->is('admin/banners*') ? 'bg-gray-800' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Banners
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2 p-2 rounded hover:bg-gray-800 {{ request()->is('admin/users*') ? 'bg-gray-800' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            Staff
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.expenses.index') }}" class="flex items-center gap-2 p-2 rounded hover:bg-gray-800 {{ request()->is('admin/expenses*') ? 'bg-gray-800' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Expenses
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-2 p-2 rounded hover:bg-gray-800 {{ request()->is('admin/reports*') ? 'bg-gray-800' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Reports
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="p-4 border-t border-gray-700">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 w-full p-2 rounded hover:bg-gray-800 text-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <div class="p-8">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')
    <script>
        function openModal(id = 'modal') {
            document.getElementById(id).classList.add('show');
        }

        function closeModal(id = 'modal') {
            document.getElementById(id).classList.remove('show');
        }

        // Close modal when clicking outside
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal(this.id);
                }
            });
        });
    </script>
</body>
</html>
