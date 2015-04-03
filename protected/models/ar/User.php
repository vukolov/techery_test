<?php

class User extends EMongoDocument{
    function collectionName(){
        return 'users';
    }

    public static function model($className=__CLASS__){
        return parent::model($className);
    }
}