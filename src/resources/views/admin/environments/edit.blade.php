@extends('layouts.app')

@section('title', __('Environments - :name', ['name' => $environment->name]))

@section('content')
    <h1 class="page-title mb-5">
        <b>@yield('title')</b>
    </h1>
    {{ Form::open(['route' => ['admin.environments.update', $environment], 'method' => 'PATCH']) }}
        <div class="form-group">
            <div class="row">
                <label class="col-sm-3">
                    <b>{{ __('Name') }}</b>
                </label>
                <div class="col-sm-9">
                    {{ Form::text('name', old('name', $environment->name), ['class' => !$errors->has('name') ? 'form-control' : 'form-control is-invalid']) }}
                    @error('name')
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label class="col-sm-3">
                    <b>{{ __('Description') }}</b>
                </label>
                <div class="col-sm-9">
                    {{ Form::textarea('description', old('description', $environment->description), ['class' => !$errors->has('description') ? 'form-control' : 'form-control is-invalid']) }}
                    @error('description')
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label class="col-sm-3">
                    <b>{{ __('Variables') }}</b>
                </label>
                <div class="col-sm-9">
                    <web-editor editor-class="@error('variables') is-invalid @enderror" editor-subject-name="variables">{{ old('variables', \Symfony\Component\Yaml\Yaml::dump($environment->variables)) }}</web-editor>
                    @error('variables')
                        <span class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="btn-list mt-4 text-right">
            <button type="submit" class="btn btn-primary btn-space">{{ __('Update') }}</button>
        </div>
    {{ Form::close() }}
@endsection
