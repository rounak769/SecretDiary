<?php
    session_start();

    if (array_key_exists("id", $_COOKIE)) {
        
        $_SESSION['id'] = $_COOKIE['id'];
        
    }

    if (array_key_exists("id", $_SESSION)) {
        
        
    } else {
        
        header("Location: index.php");
        
    }
$link = mysqli_connect("localhost", "root", "", "users");
if (mysqli_connect_error()) {
            
            die ("Database Connection Error");
            
        }
$query = "SELECT `diary` FROM `users` WHERE id='".$_SESSION['id']."' ";
$res=mysqli_query($link,$query);
$array=mysqli_fetch_array($res);
if($array['diary']=="")
    $array="This text gets saved to the server automatically as you type..";
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
      <style>
      body {
               background: url(background.jpg) no-repeat center center fixed; 
              -webkit-background-size: cover;
              -moz-background-size: cover;
              -o-background-size: cover;
               background-size: cover;
                  }
          .container-fluid {
              display: flex;
              height: 95vh;
          }
          textarea {
              margin-top: 1rem;
              height: 100%;
              resize: none;
          }
      </style>
    </head>
  <body>
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Secret Diary</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <div class="form-inline my-2 my-lg-0 pull-xs-right">
      <a href="index.php?logout=1"><button class="btn btn-outline-success my-2 my-sm-0" type="submit">Log out</button></a> 
    </div>
  </div>
</nav>
      <div class="container-fluid">
          <textarea name="diary" class="form-control" id="diary"><?php echo $array['diary'] ?></textarea>
      </div>
            <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
      <script type="text/javascript" src="jquery-3.5.1.min.js"></script>
      <script type="text/javascript">
            var oldVal = "";
            $("#diary").on("change keyup paste", function() {
                var currentVal = $(this).val();
                if(currentVal == oldVal) {
                    return; //check to prevent multiple simultaneous triggers
                }

                oldVal = currentVal;
                //action to be performed on textarea changed
                var values=$('#diary').val();
                $.ajax({
                    url: "updateDatabase.php",
                    method: "POST",
                    data: {content: values}
                });
    }); 
      </script>
  </body>
</html>