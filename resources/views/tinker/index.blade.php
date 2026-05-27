<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Web Tinker Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .dark { background-color: #1a202c; color: #e2e8f0; }
        .dark .bg-white { background-color: #2d3748; color: #e2e8f0; }
        .dark .bg-gray-100 { background-color: #1a202c; }
        .dark .text-gray-800 { color: #edf2f7; }
        .dark .border { border-color: #4a5568; }
        .dark .bg-gray-200 { background-color: #4a5568; }
    </style>
    <script>
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="bg-gray-100 p-6 transition-colors duration-300">

    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold text-gray-800">⚡ Laravel Web Tinker Dashboard</h1>
            <button onclick="toggleDarkMode()" class="bg-gray-700 text-white px-4 py-2 rounded">🌙 Dark Mode</button>
        </div>

        <div class="mb-4 space-x-2">
            <a href="/dashboard?view=trash" class="bg-red-600 text-white px-4 py-2 rounded">🗑 Trash</a>
            <a href="/dashboard" class="bg-blue-600 text-white px-4 py-2 rounded">🏠 Home</a>
        </div>

        @if(session('success')) <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">{{ session('success') }}</div> @endif
        @if(session('error')) <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">{{ session('error') }}</div> @endif

        @if(!request('view') || request('view') != 'trash')
            <div class="bg-white p-4 rounded shadow mb-6">
                <form method="POST" action="/run" id="tinkerForm">
                    @csrf
                    <input name="command" id="cmdInput" class="border p-3 w-full rounded bg-gray-50 dark:bg-gray-700" placeholder="Enter command (e.g. User::count()) - Press Ctrl + Enter to run" required />
                    <button type="submit" class="mt-3 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">▶ Run Command</button>
                </form>
            </div>
        @endif

        <div class="bg-white p-4 rounded shadow mb-6">
            <form method="GET" class="flex gap-2">
                <input name="search" class="border p-2 flex-1 rounded bg-gray-50 dark:bg-gray-700" placeholder="Search commands..." value="{{ request('search') }}" />
                @if(request('view') == 'trash') <input type="hidden" name="view" value="trash"> @endif
                <button class="bg-gray-700 text-white px-4 rounded">Search</button>
            </form>
        </div>

        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-200 dark:bg-gray-600">
                    <tr>
                        <th class="p-3">ID</th>
                        <th class="p-3">Command</th>
                        <th class="p-3">Result</th>
                        <th class="p-3">Metrics</th>
                        <th class="p-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($commands as $cmd)
                        <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="p-3">{{ $cmd->id }}</td>
                            <td class="p-3 font-mono text-blue-600">{{ $cmd->command }}</td>
                            <td class="p-3 text-gray-700 dark:text-gray-300">{{ \Illuminate\Support\Str::limit($cmd->result, 50) }}</td>
                            <td class="p-3 text-xs text-gray-500">
                                <div>Time: {{ $cmd->execution_time ?? 'N/A' }}</div>
                                <div>Mem: {{ $cmd->memory_usage ?? 'N/A' }}</div>
                            </td>
                            <td class="p-3 space-x-2">
                                @if(request('view') != 'trash')
                                    <a href="/favorite/{{ $cmd->id }}" class="text-yellow-500">⭐ Toggle</a>
                                    <a href="/delete/{{ $cmd->id }}" class="text-red-500">Delete</a>
                                @else
                                    <a href="/restore/{{ $cmd->id }}" class="text-green-500">♻ Restore</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="p-6 text-center text-gray-500">No commands found 😢</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $commands->links() }}</div>
    </div>

    <script>
        function toggleDarkMode() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('darkMode', isDark);
        }

        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'Enter') {
                document.getElementById('tinkerForm').submit();
            }
        });
    </script>
</body>
</html>