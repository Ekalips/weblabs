<?php
error_reporting(0);
//retard, izmeni form action i dannie bazi. i a href ne zabud'
$tableResult= array();
$e = null;
if(count($_POST)>0){
  if(!strlen($_POST["e-mail"])>6)
    $errMessage.="\nВведите корректный email!";
  if (!strlen($_POST["usrtel"])>5 || !preg_match('/^[0-9]*$/', $_POST["usrtel"]))
    $errMessage.="<p>Введите корректный телефонный номер!</p>";
  if(strlen($_POST["pass"]!==$_POST["check_pass"])){
    $errMessage.="<p>Пароли не совпадают!</p>";
  }else{
    if(strlen($_POST["pass"])<6)                   $errMessage.='<p>Слишком короткий пароль!(менее 6 символов)</p>';
    if(!preg_match("/([0-9]+)/", $_POST["pass"]))  $errMessage.='<p>В пароле не хватает цифр</p>';
    if(!preg_match("/([a-z]+)/", $_POST["pass"]))  $errMessage.='<p>В пароле не хватает маленьких букв</p>';
    if(!preg_match("/([A-Z]+)/", $_POST["pass"]))  $errMessage.='<p>В пароле не хватает больших букв</p>';
  }
  if ($errMessage) {
    $e = new Exception($errMessage);
  }
}
try {
    $dbh = new PDO('mysql:host=localhost;dbname=ekalips', "admin", "kamisamad");
    if(count($_POST)>0 && !$e){
      $res = $dbh->query("SELECT id FROM test WHERE login=".$dbh->quote($_POST['login']));   // Проверка на наличие юзера
      if($res->rowCount()>0)                                                                // в базе
        $e = new Exception("<p>Пользователь с таким логином уже существует!</p>");          //
      if(!$e){
        $phash = password_hash($_POST["pass"], PASSWORD_DEFAULT);
        $sql = "INSERT INTO `test` (`login`, `pass_hash`, `email`, `name`, `phone`) VALUES
               (".$dbh->quote($_POST['login'] ).",
               '".$phash."',
                ".$dbh->quote($_POST['e-mail']).",
                ".$dbh->quote($_POST['uname'] ).",
                ".$dbh->quote($_POST['usrtel']).");";
        $dbh->exec($sql);
      }
    }
    foreach($dbh->query('SELECT * from test') as $row) {                //получение таблицы
        $tableResult[]=$row;
    }
    $dbh = null;
} catch (Exception $ex) {
  $e = $ex;
}
?>
<!DOCTYPE html>
<html>
  <head>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">    
    <link href="./bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href=".yolo/lab1.css">
    <meta charset="utf-8">
    <title>Lab 2</title>
  </head>
  <body>
    <div class="container">
      <h2>Lab 2</h2>
      <div class="row">
        <div class="col-md-6 col-sm-12">
          <?php if(count($_POST)>0): ?>
            <?php if ($e): ?>
              <div class="alert alert-danger" role="alert"><h4>Ошибка:</h4> <?php echo $e->getMessage();?></div>
            <?php else:?>
              <div class="alert alert-success" role="alert">Данные успешно добавлены в базу!</div>
            <?php endif; ?>
          <?php endif; ?>
          <form method="POST" action="./lab2.php">
            <div class="form-group">
            <label for="fname">Ваше имя:</label>
              <input type="text" name="uname" class="form-control" id="fname" placeholder="имя" required value="<?php echo htmlspecialchars($_POST["uname"]) ?>">
            </div>
            <div class="form-group">
              <label for="flogin">Логин</label>
              <input type="text" name="login" class="form-control" id="flogin" placeholder="логин" required value="<?php echo htmlspecialchars($_POST["login"]) ?>">
            </div>
            <div class="form-group">
              <label for="fpass">Пароль</label>
              <input type="password" name="pass" class="form-control" id="fpass" required>
            </div>
            <div class="form-group">
              <label for="frpass">Повторите пароль</label>
              <input type="password" name="check_pass" class="form-control" id="frpass" required>
            </div>
            <div class="form-group">
              <label for="femail">Ваш Email</label>
              <input type="email" name="e-mail" class="form-control" id="femail" required value="<?php echo htmlspecialchars($_POST["e-mail"]) ?>">
            </div>
            <div class="form-group">
              <label for="exampleInputPassword1">Ваш телефон</label>
              <div class="input-group">
                <span class="input-group-addon" id="sizing-addon2">+380</span>
                <input type="text" name="usrtel" class="form-control"  aria-describedby="sizing-addon2" value="<?php echo htmlspecialchars($_POST["usrtel"]) ?>">
              </div>
            </div>
            <p><input type="submit" class="btn btn-primary" href="#"></p>
          </form>
        </div>
      </div>
      <div style="margin-top: 20px;" class="row">
        <div class="col-md-6 col-sm-12">
          <table class="table table-striped table-hover">
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Login</th>
                <th>Pass</th>
                <th>Email</th>
                <th>Phone</th>
              </tr>
              <?php foreach ($tableResult as $key => $value): ?>
                <tr>
                  <td><?php echo $value['id']; ?></td>
                  <td><a href="./lab2user.php?id=<?php echo $value['id']; ?>"><?php echo $value['name']; ?></a></td>
                  <td><?php echo $value['login']; ?></td>
                  <td><?php echo $value['pass_hash']; ?></td>
                  <td><?php echo $value['email']; ?></td>
                  <td><?php echo $value['phone'] ? $value['phone'] : "-" ; // $a ?: "nil" ?></td>
                </tr>
              <?php endforeach; ?>
          </table>
        </div>
      </div>
    </div>
  </body>
</html>
