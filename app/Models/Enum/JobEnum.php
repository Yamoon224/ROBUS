<?php
namespace App\Models\Enum;

abstract class JobEnum {
    /**
     * @STATUS
     */
    public const SUCCESS = "SUCCESS";
    public const PENDING = "PENDING";
    public const ERROR = "ERROR";

    /**
     * TODO
     */
    public const SEND_MONEY = "SEND_MONEY";
}