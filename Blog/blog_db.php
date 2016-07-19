<?php
include_once "database.php";
function getPosts()
{
    global $dbConnection;
    $posts = [];

    $sql = "SELECT id, title, date_created, content, author_id, image FROM blog_posts";

    $result = mysqli_query($dbConnection, $sql);

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            $posts[] = $row;
        }
    }

    return $posts;
}

function getPostAtId($post_id){
    
    global $dbConnection;

    $answer = [];
    
    $sql = "SELECT id, title, date_created, content, author_id, image FROM blog_posts WHERE id='{$post_id}'";
    $result = mysqli_query($dbConnection, $sql);

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            $answer = $row;
        }
    }
    
    return $answer;
    
}

function getCategories()
{
    global $dbConnection;
    $categories = [];

    $sql = "SELECT id, title FROM blog_post_categories";

    $result = mysqli_query($dbConnection, $sql);

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            $categories[] = $row;
        }
    }

    return $categories;
}

function createPost($data){

    global $dbConnection;
    $title = $data['title'];
    $content = $data['content'];
    $tags = $data['tags'];
    $pic = $data['image'];

    $sql = "INSERT INTO blog_posts(title, content, image) VALUES ('" . $title . "', '" . $content . "', '" . $pic . "')";

    $result = mysqli_query($dbConnection, $sql);

    $last_id = mysqli_insert_id($dbConnection);

    foreach ($tags as $tag){
        $sql = "INSERT INTO rel_blog_post_category (blog_post_id, category_id) VALUES ('{$last_id}', '" . $tag . "')";
        mysqli_query($dbConnection, $sql);
    }

    if(!$result){
        return false;
    }
    
    return true;

}

function createCategory($data){

    global $dbConnection;
    $title = $data['title'];

    $sql = "INSERT INTO blog_post_categories (title) VALUES ('" . $title . "')";

    $result = mysqli_query($dbConnection, $sql);

    if(!$result){
        return false;
    }

    return true;

}

function deletePost($postId){

    global $dbConnection;

    $path = "SELECT * FROM blog_posts WHERE id=" . $postId;

    $result = mysqli_query($dbConnection, $path);

    $sql = "DELETE FROM rel_blog_post_category WHERE blog_post_id=" . $postId;

    mysqli_query($dbConnection, $sql);

    $sql = "DELETE FROM blog_posts WHERE id=" . $postId;

    mysqli_query($dbConnection, $sql);




    $imgDir = '';

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            $imgDir = $row['image'];
        }
    }

    unlink($imgDir);


}

function deleteCategory($categoryId){

    global $dbConnection;
    $sql = "DELETE FROM blog_post_categories WHERE id=" . $categoryId;

    if (mysqli_query($dbConnection, $sql)) {
        return true;
    } else {
        return false;
    }

}

function updatePost($data, $postId){
    
    global $dbConnection;

    $title = $data['title'];
    $content = $data['content'];
    $sql = "UPDATE blog_posts SET title = '{$title}', content = '{$content}' WHERE id=" . $postId;

    $result = mysqli_query($dbConnection, $sql);

}

function updateCategory($data, $categoryId){

    global $dbConnection;

    $title = $data['title'];
    $sql = "UPDATE blog_post_categories SET title = '{$title}' WHERE id=" . $categoryId;

    $result = mysqli_query($dbConnection, $sql);

}

function makeKeywords (){

    global $dbConnection;
    $posts = [];

    $sql = "DELETE FROM blog_post_keywords";
    mysqli_query($dbConnection, $sql);

    $sql = "DELETE FROM rel_blog_post_keywords";
    mysqli_query($dbConnection, $sql);


    $sql = "SELECT id, content FROM blog_posts";

    $result = mysqli_query($dbConnection, $sql);

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            $posts[$row['id']] = $row['content'];
        }
    }

    $totalKeywords = [];

    foreach ($posts as $content) {
        $keywords = preg_split('/((^\p{P}+)|(\p{P}*\s+\p{P}*)|(\p{P}+$))/', $content, -1, PREG_SPLIT_NO_EMPTY);
        $keywords = array_unique($keywords);
        $totalKeywords = array_merge($totalKeywords, $keywords);
    }

    $totalKeywordsUnique = array_unique($totalKeywords);
    $totalKeywordsArray = [];

    foreach ($totalKeywordsUnique as $totalKeyword) {
        $sql = "INSERT INTO blog_post_keywords (keyword) VALUES ('" . $totalKeyword . "')";
        $result = mysqli_query($dbConnection, $sql);
        $keywordId = mysqli_insert_id($dbConnection);
        $totalKeywordsArray[$keywordId] = $totalKeyword;
    }

    foreach ($posts as $postId => $content) {
        foreach ($totalKeywordsArray as $keywordId => $totalKeyword) {
            $count = substr_count($content, $totalKeyword);
            if ($count) {
                $sql = "INSERT INTO rel_blog_post_keywords (`blog_post_id`, `keyword_id`, `count`) VALUES (" . $postId . "," . $keywordId . "," . $count . ")";
                mysqli_query($dbConnection, $sql);
            }
        }
    }
}

makeKeywords();

function searchFor($keyword) {
    global $dbConnection;
    $id = '';
    $posts = [];
    $answer = [];

    $sql = "SELECT id FROM blog_post_keywords WHERE keyword='{$keyword}'";
    $result = mysqli_query($dbConnection, $sql);


    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
        }
    }

    $sql = "SELECT blog_post_id, `count` FROM rel_blog_post_keywords WHERE keyword_id='{$id}'";
    $result = mysqli_query($dbConnection, $sql);


    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            $posts[$row['blog_post_id']] = $row['count'];
        }
    }

    arsort($posts);

    foreach ($posts as $post => $key){

        $sql = "SELECT id, title, date_created, content, author_id FROM blog_posts WHERE id='{$post}'";
        $result = mysqli_query($dbConnection, $sql);

        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                $answer[] = $row;
            }
        }

    }
    
    return $answer;

}

function getTagsOfPostAtId($post_id){

    global $dbConnection;
    $tags = [];
    $answer =[];

    $sql = "SELECT category_id FROM rel_blog_post_category WHERE blog_post_id='{$post_id}'";
    $result = mysqli_query($dbConnection, $sql);

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            $tags[] = $row['category_id'];
        }
    }

    foreach ($tags as $tag) {
        $sql = "SELECT title FROM blog_post_categories WHERE id='{$tag}'";
        $result = mysqli_query($dbConnection, $sql);

        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
                $answer[] = $row['title'];
            }
        }
    }

    return $answer;
}

function searchPostsAtTag($tag){
    global $dbConnection;
    $currentTag = [];
    $answer =[];
    $posts = [];
    $postId = [];

    $sql = "SELECT id FROM blog_post_categories WHERE title='{$tag}'";
    $result = mysqli_query($dbConnection, $sql);

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            $currentTag = $row['id'];
        }
    }

    $sql = "SELECT blog_post_id FROM rel_blog_post_category WHERE category_id='{$currentTag}'";
    $result = mysqli_query($dbConnection, $sql);

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            $postId[] = $row['blog_post_id'];
        }
    }

    foreach ($postId as $item) {

        $sql = "SELECT id, title, date_created, content, author_id, image FROM blog_posts WHERE id='{$item}'";
        $result = mysqli_query($dbConnection, $sql);

        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                $posts[] = $row;
            }
        }
    }

    return $posts;
}


?>