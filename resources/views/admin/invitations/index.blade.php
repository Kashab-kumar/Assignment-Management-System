@extends('layouts.admin')

@section('title', 'Manage Invitations')
@section('page-title', 'Invitations')

@section('content')
<style>
    .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .section { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
    th { background: #f8f8f8; font-weight: bold; }
    .btn { padding: 8px 16px; background: #9C27B0; color: white; text-decoration: none; border-radius: 4px; display: inline-block; border: none; cursor: pointer; }
    .btn-success { background: #4CAF50; }
    .btn-danger { background: #f44336; }
    .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; color: white; }
    .badge-teacher { background: #2196F3; }
    .badge-student { background: #4CAF50; }
    .badge-used { background: #666; }
    .badge-expired { background: #f44336; }
    .badge-active { background: #4CAF50; }
    .alert-success { padding: 15px; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 20px; word-break: break-all; }
</style>

<div class="section">
    <div class="section-header">
        <h2>All Invitations</h2>
        <a href="{{ route('admin.invitations.create') }}" class="btn btn-success">Create New Invitation</a>
    </div>

    @if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
    @endif

    <x-ui.table>
        <x-slot name="head">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created By</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expires</th>
                <th class="px-6 py-3"></th>
            </tr>
        </x-slot>

        @forelse($invitations as $invitation)
        <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><span class="badge badge-{{ $invitation->role }}">{{ ucfirst($invitation->role) }}</span></td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $invitation->inviter->name }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm">
                @if($invitation->used)
                    <span class="badge badge-used">Used</span>
                @elseif($invitation->isExpired())
                    <span class="badge badge-expired">Expired</span>
                @else
                    <span class="badge badge-active">Active</span>
                @endif
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $invitation->created_at->format('d/m/Y') }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $invitation->expires_at->format('d/m/Y') }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                @if(!$invitation->used && !$invitation->isExpired())
                    <a href="{{ route('admin.invitations.show', $invitation) }}" class="btn" style="padding: 6px 12px; font-size: 13px; margin-right: 5px;">Share</a>
                    <form action="{{ route('admin.invitations.destroy', $invitation) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 13px;" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No invitations yet</td></tr>
        @endforelse
    </x-ui.table>

    <div style="margin-top: 20px;">
        {{ $invitations->links() }}
    </div>
</div>
@endsection
