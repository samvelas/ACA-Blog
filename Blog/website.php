<head>
    <meta charset="UTF-8">
    <title>ACA</title>
    <link rel="stylesheet" href="assets/website-main.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymo">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
</head>

<?php
require_once "blog_db.php";

$categories = getCategories();
$posts = getPosts();
$currentTag = '';




if (isset($_GET['tag']) && $_GET['tag'] != ''){
    $currentTag = $_GET['tag'];
    $posts = searchPostsAtTag($_GET['tag']);
}

?>
<body>
    <nav class="navbar navbar-default">
        <div class="container-fluid">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="website.php">ACA</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <?php
                    foreach ($categories as $category){

                        $style = '';

                        if ($currentTag == $category['title']){
                            $style = "active";
                        }

                        echo '<li class="' . $style . '"><a href="website.php?tag=' . $category['title'] . '">';
                        echo $category['title'];
                        echo '</a></li>';
                    }
                    ?>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
    <div class="container">
        <?php
        foreach ($posts as $post){
            echo '<div class="row">';
            echo '<div class="col-md-3">';
            echo '<img class="image" src="' . $post['image'] . '">';
            echo '</div>';
            echo '<div class="col-md-8">';
            echo '<h3><strong>' . $post['title'] . '</strong></h3>';
            echo '<h5>' . $post['content'] . '</h5>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
</body>
<?php
require_once "components/footer.php";
?>
