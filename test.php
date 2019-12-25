<?php
$class= 'small-posts';
function get_field($arg) {
 return $arg;
}

get_field($class);

function get_my_class($class){
    $my_class = "";
    if (get_field($class) == 'big-post') {
        $my_class = 'big-post';
    } else if (get_field($class) == 'small-post') {
        $my_class = 'small-post';
    }else{
        $my_class = 'field not valid';
    }
    echo $my_class;
}

get_my_class($class);