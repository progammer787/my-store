@extends('admin.layouts.app')
@section('title', 'Roles')
@section('content')

<div class="page-header">
    <h1>Roles & Permissions</h1>
    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Role</a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Role Name</th>
                <th>Description</th>
                <th>Permissions</th>
                <th>Users</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($roles as $role)
        <tr>
            <td>
                <strong style="color:#fff">{{ $role->name }}</strong>
                <div style="font-size:11px;color:var(--text-muted);margin-top:2px">{{ $role->slug }}</div>
            </td>
            <td style="color:var(--text-muted)">{{ $role->description ?? '—' }}</td>
            <td>
                @if(in_array('*', $role->permissions ?? []))
                    <span class="badge badge-gold">All Permissions</span>
                @else
                    @foreach($role->permissions ?? [] as $perm)
                        <span class="badge badge-secondary" style="margin:2px">{{ ucfirst($perm) }}</span>
                    @endforeach
                @endif
            </td>
            <td>
                <span class="badge badge-info">{{ $role->users_count }} users</span>
            </td>
            <td>
                @if($role->is_system)
                    <span class="badge badge-warning">System</span>
                @else
                    <span class="badge badge-secondary">Custom</span>
                @endif
            </td>
            <td>
                <div style="display:flex;gap:8px">
                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-outline btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    @if(!$role->is_system)
                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST"
                          onsubmit="return confirm('Delete role {{ $role->name }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                    </form>
                    @endif
                </div>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>

{{-- Permission Reference --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">Permission Reference</div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px">
        @foreach(\App\Models\Role::allPermissions() as $key => $label)
        <div style="background:rgba(255,255,255,.03);border:1px solid var(--card-border);border-radius:8px;padding:14px">
            <div style="font-weight:700;color:#fff;margin-bottom:4px">{{ $label }}</div>
            <div style="font-size:12px;color:var(--text-muted)">Key: <code style="color:var(--accent)">{{ $key }}</code></div>
        </div>
        @endforeach
    </div>
</div>

@endsection