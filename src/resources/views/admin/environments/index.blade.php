@extends('layouts.app')

@section('title', __('Environments'))

@section('content')
    <h1 class="page-title mb-5">
        <b>@yield('title')</b>
    </h1>
    <div class="card">
        <div class="card-header">
            <form action="">
                <div class="input-icon">
                    @include('components.grid.search')
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover card-table">
                <thead class="thead-light">
                    <tr>
                        <th class="text-nowrap w-25">{{ __('Name') }}</th>
                        <th class="text-nowrap">{{ __('Description') }}</th>
                        <th class="text-nowrap w-1"></th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($environments as $environment)
                    <tr>
                        <td class="text-break">
                            <a href="{{ route('admin.environments.edit', $environment) }}">{{ $environment->name }}</a>
                        </td>
                        <td class="text-break">
                            {{ \Illuminate\Support\Str::limit($environment->description) }}
                        </td>
                        <td class="text-center">

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="7">
                            {{ __('No Results') }}
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            @include('components.grid.pagination', ['paginator' => $environments])
        </div>
    </div>
@endsection
