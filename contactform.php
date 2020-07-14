<?php 
  session_start();
  $mode = 'input' ;
  $errmessage = array();
  if( isset($_POST['back']) && $_POST['back'] ){
    // 何もしない
  } else if ( isset($_POST['confirm']) && $_POST['confirm'] ){
    // 確認画面
    if( !$_POST['fullname'] ) {
      $errmessage[] = "※名前を入力してください";
    } else if( mb_strlen($_POST['fullname']) > 100 ){
      $errmessage[] = "※名前は100文字以内にしてください";
    }
    $_SESSION['fullname'] = htmlspecialchars($_POST['fullname'], ENT_QUOTES);

    if( !$_POST['email']) {
      $errmessage[] = "※Eメールを入力してください";
    } else if( mb_strlen($_POST['email']) > 200 ){
      $errmessage[] = "※Eメールは200文字以内にしてください";
    } else if( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ){
      $errmessage[] = "※メールアドレスが不正です。";
    }
    $_SESSION['email'] = htmlspecialchars($_POST['email'], ENT_QUOTES);


    if( !$_POST['number'] < 10 && !$_POST['number'] > 0) {
      $errmessage[] = "※数字を入力してください";
    } elseif( !$_POST['number']){
      $errmessage[] = "※数字を入力してください";
    }
    $_SESSION['number'] = htmlspecialchars($_POST['number'], ENT_QUOTES);

    if( !$_POST['message'] ) {
      $errmessage[] = "※メッセージを入力してください";
    } else if( mb_strlen($_POST['message']) > 500 ){
      $errmessage[] = "※メッセージは500文字以内にしてください";
    }
    $_SESSION['message'] = htmlspecialchars($_POST['message'], ENT_QUOTES);
    
    if ( $errmessage ){
      $mode = 'input';
    } else {
      $mode = 'confirm';
    }
  } else if ( isset($_POST['send']) && $_POST['send'] ){ 
    //送信ボタンが押されたとき
    $message = "御解答を受けつけました。\r\n"
              ."名前:" . $_SESSION['fullname'] ."\r\n"
              ."email" .$_SESSION['email'] ."\r\n"
              ."合計購入個数:".$_SESSION['number']."\r\n"
              ."お問い合わせ内容:\r\n"
              .preg_replace("/\r\n|\r|\n/", "\r\n", $_SESSION['message']);
    mail($_SESSION['email'], '御解答ありがとうございます', $messeage);
    mail('takenaka@guruguru.com', '御解答ありがとうございます', $messeage);
    $_SESSION = array();
    $mode = 'send';
  } else {
    $_SESSION['fullname'] = "";
    $_SESSION['email']    = "";
    $_SESSION['number']    = "";
    $_SESSION['message']  = "";
  }
?>


<!doctype html>
<html lang="ja">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

    <title>お問い合わせフォーム [Atelie Ito]</title >
  </head>
  <body>
    <header class="navbar navbar-expand-lg navbar-light bg-light fixed-top col-12 col-md-8 mx-auto">
      <a class="navbar-brand pl-2" href="#"><img class="logo-img mr-2" style="width: 60px; height: 60px;" src="Atelie Ito pics/Atelie Logo.jpeg" alt=""> Atelie Ito</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="mr-auto">
        </ul>
        <form class="form-inline my-2 my-lg-0 float-right">
          <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
        </form>
      </div>
    </header>
    <?php if( $mode == 'input' ){ ?>
      <!-- 入力画面 -->
      <div class="alert mx-auto col-md-8 col-12">
      <?php 
      if ( $errmessage ){
        echo '<div style="color:red;">';
        echo implode('<br>', $errmessage);
        echo '</div>';
      } 
      ?>
      </div>
      <form action="./contactform.php" method="post" class="col-12 col-md-8 mx-auto pt-5">
        お名前<input type="text" name="fullname" value="<?php echo $_SESSION['fullname']?>" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter name"><br>
        E-mail <input type="email" name="email" value="<?php echo $_SESSION['email']?>" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email"><br>
        <div class="form-group">
          <label for="exampleFormControlSelect1">購入合計個数</label>
          <input type="text" name="number" value="<?php echo $_SESSION['number']?>" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter number 1 to 10">
        </div>
        <br>
        商品のご感想<textarea name="message" cols="40" rows="8" class="form-control" id="exampleFormControlTextarea1"><?php echo $_SESSION['message']?></textarea><br>
        <div class="text-center"><input type="submit" name="confirm" value="確認" class="btn btn-success"></div>
      </form>
    <?php } else if( $mode == 'confirm' ){ ?>
      <!-- 確認画面 -->
      <form action="./contactform.php" method="post">
        名前 <?php echo $_SESSION['fullname'] ?><br>
        E-mail <?php echo $_SESSION['email'] ?><br>
        合計購入個数 <?php echo $_SESSION['number'] ?><br>
        お問い合わせ内容<br>
        <?php echo nl2br($_SESSION['message'])?><br>
        <input type="submit" name="back" value="戻る" >
        <input type="submit" name="send" value="送信" >
      </form>
    <?php } else { ?>
      <!-- 完了画面 -->
      送信しました。<br>
    <?php } ?> 

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  </body>
</html>