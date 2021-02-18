<?php declare(strict_types=1);

namespace App\Providers;

use App\Extensions\Twig\IlpPacket;
use App\Models\{
    Group,
    GroupEnvironment,
    ImplicitSut,
    Session,
    TestResult,
    TestRun
};
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
            'group' => Group::class,
            'group_environment' => GroupEnvironment::class,
            'session' => Session::class,
            'implicit_sut' => ImplicitSut::class,
        ]);

        Validator::extend('ilpPacket', function (
            $attribute,
            $value,
            $parameters,
            $validator
        ) {
            return IlpPacket::validateIlpPacket(
                $value,
                $parameters,
                $validator->getData()
            );
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
    }
}
