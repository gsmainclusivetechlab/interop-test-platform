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
     * Chart type
     */
    const TYPE = 'bar';

    /**
     * Chart height
     */
    const HEIGHT = 360;

    /**
     * Color for passed tests
     */
    const COLOR_PASSED = '#9cb227';

    /**
     * Color for failed tests
     */
    const COLOR_FAILED = '#de002b';

    /**
     * Opacity for filled  parts
     */
    const OPACITY = 1;

    /**
     * Legend position
     */
    const LEGEND_POSITION = 'top';

    /**
     * Marker radius
     */
    const MARKER_RADIUS = 100;

    /**
     * Padding for label
     */
    const LABEL_PADDING = 0;

    /**
     * @var string
     */
    public $ajaxUrl;

    /**
     * @var string
     */
    public $type;

    /**
     * @var int
     */
    public $height;

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
        'Day',
        'Month',
        'Year',
    ];

    /**
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
        $this->ajaxUrl = route('sessions.chart', $session);
        $this->type = self::TYPE;
        $this->height = self::HEIGHT;
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
            'colors' => [self::COLOR_PASSED, self::COLOR_FAILED],
            'fill' => [
                'opacity' => self::OPACITY,
            ],
            'legend' => [
                'position' => self::LEGEND_POSITION,
                'markers' => [
                    'radius' => self::MARKER_RADIUS,
                ]
            ],
            'xaxis' => [
                'labels' => [
                    'padding' => self::LABEL_PADDING,
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

        if ($interval !== self::INTERVAL_YEAR) {
            $select .= ', MONTH(created_at) AS month';
            $groupBy .= ', MONTH(created_at)';
            $orderBy .= ', month DESC';
        }

        if ($interval === self::INTERVAL_DAY) {
            $select .= ', DAY(created_at) AS day';
            $groupBy .= ', DAY(created_at)';
            $orderBy .= ', day DESC';
        }

        $query = DB::table('test_runs')
            ->selectRaw($select)
            ->where(['session_id' => $this->session->id])
            ->groupByRaw($groupBy);

        if ($query->get()->count() > self::GROUP_LIMIT && next($this->intervals)) {
            return $this->getTestRuns();
        }

        return $query->orderByRaw($orderBy)->limit(self::GROUP_LIMIT)->get();
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
            case self::INTERVAL_DAY:
                $format = 'j M';

                if (!$date->isCurrentYear()) {
                    $format .= ' Y';
                }

                break;
            case self::INTERVAL_MONTH:
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
