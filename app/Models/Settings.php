<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    /**
     * Model extensions
     *
     * @var array
     */
    public $implement = ['System.Behaviors.SettingsModel'];

    private $enabledModulesOptions = [true, false];

    /**
     * Settings code
     *
     * @var string
     */
    //public $settingsCode = 'ulogic_analyctics_settings';
    public $table = 'ulogic_analyctics_settings';

    /**
     * Settings form
     *
     * @var string
     */
    public $settingsFields = 'fields.yaml';

    /**
     * Initial plugin settings
     *
     * @return void
     */
    public function initSettingsData()
    {
        $this->registrationModeratorMail = '';
        $this->rewardForReport = 250;
    }

    public function getShareModuleOptions()
    {
        return $this->enabledModulesOptions;
    }
}
