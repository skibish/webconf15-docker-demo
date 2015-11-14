<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', 'ToDoController@mainPage');

$app->get('/list', 'ToDoController@listOfTodo');

$app->post('/list', 'ToDoController@createTodo');

$app->put('/list', 'ToDoController@updateTodo');