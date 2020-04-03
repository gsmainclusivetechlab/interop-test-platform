<?php

namespace App\Providers;

use App\View\Components\TestRunsChart;
use Illuminate\Support\Facades\Blade;

class ViewServiceProvider extends \Illuminate\View\ViewServiceProvider
{
	/**
	 * Bootstrap your package's services.
	 */
	public function boot()
	{
		Blade::component('test-runs-chart', TestRunsChart::class);
	}
}