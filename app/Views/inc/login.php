
<?php if (isset($error)) : ?>
  <p><?= $error ?></p>
<?php endif; ?>

<!--  -->


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        .main {
            padding: 5rem 10rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-form {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 80px;
            border: 2px solid #dfdbda;
            border-radius: 5px;
        }

        .button {
            display: flex;
            justify-content: center;
        }

        .button button {
            width: 100%;
        }
        .wrong-password{
            color: red;
        }
    </style>
</head>

<body>
    <div class="main">
        <div class="login-form">
            <h1 style="margin-bottom: 30px;">Log in</h1>
            <form action="/login" method="post">

                <div data-mdb-input-init class="form-outline mb-4">
                    <label class="form-label" for="form2Example1"> Username</label>
                    <input type="text" id="form2Example1" class="form-control" name="username" />
                    <div class="wrong-password">
                        <P><?php echo session()->getFlashData("usernameError")?></P>
                    </div>
                </div>

                <div data-mdb-input-init class="form-outline mb-4">
                    <label class="form-label" for="form2Example2">Password</label>
                    <input type="password" id="form2Example2" class="form-control" name="password" />
                    <div class="wrong-password">
                        <P><?php echo session()->getFlashData("passwordError")?></P>
                    </div>
                </div>



                <div class="button">
                    <button  type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4" >Sign in </button>
                </div>  
                <div class="text-center">
        <p>Not a member? <a href="/register">register</a></p>
      </div>
            </form>
        </div>
    </div>  
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</html>