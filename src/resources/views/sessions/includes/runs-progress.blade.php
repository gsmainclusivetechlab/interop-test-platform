<b-progress class="w-100 h-3 rounded-0 progress">
    @php
        $totalCount = $session->testCases()->count();
        $passedCount = $session->testRuns()->latest()->get()->unique('test_case_id')->where('total', '=', 'successful')->count();
        $failureCount = $session->testRuns()->latest()->get()->unique('test_case_id')->where('total', '!=', 'successful')->count();
        $notExecutedCount = $totalCount - $passedCount - $failureCount;
    @endphp
    <b-progress-bar :value={{ $totalCount ? $passedCount / $totalCount * 100 : 0 }} variant="success" v-b-tooltip.hover title="{{ __(':n Pass', ['n' => $passedCount]) }}"></b-progress-bar>
    <b-progress-bar :value={{ $totalCount ? $failureCount / $totalCount * 100 : 0 }} variant="danger" v-b-tooltip.hover title="{{ __(':n Fail', ['n' => $failureCount]) }}"></b-progress-bar>
    <b-progress-bar :value={{ $totalCount ? $notExecutedCount / $totalCount * 100 : 0 }} variant="secondary" v-b-tooltip.hover title="{{ __(':n Not executed', ['n' => $notExecutedCount]) }}"></b-progress-bar>
</b-progress>
