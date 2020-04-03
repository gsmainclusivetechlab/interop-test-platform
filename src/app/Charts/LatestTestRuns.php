<?php

namespace App\Charts;

use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Builds chart data for latest test runs
 *
 * @package App\Charts
 */
class LatestTestRuns
{
	/**
	 * Limit for number of date groups
	 */
	const GROUP_LIMIT = 20;

	/**
	 * Passes
	 */
	const PASSED = 'Passed';

	/**
	 * Failed
	 */
	const FAILED = 'Failed';

	/**
	 * Maps words for interval (which are parts of method names)
	 * to date format
	 */
	const INTERVALS_MAP = [
		//'Hour' => 'Y-m-d H',
		'Day' => 'Y-m-d',
		'Month' => 'Y-m',
		'Year' => 'Y',
	];

	/**
	 * Current test session
	 *
	 * @var Session
	 */
	private $session;

	/**
	 * Chart data
	 *
	 * @var array
	 */
	private $data = [
		[
			'name' => self::PASSED,
			'data' => []
		],
		[
			'name' => self::FAILED,
			'data' => []
		],
	];

	/**
	 * Variable array for intervals
	 *
	 * @var array
	 */
	private $intervalsMap = self::INTERVALS_MAP;

	/**
	 * LatestTestRuns constructor.
	 *
	 * @param Session $session
	 */
	public function __construct(Session $session)
	{
		$this->session = $session;

		$this->initData();
	}

	/**
	 * Returns chart data
	 *
	 * @return array
	 */
	public function getData(): array
	{
		return $this->data;
	}

	/**
	 * Init chart data
	 */
	private function initData()
	{
		foreach ($this->getTestRuns() as $date => $testRunsByDate) {
			$methodForDateFormatString = 'getDateFormatFor' . $this->getCurrentInterval();

			$formattedDate = $testRunsByDate[0]->created_at
				->format($this->$methodForDateFormatString($testRunsByDate[0]->created_at));

			$chartDataPassed = $chartDataFailed = [
				'x' => (string)$formattedDate,
				'y' => 0,
			];

			foreach ($testRunsByDate as $testRun) {
				$testRunResult = $testRun->successful ? self::PASSED : self::FAILED;
				${'chartData' . $testRunResult}['y']++;
			}

			$this->data[0]['data'][] = $chartDataPassed;
			$this->data[1]['data'][] = $chartDataFailed;
		}
	}

	/**
	 * Returns test runs eloquent collection for current session.
	 * Recursive method, called until we can get number of date groups
	 * for some interval (hour, day, month etc.)
	 * that doesn't bigger than limit
	 *
	 * @return Collection
	 */
	private function getTestRuns(): Collection
	{
		$query = $this->session->testRuns();

		$interval = $this->getCurrentFormattedInterval();

		if ($this->isLastInterval()) {
			reset($this->intervalsMap);

			return $query->limit(self::GROUP_LIMIT)->get()
				->groupBy(function($date) use ($interval) {
					return Carbon::parse($date->created_at)->format($interval);
				});
		}

		$testRuns = $query->latest()->get()->groupBy(function($date) use ($interval) {
			return Carbon::parse($date->created_at)->format($interval);
		});

		if ($testRuns->count() > self::GROUP_LIMIT) {
			next($this->intervalsMap);

			return $this->getTestRuns();
		}

		return $testRuns;
	}

	/**
	 * Get current date interval
	 *
	 * @return string
	 */
	private function getCurrentInterval(): string
	{
		return key($this->intervalsMap);
	}

	/**
	 * Get formatted current date interval
	 *
	 * @return string
	 */
	private function getCurrentFormattedInterval(): string
	{
		return current($this->intervalsMap);
	}

	/**
	 * Check if it is the last interval in array
	 *
	 * @return bool
	 */
	private function isLastInterval(): bool
	{
		return $this->getCurrentInterval() === 'Year';
	}

	/**
	 * Get date format string for hour
	 *
	 * @param Carbon $date
	 *
	 * @return string
	 */
	private function getDateFormatForHour(Carbon $date): string
	{
		$format = 'G\h';

		if ($date->isToday()) {
			return $format;
		}

		return $this->getDateFormatForDay($date) . ' ' . $format;
	}

	/**
	 * Get date format string for day
	 *
	 * @param Carbon $date
	 *
	 * @return string
	 */
	private function getDateFormatForDay(Carbon $date): string
	{
		return 'j ' . $this->getDateFormatForMonth($date);
	}

	/**
	 * Get date format string for month
	 *
	 * @param Carbon $date
	 *
	 * @return string
	 */
	private function getDateFormatForMonth(Carbon $date): string
	{
		$format = 'M';

		if ($date->isCurrentYear()) {
			return $format;
		}

		return $format . ' ' . $this->getDateFormatForYear();
	}

	/**
	 * Get date format string for year
	 *
	 * @return string
	 */
	private function getDateFormatForYear(): string
	{
		return 'Y';
	}
}