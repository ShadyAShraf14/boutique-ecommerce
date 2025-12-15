{{-- resources/views/Backend/pages/supervisors/_form.blade.php --}}

@csrf

<div class="form-group">
    <label>Name</label>
    <input type="text" name="name" class="form-control"
           value="{{ old('name', $supervisor->name ?? '') }}" required>
</div>

<div class="form-group">
    <label>Email</label>
    <input type="email" name="email" class="form-control"
           value="{{ old('email', $supervisor->email ?? '') }}" required>
</div>

<div class="form-group">
    <label>Password</label>
    <input type="password" name="password" class="form-control"
           {{ isset($supervisor) ? '' : 'required' }}>
    @isset($supervisor)
        <small class="text-muted">اتركي الحقل فاضي لو مش عايزة تغيري الباسورد.</small>
    @endisset
</div>

<div class="form-group form-check">
    <input type="checkbox" name="is_active" value="1" class="form-check-input"
           id="is_active"
           {{ old('is_active', $supervisor->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Active</label>
</div>

<button type="submit" class="btn btn-primary">
    {{ $button ?? 'Save' }}
</button>
<a href="{{ route('admin.supervisors.index') }}" class="btn btn-secondary ml-2">Cancel</a>
