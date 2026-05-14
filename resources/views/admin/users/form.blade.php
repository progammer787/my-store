@extends('admin.layouts.app')
@section('title', isset($user) ? 'Edit User' : 'Add User')
@section('content')

<div class="page-header">
    <h1>{{ isset($user) ? 'Edit User' : 'Add User' }}</h1>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div style="display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start">
    <div class="card">
        <div class="card-header"><div class="card-title">User Details</div></div>
        <div class="card-body">
            <form action="{{ isset($user) ? route('admin.users.update',$user) : route('admin.users.store') }}"
                  method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($user)) @method('PUT') @endif

                @if($errors->any())
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="form-grid">
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address *</label>
                        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Password {{ isset($user) ? '(leave blank to keep)' : '*' }}</label>
                        <input type="password" name="password" {{ isset($user) ? '' : 'required' }} minlength="8" placeholder="Min 8 characters">
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="password_confirmation" placeholder="Repeat password">
                    </div>
                </div>

                <hr style="border:none;border-top:1px solid var(--border);margin:20px 0">

                <div class="form-grid">
                    <div class="form-group">
                        <label>Assign Role</label>
                        <select name="role_id" onchange="syncRole(this)">
                            <option value="">— Select Role —</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}"
                                    data-slug="{{ $role->slug }}"
                                    data-perms="{{ implode(', ', array_map('ucfirst', $role->permissions)) }}"
                                    {{ old('role_id', $user->role_id ?? '') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="role" id="roleSlug"
                               value="{{ old('role', $user->role ?? 'staff') }}">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="is_active">
                            <option value="1" {{ old('is_active', $user->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $user->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                {{-- Role permissions preview --}}
                <div id="permPreview" style="display:none;background:var(--bg);border-radius:8px;padding:14px;margin-top:8px;font-size:13px">
                    <strong style="display:block;margin-bottom:6px;color:var(--text-muted)">This role has access to:</strong>
                    <span id="permList" style="color:var(--text)"></span>
                </div>

                <div style="margin-top:24px;display:flex;gap:10px">
                    <button type="submit" class="btn btn-accent">
                        <i class="fas fa-save"></i> {{ isset($user) ? 'Update User' : 'Create User' }}
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:16px">
        {{-- Avatar upload --}}
        <div class="card">
            <div class="card-header"><div class="card-title">Avatar</div></div>
            <div class="card-body" style="text-align:center">
                <img src="{{ isset($user) ? $user->avatar_url : 'https://ui-avatars.com/api/?name=New+User&background=c8a96e&color=fff&size=120' }}"
                     id="avatarPreview"
                     style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:3px solid var(--border);margin:0 auto 16px">
                <div class="img-upload-wrap" onclick="document.getElementById('avatarInput').click()">
                    <i class="fas fa-camera" style="font-size:20px;color:var(--text-muted);margin-bottom:6px"></i>
                    <p style="font-size:13px;color:var(--text-muted)">Click to upload avatar</p>
                    <input type="file" id="avatarInput" name="avatar" accept="image/*" style="display:none"
                           onchange="previewAvatar(this)">
                </div>
            </div>
        </div>

        {{-- Role info card --}}
        <div class="card">
            <div class="card-header"><div class="card-title">Role Guide</div></div>
            <div class="card-body" style="font-size:13px">
                @foreach($roles as $role)
                <div style="margin-bottom:12px;padding-bottom:12px;border-bottom:1px solid var(--border)">
                    <div style="font-weight:700;margin-bottom:4px">{{ $role->name }}</div>
                    <div style="color:var(--text-muted)">{{ $role->description }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function syncRole(sel) {
    const opt = sel.options[sel.selectedIndex];
    document.getElementById('roleSlug').value = opt.dataset.slug || 'staff';
    const perms = opt.dataset.perms || '';
    if (perms) {
        document.getElementById('permPreview').style.display = 'block';
        document.getElementById('permList').textContent = perms;
    } else {
        document.getElementById('permPreview').style.display = 'none';
    }
}

function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('avatarPreview').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}

// Init permissions on edit
window.addEventListener('DOMContentLoaded', () => {
    const sel = document.querySelector('select[name="role_id"]');
    if (sel && sel.value) syncRole(sel);
});
</script>
@endpush
@endsection