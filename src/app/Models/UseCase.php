<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @mixin \Eloquent
 * @method static withTestCasesOfSession(Session $session)
 */
class UseCase extends Model
{
    /**
     * @var string
     */
    protected $table = 'use_cases';

    /**
     * @var array
     */
    protected $fillable = ['name', 'description'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testCases()
    {
        return $this->hasMany(TestCase::class, 'use_case_id');
    }

    /**
     * @param Builder $query
     * @param Session $session
     * @return mixed
     */
    public function scopeWithTestCasesOfSession($query, Session $session)
    {
        return $query
            ->with([
                'testCases' => function ($query) use ($session) {
                    $query
                        ->with([
                            'lastTestRun' => function ($query) use ($session) {
                                $query->where('session_id', $session->getKey());
                            },
                        ])
                        ->whereExists(function ($query) use ($session) {
                            $query->select(DB::raw(1))
                                ->from('session_test_cases')
                                ->where('session_test_cases.deleted_at', null)
                                ->where('session_test_cases.session_id', $session->getKey())
                                ->whereColumn('session_test_cases.test_case_id', 'test_cases.id');
                        });
                },
            ])
            ->whereHas('testCases', function ($query) use ($session) {
                $query->whereExists(function ($query) use ($session) {
                    $query->select(DB::raw(1))
                        ->from('session_test_cases')
                        ->where('session_test_cases.deleted_at', null)
                        ->where('session_test_cases.session_id', $session->getKey())
                        ->whereColumn('session_test_cases.test_case_id', 'test_cases.id');
                });
            });
    }
}
