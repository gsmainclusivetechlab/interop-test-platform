<?php

namespace App\View\Components\Charts;

use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class LatestTestRuns extends Component implements Arrayable
{
	/**
	 * Limit for number of date groups
	 */
	const GROUP_LIMIT = 20;

	const INTERVAL_YEAR = 'Year';
	const INTERVAL_MONTH = 'Month';
	const INTERVAL_DAY = 'Day';

	public $session;

	/**
	 * Variable array for intervals
	 *
	 * @var array
	 */
	private $intervals = [
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
