<b-progress class="w-100 h-3 rounded-0 progress">
    <b-progress-bar :value={{ $session->test_runs_count ? $session->passed_test_runs_count / $session->test_runs_count * 100 : 0 }} variant="success" v-b-tooltip.hover title="{{ __(':n Pass', ['n' => $session->passed_test_runs_count]) }}"></b-progress-bar>
    <b-progress-bar :value={{ $session->test_runs_count ? $session->failure_test_runs_count / $session->test_runs_count * 100 : 0 }} variant="danger" v-b-tooltip.hover title="{{ __(':n Fail', ['n' => $session->failure_test_runs_count]) }}"></b-progress-bar>
</b-progress>
