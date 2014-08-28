<?php
require_once('/controller/Controller.php');

if($_POST['action'] === 'insert'){
    $error = Controller::insert($_POST);
}

switch ($_GET['action']){
    case NULL:
        if(isset($_GET['page'])){
            $result = Controller::index($_GET['page']);
        }
        else{
            $result = Controller::index();
        }
        $quotes = $result['result'];
        $pager = $result['pages'];
        break;
    case 'cat':
        if(isset($_GET['page'])){
            $result = Controller::category($_GET['id'],$_GET['page']);
        }
        else{
            $result = Controller::category($_GET['id']);
        }
        $quotes = $result['result'];
        $pagerCat = $result['pages'];
        break;
}

$category = Controller::getCategory();
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>BashOrgClone</title>
    <link rel="stylesheet" href="css/style.css"/>
</head>
<body>
    <div class="center">
        <div class="logo">
            <h1>BashOrgClone</h1>
        </div>
        <div class="category">
            <?php foreach($category as $cat){
                echo "<a href='index.php?action=cat&id=".$cat['id']."'>".$cat['name']."</a>";
            }?>
        </div>
        <div class="cont">
            <?php
                foreach($quotes as $quote){ ?>
                     <div class='quote'>
                         <p>
                            # <strong><?= $quote['id'];?></strong> <span><strong><?= htmlspecialchars($quote['title']);?></strong></span>
                         </p>
                         <p>
                            <span>Дата: <?= $quote['datePub'];?></span>
                         </p>
                         <p>
                             <?=  htmlspecialchars($quote['text']);?>
                         </p>
                         <p>Автор - <?= htmlspecialchars($quote['author']);?></p>
                     </div>
                <?}?>
        </div>
        <div class="pagination">
            <ul>
            <?php

            if(isset($pagerCat)){
                for($page=1;$page<=$pagerCat;$page++){
                    if($page === $result['activePage']){
                        echo "<li class='active'><a href='index.php?action=cat&id=" . $_GET['id'] . "&page=" . $page . "'>" . $page . "</a></li>";
                    }
                    else
                    {
                        echo "<li><a href='index.php?action=cat&id=" . $_GET['id'] . "&page=" . $page . "'>" . $page . "</a></li>";
                    }

                }
            }
            else{
                for($page=1;$page<=$pager;$page++){
                    if($page === $result['activePage']){
                        echo "<li class='active'><a href='index.php?page=" . $page . "'>" . $page . "</a></li>";
                    }
                    else
                    {
                        echo "<li><a href='index.php?page=" . $page . "'>" . $page . "</a></li>";
                    }
                }
            }
            ?>
            </ul>
        </div>
        <div class="form">
            <form action="#" method="post">
                <div class="input">
                    <input type="hidden" name="action" value="insert"/>
                    <input type="text" name="title" placeholder="Заголовок"/>
                    <?php if($error->error['title']!==null){
                        echo $error->error['title'];
                    }
                    ?>
                </div>
                <div class="input">
                    <textarea name="text" cols="30" rows="10" placeholder="Текст"></textarea>
                    <?php if($error->error['text']!==null){
                        echo $error->error['text'];
                    }
                    ?>
                </div>
                <div class="input">
                    <input type="text" name="author" placeholder="Ваше имя"/>
                    <?php if($error->error['author']!==null){
                        echo $error->error['author'];
                    }
                    ?>
                </div>
                <div class="input">
                <select name="category">
                    <?php
                        foreach($category as $val){
                            echo "<option value='" . $val['id'] . "'>" . $val['name'] . "</option>";
                        }
                    ?>
                    </select>
                    <?php if($error->error['category']!==null){
                        echo $error->error['category'];
                    }
                    ?>
                </div>
                <input type="submit" value="Отправить"/>
            </form>
        </div>
    </div>
</body>
</html>