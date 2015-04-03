<?php

class InvitationController extends Controller
{
    /**
     * Invitations
     * API version: api1
     */
    public function actionIndex(){
        $response = new Response();
        if(Yii::app()->getRequest()->requestType == 'POST'){
            //POST - create an invitation
            //url: /api1/invitation
            $fromUserId = Yii::app()->getRequest()->getPost('from_user_id', null);
            $toUserId = Yii::app()->getRequest()->getPost('to_user_id', null);
            $response = Invitations::create($fromUserId, $toUserId);
        } elseif (Yii::app()->getRequest()->requestType == 'GET'){
            //GET - show all invitations
            //url: /api1/invitation?user_id=551c207e4ba4c999682a440e
            $userId = Yii::app()->getRequest()->getParam('user_id', null);
            $response = Invitations::get($userId);
        } elseif (Yii::app()->getRequest()->requestType == 'PUT'){
            //PUT - accept an invitation or decline an invitation
            //url: /api1/invitations/1
            $invitationId = Yii::app()->getRequest()->getParam('id', null);
            $action = Yii::app()->getRequest()->getPut('action', null);
            $response = Invitations::handle($invitationId, $action);
        }
        $this->sendResponse($response->code, $response->message);
    }
} 