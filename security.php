<?php
function anti($sql){
    $sql = preg_replace("/(from|select|insert|delete|where|drop table|show tables|\*|--|\\\\)/", "" ,$sql);
    $sql = trim($sql);
    $sql = addslashes($sql); 
    return $sql;
}

