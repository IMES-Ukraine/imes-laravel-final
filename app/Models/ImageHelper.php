<?php
namespace App\Models;


use App\Models\User;

class ImageHelper
{
    private $user;
    public function __construct( User $apiUser = null){
       $this->user = $apiUser;
    }

    public function uploadImage( $type, $file){
        $result = false;

        switch ( $type) {
            case 'avatar':
                $basic_information = $this->user->basic_information;
                if ( !is_array( $basic_information)) $basic_information =  [];
                $basic_information[$type] = $file;
                $this->user->basic_information = $basic_information;
                $result = $this->user->save();
                break;
            case 'education_document':
                $specialized_information = $this->user->specialized_information;
                if ( !is_array( $specialized_information)) $specialized_information = [];
                $specialized_information[$type] = $file;
                $this->user->specialized_information = $specialized_information;
                $result = $this->user->save();
                break;
            case 'passport':
                $specialized_information = $this->user->specialized_information;
                if ( !is_array( $specialized_information)) $specialized_information = [];
                $specialized_information[$type] = $file;
                $this->user->specialized_information = $specialized_information;
                $result = $this->user->save();
                break;
            case 'mic_id':
                $specialized_information = $this->user->specialized_information;
                if ( !is_array( $specialized_information)) $specialized_information = [];
                $specialized_information[$type] = $file;
                $this->user->specialized_information = $specialized_information;
                $result = $this->user->save();
                break;
            default:
                $result = $file;
                break;
        }

        return $result;
    }
}
