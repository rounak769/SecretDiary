<?php

    session_start();

    $error = "";    

    if (array_key_exists("logout", $_GET)) {
        
        unset($_SESSION);
        setcookie("id", "", time() - 60*60);
        $_COOKIE["id"] = "";  
        
    } else if ((array_key_exists("id", $_SESSION) AND $_SESSION['id']) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE['id'])) {
        
        header("Location: session.php");
        
    }

    if (array_key_exists("submit", $_POST)) {
        
        $link = mysqli_connect("localhost", "root", "", "users");
        
        if (mysqli_connect_error()) {
            
            die ("Database Connection Error");
            
        }
        
        
        
        if (!$_POST['email']) {
            
            $error .= "An email address is required<br>";
            
        } 
        
        if (!$_POST['password']) {
            
            $error .= "A password is required<br>";
            
        } 
        
        if ($error != "") {
            
            $error = "<p>There were error(s) in your form:</p>".$error;
            
        } else {
            
            if ($_POST['signUp'] == '1') {
            
                $query = "SELECT id FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";

                $result = mysqli_query($link, $query);

                if (mysqli_num_rows($result) > 0) {

                    $error = "That email address is taken.";

                } else {

                    $query = "INSERT INTO `users` (`email`, `password`) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['password'])."')";

                    if (!mysqli_query($link, $query)) {

                        $error = "<p>Could not sign you up - please try again later.</p>";

                    } else {

                        $query = "UPDATE `users` SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = ".mysqli_insert_id($link)." LIMIT 1";

                        mysqli_query($link, $query);

                        $_SESSION['id'] = mysqli_insert_id($link);

                        if ($_POST['stayLoggedIn'] == '1') {

                            setcookie("id", mysqli_insert_id($link), time() + 60*60*24*365);

                        } 

                        header("Location: session.php");

                    }

                } 
                
            } else {
                    
                    $query = "SELECT * FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
                
                    $result = mysqli_query($link, $query);
                
                    $row = mysqli_fetch_array($result);
                
                    if (isset($row)) {
                        
                        $hashedPassword = md5(md5($row['id']).$_POST['password']);
                        
                        if ($hashedPassword == $row['password']) {
                            
                            $_SESSION['id'] = $row['id'];
                            
                            if ($_POST['stayLoggedIn'] == '1') {

                                setcookie("id", $row['id'], time() + 60*60*24*365);

                            } 

                            header("Location: session.php");
                                
                        } else {
                            
                            $error = "That email/password combination could not be found.";
                            
                        }
                        
                    } else {
                        
                        $error = "That email/password combination could not be found.";
                        
                    }
                    
                }
            
        }
        
        
    }


?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <title>Secret Diary</title>
      <style type="text/css">
          body {
               background: url(background.jpg) no-repeat center center fixed; 
              -webkit-background-size: cover;
              -moz-background-size: cover;
              -o-background-size: cover;
               background-size: cover;
                  }
          .container {
              text-align: center;
              width: 450px;
              color: white;
              margin-top: 150px;
          }
          h1 {
              font-weight: bold;
          }
          .formtoggle1, .formtoggle2 {
              color: blue;
              font-weight: bold;
              cursor: pointer;
          }
          .login {
              display: none;
          }
      </style>
  </head>
  <body>
      <div class="container">
        <h1>Secret Diary</h1>
          <h5>Store your thoughts permanently and securely.</h5>
          
        <div id="error"><?php
            if($error!="")
            echo '<div class="alert alert-danger" role="alert">'.$error.'</div>'; ?></div>

        <form method="post" class="signup">
            <p>Interested? Sign up now!</p>
            <div class="form-group">

            <input type="email" name="email" placeholder="Your Email" class="form-control" class="form-control">
            </div>
            <div class="form-group">

            <input type="password" name="password" placeholder="Password" class="form-control">
                </div>
            <div class="form-group form-check">

            <input type="checkbox" name="stayLoggedIn" value=1><span>Stay Logged In</span>
            </div>

            <input type="hidden" name="signUp" value="1">

            <input type="submit" name="submit" value="Sign Up!" class="btn btn-success"><br><br>
            <a class="formtoggle1">Log In</a><br>
        </form>
                  
        <form method="post" class="login">
            <p>Login using your username and password.</p>
            <div class="form-group">

            <input type="email" name="email" placeholder="Your Email" class="form-control">
            </div>
            <div class="form-group">
            <input type="password" name="password" placeholder="Password" class="form-control">
                </div>
            <div class="form-group form-check">
                <input type="checkbox" name="stayLoggedIn" value=1><span>Stay Logged In</span>
            </div>
            <input type="hidden" name="signUp" value="0">

            <input type="submit" name="submit" value="Log In!" class="btn btn-success"><br><br>
            <a class="formtoggle2">Sign Up</a><br>
        </form>
                
          </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
      <script type="text/javascript">
       /*   $(document).ready(function() {
              $('.login').hide();
          });  */
      $('.formtoggle1').click(function() {
          $('.signup').hide();
          $('.login').show();
      });
      $('.formtoggle2').click(function() {
          $('.login').hide();
          $('.signup').show();
      });
      </script>
  </body>
</html>

