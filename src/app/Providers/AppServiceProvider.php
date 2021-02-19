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
    /** @var string[] */
    protected $ilpValidatorsMap = [
        'ilpPacketAmount' => 'validateIlpPacketAmount',
        'ilpPacketDestination' => 'validateIlpPacketDestination',
        'ilpPacketCondition' => 'validateIlpPacketCondition',
        'ilpPacketExpiration' => 'validateIlpPacketExpiration',
    ];

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

        $this->registerValidators();
    }

    /**
     * @return void
     */
    protected function registerObservers()
    {
        TestRun::observe(TestRunObserver::class);
        TestResult::observe(TestResultObserver::class);
    }

    protected function registerValidators()
    {
        foreach ($this->ilpValidatorsMap as $validatorName => $function) {
            Validator::extend($validatorName, function (
                $attribute,
                $value,
                $parameters,
                $validator
            ) use ($function) {
                return IlpPacket::$function(
                    $value,
                    $parameters,
                    $validator->getData()
                );
            });
        }

        Validator::extend(
            'str_equals',
            function (
                $attribute,
                $value,
                $parameters,
                $validator
            ) {
                return $value === implode(',', $parameters);
            },
            'The :attribute value is invalid.');
    }
}
