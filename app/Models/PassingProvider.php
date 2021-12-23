<?php

namespace App\Models;


class PassingProvider
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }


    public function getIds($entity)
    {
        $result = [];
        if (!is_string($entity)) {
            $entity = get_class($entity);
        }
        $data = Passing::where('entity_type', $entity)
            ->where('user_id', $this->user->id)
            ->where('status', '1');
        if ($response = $data->pluck('entity_id')->toArray()/*->get()->lists('entity_id')*/) {
            $result = $response;
        }
        return $result;
    }

    public function setId($entity, $status = Passing::PASSING_NOT_ACTIVE, $userVariants = [])
    {
        return Passing::updateOrCreate([
            'entity_type' => get_class($entity),
            'entity_id' => $entity->id,
            'user_id' => $this->user->id
        ], [
            'status' => $status,
            'answer' => $userVariants
        ]);

    }




}
