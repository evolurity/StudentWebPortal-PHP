<?php
require  "../includes/config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Студенческий Портал ФКН</title>

    <!-- Bootstrap Grid -->
    <link rel="stylesheet" type="text/css" href="../media/assets/bootstrap-grid-only/css/grid12.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">

    <!-- Custom -->
    <link rel="stylesheet" type="text/css" href="../media/css/style.css">
</head>
<body>

<div id="wrapper">

    <?php include "../includes/header.php";?>
    <?php 
    $article = mysqli_query($connection,"SELECT * FROM `articles` WHERE `id` = ".(int)$_GET['id']);
    if(mysqli_num_rows($article)<= 0){
      ?>
      <div id="content">
        <div class="container">
            <div class="row">
                <section class="content__left col-md-8">
                    <div class="block">

                        <h3>Запись не найдена!</h3>
                        <div class="block__content">
                            <img src="../static/images/<?php echo $art['image']; ?>" style = "max-width: 100%;">

                            <div class="full-text">
                                Запрашиваемая Вами запись успешно удалена!
                                
                            </div>
                        </div>
                    </div>


                </section>
                <section class="content__right col-md-4">
                    <?php include "../includes/sidebar.php"; ?>
                </section>
            </div>
        </div>
    </div>
    <?php
    } else{
        $art = mysqli_fetch_assoc($article);
        mysqli_query($connection, "UPDATE `articles` SET `views` = `views` WHERE `id` = ".(int)$art['id']);

     ?> 
     <div id="content">
        <div class="container">
            <div class="row">
                <section class="content__left col-md-8">
                    <div class="block"> 
                    
                    

                
                    <form class="form" method="POST">
                      <input type="submit" class="form__control" name="delete_post" value="Удалить эту запись">
                        
                            <?php 
                if (isset($_POST['delete_post'])) {        
                        mysqli_query($connection, "DELETE FROM `articles` WHERE `id` = ".(int)$art['id']);
                        echo '<span style="color: green; font-weight:bold; margin-bottom: 10px; display: block;">Данная запись успешно удалена!Обновите страницу, чтобы увидеть изменения!</span>';
                }?>
 </form>
                        

                        <h3><?php echo $art['title']; ?></h3>
                        <div class="block__content">
                            <img src="../static/images/<?php echo $art['image']; ?>" style = "max-width: 100%;">
                            <a><?php echo $art['views'];?> просмотров <a>
                            <div class="full-text"><br>
                                <?php echo $art['text']; ?></div>
                      </div>
                    </div>
<div class="block">
    <div class="block" id="comment-red-form">
              <h3>Редактировать запись</h3>
              <div class="block__content">
                <form class="form" method="POST" action="article_admin.php?id=<?php echo $art['id'];   ?>#comment-red-form">
                <?php 
                if (isset($_POST['red_post'])) {
                        $errors = array();
                        if($_POST['title'] == '')
                        {
                            $errors[] = 'Введите желаемый заголовок!<br> Это поле не может быть пустым!';
                        }
                        
                        if($_POST['text'] == '')
                        {
                            $errors[] = 'Введите  желаемый текст записи! <br> Это поле не может быть пустым!';
                        }

                        if(empty($errors)){
                            #add somment
                            $new_title = $_POST['title'];
                            $new_text = $_POST['text'];
                            mysqli_query($connection, "UPDATE `articles` SET `title` ='$new_title', `text` = '$new_text' WHERE `id` = ".(int)$art['id']);
                            echo '<span style="color: green; font-weight:bold; margin-bottom: 10px; display: block;">Новые изменения успешно сохранены!</span>'; 

                        } else
                        {
                            #output error
                            echo '<span style="color: red; font-weight:bold; margin-bottom: 10px; display: block;">'.$errors['0'].'</span>'; 
                        }

                     
                 } 

                ?> 
                  <div class="form__group">
                    <div class="row">
                      <div class="col-md-4">
                        Изменить заголовок записи:
                        <input type="text" class="form__control"  name="title"  value="<?=$art['title']?>">
                      </div>

                       
                    </div>
                  </div>
                  <div class="form__group">
                    Изменить текст записи:
                    <textarea name="text"  class="form__control" ><?php echo $art['text'];?>  </textarea>
                  </div>
                  <div class="form__group">
                    <input type="submit" class="form__control" name="red_post" value="Сохранить изменения">
                  </div>
                </form>
              </div>
            </div>
               







<div class="block" id="comment-add-form">
              <h3>Добавить комментарий</h3>
              <div class="block__content">
                <form class="form" method="POST" action="article_admin.php?id=<?php echo $art['id'];   ?>#comment-add-form">
                <?php 
                if (isset($_POST['add_post'])) {
                        $errors = array();
                        if($_POST['name'] == '')
                        {
                            $errors[] = 'Введите имя!';
                        }
                        if($_POST['nickname'] == '')
                        {
                            $errors[] = 'Введите Ваш никнейм!';
                        }
                        if($_POST['email'] == '')
                        {
                            $errors[] = 'Введите Email!';
                        }
                        if($_POST['text'] == '')
                        {
                            $errors[] = 'Введите текст комментария!';
                        }

                        if(empty($errors)){
                            #add somment
                            mysqli_query($connection, "INSERT INTO `comments` (`author`,`nickname`,`email`,`text`,`pubdate`,`articles_id`) VALUES ('".$_POST['name']."','".$_POST['nickname']."','".$_POST['email']."','".$_POST['text']."',NOW(),'".$art['id']."')");
                            echo '<span style="color: green; font-weight:bold; margin-bottom: 10px; display: block;">Комментарий успешно добавлен!</span>'; 

                        } else
                        {
                            #output error
                            echo '<span style="color: red; font-weight:bold; margin-bottom: 10px; display: block;">'.$errors['0'].'</span>'; 
                        }

                     
                 } 

                ?> 
                  <div class="form__group">
                    <div class="row">
                      <div class="col-md-4">
                        <input type="text" class="form__control"  name="name" placeholder="Имя" value="<?php echo $_POST['name'];  ?>">
                      </div>
                      <div class="col-md-4">
                        <input type="text" class="form__control"  name="nickname" placeholder="Никнейм"
                         value="<?php echo $_POST['nickname'];  ?>">
                      </div>
                       <div class="col-md-4">
                        <input type="text" class="form__control"  name="email" placeholder="Email (не будет показан)"  value="<?php echo $_POST['email'];  ?>">
                      </div>
                    </div>
                  </div>
                  <div class="form__group">
                    <textarea name="text"  class="form__control" placeholder="Текст комментария ..."><?php echo $_POST['text'];?>  </textarea>
                  </div>
                  <div class="form__group">
                    <input type="submit" class="form__control" name="add_post" value="Добавить комментарий">
                  </div>
                </form>
              </div>
            </div>
                </section>
                <section class="content__right col-md-4">
                    
                </section>
            </div>
        </div>
    </div>
     <?php
    }
    ?>

    

</div>

</body>
</html>