<?php

class FriendController extends Controller
{
    /**
     * User's friends
     * API version: api1
     * url: /api1/friend?user_id=551c207e4ba4c999682a440e
     */
    public function actionIndex(){
        $response = new Response();
        if(Yii::app()->getRequest()->requestType == 'GET'){
            //GET - get friends list
            $userId = Yii::app()->getRequest()->getParam('user_id', null);
            $response = Friends::get($userId);
        }
        $this->sendResponse($response->code, $response->message);
    }

    /**
     * User's friends of friends
     * API version: api1
     * url: /api1/friend/_subfriends?user_id=551c207e4ba4c999682a440e
     */
    public function actionSubFriends(){
        $response = new Response();
        if(Yii::app()->getRequest()->requestType == 'GET'){
            //GET - get friends of friends list
            $userId = Yii::app()->getRequest()->getParam('user_id', null);
            $response = Friends::getSubFriends($userId);
        }
        $this->sendResponse($response->code, $response->message);
    }
} 