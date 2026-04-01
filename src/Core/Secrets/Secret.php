<?php

namespace App\Core\Secrets;

enum Secret
{
    case VERBOSE_MODE;
    case DEVELOPER_MODE;
    case APP_URL;
    case USOS_CONSUMER_KEY;
    case USOS_CONSUMER_SECRET;
}