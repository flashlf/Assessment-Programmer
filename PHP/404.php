<?php
    require_once "config/config.php";
?>
<!DOCTYPE html>

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
<style>
    body {
        background-color: white !important;
    }
    
    
    .page_404{ padding:40px 0; background:#fff; font-family: 'Arvo', serif;
    }
    
    .page_404  img{ width:100%;}
    
    .four_zero_four_bg{
        
        background-image: url(https://cdn.dribbble.com/users/285475/screenshots/2083086/dribbble_1.gif);
        height: 400px;
        background-position: center;
    }
    
    
    .four_zero_four_bg h1{
        font-size:80px;
    }
    
    .four_zero_four_bg h3{
        font-size:80px;
    }
    
    .link_404{
        color: #fff!important;
        padding: 10px 20px;
        background: #39ac31;
        margin: 20px 0;
        display: inline-block;}
    .contant_box_404{ margin-top:-50px;}
    p {
        color: dimgray !important;
        font-family: Helvetica;
    }
  </style>
<body>
    <section class="page_404 mt-5">
        <div class="container">
            <div class="row mt-5">
                <div class="col-sm-12 mt-5">
                    <div class="col-sm-12 text-center">
                        <div class="four_zero_four_bg">
                            <h1 class="text-center ">404</h1>
                        </div>
                    
                        <div class="contant_box_404">
                            <h3 class="h2">
                            Look like you're lost
                            </h3>
                            
                            <p>the page you are looking for not avaible!</p>
                            
                            <?php
                                if(isset($_SERVER['HTTP_REFERER'])) {
                            ?>
                            <a href="<?= $_SERVER['HTTP_REFERER'] ?>" class="link_404">Back to Previous Page</a> &nbsp;
                            
                            <?php
                                }
                            ?>
                            <a href="<?= URLROOT ?>" class="link_404">Go to Home</a>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>