<?PHP
    require 'includes/master.inc.php';

    if($Auth->loggedIn()) redirect('/');

    if(!empty($_POST['username']))
    {
        if($Auth->login($_POST['username'], $_POST['password']))
        {
            if(isset($_REQUEST['r']) && strlen($_REQUEST['r']) > 0)
                redirect($_REQUEST['r']);
            else
                redirect('/');
        }
        else
            $Error->add('username', "We're sorry, you have entered an incorrect username and password. Please try again.");
    }

    // Clean the submitted username before redisplaying it.
    $username = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Вхід</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }

    </style>
</head>
<body>
    <div class="container">
        <?PHP echo $Error; ?>
      <form action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-signin">
        <h2 class="form-signin-heading">Назвіться</h2>
        <input type="text" class="input-block-level" placeholder="Логін" name="username" value="<?PHP echo $username;?>">
        <input type="password" class="input-block-level" placeholder="Пароль" name="password">
        <button class="btn btn-large btn-primary" name="btnlogin" type="submit">Увійти</button>
        <input type="hidden" name="r" value="<?PHP echo htmlspecialchars(@$_REQUEST['r']); ?>" id="r">
      </form>

    </div> <!-- /container -->
</body>
</html>