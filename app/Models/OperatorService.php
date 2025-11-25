<?php

namespace App\Models;

use App\Models\Operator;
use App\Models\Sid\SidUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperatorService extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'operator_services';

    protected $fillable = ['operator_id', 'sid_user_id'];


    public function operator()
    {
        return $this->belongsTo(Operator::class, 'operator_id', 'id');
    }

    public function sidUser()
    {
        return $this->belongsTo(SidUser::class, 'sid_user_id', 'id');
    }

    public static function getOperatorsWithCommissions($userId)
    {

        $operatorCommissionIds = self::where('sid_user_id', $userId)
            ->pluck('operator_id')
            ->toArray();

        return Operator::whereStatus(true)
            ->whereIsCommission(true)
            ->whereIn('id', $operatorCommissionIds)
            ->orderBy("name", 'desc')
            ->get()
            ->map(function ($operator) {
                $operator->isChecked = true;
                return $operator;
            });
    }

    public static function getOperatorsWithTranactions($userId)
    {

        $operatorCommissionIds = self::where('sid_user_id', $userId)
            ->pluck('operator_id')
            ->toArray();

        return Operator::whereStatus(true)
            ->whereIsUnder(true)
            ->whereIn('id', $operatorCommissionIds)
            ->orderBy("name", 'desc')
            ->get()
            ->map(function ($operator) {
                $operator->isChecked = true;
                return $operator;
            });
    }

    public static function getOperatorsWithIntegrateTranactions($userId)
    {

        $operatorCommissionIds = self::where('sid_user_id', $userId)
            ->pluck('operator_id')
            ->toArray();

        return Operator::whereIsApi(true)
            ->whereIn('id', $operatorCommissionIds)
            ->orderBy("name", 'desc')
            ->get()
            ->map(function ($operator) {
                $operator->isChecked = true;
                return $operator;
            });
    }
}
