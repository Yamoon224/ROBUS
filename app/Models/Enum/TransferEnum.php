<?php
namespace App\Models\Enum;

abstract class TransferEnum {

    public const PENDING = "PENDING";
    public const SUCCESS = "SUCCESS";
    public const CANCELLED = "CANCELLED";
    public const ERROR = "ERROR";

    public const MIN_AMOUNT = 1000;

    public const SEND_FROM_CONTACT_LIST = "CONTACT_LIST";
    public const SEND_FROM_CONTACT = "CONTACT";
    public const SEND_FROM_EXTERNAL = "EXTERNAL_FILE";

}