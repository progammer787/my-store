@extends('admin.layouts.app')
@section('title','Users')
@section('content')
<div class="page-header">
    <h1>Users</h1>
    <a href="{{ route('admin.users.create') }}" class="btn btn-accent"><i class="fas fa-plus"></i> Add User</a>
</div>

<div class="card">
    <div class="card-header">
        <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" id="srch" placeholder="Search users..." oninput="tableSearch('srch','usersTable')">
        </div>
    </div>
    <div style="overflow-x:auto">
        <table class="data-table" id="usersTable">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Permissions</th>
                    <th>Status</th>
                    <th>Last Login</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:12px">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                             style="width:40px;height:40px;border-radius:50%;object-fit:cover">
                        <div>
                            <div style="font-weight:600;font-size:14px">
                                {{ $user->name }}
                                @if($user->id === auth()->id())
                                    <span class="badge badge-info" style="margin-left:6px">You</span>
                                @endif
                            </div>
                            <div style="font-size:12px;color:var(--text-muted)">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="badge badge-{{ $user->role_badge_color }}">{{ $user->role_name }}</span>
                </td>
                <td style="font-size:12px">
                    @if($user->isSuperAdmin())
                        <span class="badge badge-gold">All Permissions</span>
                    @elseif($user->roleModel)
                        @foreach($user->roleModel->permissions as $perm)
                            @if($perm !== '*')
                                <span class="badge badge-secondary" style="margin:2px">{{ ucfirst($perm) }}</span>
                            @endif
                        @endforeach
                    @else
                        <span style="color:var(--text-muted)">—</span>
                    @endif
                </td>
                <td>
                    @if($user->id !== auth()->id())
                    <form action="{{ route('admin.users.toggle', $user) }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" class="badge {{ $user->is_active ? 'badge-success' : 'badge-danger' }}" style="border:none;cursor:pointer">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </button>
                    </form>
                    @else
                        <span class="badge badge-success">Active</span>
                    @endif
                </td>
                <td style="font-size:13px;color:var(--text-muted)">
                    {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                </td>
                <td>
                    <div style="display:flex;gap:6px">
                        @if(!$user->isSuperAdmin() || auth()->user()->isSuperAdmin())
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endif
                        @if($user->id !== auth()->id() && !$user->isSuperAdmin())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                              onsubmit="return confirm('Delete {{ $user->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@if(auth()->user()->isSuperAdmin())
<div style="margin-top:20px">
    <div class="page-header" style="margin-bottom:16px">
        <h2 style="font-family:var(--font-head);font-size:18px">Roles</h2>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline btn-sm">Manage Roles <i class="fas fa-arrow-right"></i></a>
    </div>
</div>
@endif
@endsection