@extends('layouts.admin')

@section('content')
<div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Pending Leave Requests</h2>
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">User</th>
                <th class="py-2 px-4 border-b">Start Date</th>
                <th class="py-2 px-4 border-b">End Date</th>
                <th class="py-2 px-4 border-b">Reason</th>
                <th class="py-2 px-4 border-b">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requests as $request)
            <tr>
                <td class="py-2 px-4 border-b">{{ $request->user->name }}</td>
                <td class="py-2 px-4 border-b">{{ $request->start_date }}</td>
                <td class="py-2 px-4 border-b">{{ $request->end_date }}</td>
                <td class="py-2 px-4 border-b">{{ $request->reason }}</td>
                <td class="py-2 px-4 border-b">
                    <form method="POST" action="{{ route('admin.leave-requests.update', $request->id) }}" class="inline">
                        @csrf
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Approve</button>
                    </form>
                    <form method="POST" action="{{ route('admin.leave-requests.update', $request->id) }}" class="inline ml-2">
                        @csrf
                        <input type="hidden" name="status" value="denied">
                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Deny</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-4 px-4 text-center text-gray-500">No pending leave requests.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
