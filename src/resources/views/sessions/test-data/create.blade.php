@extends('layouts.sessions.test-case', [$session, $testCase])

@section('title', $session->name)

@section('content')
    <form action="{{ route('sessions.test-cases.test-data.store', [$session, $testCase]) }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">
                {{ __('Name') }}
            </label>
            <input name="name" value="{{ old('name') }}" type="text" class="form-control @error('name') is-invalid @enderror">
            @error('name')
            <span class="invalid-feedback">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            <label class="form-label">
                {{ __('Method') }}
            </label>
            <select name="method" class="form-control custom-select @error('method') is-invalid @enderror">
                @foreach(\App\Enums\HttpMethodEnum::values() as $value)
                <option value="{{ $value }}" @if($value == old('method')) selected @endif>{{ $value }}</option>
                @endforeach
            </select>
            @error('method')
            <span class="invalid-feedback">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            <label class="form-label">
                {{ __('URI') }}
            </label>
            <input name="uri" value="{{ old('uri') }}" type="text" class="form-control @error('uri') is-invalid @enderror">
            @error('uri')
            <span class="invalid-feedback">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            <label class="form-label">
                {{ __('Headers') }}
            </label>
            <web-editor name="headers" editor-class="@error('uri') is-invalid @enderror" :options='@json(['mode' => 'ace/mode/json'])'>
                <template v-slot:content>{{ old('headers') }}</template>

                <template v-slot:validation>
                    @error('headers')
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </template>
            </web-editor>
        </div>
        <div class="form-group">
            <label class="form-label">
                {{ __('Body') }}
            </label>
            <web-editor name="body" editor-class="@error('uri') is-invalid @enderror" :options='@json(['mode' => 'ace/mode/yaml'])'>
                <template v-slot:content>{{ old('body') }}</template>

                <template v-slot:validation>
                    @error('body')
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </template>
            </web-editor>
        </div>
        <div class="mt-4 d-flex justify-content-between">
{{--            <a href="{{ route('sessions.test-cases.show') }}" class="btn btn-outline-primary">{{ __('Cancel') }}</a>--}}
            <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
        </div>
    </form>
@endsection
