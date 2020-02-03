<footer class="footer">
    <div class="container-fluid">
        <div class="row align-items-center flex-row-reverse">
            <div class="col-auto ml-lg-auto">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <ul class="list-inline list-inline-dots mb-0">
                            <li class="list-inline-item">
                                <a href="{{ env('APP_COMPANY_LEGAL_URL') }}" target="_blank">{{ __('Legal') }}</a>
                            </li>
                            <li class="list-inline-item">
                                <a href="{{ env('APP_COMPANY_COOKIES_URL') }}" target="_blank">{{ __('Cookies') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-auto mt-3 mt-lg-0 text-center">
                {{ __('Copyright © :year', ['year' => now()->year]) }}
                <a href="{{ env('APP_COMPANY_URL') }}" target="_blank">{{ env('APP_COMPANY_NAME') }}</a>.
                {{ __('All rights reserved') }}.
            </div>
        </div>
    </div>
</footer>
