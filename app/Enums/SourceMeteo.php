<?php

namespace App\Enums;

enum SourceMeteo: string
{
    case OpenWeather = 'OpenWeather';
    case StationLocale = 'StationLocale';
}