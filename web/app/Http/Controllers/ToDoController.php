<?php

namespace App\Http\Controllers;


use DB;
use Illuminate\Http\Request;

class ToDoController extends Controller
{
    public function mainPage()
    {
        return "<!DOCTYPE html>
<head>
<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css'>
<style>.inputForm {margin-bottom: 15px;}</style>
</head>
<body>
<div class='row'>
<div class='container'><h1>Todo</h1></div>
</div>
<div id='app' class='container'><div class='alert alert-danger' role='alert'>Something is wrong</div></div>
<script src='/js/app.js'></script>
</body>
</html>";
    }

    public function listOfTodo()
    {
        return json_encode(DB::table('Todo')->select()->orderBy('id', 'desc')->get());
    }

    public function createTodo(Request $request)
    {
        DB::table('Todo')->insert(['status' => 0, 'text' => $request->get('text'), 'created_at' => date('Y-m-d H:i:s', time())]);
        return $this->listOfTodo();
    }

    public function updateTodo(Request $request)
    {
        $status = $request->get('status') == "true" ? 1 : 0;
        DB::table('Todo')->where('id', '=', $request->get('id'))->update(['status' => $status, 'updated_at' => date('Y-m-d H:i:s', time())]);
        return json_encode($request->all());
    }

}
