<?php

namespace App\Charts;

use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class LatestTestRuns
{
	const MIN_TEST_NUMBER = 5;

	private $session;

	private $data = [
		[
			'name' => 'Passed',
			'data' => []
		],
		[
			'name' => 'Failed',
			'data' => []
		],
	];

	public function __construct(Session $session)
	{
		$this->session = $session;
	}

	private function initData()
	{
		$testRunsData = [
			'failed' => [],
			'passed' => [],
		];

		$method = 'chart' . $this->getInterval();

		foreach ($this->session->testRuns()->latest()->get() as $testRun)  {
			array_walk($this->data, function (&$item, $key) use ($testRun, $method) {
				if (! Arr::has($item, $testRun->$method)) {
					$item[$testRun->$method] = 0;
				}
			});

			$index = $testRun->failures ? 'failed' : 'passed';

			if (!Arr::has($this->data[$index], $testRun->$method)) {
				$this->data[$index][$testRun->$method]++;
				continue;
			}

			$this->data[$index][$testRun->$method] = 1;
		}

		foreach ($testRunsData['passed'] as $date => $count) {
			$this->data[0]['data'][] = [
				'x' => $date,
				'y' => $count,
			];
		}

		foreach ($testRunsData['failed'] as $date => $count) {
			$this->data[1]['data'][] = [
				'x' => $date,
				'y' => $count,
			];
		}
	}

	public function getData()
	{
		return $this->data;
	}

	private function getInterval()
	{
		if (
			$this->session->testRuns()
				->where('created_at', '>', Carbon::now()->subDay())
				->count() >= self::MIN_TEST_NUMBER
		) {
			return 'Hour';
		}

		if (
			$this->session->testRuns()
				->where('created_at', '>', Carbon::now()->subMonth())
				->count() >= self::MIN_TEST_NUMBER
		) {
			return 'Day';
		}

		if (
			$this->session->testRuns()
				->where('created_at', '>', Carbon::now()->subYear())
				->count() >= self::MIN_TEST_NUMBER
		) {
			return 'Month';
		}

		return 'Year';
	}
}