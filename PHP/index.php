<?php
    require_once "config/config.php";

    spl_autoload_register(function($className){
        require_once 'todo/model/' . $className . '.php';
    });

    function getUrl() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);

            return $url;
        }
    }

    if (isset($_COOKIE["user_info"])) {
        $user_info = $_COOKIE["user_info"];
        
        // Membagi string menjadi informasi terpisah
        list($user_id, $username, $name) = explode("|", $user_info);
    
        header("Location: ".URLROOT."/todo");
    } else {
        // Handling CSRF
        session_start();
        $_SESSION['token'] = md5(uniqid(mt_rand(), true));
    }

    if (!empty($_SERVER['REQUEST_URI']) && strlen($_SERVER['REQUEST_URI']) > 1) {
        $url = getUrl();
        print_r($url); die;
        if (is_dir($url[0])) {
            
            if (@file_exists("/$url[0]/$url[1]")) {
                echo "File Exists";
            } else {
                header("Location: ".URLROOT."/404");
            }
        } else {
            header("Location: ".URLROOT."/404");
        }
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
        <link rel="stylesheet" href="<?= URLROOT ?>/css/main.css">
        <!-- Js -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="container">
            <header class="blog-header py-3">
                <div class="row flex-nowrap justify-content-betwen align-items-center">
                    <div class="col-4 pt-1">Logo Here</div>
                    <div class="col-4 pt-1 text-center">
                        <a href="index.html" class="blog-header-logo">Todo App</a>
                    </div>
                    <div class="col-4 d-flex justify-content-end align-items-center">
                        <a class="text-muted" href="#">
                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="mx-3" role="img" viewBox="0 0 24 24" focusable="false"><title>Search</title><circle cx="10.5" cy="10.5" r="7.5"></circle><path d="M21 21l-5.2-5.2"></path></svg>
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#modalSignup">
                            Sign Up
                        </button>&nbsp;

                        <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#modalLogin">
                            Login
                        </button>

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

                <h2 class="mt-5">Published Todos</h2>
                <div class="row my-4">
                    <div class="col-md-6">
                        <div class="border rounded overflow-hidden flex-md-row mb-4 post">
                          <div class="p-4 d-flex flex-column">
                            <strong class="d-inline-block mb-2 text-primary">World</strong>
                            <h3 class="mb-0">Featured post</h3>
                            <div class="mb-1 text-muted">Nov 12</div>
                            <p class="card-text mb-auto">This is a wider card with supporting text below as a natural lead-in to additional content.</p>
                            <a href="#">Continue reading</a>
                          </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="border rounded overflow-hidden flex-md-row mb-4 post">
                          <div class="col p-4 d-flex flex-column position-static">
                            <strong class="d-inline-block mb-2 text-success">World</strong>
                            <h3 class="mb-0">Featured post</h3>
                            <div class="mb-1 text-muted">Nov 12</div>
                            <p class="card-text mb-auto">This is a wider card with supporting text below as a natural lead-in to additional content.</p>
                            <a href="#">Continue reading</a>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Login -->
        <div class="modal fade" id="modalLogin" tabindex="-1" role="dialog" aria-labelledby="modalLoginTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLoginTitle">Login</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="<?= URLROOT ?>/todo/user">

                    <div class="modal-body">
                    <div class="form-outline mb-4">
                        <input type="hidden" name="token" value="<?= $_SESSION['token'] ?? ''?>" class="form-control" />
                        <input type="hidden" name="code" value="login" class="form-control" />
                        <input type="text" id="loginName" name="username" class="form-control" />
                        <label class="form-label" for="loginName">Email or username</label>
                    </div>

                    <!-- Password input -->
                    <div class="form-outline mb-4">
                        <input type="password" id="loginPassword" name="password" class="form-control" />
                        <label class="form-label" for="loginPassword">Password</label>
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Login</button>
                    </div>
                    
                </form>
                </div>
            </div>
        </div>
        <!-- Modal Login End Here -->

        <!-- Modal Login -->
        <div class="modal fade" id="modalSignup" tabindex="-1" role="dialog" aria-labelledby="modalSignUpTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSignUpTitle">Sign Up Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="<?= URLROOT ?>/todo/user">

                    <div class="modal-body">
                         <!-- Name input -->
                        <div class="form-outline mb-4">
                            <input type="hidden" name="token" value="<?= $_SESSION['token'] ?? ''?>" class="form-control" />
                            <input type="hidden" name="code" value="register" class="form-control" />
                            <input type="text" id="registerName" name="name" class="form-control" />
                            <label class="form-label" for="registerName">Name</label>
                        </div>

                        <!-- Username input -->
                        <div class="form-outline mb-4">
                            <input type="text" id="registerUsername" name="username" class="form-control" />
                            <label class="form-label" for="registerUsername">Username</label>
                        </div>

                        <!-- Email input -->
                        <div class="form-outline mb-4">
                            <input type="email" id="registerEmail" name="email" class="form-control" />
                            <label class="form-label" for="registerEmail">Email</label>
                        </div>

                        <!-- Password input -->
                        <div class="form-outline mb-4">
                            <input type="password" id="registerPassword" name="password" class="form-control" />
                            <label class="form-label" for="registerPassword">Password</label>
                        </div>

                        <!-- Repeat Password input -->
                        <div class="form-outline mb-4">
                            <input type="password" id="registerRepeatPassword" name="repeat_password" class="form-control" />
                            <label class="form-label" for="registerRepeatPassword">Repeat password</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Signup</button>
                    </div>

                </form>
                </div>
            </div>
        </div>
        <!-- Modal Login End Here -->

        <footer>
            <div class="container mb-5">
                <div class="row">
                    <div class="col-md-4 col-sm-4 mb-3">
                        <h4><img class="responsive-image" src="" style="max-width:200px;" alt="logo"></img>Logo here</h4>
                        <p><b>Adul Todos Apps</b> adalah perusahaan yang bergerak di bidang Teknologi informasi.
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
        $(document).ready(function() {

        });
    </script>
</html>