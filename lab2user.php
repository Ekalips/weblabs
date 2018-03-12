<?php
  //error_reporting(0);
  $id = intval($_GET['id']);
  if(!$id) die("<h1><a href='./lab2.php'>go back</a></h1>");
  $user=array();
  $e = null;
  try {
      $dbh = new PDO('mysql:host=localhost;dbname=test', "root", "");
      if($_POST['usrtel'] && strlen($_POST["usrtel"])>1){
        $dbh->exec("UPDATE test SET phone=".$dbh->quote($_POST['usrtel'])." WHERE id=$id");
      }
      if($_POST['pass'] && strlen($_POST["pass"])>1){
        $dbh->exec("UPDATE test SET pass=".$dbh->quote($_POST['pass'])." WHERE id=$id");
      }
      if($_POST['e-mail'] && strlen($_POST["e-mail"])>1){
        $dbh->exec("UPDATE test SET email=".$dbh->quote($_POST['e-mail'])." WHERE id=$id");
      }
      if($_POST['uname'] && strlen($_POST["uname"])>1){
        $dbh->exec("UPDATE test SET name=".$dbh->quote($_POST['uname'])." WHERE id=$id");
      }
      if ($id) //защита, смотри статью на хабре https://habrahabr.ru/post/143035/
      {
          $sql = "SELECT * FROM test WHERE id=".$dbh->quote($id);
          $res = $dbh->query($sql);
          if($res->rowCount()>0){
            $user = $res->fetchAll()[0];
          }
      }
  } catch (Exception $ex) {
      $e = $ex;
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">    
    <meta charset="utf-8">
    <title>User page</title>
    <link href="./bootstrap.min.css" rel="stylesheet">
    <style>
      .details-block{  padding: 10px;  border-radius: 5px;}
    </style>
  </head>
  <body>
    <div class="container">
      <h2><small><a href="./lab2.php"><-  </a></small>Lab 2</h2>
      <div class="row">
        <div class="col-md-6">
          <div class="bg-success details-block">
            <h3 style="font-weight: bold;"><?php echo $user['name']; ?></h3>
            <h4><?php echo $user['email']; ?></h4>
            <h4><?php echo $user['phone']==="" ? "" : "+380".$user['phone']; ?></h4>
          </div>
        </div>
        <div class="col-md-6">
          <form method="POST" action="./lab2user.php?id=<?php echo $user['id']?>">
            <div class="form-group">
            <label for="fname">Изменить имя:</label>
              <input type="text" name="uname" class="form-control" id="fname" placeholder="имя" value="<?php echo htmlspecialchars($user['name']) ?>">
            </div>
            <div class="form-group">
              <label for="fpass">Задать новый пароль:</label>
              <input type="password" name="pass" class="form-control" id="fpass">
            </div>
            <div class="form-group">
              <label for="femail">Изменить Email:</label>
              <input type="email" name="e-mail" class="form-control" id="femail" value="<?php echo htmlspecialchars($user["email"]) ?>">
            </div>
            <div class="form-group">
              <label for="exampleInputPassword1">Изменить телефонный номер:</label>
              <div class="input-group">
                <span class="input-group-addon" id="sizing-addon2">+380</span>
                <input type="text" name="usrtel" class="form-control"  aria-describedby="sizing-addon2" value="<?php echo htmlspecialchars($user["phone"]) ?>">
              </div>
            </div>
            <p><input type="submit" class="btn btn-primary" href="#"></p>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>
