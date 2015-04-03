<?php

class Friends
{
    public static function get($userId){
        $response = new Response();
        $friends = User::model()->findAll(array('friends' => new MongoId($userId)));
        $response->code = 200;
        $response->message = $friends;
        return $response;
    }

    public static function getSubFriends($userId){
        $response = new Response();
        $friends = User::model()->findAll(array('friends' => new MongoId($userId)), array('friends' => 1));

        $subIds = array();
        foreach($friends as $friend){
            $subIds = array_merge($subIds, $friend->friends);
        }

        $subFriends = array();
        foreach($subIds as $subId){
            if((string)$subId->{'$id'} == $userId) continue;
            $subFriends[] = User::model()->findBy_id($subId);
        }

        $response->code = 200;
        $response->message = $subFriends;
        return $response;
    }

    public static function add($userId, $friendId){
        if($userId && $friendId) {
            $user = User::model()->findBy_id($userId);
            if($user) {
                if (isset($user->friends) && is_array($user->friends)) {
                    $user->friends = array_merge($user->friends, array(new MongoId($friendId)));
                } else {
                    $user->friends = array(new MongoId($friendId));
                }
                $user->save();
            }
        }
    }

} 