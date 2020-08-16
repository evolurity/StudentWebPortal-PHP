<?php
require  "../includes/config.php";
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo $config['title']; ?></title>

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

    <div id="content">
      <div class="container">
        <div class="row">
          <section class="content__left col-md-8">
            <div class="block">
           
              <h3>Навигация по всем записям для их редактирования или удаления</h3>
              <div class="block__content">
                <div class="articles articles__horizontal">
                    <?php
                    $per_page = 4;
                    $page = 1;
                    

                    if(isset($_GET['page']))
                    {
                      $page = (int)$_GET['page'];
                    }
                    
                    $total_count_q = mysqli_query($connection, "SELECT COUNT(`id`) AS `total_count` FROM `articles`");
                    $total_count = mysqli_fetch_assoc($total_count_q);
                    $total_count = $total_count['total_count'];
                    $total_pages = ceil($total_count / $per_page); 
                    if($page < 1 || $page > $total_pages){
                      $page = 1;
                    }

                    
                    $offset = ($per_page*$page) - $per_page;
                    
                    $articles = mysqli_query($connection, "SELECT * FROM `articles` ORDER BY `id`  DESC LIMIT $offset,$per_page");
                     $articles_exist =  true;

                    if(mysqli_num_rows($articles) <= 0){
                      echo "Статей не существует!";
                      $articles_exist = false;
                    }
                   ?>
                    <?php

                        while($art =  mysqli_fetch_assoc($articles)) {
                            ?>
                            <article class="article">
                                <div class="article__image"
                                     style="background-image: url(../static/images/<?php echo $art['image']; ?>);"></div>
                                <div class="article__info">
                                    <a href="article_admin.php?id=<?php echo $art['id']; ?>"><?php echo $art['title']; ?></a>
                                    <div class="article__info__meta">
                                        <?php
                                        $art_cat = false;
                                        foreach ($categories as $cat) {
                                            if ($cat['id'] == $art['categories_id']) {
                                                $art_cat = $cat;
                                                break;
                                            }
                                        }
                                        ?>
                                        <small>Категория: <a
                                                    href="articles_admin.php?categories=<?php echo $art_cat['id'] ?>"><?php echo $art_cat['title']; ?></a>
                                        </small>
                                    </div>
                                    <div class="article__info__preview"><?php echo mb_substr(strip_tags($art['text']), 0, 100, 'utf-8')." ..."; ?></div>
                                </div>
                            </article>
                            <?php
                        }
                    ?>
                </div>
                <?php
                if($articles_exist == true)
                {

                          echo '<div class="paginator">';
                          if($page > 1){
                              
                            echo '<a href="articles_admin.php?page='.($page-1).'">      &laquo;    Прошлая страница          </a>         '          ;
                          }
                          if($page < $total_pages){

                              echo '<a href="articles_admin.php?page='.($page+1).'">        Следующая страница &raquo;   </a>   '   ;

                          }

                          echo '</div>';
                        }
                ?>
              </div>
            </div>
            </div>
Добавить новую запись:
          
           <br><br>
           
         <div class="block" id="comment-add-form">
             
              <div class="block__content">

                <form class="form" method="POST" action="articles_admin.php?id=<?php echo $art['id']+1;   ?>#comment-add-form">

                <?php 
                if (isset($_POST['do_post'])) {
                        $errors = array();

                        if($_POST['title'] == '')
                        {
                            $errors[] = 'Введите заголовок!';
                        }
                        
                        if($_POST['cat'] == '')
                        {
                            $errors[] = 'Введите id категории!';
                        }
                        if($_POST['text'] == '')
                        {
                            $errors[] = 'Введите текст записи!';
                        }

                        if(empty($errors)){
                            #add somment

                          $title_ar =  $_POST['title'];
                          $text_ar =  $_POST['text'];
                          $cat_ar =  $_POST['cat'];

                          mysqli_query($connection,"INSERT INTO `articles` (`id`, `title`, `image`, `text`, `categories_id`, `update`, `views`) VALUES (NULL, '$title_ar', 'test.jpg', '$text_ar', '$cat_ar', current_timestamp(), '0')");

                            #mysqli_query($connection, "INSERT INTO `articles` (`id`,`title`,`image`,`text`, `categories_id`,`update`,`views`) VALUES (NULL,'$title_ar',NULL,'$text_ar','$cat_ar',NOW(),NULL)");
                            echo '<span style="color: green; font-weight:bold; margin-bottom: 10px; display: block;">Запись успешно добавлена! Обновите страницу, чтобы увидеть изменения!</span>'; 

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
                        
                        <input type="text" class="form__control"  name="title" placeholder="Заголовок записи"></div>
                       
                      <div class="col-md-4">
                        
                        <input type="text" class="form__control"  name="cat" placeholder="id категории"
                      
                      <div class="form__group">
                        <br>
                        
                    <textarea name="text"  class="form__control" placeholder="Текст Вашей записи ..."></textarea>
                    
                  </div>
                      
                    </div>
                  </div>
                 
                  <div class="form__group">
                    <input type="submit" class="form__control" name="do_post" value="Добавить запись">
                  </div>
                </form>
                </div>
      </div>
    </div>
          
</section>
</div>
      </div>
    </div>

          
  </div>

</body>
</html> 