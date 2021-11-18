<?php
namespace App\Models;


class PassingProvider
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function getIds( $entity) {

        $result = [];

        if( !is_string( $entity ))
            $entity = get_class($entity);

        $data = Passing::where('entity_type', $entity)->where('user_id', $this->user->id);//->pluck('entity_id');

        if( $response = $data->pluck('entity_id')->toArray()/*->get()->lists('entity_id')*/)
            $result = $response;

        return $result;

    }

    public function setId( $entity, $entityId, $status=0, $userVariants=[]) {
        $model = new Passing();
        $model->entity_type = get_class($entity);
        $model->entity_id = $entityId;
        $model->user_id = $this->user->id;
        $model->status = $status;
        $model->answer = $userVariants;

        return $model->save();
    }

}
