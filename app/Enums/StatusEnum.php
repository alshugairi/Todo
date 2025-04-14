<?php

namespace App\Enums;


enum StatusEnum:int
{
    case IN_ACTIVE = 0;
    case ACTIVE = 1;
    case DELETED = 2;
}
