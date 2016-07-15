<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="form-group">
    <label for="email">Name</label>
    <input type="name" class="form-control" id="name" placeholder="Name" name="name" value="{{ (Session::has('errors')) ? old('name', '') : $user->name }}">
</div>
<div class="form-group">
    <label for="email">Email address</label>
    <input disabled type="email" class="form-control" id="email" placeholder="Email" name="email" value="{{ (Session::has('errors')) ? old('email', '') : $user->email }}">
</div>
<div class="form-group">
    <label for="roles">Roles</label>
    <select name="roles[]" id="roles" multiple class="form-control">
        @foreach($roles as $index => $role)
            <option value="{{ $index }}" {{ ((in_array($index, old('roles', []))) || ( ! Session::has('errors') && $user->roles->contains('id', $index))) ? 'selected' : '' }}>{{ $role }}</option>
        @endforeach
    </select>
</div>
