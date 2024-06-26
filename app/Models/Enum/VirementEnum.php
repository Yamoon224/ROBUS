<?php
namespace App\Models\Enum;

abstract class VirementEnum {

    public const CANAL_RIB = "RIB";
    public const CANAL_BTC = "BTC";
    public const CANAL_MOBILE_MONEY = "MOBILE MONEY";

    public const STATUS_PENDING = "PENDING";
    public const STATUS_SUCCESS = "SUCCESS";
    public const STATUS_ERROR = "ERROR";
}