<?php
require 'connection.php';
$errors = [];
$title = '';
$description = '';
$price = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $date = date('Y-m-d H-i-s');


    // errors code
    if (!$title) {
        $errors[] = 'Product title is required';
    }

    if (!$price) {
        $errors[] = 'Product price is required';
    }
    if(!is_dir('images')){
        mkdir('images');
    }
    function randomstring($n){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $str = '';
        for($i = 0;$i<$n;$i++){
            $index = rand(0,strlen($characters) - 1);
            $str .=$characters[$index];
        }
        return $str;
    }
    if (empty($errors)) {
        $image = $_FILES['image'] ?? null;
        $imagePath = '';
        if($image){
            $imagePath = 'images/'.randomstring(8).'/'.$image['name'];
            mkdir(dirname($imagePath));
            move_uploaded_file($image['tmp_name'],$imagePath );
        }
        $binding = $connection->prepare("INSERT INTO products (title,image,description,price,create_date) VALUES (:title,:image,:description,:price,:date)");
        $binding->execute(array(
            ':title' => $title,
            ':image' => $imagePath,
            ':description' => $description,
            ':price' => $price,
            ':date' => $date
        ));
        header('Location:index.php');
    }
}

?>
<!doctype html>
<html lang="en">

<head>
    <title>products crud</title>
    <?php if (!empty($errors)) :  ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error) : ?>
                <div><?php echo $error; ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS v5.0.2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- css externa link -->
    <link rel="stylesheet" href="index.css" />

</head>

<body>
    <h1>CREATE PRODUCT</h1>
    <p><a href="create.php" class="btn btn-success">Create Product</a></p>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Product Image</label>
            <br>
            <input type="file" name="image">
        </div>
        <div class="mb-3">
            <label class="form-label">Product Title</label>
            <input type="text" name="title" class="form-control" value="<?php echo $title;  ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Product Description</label>
            <textarea name="description" class="form-control"><?php echo $description;?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Product Price</label>
            <input type="number" name="price" step=".01" class="form-control" value="<?php echo $price; ?>">
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
    </form>
</body>