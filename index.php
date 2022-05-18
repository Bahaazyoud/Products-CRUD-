<?php
include 'connection.php';

$search = $_GET['search'] ?? '';
if($search){
    $statement = $connection->prepare("SELECT * FROM products WHERE title LIKE :title");
    $statement->bindValue(':title','%$search%');
}else{
$statement = $connection->prepare('SELECT * FROM products');
}
$statement->execute();
$products = $statement->fetchAll(PDO::FETCH_OBJ);

?>



<!doctype html>
<html lang="en">

<head>
    <title>products crud</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS v5.0.2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- css externa link -->
    <link rel="stylesheet" href="index.css" />

</head>

<body>
    <h1>PRODUCT CRUD</h1>
    <p><a href="create.php" class="btn btn-success">Create Product</a></p>
    <form>
    <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Search for product" name="search" value="<?php echo $search ?>">
        <button class="btn btn-outline-secondary" type="subimt" id="button-addon2">Search</button>
    </div>
    </form>
    <table class="table ">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Image</th>
                <th scope="col">Title</th>
                <th scope="col">Price</th>
                <th scope="col">Create Date</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product) : ?>
                <tr>
                    <th scope="row"><?php echo $product->id ?></th>
                    <td><img src="<?php echo $product->image ?>" class="thumb_image"></img></td>
                    <td><?php echo $product->title ?></td>
                    <td><?php echo $product->price ?></td>
                    <td><?php echo $product->create_date ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $product->id ?>" type="button" class="btn btn-sm btn-primary">Edit</a>
                        <form action="delete.php" method="post" style="display: inline-block;">
                            <input type="hidden" name="id" value="<?php echo $product->id ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>