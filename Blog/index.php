<?php
require_once "blog_db.php";
require_once "components/header.php";
define("ITEMS_PER_PAGE", 3);
$currentPage = 1;

if (isset($_GET['page'])) {
    $currentPage = $_GET['page'];
}

if (isset($_GET['del'])){

    $del = $_GET['del'];
    deletePost($del);
}

if (isset($_POST['title']) && isset($_POST['content']) && isset($_POST['cats'])) {
    $title = ($_POST['title']);
    $content = ($_POST['content']);
    $cats = $_POST['cats'];

    if ($_FILES['pic']['name'] != "") {
        require_once "upload-image.php";
        $pic = "post_images/" . $name;
    }

    $post = [
        'title' => $title,
        'content' => $content,
        'tags' => $cats,
        'image' => $pic
    ];

    if(isset($_POST['isEditing'])){
        $postId = $_POST['isEditing'];
        updatePost($post, $postId);;
    } else {

        if ($title != '' && $content != '') {

            createPost($post);
            $_POST['title'] = '';
        }
    }
}



$posts = getPosts();
$categories = getCategories();


if (isset($_POST['search']) && $_POST['search'] != ''){
    $posts = searchFor($_POST['search']);
}

if (isset($_GET['tag']) && $_GET['tag'] != ''){
    $posts = searchPostsAtTag($_GET['tag']);
}

$size = count($posts);

$totalPageCount = ceil($size / ITEMS_PER_PAGE);

$start = ($currentPage - 1) * ITEMS_PER_PAGE + 1;
$limit = ITEMS_PER_PAGE;

if ($start + $limit > $size) {
    $limit = $size - $start;
}

?>

<div class="container-fluid">
    <div id="myModal" class="modal">

        <!-- Modal content -->

        <div class="modal-content" id="content">
            <form method="post" action="index.php" enctype="multipart/form-data" name="myForm" id="form">
                <h2>Add Post</h2>
                <input id="title" class="form-control" name="title" placeholder="Title"><br>
                <textarea id="conte" class="form-control" name="content" placeholder="Content"></textarea><br>
                <input id="picture" class="form-control" type="file" name="pic">
                <br>
                <?php
                foreach ($categories as $category) {
                    echo '<div class="checkbox">
                             <label>
                        <input type="checkbox" name="cats[]" value="' . $category['id'] . '">' . $category['title'] . '
                            </label>
                        </div>';
                }
                ?><br>
                <button class="btn btn-info btn-md" type="submit">Add</button>
            </form>
        </div>

    </div>
    <div class="col-md-2">
        <h1 class="page-header">Menu</h1>

        <div class="list-group">
            <a href="categories.php" class="list-group-item">Categories</a>
            <a class="list-group-item active" href="index.php">Posts</a>
        </div>

    </div>
    <div class="col-md-offset-1 col-md-8">
        <h1 class="page-header">Posts</h1>
        <button class="btn btn-success btn-md" id="myBtn">Add<span style="margin-left: 15px; color: white" class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
        <form class="search" action="index.php" method="post">
            <input name="search" class="form-control" placeholder="Search for...">
        </form>
        <table class="table">
            <thead>
                <th>#</th>
                <th>Picture</th>
                <th>Title</th>
                <th>Date created</th>
                <th>Content</th>
                <th>Delete</th>
                <th>Edit</th>
            </thead>
            <tbody>
            <?php
            for ($i = $start - 1; $i < $start + $limit; $i++) {
                echo "<tr class='cont' onclick='showPost(" . $posts[$i]['id'] . ")'>";
                    echo "<td style='font-weight: bolder'>" . ($i + 1) . "</td>";
                    echo "<td id='image-cell'><button id='image'><img class='avatar' width='100px' height='75px' src='" . $posts[$i]['image'] . "'></button></td>";
                    echo "<td id='title-limit'>" . $posts[$i]['title'] . "</td>";
                    echo "<td>" . $posts[$i]['date_created'] . "</td>";
                    echo "<td id='content-limit'>" . $posts[$i]['content'] . "</td>";
                    echo '<td><a class="btn btn-danger btn-md" href="index.php?page=' . $currentPage . '&del=' . $posts[$i]['id'] . '">Delete</a></td>';
                    echo '<td><button class="btn btn-warning btn-md" id="edit" onclick="editPost(' . $i . ')">Edit</button></td>';
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
        <nav>
            <ul class="pagination">
                <?php
                for ($i = 1; $i <= $totalPageCount; $i++) {
                    $style = '';
                    if ($i == $currentPage) {
                        $style = "active";
                    }

                    echo '<li class="' . $style . '"><a href="index.php?page=' . $i . '">' . $i . '</a></li>';

                }
                ?>
            </ul>
        </nav>
    </div>
</div>

<?php
require_once "components/footer.php";
?>

<script type="text/javascript">

    function editPost(ida) {
        var cur = parseInt(ida);
        var users = <?php echo json_encode($posts);?>;
        console.log(users);
        var firstField = document.getElementById("title");
        firstField.value = users[cur].title;
        var lastField = document.getElementById("conte");
        lastField.value = users[cur].content;

        var form = document.getElementById('form');
        modal.style.display = "block";
        var theForm = document.forms['myForm'];
        addHidden(theForm, "isEditing", users[cur].id, 'hidden');
    }

    function showPost(post_id) {
        var edit = document.getElementById('edit');
        if(event.target != edit) {
            window.location = "blog_post.php?id=" + post_id;
        }
    }

</script>

