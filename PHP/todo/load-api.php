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
        case '0' :
            $validate->requiredInput($requiredKey, $restapi->data['data']);
            if ($validate->passed === false) {
                $restapi->code = 400;
                $restapi->info = $validate->message;
            } else {
                $data = new \stdClass();
                $data->user_id = $restapi->data['data']['user_id'];
                $data->description = $restapi->data['data']['description'];
                $data->title = $restapi->data['data']['title'];
                if (isset($restapi->data['data']['image_attachment'])
                 && !empty($restapi->data['data']['image_attachment'])) {
                    $validate->getDataBase64($restapi->data['data']['image_attachment']);

                    if ($validate->passed === false) {
                        $restapi->data = null;
                        throw new \OutOfBoundsException("Format extensi tidak diterima atau file corrupt");
                    } else {
                        $data->image_attachment = $validate->saveBase64ToFile();
                    }
                }
                $todo = new Model\Todo($data);
                if ($todo->save()) {
                    $restapi->code = 200;
                    $restapi->info = "Success insert new task";
                    $restapi->data = $todo->storage->getLastInsertId();
                } else {
                    $restapi->code = 400;
                    $restapi->info = "Failed insert new task";
                    $restapi->data = null;
                }
            }

            break;
        case '1' :
            $requiredKey = ["todo_id", "description"];
            $validate->requiredInput($requiredKey, $restapi->data['data']);
            if ($validate->passed === false) {
                $restapi->code = 400;
                $restapi->info = $validate->message;
            } else {
                $data = new \stdClass();
                $data->todo_id = $restapi->data['data']['todo_id'];
                $data->description = $restapi->data['data']['description'];
                $data->status = 0;

                $task = new Model\Task($data);
                if ($task->save()) {
                    $restapi->code = 200;
                    $restapi->info = "Success insert new task";
                    $restapi->data = $task->storage->getLastInsertId();
                } else {
                    $restapi->code = 400;
                    $restapi->info = "Failed insert new task";
                    $restapi->data = null;
                }
            }
            break;
        default :
            
            $validate->requiredInput($requiredKey, $restapi->data['data']);
            if ($validate->passed === false) {
                $restapi->code = 400;
                $restapi->info = $validate->message;
            } else {
                $data = new \stdClass();
                $data->user_id = $restapi->data['data']['user_id'];
                $data->description = $restapi->data['data']['description'];
                $data->title = $restapi->data['data']['title'];
                $data->todo_id = $restapi->data['data']['todo_id'];
                // if (isset($restapi->data['data']['image_attachment'])
                //  && !empty($restapi->data['data']['image_attachment'])) {
                //     $validate->getDataBase64($restapi->data['data']['image_attachment']);

                //     if ($validate->passed === false) {
                //         $restapi->data = null;
                //         throw new \OutOfBoundsException("Format extensi tidak diterima atau file corrupt");
                //     } else {
                //         $data->image_attachment = $validate->saveBase64ToFile();
                //     }
                // }
                $todo = new Model\Todo($data);
                $todo->getTasks();
                print_r(json_encode($todo->pull())); exit;
                // if ($todo->save()) {
                //     $restapi->code = 200;
                //     $restapi->info = "Success insert new task";
                //     $restapi->data = $todo->storage->getLastInsertId();
                // } else {
                //     $restapi->code = 400;
                //     $restapi->info = "Failed insert new task";
                //     $restapi->data = null;
                // }
            }

            $restapi->code = 400;
            $restapi->info = "Code value out of range";
            break;
    }

} catch (\Exception $ex) {
    $restapi->code = 500;
    $restapi->info = $ex->getMessage();
} finally {
    $restapi->getResponse();
}
