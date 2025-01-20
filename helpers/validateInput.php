<?php

function validateInput ($value){
    $value = trim($value);
    $value = htmlspecialchars($value);
    return $value;
}