<?php
//retard, izmeni form action
error_reporting(0);
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
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>PHP lab 1</title>
    <link rel="stylesheet" type="text/css" href="./lab1.css">
  </head>
  <body>
    <h1>Lab ichi ╰( ͡° ͜ʖ ͡° )つ──☆*:・ﾟ</h1>
      <div class="container">
          <div class="form-wrapper">
            <h4>Форма регистрации</h4>
            <form action="/lab/lab1.php" method="post">
              <p>Ваше имя: <input type="text" name="uname" value="<?php echo htmlspecialchars($_POST["uname"]) ?>" placeholder="name"></p>
              <p>Ваш логин: <input value="<?php echo htmlspecialchars($_POST["login"]) ?>" placeholder="login" type="text" name="login" required></p>
              <p>Пароль: <input type="password" name="pass" required=""></p>
              <p>Повторите ваш пароль: <input type="password" name="check_pass" required=""></p>
              <p>Ваш email: <input placeholder="email" type="email" name="e-mail" value="<?php echo htmlspecialchars($_POST["e-mail"]) ?>" required></p>
              <p>Ваш телефон: +380<input value="<?php echo htmlspecialchars($_POST["usrtel"]) ?>" type="text" name="usrtel"></p>
              <input type="submit" name="" value="Регистрация">
            </form>
          </div>
            <?php if(count($_POST)>0): ?>
              <div class="result-wrapper <?php echo  $errMessage ? "error" : "success" ?>" >
                <?php if (!$errMessage): ?>
                  <table>
                    <tr>
                      <th>Name</th>
                      <th>Login</th>
                      <th>Pass</th>
                      <th>Email</th>
                      <th>Phone</th>
                    </tr>
                    <tr>
                      <td><?php echo $_POST['uname']; ?></td>
                      <td><?php echo $_POST['login']; ?></td>
                      <td><?php echo $_POST['pass']; ?></td>
                      <td><?php echo $_POST['e-mail']; ?></td>
                      <td><?php echo $_POST['usrtel'] ? $_POST['usrtel'] : "-" ; ?></td>
                    </tr>
                  </table>
                  <p>hashed pass: <?php echo password_hash($_POST['pass'], PASSWORD_DEFAULT); ?></p>
                <?php else: ?>
                  <h3>Некорректные данные!</h3><p><?php echo $errMessage;?></p>
                <?php endif; ?>
              </div>
          <?php endif;?>
      </div>
  </body>
</html>
