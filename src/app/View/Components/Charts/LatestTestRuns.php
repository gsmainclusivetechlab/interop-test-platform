<?php

namespace App\View\Components\Charts;

use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

/**
 * Class LatestTestRuns
 *
 * @package App\View\Components\Charts
 */
class LatestTestRuns extends Component implements Arrayable
{
    /**
     * Limit for number of date groups
     */
    const GROUP_LIMIT = 20;

    /**
     * Interval year
     */
    const INTERVAL_YEAR = 'Year';

    /**
     * Interval month
     */
    const INTERVAL_MONTH = 'Month';

    /**
     * Interval day
     */
    const INTERVAL_DAY = 'Day';

    /**
     * @var string
     */
    public $ajaxUrl;

    /**
     * @var Session
     */
    protected $session;

    /**
     * Array for intervals
     *
     * @var array
     */
    protected $intervals = [
        self::INTERVAL_DAY,
        self::INTERVAL_MONTH,
        self::INTERVAL_YEAR,
    ];

    /**
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
        $this->ajaxUrl = route('sessions.chart', $session);
    }

    /**
     * Chart options
     *
     * @return false|string
     */
    public function options()
    {
        return json_encode([
            'chart' => [
                'stacked' => true,
                'toolbar' => [
                    'show'    => false,
                ],
                'zoom'    => [
                    'enabled' => false,
                ],
            ],
            'colors' => ['#9cb227', '#de002b'],
            'fill' => [
                'opacity' => 1,
            ],
            'legend' => [
                'position' => 'top',
                'markers' => [
                    'radius' => 100,
                ]
            ],
            'xaxis' => [
                'labels' => [
                    'padding' => 0,
                ],
                'tooltip' => [
                    'enabled' => false,
                ]
            ]
        ]);
    }

    /**
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.charts.latest-test-runs');
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $data = [
            [
                'name' => 'Passed',
                'data' => []
            ],
            [
                'name' => 'Failed',
                'data' => []
            ],
        ];

        foreach ($this->getTestRuns() as $testRuns) {
            $date = Carbon::create($testRuns->year, $testRuns->month ?? null, $testRuns->day ?? null);

            $formattedDate = $this->getFormattedDate($date, current($this->intervals));

            $data[0]['data'][] = [
                'x' => (string)$formattedDate,
                'y' => $testRuns->passed
            ];

            $data[1]['data'][] = [
                'x' => (string)$formattedDate,
                'y' => $testRuns->failed
            ];
        }

        return $data;
    }

    /**
     * Returns test runs eloquent collection for current session.
     * Recursive method, called until we can get number of date groups
     * for some interval (hour, day, month etc.)
     * that doesn't bigger than limit
     *
     * @return Collection
     */
    protected function getTestRuns(): Collection
    {
        $select = 'COUNT(IF (successful = 1, 1, NULL)) AS passed, COUNT(IF (successful = 0, 1, NULL)) AS failed, YEAR(created_at) AS year';
        $groupBy = 'YEAR(created_at)';
        $orderBy = 'year DESC';

        $interval = current($this->intervals);

        if ($interval !== static::INTERVAL_YEAR) {
            $select .= ', MONTH(created_at) AS month';
            $groupBy .= ', MONTH(created_at)';
            $orderBy .= ', month DESC';
        }

        if ($interval === static::INTERVAL_DAY) {
            $select .= ', DAY(created_at) AS day';
            $groupBy .= ', DAY(created_at)';
            $orderBy .= ', day DESC';
        }

        $query = $this->session->testRuns()
            ->selectRaw($select)
            ->groupByRaw($groupBy);

        if ($query->get()->count() > static::GROUP_LIMIT && next($this->intervals)) {
            return $this->getTestRuns();
        }

        return $query->orderByRaw($orderBy)->limit(static::GROUP_LIMIT)->get();
    }

    /**
     * @param Carbon $date
     * @param string $targetInterval
     *
     * @return string
     */
    protected function getFormattedDate(Carbon $date, string $targetInterval): string
    {
        switch ($targetInterval) {
            case static::INTERVAL_DAY:
                $format = 'j M';

                if (!$date->isCurrentYear()) {
                    $format .= ' Y';
                }

                break;
            case static::INTERVAL_MONTH:
                $format = 'M';

                if (!$date->isCurrentYear()) {
                    $format .= ' Y';
                }

                break;
            default:
                $format = 'Y';
                break;
        }

        return $date->format($format);
    }
}
