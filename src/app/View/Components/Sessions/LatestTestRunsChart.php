<?php

namespace App\View\Components\Sessions;

use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class LatestTestRunsChart extends Component implements Arrayable
{
    const GROUP_LIMIT = 20;

    const INTERVAL_YEAR = 'Year';
    const INTERVAL_MONTH = 'Month';
    const INTERVAL_DAY = 'Day';

    /**
     * @var Session
     */
    public $session;

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
    }

    /**
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.sessions.latest-test-runs-chart');
    }

    /**
     * @return array
     */
    public function chartOptions()
    {
        return [
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
        ];
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
     * @return Collection
     */
    protected function getTestRuns(): Collection
    {
        $select = 'COUNT(IF (total = passed, 0, NULL)) AS passed, COUNT(IF (total != passed, 0, NULL)) AS failed, YEAR(created_at) AS year';
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
            ->completed()
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
