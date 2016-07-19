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
    deleteCategory($del);
}

if (isset($_POST['title'])) {
    $title = ($_POST['title']);

    $category = [
        'title' => $title
    ];

    if(isset($_POST['isEditing'])){
        $categoryId = $_POST['isEditing'];
        updateCategory($category, $categoryId);
    } else {

        if ($title != '') {

            createCategory($category);
            $_POST['title'] = '';
        }
    }
}


$categories = getCategories();
$size = count($categories);


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
            <form method="post" action="categories.php" enctype="multipart/form-data" name="myForm" id="form">
                <h2>Add Post</h2>
                <input id="title" class="form-control" name="title" placeholder="Title"><br>
                <br>
                <button class="btn btn-info btn-md" type="submit">Add</button>
            </form>
        </div>

    </div>
    <div class="col-md-2">
        <h1 class="page-header">Menu</h1>

        <div class="list-group disabled">
            <a class="list-group-item active" href="categories.php">Categories</a>
            <a href="index.php" class="list-group-item">Posts</a>
        </div>
    </div>
    <div class="col-md-offset-1 col-md-8">
        <h1 class="page-header">Categories</h1>
        <button class="btn btn-success btn-lg" id="myBtn" type="submit">Add<span style="margin-left: 15px; color: white" class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
        <table class="table">
            <thead>
            <th>#</th>
            <th>Title</th>
            <th>Delete</th>
            <th>Edit</th>
            </thead>
            <tbody>
            <?php
            for ($i = $start - 1; $i < $start + $limit; $i++) {
                echo "<tr onclick='showTags(\"" . $categories[$i]['title'] . "\")'>";
                echo "<td style='font-weight: bolder'>" . ($i + 1) . "</td>";
                echo "<td>" . $categories[$i]['title'] . "</td>";
                echo '<td><a class="btn btn-danger btn-md" href="categories.php?page=' . $currentPage . '&del=' . $categories[$i]['id'] . '">Delete</a></td>';
                echo '<td><button class="btn btn-warning btn-md" id="edit" onclick="editCategory(' . $i . ')">Edit</button></td>';
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

                    echo '<li class="' . $style . '"><a href="categories.php?page=' . $i . '">' . $i . '</a></li>';

                }
                ?>
            </ul>
        </nav>
    </div>
</div>
</div>

<?php
require_once "components/footer.php";
?>

<script type="text/javascript">

    function editCategory(ida) {
        var cur = parseInt(ida);
        var users = <?php echo json_encode($categories);?>;
        console.log(users);
        var firstField = document.getElementById("title");
        firstField.value = users[cur].title;

        var form = document.getElementById('form');
        modal.style.display = "block";
        var theForm = document.forms['myForm'];
        addHidden(theForm, "isEditing", users[cur].id, 'hidden');
    }

    function showTags(tag) {
        window.location = "index.php?tag=" + tag;
    }


</script>

