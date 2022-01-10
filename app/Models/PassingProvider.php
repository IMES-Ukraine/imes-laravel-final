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
            ->where('status', '1')
            ->orderBy('entity_id', 'desc');
        if ($response = $data->pluck('entity_id')->toArray()) {
            $result = $response;
        }
        return $result;
    }

    public function getResults($entity)
    {
        $result = [];
        if (!is_string($entity)) {
            $entity = get_class($entity);
        }
        $data = Passing::where('entity_type', $entity)
            ->where('user_id', $this->user->id)
            ->where('result', '1')
        ->orderBy('entity_id', 'desc');
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
            'answer' => $userVariants,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

    }

    public function setResult($entity, $result = Passing::PASSING_RESULT_ACTIVE, $status = Passing::PASSING_NOT_ACTIVE)
    {
        return Passing::updateOrCreate([
            'entity_type' => get_class($entity),
            'entity_id' => $entity->id,
            'user_id' => $this->user->id
        ], [
            'result' => $result,
            'status' => $status
        ]);

    }


}
