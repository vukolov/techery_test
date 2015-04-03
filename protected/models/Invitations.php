<?php

class Invitations
{
    const STATUS_WAIT = 'wait';
    const STATUS_ACCEPT = 'accept';
    const STATUS_DECLINE = 'decline';

    public static function create($fromUserId, $toUserId){
        $response = new Response();
        if($fromUserId && $toUserId) {
            $userFrom = User::model()->findBy_id($fromUserId);
            $userTo = User::model()->findBy_id($toUserId);
            if($userFrom && $userTo) {
                $invitation = Invitation::model()->findOne(
                    array(
                        'from_user_id' => new MongoId($fromUserId),
                        'to_user_id' => new MongoId($toUserId),
                    )
                );
                if(!$invitation) {
                    $invitation = new Invitation();
                    $invitation->from_user_id = new MongoId($fromUserId);
                    $invitation->to_user_id = new MongoId($toUserId);
                    $invitation->status = self::STATUS_WAIT;
                    if ($invitation->save()) {
                        $response->code = 201;
                        $response->message = array('id' => $invitation->_id);
                    } else {
                        $response->code = 500;
                        $response->message = array('info' => 'can\'t save');
                    }
                } else {
                    $response->code = 409;
                    $response->message = array('info' => 'record is already exists');
                }
            } else {
                $response->message = array('info' => 'users is not exists');
            }
        } else {
            $response->message = array('info' => 'wrong params');
        }
        return $response;
    }

    public static function get($userId){
        $response = new Response();
        $invitations = Invitation::model()->findAll(array('to_user_id' => new MongoId($userId)));
        $response->code = 200;
        $response->message = $invitations;
        return $response;
    }

    public static function handle($invitationId, $newStatus){
        $response = new Response();
        if($invitationId && ($newStatus == self::STATUS_ACCEPT || $newStatus == self::STATUS_DECLINE)){
            $invitation = Invitation::model()->findBy_id($invitationId);
            if($invitation){
                if($invitation->status == self::STATUS_WAIT){
                    $invitation->status = $newStatus;
                    if ($invitation->save()) {
                        if($newStatus == self::STATUS_ACCEPT) {
                            Friends::add($invitation->from_user_id, $invitation->to_user_id);
                            Friends::add($invitation->to_user_id, $invitation->from_user_id);
                        }
                        $response->code = 202;
                    } else {
                        $response->code = 500;
                        $response->message = array('info' => 'can\'t save');
                    }
                } else {
                    $response->code = 403;
                    $response->message = array('info' => 'invitation is already handled');
                }
            } else {
                $response->code = 403;
                $response->message = array('info' => 'invitation not found');
            }
        }
        return $response;
    }
} 