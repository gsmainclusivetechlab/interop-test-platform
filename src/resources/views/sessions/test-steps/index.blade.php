@extends('layouts.sessions.test-case', $session)

@section('title', $session->name)

@section('content')
    <div class="card">
        <div class="table table-striped table-hover card-table">
            <table class="table table-striped table-hover card-table">
                <thead class="thead-light">
                <tr>
                    <th class="text-nowrap align-middle">{{ __('Step') }}</th>
                    <th class="text-nowrap align-middle">{{ __('Source') }}</th>
                    <th class="text-nowrap align-middle">{{ __('Target') }}</th>
                    <th class="text-nowrap align-middle">{{ __('URL') }}</th>
                    <th class="text-nowrap align-middle">{{ __('Data examples') }}</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($testSteps as $testStep)
                        <tr>
                            <td class="align-middle">
                                {{ __('Step :n', ['n' => $testStep->position]) }}
                            </td>
                            <td class="align-middle">
                                {{ $testStep->source->name }}
                            </td>
                            <td class="align-middle">
                                {{ $testStep->target->name }}
                            </td>
                            <td class="align-middle">
                                {{ $testStep->forward }}
                            </td>
                            <td class="align-middle">
                                @if ($testStep->request_example || $testStep->response_example)
                                    <div class="btn-group" role="group">
                                        @if ($testStep->request_example)
                                            <button type="button" class="btn btn-secondary border" data-fancybox data-src="#step-{{ $testStep->id }}-request">{{ __('Request') }}</button>
                                        @endif
                                        @if ($testStep->response_example)
                                            <button type="button" class="btn btn-secondary border" data-fancybox data-src="#step-{{ $testStep->id }}-response">{{ __('Response') }}</button>
                                        @endif
                                    </div>
                                @endif
                                @if ($testStep->request_example)
                                    @include('sessions.test-steps.includes.request')
                                @endif
                                @if ($testStep->response_example)
                                    @include('sessions.test-steps.includes.response')
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($testSteps->count())
            <div class="card-footer">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        {{ __('Showing :from to :to of :total entries', [
                            'from' => (($testSteps->currentPage() - 1) * $testSteps->perPage()) + 1,
                            'to' => (($testSteps->currentPage() - 1) * $testSteps->perPage()) + $testSteps->count(),
                            'total' => $testSteps->total(),
                        ]) }}
                    </div>
                    <div class="col-md-6">
                        <div class="justify-content-end d-flex">
                            {{ $testSteps->appends(request()->all())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
