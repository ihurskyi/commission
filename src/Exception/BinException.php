<?php

declare(strict_types=1);


namespace App\Exception;

class BinException extends \Exception
{
    public function __toString(): string {
        return __CLASS__ . ": {$this->message}";
    }
}