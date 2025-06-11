<?php
include '../connection.php';
admin_require_login();

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category_id = $_POST['category_id'];
    
    $image = $_FILES['image']['name'];
    $imageTmp = $_FILES['image']['tmp_name'];
    $imagePath = "../assets/product/" . $image;

    if (move_uploaded_file($imageTmp, $imagePath)) {
        $query = mysqli_query($conn, "INSERT INTO products VALUES (uuid(), '$name', '$image', '$description', '$price', '$stock', '$category_id')");
        
        if ($query) {
            $_SESSION['success'] = "Produk berhasil ditambahkan.";
            header("Location: products.php");
            exit();
        } else {
            $_SESSION['error'] = "Gagal menambahkan produk.";
            header("Location: add_edit_product.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Gagal mengunggah gambar.";
        header("Location: add_edit_product.php");
        exit();
    }
}

$editData = null;
if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    $query = mysqli_query($conn, "SELECT * FROM products WHERE id = '$productId'");
    
    if (mysqli_num_rows($query) > 0) {
        $editData = mysqli_fetch_assoc($query);
    } else {
        $_SESSION['error'] = "Produk tidak ditemukan.";
        header("Location: products.php");
        exit();
    }
}

if (isset($_POST['update'])) {
    $productId = $_POST['product_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category_id = $_POST['category_id'];

    $oldImage = $_POST['old_image'];
    $image = $_FILES['image']['name'];
    $imageTmp = $_FILES['image']['tmp_name'];
    $imagePath = "../assets/product/" . $image;

    if ($image) {
        if (move_uploaded_file($imageTmp, $imagePath)) {
            unlink("../assets/product/" . $oldImage);
        } else {
            $_SESSION['error'] = "Gagal mengunggah gambar baru.";
            header("Location: add_edit_product.php?id=$productId");
            exit();
        }
    } else {
        $image = $oldImage;
    }

    $query = mysqli_query($conn, "UPDATE products SET name = '$name', image = '$image', description = '$description', price = '$price', stock = '$stock', category_id = '$category_id' WHERE id = '$productId'");
    if ($query) {
        $_SESSION['success'] = "Produk berhasil diperbarui.";
        header("Location: products.php");
        exit();
    } else {
        $_SESSION['error'] = "Gagal memperbarui produk.";
        header("Location: add_edit_product.php?id=$productId");
        exit();
    }
}

include '../templates/template_admin.php';
header_navbar('Tambah Produk');
?>

<center>
    <h1 class="mt-5">Tambah Data Produk</h1>
</center>

<?php
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . $_SESSION['error'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    unset($_SESSION['error']);
}
?>

<form method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="name" class="form-label">Nama Produk</label>
        <input type="text" class="form-control" id="name" name="name" value="<?= $editData['name'] ?? '' ?>" required>
    </div>
    <div class="mb-3">
        <?php
        if (isset($editData['image'])) { ?>
            <img src="../assets/product/<?= $editData['image']?>" alt="<?= $editData['image'] ?>" class="img-thumbnail mb-3" style="max-width: 200px;"><br>
            <span class="text-secondary fst-italic">Note. Jangan pilih gambar jika tidak ingin memperbarui gambar.</span><br>
        <?php } ?>
        <label for="image" class="form-label">Gambar</label>
        <input type="file" class="form-control" id="image" name="image" accept=".jpg,.jpeg,.png" <?= isset($editData['image']) ? '': 'required' ?> >
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Deskripsi</label>
        <textarea class="form-control" id="description" name="description" rows="4" required><?= $editData['description'] ?? '' ?></textarea>
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Harga/hari</label>
        <input type="number" class="form-control" id="price" name="price" value="<?= $editData['price'] ?? '' ?>" required>
    </div>
    <div class="mb-3">
        <label for="stock" class="form-label">Jumlah Stok</label>
        <input type="number" class="form-control" id="stock" name="stock" value="<?= $editData['stock'] ?? '' ?>" required>
    </div>
    <div class="mb-3">
        <label for="category_id" class="form-label">Kategori</label>
        <select class="form-select" id="category_id" name="category_id" required>
            <?php
            $categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY name ASC");
            while ($category = mysqli_fetch_assoc($categories)) { ?>
                <option value="<?= $category['id'] ?>" <?php if(isset($editData)) { if($editData['category_id'] == $category['id']) {echo 'selected';} } ?> ><?= $category['name']?></option>
            <?php } ?>
        </select>
    </div>
    <?php
    if (isset($editData)) { ?>
        <input type="hidden" name="old_image" value="<?= $editData['image'] ?>">
        <input type="hidden" name="product_id" value="<?= $editData['id'] ?>">
        <button type="submit" name="update" class="btn btn-primary"><i class="fa-solid fa-pen-to-square"></i> Perbarui</button>
    <?php } else { ?>
        <button type="submit" name="submit" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Simpan</button>
    <?php } ?>
</form>

<?php
footer();
?>