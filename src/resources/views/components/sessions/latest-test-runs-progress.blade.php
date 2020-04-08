<b-progress class="w-100 h-3 rounded-0 progress">
    @foreach($progressData as $datum)
        <b-progress-bar :value={{ $datum['value'] }} variant="{{ $datum['variant'] }}" v-b-tooltip.hover title="{{ $datum['title'] }}"></b-progress-bar>
    @endforeach
</b-progress>
