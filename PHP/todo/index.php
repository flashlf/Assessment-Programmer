<?php
    require_once "../config/config.php";

    if (isset($_COOKIE["user_info"])) {
        $user_info = $_COOKIE["user_info"];
        
        // Membagi string menjadi informasi terpisah
        list($user_id, $username, $name) = explode("|", $user_info);
    } else {
        header("Location: ".URLROOT);
    }

?>
<!DOCTYPE html>
<html lang="id">
    <head>
        <title>Todo Apps</title>
        <!-- Meta tags -->
        <meta content="Belajar HTML CSS" name="description" />
        <meta content="HTML CSS" name="keywords" />
        <!-- Icon Web -->
        <link rel="icon" href="<?= URLROOT ?>/asset/favicon/icons8-star-papercut-16.png" sizes="16x16" type="image/png">
        <link rel="icon" href="<?= URLROOT ?>/asset/favicon/icons8-star-papercut-32.png" sizes="32x32" type="image/png">
        <!-- External Dependency here -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=DM+Serif+Text">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Titillium+Web">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Playfair+Display">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="<?= URLROOT ?>/css/main.css">
        <!-- Js -->
        <script
  src="https://code.jquery.com/jquery-3.7.1.js"
  integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
  crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="container">
            <header class="blog-header py-3">
                <div class="row flex-nowrap justify-content-betwen align-items-center">
                    <div class="col-4 pt-1">Welcome <?= $name ?? 'ADUL'?></div>
                    <div class="col-4 pt-1 text-center">
                        <a href="index.html" class="blog-header-logo">Todo App</a>
                    </div>
                    <div class="col-4 d-flex justify-content-end align-items-center">
                        <a class="text-muted" href="#">
                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="mx-3" role="img" viewBox="0 0 24 24" focusable="false"><title>Search</title><circle cx="10.5" cy="10.5" r="7.5"></circle><path d="M21 21l-5.2-5.2"></path></svg>
                        </a>
                        <form action="<?= URLROOT ?>/todo/user" method="POST">
                            <input type="hidden" name="code" value="logout" class="form-control" />
                            <button type="submit" class="btn btn-sm btn-outline-secondary">Logout</button>
                        </form>
                    </div>
                </div>
            </header>
            <!-- Film Category -->
            <div class="nav-scroller py-2 mb-2 sticky" style="display: none;">
                <nav class="nav d-flex justify-content-between">
                  <a class="p-2" href="#">Action</a>
                  <a class="p-2" href="#">Adventure</a>
                  <a class="p-2" href="#">Comedy</a>
                  <a class="p-2" href="#">Crime &amp; Mystery</a>
                  <a class="p-2" href="#">Fantasy</a>
                  <a class="p-2" href="#">Historical</a>
                  <a class="p-2" href="#">Horror</a>
                  <a class="p-2 text-muted" href="#">Romance</a>
                  <a class="p-2" href="#">Science Fiction</a>
                  <a class="p-2 text-muted" href="#">Thriller</a>
                  <a class="p-2" href="list.html">List Film</a>
                </nav>
            </div>

            <div class="content">
                <div class="row">
                    <div class="col">
                        <h2 class="mt-5"><?= $name ?? 'ADUL'?> Todos &nbsp;&nbsp;
                    
                            <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#addTodo">
                                + Todo
                            </button>
                        </h2>
                    </div>
                </div>
                

                    <div class="todolist" id="todoList">

                        <div class="todos border rounded mr-4 post">
                          <div class="p-4">
                            <strong class="d-inline-block mb-2 text-primary">World</strong>
                            <h3 class="mb-0">Featured post</h3>
                            <div class="mb-1 text-muted">Nov 12</div>
                            <p class="card-text mb-auto">This is a wider card with supporting text below as a natural lead-in to additional content.</p>
                            <a href="#">Continue reading</a>
                          </div>
                        </div>
                    
                        <div class="todos border rounded mr-4 post">
                          <div class="p-4">
                            <strong class="d-inline-block mb-2 text-success">World</strong>&nbsp;&nbsp;
                            <button style="float: right;" type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteTodo" onclick="deleteTodo()">
                                Delete Todo
                            </button>
                            <button style="float: right;" type="button" class="btn btn-sm btn-success mx-2" data-toggle="modal" data-target="#addTask">
                                + Task
                            </button>
                            <h3 class="mb-0">Featured post</h3>
                            <div class="mb-1 text-muted">Nov 12</div>
                            <p class="card-text mb-auto">This is a wider card with supporting text below as a natural lead-in to additional content.</p>

                            <div class="todo-task">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Default checkbox
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Default checkbox
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Default checkbox
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Default checkbox
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Default checkbox
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Default checkbox
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Default checkbox
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Default checkbox
                                    </label>
                                </div>
                            </div>

                            <a href="#">Continue reading</a>
                          </div>
                        </div>
                    </div>

            </div>
        </div>

        <!-- Modal Add Todo -->
        <div class="modal fade" id="addTodo" tabindex="-1" role="dialog" aria-labelledby="addTodoTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTodoTitle">Add New Todo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>

                    <div class="modal-body">
                        <div class="form-outline mb-4">
                            <input type="hidden" id="userId" name="userId" value="<?= $user_id ?>" class="form-control" />
                            <input type="text" id="title" name="title" class="form-control" />
                            <label class="form-label" for="title">Title</label>
                        </div>

                        <div class="form-outline mb-4">
                            <textarea id="description" name="description" class="form-control"> </textarea>
                            <label class="form-label" for="description">Description</label>
                        </div>

                        <div class="form-outline mb-4">
                            <input type="color" id="background" name="background" class="form-control" />
                            <label class="form-label" for="description">Background Color</label>
                        </div>

                        <div class="form-outline mb-4">
                            <input type="file" id="file" name="file" class="form-control" />
                            <label class="form-label" for="description">Attachment</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" onclick="addTodo()" data-dismiss="modal">Add Todo</button>
                    </div>
                    
                </form>
                </div>
            </div>
        </div>
        <!-- End Modal Add Todo -->

        <footer>
            <div class="container mb-5">
                <div class="row">
                    <div class="col-md-4 col-sm-4 mb-3">
                        <h4><img class="responsive-image" src="" style="max-width:200px;"></img>Logo here</h4>
                        <p><b>Adul Todos App</b> adalah perusahaan yang bergerak di bidang Teknologi informasi.
                         Kami telah menyediakan solusi dari hulu sampai hilir yang dapat membantu perusahaan dalam menghadapi tantangan bisnis yang berkembang begitu cepat & dinamis.
                         Saat ini lebih dari 100 proyek IT skala nasional maupun regional yang telah kami kerjakan. Salah satu klien kami dari luar negeri yaitu Telkomcel Timor Leste & Telin Malaysia.</p>
                    </div>
                    <div class="col-md-4 col-sm-4 mb-3">
                        <h4>Sitemap</h4>
                    </div>
                    <div class="col-md-4 col-sm-8 mb-3">
                        <h4>Contact Us</h4>
    
                        <div id="map"></div>
    
                        <p>Komp. Buah Batu Regency A2 No.9 - 10 Kel. Kujangsari / Cijawura Kec. Bandung Kidul, Bandung, Jawa Barat, Indonesia.</p>
                        <ul>
                            <li class="phone">+62 812 3456 7890</li>
                            <li class="email">support@cineasduasatu.media.com</li>
                        </ul>
                    </div>
                </div>
    
            </div>
            <div class="copyright mt-7">
                <div class="container">
                    <div class="row">
                        <p>Copyright 2023 Cineas Duasatu &copy;. All rights reserved. <i>Crafted with awesomeness by Adul</i></p>
                    </div>
                </div>
            </div>    
        </footer>
    </body>

    <script>
        // Fungsi untuk mengonversi nilai warna RGB ke format HEX
        function rgbToHex(rgb) {
            // Membuang karakter 'rgb(' dan ')'
            var rgbValues = rgb.substring(4, rgb.length - 1).split(',');

            // Mengonversi nilai RGB ke format HEX
            var hexColor = '#' + componentToHex(parseInt(rgbValues[0])) + componentToHex(parseInt(rgbValues[1])) + componentToHex(parseInt(rgbValues[2]));

            return hexColor;
        }

        // Fungsi untuk mengonversi komponen warna RGB ke format HEX
        function componentToHex(c) {
            var hex = c.toString(16);
            return hex.length == 1 ? '0' + hex : hex;
        }

        function addTodo() {

            var fileInput = $('#file')[0];
            var file = fileInput.files[0];
            var base64Data = "";
            if (file) {
                var reader = new FileReader();

                // Membaca file sebagai URL data
                reader.readAsDataURL(file);

                // Menangani peristiwa pembacaan selesai
                reader.onloadend = function() {
                    // Mendapatkan hasil dalam bentuk base64
                    base64Data = reader.result;
                };
            }

            var vObjectData = {
                code : 0,
                data : {
                    description : $("#description").val(),
                    background : rgbToHex($("#background").val()),
                    title : $("#title").val(),
                    image_attachment: base64Data ?? "",
                    user_id : $("#userId").val()
                }
            };

            $.ajax({
                url : "<?= URLROOT ?>/todo/create-api",
                type: "POST",
                dataType : "json",
                contentType: 'application/json',
                data: JSON.stringify(vObjectData),
                beforeSend: function (xhr) {
                    console.log(vObjectData);
                    xhr.setRequestHeader ("Authorization", "Basic " + btoa('aduls' + ":" + 'e10adc3949ba59abbe56e057f20f883e'));
                },
                success: function(result) {
                    alert(result.info);
                    location.reload();
                },
                error: function(xhr) {
                    alert("Terjadi Kesalahan");
                },
                complete: function(xhr, status) {
                }

            });
        };

        function loadTodo() {
            var settings = {
                "url": "https://adul.todo.app/todo/load-api?param={%22code%22:0,%22data%22:{%22user_id%22:%22<?= $user_id ?>%22}}",
                "method": "GET",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json",
                    "Authorization": "Basic YWR1bHM6ZTEwYWRjMzk0OWJhNTlhYmJlNTZlMDU3ZjIwZjg4M2U="
                },
            };

            $.ajax(settings).done(function (response) {
                if (response.code == '200') {
                    $.each(response.data, (index, item) => {
                        
                        let todoTemplate = `
                            <div class="todos border rounded mr-4 post">
                            <div class="p-4">
                                <strong class="d-inline-block mb-2 text-success">Todo</strong>
                                &nbsp;&nbsp;
                                <button style="float: right;" type="button" class="btn btn-sm btn-danger"
                                 onclick="deleteTodo(${item.todo_id}, 0)">
                                    <i class="fa fa-trash-alt"></i>
                                </button>
                                <button style="float: right;" type="button" class="btn btn-sm btn-success mx-2"
                                 data-toggle="modal" data-target="#addTask">
                                    + Task
                                </button>
                                <h3 class="mb-0">${item.title}</h3>
                                <div class="mb-1 text-muted">Nov 12</div>
                                <p class="card-text mb-auto">${item.description}</p>
                                
                                <div class="todo-task" id="taskList">
                                </div>

                                <a href="#">Continue reading</a>
                            </div>
                            </div>
                        `;

                        $("#todoList").append(todoTemplate);
                        
                        $.each(item.taskList, (taskIdx, task) => {
                            let taskTemplate = `
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" ${task.status == 1 ? 'checked' : ''}
                                 value="" id="task_${task.task_id}-todo_${task.todo_id}" data-taskid="${task.task_id}"
                                 data-todoid="${task.todo_id}" data-description="${task.description}">
                                <label class="form-check-label" for="task_${task.task_id}-todo_${task.todo_id}">
                                    ${task.description}
                                    </label>

                                <span class="text-muted delete-task"
                                    onClick="deleteTask(${task.task_id}, ${task.todo_id})" style="float:right;">
                                    <small><i class="fa fa-trash-alt"></i></small>
                                </span>

                                <span class="text-muted edit-task mx-2"
                                 onClick="editTask(${task.task_id}, ${task.todo_id})" style="float:right;">
                                    <small><i class="fa fa-pen-alt"></i></small>
                                </span>
                            </div>
                            `;

                            $("#taskList").append(taskTemplate);
                        });
                        
                    });

                    $("#taskList").on("change", "input[type=checkbox]", function() {

                        let vStatus = $(this).is(":checked");
                        console.log(typeof vStatus);
                        vAjaxData = {
                            code : 1,
                            data : {
                                task_id : $(this).data("taskid"),
                                todo_id : $(this).data("todoid"),
                                status : vStatus,
                                description : $(this).data("description")
                            }
                        };

                        $.ajax({
                            url : "<?= URLROOT ?>/todo/update-api",
                            type: "POST",
                            dataType : "json",
                            contentType: 'application/json',
                            data: JSON.stringify(vAjaxData),
                            beforeSend: function (xhr) {
                                console.log(vAjaxData);
                                xhr.setRequestHeader ("Authorization", "Basic " + btoa('aduls' + ":" + 'e10adc3949ba59abbe56e057f20f883e'));
                            },
                            success: function(result) {
                                alert(result.info);
                                location.reload();
                            },
                            error: function(xhr) {
                                alert("Terjadi Kesalahan");
                            },
                            complete: function(xhr, status) {
                            }
                        });

                    });
                }
            });

        }

        function editTask(vTask, vTodo) {
            var vDesc = prompt("Deskripsi task baru :");

            vAjaxData = {
                code : 1,
                data : {
                    task_id : vTask,
                    todo_id : vTodo,
                    status : "0",
                    description : vDesc
                }
            };

            $.ajax({
                url : "<?= URLROOT ?>/todo/update-api",
                type: "POST",
                dataType : "json",
                contentType: 'application/json',
                data: JSON.stringify(vAjaxData),
                beforeSend: function (xhr) {
                    console.log(vAjaxData);
                    xhr.setRequestHeader ("Authorization", "Basic " + btoa('aduls' + ":" + 'e10adc3949ba59abbe56e057f20f883e'));
                },
                success: function(result) {
                    alert(result.info);
                    location.reload();
                },
                error: function(xhr) {
                    alert("Terjadi Kesalahan");
                },
                complete: function(xhr, status) {
                }
            });
        }

        function deleteTodo(id, mode) {
            let key = `${mode == 0 ? 'todo_id' : 'user_id'}`
            vAjaxData = {
                code : 0,
                data : {
                }
            };
            vAjaxData['data'][key] = id;

            $.ajax({
                url : "<?= URLROOT ?>/todo/delete-api",
                type: "POST",
                dataType : "json",
                contentType: 'application/json',
                data: JSON.stringify(vAjaxData),
                beforeSend: function (xhr) {
                    console.log(vAjaxData);
                    xhr.setRequestHeader ("Authorization", "Basic " + btoa('aduls' + ":" + 'e10adc3949ba59abbe56e057f20f883e'));
                },
                success: function(result) {
                    alert(result.info);
                    location.reload();
                },
                error: function(xhr) {
                    alert("Terjadi Kesalahan");
                },
                complete: function(xhr, status) {
                }
            });
        }

        function deleteTask(vTask, vTodo) {

            vAjaxData = {
                code : 1,
                data : {
                    task_id : vTask
                }
            };

            $.ajax({
                url : "<?= URLROOT ?>/todo/delete-api",
                type: "POST",
                dataType : "json",
                contentType: 'application/json',
                data: JSON.stringify(vAjaxData),
                beforeSend: function (xhr) {
                    console.log(vAjaxData);
                    xhr.setRequestHeader ("Authorization", "Basic " + btoa('aduls' + ":" + 'e10adc3949ba59abbe56e057f20f883e'));
                },
                success: function(result) {
                    alert(result.info);
                    location.reload();
                },
                error: function(xhr) {
                    alert("Terjadi Kesalahan");
                },
                complete: function(xhr, status) {
                }
            });
        }

        $(document).ready(function() {

            loadTodo();

        });
    </script>
</html>