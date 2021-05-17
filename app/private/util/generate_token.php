<?php

function generate_token(): string
{
    try {
        return bin2hex(random_bytes(32));
    } catch (Exception $e) {
    }
    //use openssl if the above fails
    return bin2hex(openssl_random_pseudo_bytes(32));
}