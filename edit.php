<?php
require 'connection.php';


$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location:index.php');
    exit;
}
$edit = $connection->prepare('SELECT * FROM products WHERE id=:id');
$edit->execute(array(
    ':id' => $id
));
$product = $edit->fetch(PDO::FETCH_OBJ);


$errors = [];
$title = $product->title;
$description = $product->description;
$price = $product->price;
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
    if (!is_dir('images')) {
        mkdir('images');
    }
    function randomstring($n)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $str = '';
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $str .= $characters[$index];
        }
        return $str;
    }
    if (empty($errors)) {
        $image = $_FILES['image'] ?? null;
        $imagePath = $product->image;
        if ($image) {
            if($product->image){
                unlink($product->image);
            }
            $imagePath = 'images/' . randomstring(8) . '/' . $image['name'];
            mkdir(dirname($imagePath));
            move_uploaded_file($image['tmp_name'], $imagePath);
        }
        $binding = $connection->prepare("UPDATE  products SET title=:title,image=:image,description=:description,price=:price,create_date=:date WHERE id=:id");
        $binding->execute(array(
            ':title' => $title,
            ':image' => $imagePath,
            ':description' => $description,
            ':price' => $price,
            ':date' => $date,
            ':id'=>$id
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
    <a href="index.php" class="btn btn-secondary">Go back to products</a>
    <h1>EDIT PRODUCT</h1>

    <form action="" method="post" enctype="multipart/form-data">
        <?php if ($product->image) : ?>
            <img src="<?php echo $product->image ?>" class="update-image">
        <?php endif; ?>
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
            <textarea name="description" class="form-control"><?php echo $description; ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Product Price</label>
            <input type="number" name="price" step=".01" class="form-control" value="<?php echo $price; ?>">
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
    </form>
</body>