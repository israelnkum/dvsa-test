<?php

namespace App\Enums;

enum CompanyTypeEnum: string
{
    case HEAVY_GOODS_VEHICLE = "HGV";

    case LIGHT_GOODS_VEHICLE = "LGV";

    case PASSENGER_TRANSPORT = "PT";

    case MIXED_FLEET = "MF";

}
