<?php

try {

    spl_autoload_register( function($className) {
        require_once $className . '.php';
    });

    $restapi = new Model\Restapi(); // Full Qualified Namespace
    $validate = new Model\Utility();

    $restapi->validateMethod($restapi->method, 'POST');

    $requiredKey = ['todo_id', 'user_id', 'title', 'description', 'background'];


    switch ($restapi->data['code']) {
        case '0' : // Todo
            $validate->requiredInput($requiredKey, $restapi->data['data']);
            if ($validate->passed === false) {
                $restapi->code = 400;
                $restapi->info = $validate->message;
                $restapi->data = null;
            } else {
                $data = new \stdClass();
                $data->todo_id = $restapi->data['data']['todo_id'];
                $data->user_id = $restapi->data['data']['user_id'];
                $data->title = $restapi->data['data']['title'];
                $data->status = $restapi->data['data']['status'] ?? 0;
                $data->description = $restapi->data['data']['description'];
                $data->background = $restapi->data['data']['background'] ?? null;
                $data->image_attachment = $restapi->data['data']['image_attachment'] ?? null;
                $todo = new Model\Todo($data);
                if ($todo->update()) {
                    $restapi->info = "OK";
                    $restapi->code = 200;
                    $restapi->data = $todo->pull();
                } else {
                    $restapi->info = "No Data Found";
                    $restapi->code = 204;
                    $restapi->data = null;
                }

            }

            break;
        case '1' : // Task
            $requiredKey = ['todo_id', 'task_id', 'status', 'description'];
            $validate->requiredInput($requiredKey, $restapi->data['data']);
            if ($validate->passed === false) {
                $restapi->code = 400;
                $restapi->info = $validate->message;
                $restapi->data = null;
            } else {

                $data = new \stdClass();
                $data->task_id = $restapi->data['data']['task_id'];
                $data->todo_id = $restapi->data['data']['todo_id'];
                $data->status = $restapi->data['data']['status'];
                $data->description = $restapi->data['data']['description'];

                $task = new Model\Task($data);
                if ($task->update()) {
                    $restapi->info = "OK";
                    $restapi->code = 200;
                    $restapi->data = $task->pull();
                } else {
                    $restapi->info = "No Data Found";
                    $restapi->code = 204;
                    $restapi->data = null;
                }
            }
            break;
        default :

            $restapi->code = 400;
            $restapi->info = "Code value out of range";
            break;
    }

} catch (\Exception $ex) {
    $restapi->code = 500;
    $restapi->info = $ex->getMessage();
}

finally {
    $restapi->getResponse();
}