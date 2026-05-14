@extends('admin.layouts.app')
@section('title', isset($role) ? 'Edit Role' : 'Create Role')
@section('content')

<div class="page-header">
    <h1>{{ isset($role) ? 'Edit Role' : 'Create Role' }}</h1>
    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start">

    <div class="card">
        <div class="card-header"><div class="card-title">Role Details</div></div>

        @if($errors->any())
            <div class="alert alert-error" style="margin:0 24px 20px">
                <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ isset($role) ? route('admin.roles.update', $role) : route('admin.roles.store') }}"
              method="POST" style="padding:0 24px 24px">
            @csrf
            @if(isset($role)) @method('PUT') @endif

            <div class="form-grid" style="margin-bottom:18px">
                <div class="form-group">
                    <label>Role Name *</label>
                    <input type="text" name="name" value="{{ old('name', $role->name ?? '') }}"
                           placeholder="e.g. Manager" required
                           {{ isset($role) && $role->is_system ? 'readonly style=opacity:.5' : '' }}>
                </div>
                <div class="form-group">
                    <label>Slug *</label>
                    <input type="text" name="slug" id="slugInput"
                           value="{{ old('slug', $role->slug ?? '') }}"
                           placeholder="e.g. manager" required
                           {{ isset($role) ? 'readonly style=opacity:.5' : '' }}>
                    <small style="color:var(--text-muted);font-size:11px">Auto-generated. Used internally.</small>
                </div>
            </div>

            <div class="form-group">
                <label>Description</label>
                <input type="text" name="description"
                       value="{{ old('description', $role->description ?? '') }}"
                       placeholder="What can this role do?">
            </div>

            {{-- Permissions --}}
            <div class="form-group">
                <label>Permissions *</label>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:4px">
                    @foreach($allPermissions as $key => $label)
                    @php
                        $checked = in_array($key, old('permissions', $role->permissions ?? []));
                    @endphp
                    <label style="display:flex;align-items:center;gap:10px;background:rgba(255,255,255,.03);border:1px solid var(--card-border);border-radius:8px;padding:12px 14px;cursor:pointer;transition:.2s"
                           onmouseover="this.style.borderColor='var(--accent)'"
                           onmouseout="this.style.borderColor='var(--card-border)'">
                        <input type="checkbox" name="permissions[]" value="{{ $key }}"
                               {{ $checked ? 'checked' : '' }}
                               style="width:16px;height:16px;accent-color:var(--accent);cursor:pointer"
                               {{ isset($role) && $role->is_system ? 'disabled' : '' }}>
                        <div>
                            <div style="font-weight:600;color:#fff;font-size:13px">{{ $label }}</div>
                            <div style="font-size:11px;color:var(--text-muted)">{{ $key }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            @if(!isset($role) || !$role->is_system)
            <div style="margin-top:24px;display:flex;gap:10px">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ isset($role) ? 'Update Role' : 'Create Role' }}
                </button>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-outline">Cancel</a>
            </div>
            @else
            <div class="alert alert-error" style="margin-top:16px">
                <i class="fas fa-lock"></i> System roles cannot be modified.
            </div>
            @endif
        </form>
    </div>

    {{-- Info sidebar --}}
    <div class="card">
        <div class="card-header"><div class="card-title">How Roles Work</div></div>
        <div style="font-size:13px;color:var(--text-muted);line-height:1.8;padding:0 4px">
            <p style="margin-bottom:12px">Each user is assigned one role. The role defines what sections they can access in the admin panel.</p>
            <p style="margin-bottom:12px"><strong style="color:#fff">Super Admin</strong> always has full access regardless of role settings.</p>
            <p style="margin-bottom:12px"><strong style="color:#fff">System roles</strong> are built-in and cannot be deleted.</p>
            <p><strong style="color:#fff">Custom roles</strong> can be created, edited and deleted freely.</p>

            <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--card-border)">
                <div style="font-weight:700;color:#fff;margin-bottom:10px">Default Roles</div>
                <div style="margin-bottom:8px"><span class="badge badge-gold">Super Admin</span> — Everything</div>
                <div style="margin-bottom:8px"><span class="badge badge-info">Manager</span> — Products, Orders, Banners</div>
                <div style="margin-bottom:8px"><span class="badge badge-secondary">Editor</span> — Products, Categories</div>
                <div><span class="badge badge-secondary">Staff</span> — Orders only</div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-generate slug from name
document.querySelector('input[name="name"]')?.addEventListener('input', function() {
    const slugInput = document.getElementById('slugInput');
    if (slugInput && !slugInput.readOnly) {
        slugInput.value = this.value.toLowerCase().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, '');
    }
});
</script>
@endpush
@endsection