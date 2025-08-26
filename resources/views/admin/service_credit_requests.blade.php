@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Pending Service Credit Requests</h1>
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="overflow-x-auto bg-white shadow rounded">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">Teacher</th>
                    <th class="px-4 py-2 text-left">Requested Days</th>
                    <th class="px-4 py-2 text-left">Work Date</th>
                    <th class="px-4 py-2 text-left">Reason</th>
                    <th class="px-4 py-2 text-left">Submitted</th>
                    <th class="px-4 py-2 text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $req->teacher->first_name ?? 'N/A' }} {{ $req->teacher->last_name ?? '' }}</td>
                        <td class="px-4 py-2">{{ number_format($req->requested_days,2) }}</td>
                        <td class="px-4 py-2">{{ $req->work_date ? $req->work_date->format('Y-m-d') : '—' }}</td>
                        <td class="px-4 py-2">{{ $req->reason }}</td>
                        <td class="px-4 py-2">{{ $req->created_at->diffForHumans() }}</td>
                        <td class="px-4 py-2 space-x-2">
                            <form method="POST" action="{{ route('admin.service-credit-requests.approve', $req) }}" class="inline">
                                @csrf
                                <button class="px-3 py-1 bg-green-600 text-white rounded text-xs" onclick="return confirm('Approve this request?')">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('admin.service-credit-requests.deny', $req) }}" class="inline">
                                @csrf
                                <button class="px-3 py-1 bg-red-600 text-white rounded text-xs" onclick="return confirm('Deny this request?')">Deny</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">No pending requests.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
