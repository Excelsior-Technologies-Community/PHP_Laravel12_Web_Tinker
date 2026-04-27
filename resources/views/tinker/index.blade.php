<!DOCTYPE html>
<html>
<head>
    <title>Web Tinker Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-6">

<div class="max-w-6xl mx-auto">

    <h1 class="text-3xl font-bold mb-4 text-gray-800">
        ⚡ Laravel Web Tinker Dashboard
    </h1>

    <!-- VIEW SWITCH BUTTONS -->
    <div class="mb-4 space-x-2">

    

        <a href="/dashboard?view=trash"
           class="bg-red-600 text-white px-4 py-2 rounded">
            🗑 Trash
        </a>

    </div>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- ERROR MESSAGE -->
    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- RUN COMMAND FORM (only active mode) -->
    @if(!request('view') || request('view') != 'trash')

    <div class="bg-white p-4 rounded shadow mb-6">
        <form method="POST" action="/run">
            @csrf

            <input
                name="command"
                class="border p-3 w-full rounded"
                placeholder="Enter Laravel / PHP command (e.g. User::count())"
                required
            />

            <button class="mt-3 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                ▶ Run Command
            </button>
        </form>
    </div>

    @endif

    <!-- SEARCH -->
    <div class="bg-white p-4 rounded shadow mb-6">
        <form method="GET" class="flex gap-2">

            <input
                name="search"
                class="border p-2 flex-1 rounded"
                placeholder="Search commands..."
            />

            @if(request('view') == 'trash')
                <input type="hidden" name="view" value="trash">
            @endif

            <button class="bg-gray-700 text-white px-4 rounded">
                Search
            </button>

        </form>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded shadow overflow-x-auto">

        <table class="w-full text-sm text-left">

            <thead class="bg-gray-200">
                <tr>
                    <th class="p-3">ID</th>
                    <th class="p-3">Command</th>
                    <th class="p-3">Result</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Actions</th>
                </tr>
            </thead>

            <tbody>

                @forelse($commands as $cmd)
                <tr class="border-b hover:bg-gray-50">

                    <td class="p-3">{{ $cmd->id }}</td>

                    <td class="p-3 font-mono text-blue-600">
                        {{ $cmd->command }}
                    </td>

                    <td class="p-3 text-gray-700">
                        {{ \Illuminate\Support\Str::limit($cmd->result, 80) }}
                    </td>

                    <td class="p-3">
                        @if($cmd->is_favorite)
                            <span class="text-yellow-500 font-bold">⭐ Favorite</span>
                        @else
                            <span class="text-gray-400">Normal</span>
                        @endif
                    </td>

                    <td class="p-3 space-x-2">

                        <!-- ACTIVE MODE ACTIONS -->
                        @if(request('view') != 'trash')

                            <a href="/favorite/{{ $cmd->id }}"
                               class="text-yellow-500 hover:underline">
                                ⭐ Toggle
                            </a>

                            <a href="/delete/{{ $cmd->id }}"
                               class="text-red-500 hover:underline">
                                Delete
                            </a>

                        @else

                            <!-- TRASH MODE ACTION -->
                            <a href="/restore/{{ $cmd->id }}"
                               class="text-green-500 hover:underline">
                                ♻ Restore
                            </a>

                        @endif

                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-6 text-center text-gray-500">
                        No commands found 😢
                    </td>
                </tr>
                @endforelse

            </tbody>

        </table>
    </div>

    <!-- PAGINATION -->
    <div class="mt-4">
        {{ $commands->links() }}
    </div>

</div>

</body>
</html>