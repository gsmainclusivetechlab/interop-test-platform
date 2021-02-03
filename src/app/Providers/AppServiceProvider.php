<?php declare(strict_types=1);

namespace App\Providers;

use App\Extensions\Twig\IlpPacket;
use App\Models\{GroupEnvironment, Session, TestResult, TestRun};
use App\Observers\{TestResultObserver, TestRunObserver};
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->registerObservers();

        Relation::morphMap([
            'group_environment' => GroupEnvironment::class,
            'session' => Session::class,
        ]);

        Validator::extend('ilpPacket', function (
            $attribute,
            $value,
            $parameters,
            $validator
        ) {
            return (new IlpPacket())->validateIlpPacket($value);
        });
    }

    /**
     * @return void
     */
    protected function registerObservers()
    {
        TestRun::observe(TestRunObserver::class);
        TestResult::observe(TestResultObserver::class);
    }
}
