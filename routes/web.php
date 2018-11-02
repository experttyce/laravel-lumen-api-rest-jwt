<?php

    $router->post('/auth/login',[
        'uses' => 'AuthController@login',
    ]);
    $router->post('/me',[
        'uses' => 'AuthController@me'
    ]);
    $router->post('/logout', [
        'uses' => 'AuthController@logout'
    ]);
    $router->post('/refresh', [
        'uses' => 'AuthController@refresh',
    ]);

