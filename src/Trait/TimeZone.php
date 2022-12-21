<?php
namespace App\Trait;

    trait TimeZone
    {
        /**
         * Cette fonction permet de changer le fuseau horaire
         *
         * @param string $time_zone_id
         * @return void
         */
        public function setTimeZone(string $time_zone_id) : void
        {
            date_default_timezone_set($time_zone_id);
        }
    }