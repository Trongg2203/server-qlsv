<?php

namespace App\Enums;

class GOALTYPE
{
    const LOSE_WEIGHT = 0;
    const GAIN_WEIGHT = 1;
    const MAINTAIN_WEIGHT = 2;
}

class ISCOMPLETED
{
    const UNCOMPLETED = 0;
    const COMPLETED = 1;
}

class GOALTYPE_STATUS
{
    const ACTIVE = 0;
    const PAUSED = 1;
    const COMPLETED = 2;
    const ABANDONED = 3;
}
