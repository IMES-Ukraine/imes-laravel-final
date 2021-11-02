<?php
namespace App\Services;


use App\Models\Passing;

class PassingService
{
    /**
     * @param $entity_type
     * @param $entity_id
     * @param $status
     * @return mixed
     */
    public static function getPassingTypeStatusAllUsers($entity_type, $entity_id, $status) {
        return Passing::where(['entity_type' => $entity_type])
            ->where(['entity_id' => $entity_id])
            ->where(['status' => $status])
            ->get();
    }

    /**
     * @param $entity_type
     * @param $entity_id
     * @return mixed
     */
    public static function getPassingTypeAllUsers($entity_type, $entity_id) {
        return Passing::where(['entity_type' => $entity_type])
            ->where(['entity_id' => $entity_id])
            ->get();
    }

}
