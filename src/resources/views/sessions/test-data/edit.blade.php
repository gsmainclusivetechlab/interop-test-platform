@extends('layouts.sessions.test-case', [$session, $testCase])

@section('title', $session->name)

@section('content')
    <form class="card" action="{{ route('sessions.test-cases.test-data.store', [$session, $testCase]) }}" method="POST">
        @csrf
        <div class="card-header">
            <h3 class="card-title">
                <b>{{ __('Update test data') }}</b>
            </h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label class="form-label">
                    {{ __('Name') }}
                </label>
                <input name="name" value="{{ old('name', $testDatum->name) }}" type="text" class="form-control @error('name') is-invalid @enderror">
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
                        <option value="{{ $value }}" @if($value == old('method', $testDatum->method)) selected @endif>{{ $value }}</option>
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
                <input name="uri" value="{{ old('uri', $testDatum->uri) }}" type="text" class="form-control @error('uri') is-invalid @enderror">
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
                <web-editor name="headers" editor-class="@error('headers') is-invalid @enderror" :options='@json(['mode' => 'ace/mode/json'])'>
                    <template v-slot:content>{{ old('headers', $testDatum->headers) }}</template>
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
                <web-editor name="body" editor-class="@error('body') is-invalid @enderror" :options='@json(['mode' => 'ace/mode/json'])'>
                    <template v-slot:content>{{ old('body', $testDatum->body) }}</template>
                    <template v-slot:validation>
                        @error('body')
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </template>
                </web-editor>
            </div>
        </div>
        <div class="card-footer text-right">
            <a href="{{ route('sessions.test-cases.test-data.index', [$session, $testCase]) }}" class="btn btn-link">{{ __('Cancel') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
        </div>
    </form>
@endsection
