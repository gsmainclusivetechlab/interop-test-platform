@extends('layouts.default')

@section('title', __('Import test steps for :name', ['name' => $testCase->name]))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-8 mx-auto">
                <form class="card" action="{{ route('admin.test-cases.test-steps.import', $testCase) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-header">
                        <h3 class="card-title">
                            <b>@yield('title')</b>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="form-label">{{ __('File') }}</div>
                            <div class="custom-file">
                                <input type="file" name="file" class="custom-file-input @error('file') is-invalid @enderror">
                                <label class="custom-file-label">
                                    {{ __('Choose file') }}
                                </label>
                                @error('file')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('admin.test-cases.test-steps.index', $testCase) }}" class="btn btn-link">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary">{{ __('Import') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
