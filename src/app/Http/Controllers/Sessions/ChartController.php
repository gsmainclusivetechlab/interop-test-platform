<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Models\Session;
use Carbon\Carbon;

class ChartController extends Controller
{
    const INTERVAL_YEAR = 'Year';
    const INTERVAL_MONTH = 'Month';
    const INTERVAL_DAY = 'Day';

    /**
     * @var array
     */
    protected $intervals = [
        self::INTERVAL_DAY,
        self::INTERVAL_MONTH,
        self::INTERVAL_YEAR,
    ];

    /**
     * OverviewController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @param Session $session
     * @return array[]
     */
    public function __invoke(Session $session)
    {
        $data = [
            [
                'name' => __('Passed'),
                'data' => []
            ],
            [
                'name' => __('Failed'),
                'data' => []
            ],
        ];

        foreach ($this->getTestRuns($session) as $testRuns) {
            $date = Carbon::create($testRuns->year, $testRuns->month ?? null, $testRuns->day ?? null);

            $formattedDate = $this->getFormattedDate($date, current($this->intervals));

            $data[0]['data'][] = [
                'x' => (string) $formattedDate,
                'y' => $testRuns->passed
            ];

            $data[1]['data'][] = [
                'x' => (string) $formattedDate,
                'y' => $testRuns->failed
            ];
        }

        return $data;
    }

    /**
     * @param Session $session
     * @return mixed
     */
    protected function getTestRuns(Session $session)
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

        $query = $session->testRuns()
            ->selectRaw($select)
            ->completed()
            ->groupByRaw($groupBy);

        if ($query->get()->count() > 20 && next($this->intervals)) {
            return $this->getTestRuns($session);
        }

        return $query->orderByRaw($orderBy)->limit(20)->get();
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
