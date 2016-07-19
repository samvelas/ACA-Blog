<?php
require_once "components/header.php";
require_once "blog_db.php";

$currentPost = getPostAtId($_GET['id']);

$currentTags = getTagsOfPostAtId($_GET['id']);


?>

<script type="text/javascript">
    document.body.style.backgroundImage = "url('')";
</script>

<div class="container">
    <h1 style="display: block !important; width: 100%; color: black !important;" id="title" class="page-header"><?php echo $currentPost['title'] ?></h1>
    <div class="row">
        <div class="tags col-md-8">
            <?php
                foreach ($currentTags as $currentTag) {
                        echo '<span class="tag"><a href="index.php?tag=' . $currentTag . '">#' . $currentTag . '</a></span>';
                }
            ?>
        </div>
        <div style="background-color: transparent" class="col-md-offset-1 col-md-3">
            Created at <?php echo $currentPost['date_created']; ?>
        </div>
    </div>
    <div class="row">
        <h3 class="content_container">
            <?php echo $currentPost['content']; ?>
        </h3>
    </div>
</div>
