<?php
namespace App\Traits;


//use ULogic\Analytics\Models\Settings;
use App\Models\Settings;

trait UserSettings
{
    public function getUserSettings() {
        return [
            'shareModule' => (boolean) Settings::get('shareModule'),
            'popularModule' => (boolean) Settings::get('popularModule'),
            'recommendedModule' => (boolean) Settings::get('recommendedModule'),
        ];
    }
}
