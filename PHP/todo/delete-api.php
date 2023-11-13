<?php

try {

    spl_autoload_register( function($className) {
        require_once $className . '.php';
    });

    $restapi = new Model\Restapi(); // Full Qualified Namespace
    $validate = new Model\Utility();

    $restapi->validateMethod($restapi->method, 'POST');

    $requiredKey = [
        "description", "title", "user_id",
    ];


    switch ($restapi->data['code']) {
        case '0' : // Todo

            if (empty($restapi->data['data']['todo_id'])
             && empty($restapi->data['data']['user_id'])) {
                $restapi->code = 400;
                $restapi->info = "Attribute user_id or todo_id required.";
            }

            if (isset($restapi->data['data']['todo_id'])) {
                $data = new \stdClass();
                $data->todo_id = $restapi->data['data']['todo_id'];

                $todo = new Model\Todo($data);
                if ($todo->delete($data->todo_id)) {
                    $restapi->info = "Data Berhasil Dihapus";
                    $restapi->code = 200;
                    $restapi->data = null;
                } else {
                    $restapi->info = "Terjadi Kesalahan saat menghapus data";
                    $restapi->code = 204;
                    $restapi->data = null;
                }
            }

            if (isset($restapi->data['data']['user_id'])) {
                $data = new \stdClass();
                $data->user_id = $restapi->data['data']['user_id'];
                $todo = new Model\Todo($data);

                if ($todo->delete($data->user_id, Model\Todo::LOADBY_USER)) {
                    $restapi->info = "Data Berhasil Dihapus";
                    $restapi->code = 200;
                    $restapi->data = null;
                } else {
                    $restapi->info = "Terjadi Kesalahan saat menghapus data";
                    $restapi->code = 204;
                    $restapi->data = null;
                }
            }

            break;
        case '1' : // Task
            if (empty($restapi->data['data']['todo_id'])
             && empty($restapi->data['data']['task_id'])) {
                $restapi->code = 400;
                $restapi->info = "Attribute task_id or todo_id required.";
            }

            if (isset($restapi->data['data']['task_id'])) {
                $data = new \stdClass();
                $data->task_id = $restapi->data['data']['task_id'];

                $task = new Model\Task($data);
                if ($task->delete($data->task_id)) {
                    $restapi->info = "Data Berhasil terhapus";
                    $restapi->code = 200;
                    $restapi->data = $task->pull();
                } else {
                    $restapi->info = "No Data Found";
                    $restapi->code = 204;
                    $restapi->data = null;
                }
            }

            if (isset($restapi->data['data']['todo_id'])) {
                $data = new \stdClass();
                $data->todo_id = $restapi->data['data']['todo_id'];

                $task = new Model\Task($data);
                if ($task->delete($data->todo_id, Model\Task::LOADBY_TODO)) {
                    $restapi->info = "Data Berhasil terhapus";
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