<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\User;

class UserTransformer extends TransformerAbstract {
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'roles'
    ];

    public function transform( $user ) {

        return [
            'id'            => (int)$user->id,
            'name'          => $user->name,
            'email'         => $user->email,
            'firstname'     => $user->firstname,
            'lastname'      => $user->lastname,
            'avatar'        => $user->avatar,           
            'status'        => $user->status,       
            'permissions'   => $user->permissions,       
            'last_loged_in' => $user->last_loged_in,       
            'ip'            => $user->ip,       
            'created_at'    => $user->created_at->toDateTimeString(),
        ];

        
    }


    public function includeRoles( $user ) {
        $roles = $user->roles;

        return $this->collection( $roles, new RoleTransformer );
    }
}