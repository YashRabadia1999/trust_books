<div class="{{ $divClass }}">
    <div class="form-group">
        {{ Form::label($name, $label, ['class' => 'form-label']) }}<x-required></x-required>
        {{ Form::text($name, $value, ['class' => $class, 'placeholder' => $placeholder, 'pattern' => '^\+\d{1,3}\d{9,13}$', 'id' => $id, 'required' => 'required']) }}
        <div class=" text-sm text-danger mt-1">
            {{ __('Please use with country code. (ex. +91)') }}
        </div>
    </div>
</div>
