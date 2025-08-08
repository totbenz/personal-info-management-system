<x-app-layout>
    <div class="max-w-2xl mx-auto py-8">
        <h2 class="text-2xl font-bold mb-6">Signatures Settings</h2>
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif
        <form method="POST" action="{{ route('admin.signatures.update') }}">
            @csrf
            <table class="min-w-full mb-4">
                <thead>
                    <tr>
                        <th class="text-left py-2">Position</th>
                        <th class="text-left py-2">Full Name</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($signatures as $signature)
                    <tr>
                        <td class="py-2">{{ $signature->position }}</td>
                        <td class="py-2" style="min-width: 350px;">
                            <input type="hidden" name="signatures[{{ $loop->index }}][id]" value="{{ $signature->id }}">
                            <input type="text" name="signatures[{{ $loop->index }}][full_name]" value="{{ old('signatures.'.$loop->index.'.full_name', $signature->full_name) }}" class="border rounded px-2 py-1 w-full" style="min-width: 300px; width: 100%;">
                            @error('signatures.'.$loop->index.'.full_name')
                            <div class="text-red-600 text-sm">{{ $message }}</div>
                            @enderror
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save Changes</button>
        </form>
    </div>
</x-app-layout>
