<?php

class Invitation extends EMongoDocument{
    function collectionName(){
        return 'invitations';
    }

    public static function model($className=__CLASS__){
        return parent::model($className);
    }
}