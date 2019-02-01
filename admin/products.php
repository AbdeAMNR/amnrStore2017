<?php
/**
 * Created by PhpStorm.
 * User: AbdeAMNR
 * Date: 05/03/2017
 * Time: 23:40
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/amnrStore2017/core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';

if (isset($_GET['add']) || isset($_GET['edit'])) {
    $brandQuery = $db->query("SELECT * FROM brand ORDER BY brand");
    $parentQuery = $db->query("SELECT * FROM categories WHERE parent=0 ORDER BY category");

    $title = ((isset($_POST['title']) && $_POST['title'] != '') ? sanitize($_POST['title']) : '');
    $drand = ((isset($_POST['drand']) && !empty($_POST['drand'])) ? sanitize($_POST['drand']) : '');
    $parent = ((isset($_POST['parent']) && !empty($_POST['parent'])) ? sanitize($_POST['parent']) : '');

    if (isset($_GET['edit'])) {
        $edit_id = (int)$_GET['edit'];
        $productResults = $db->query("SELECT * FROM products WHERE id='$edit_id'");
        $product = mysqli_fetch_assoc($productResults);
        $title = ((isset($_POST['title']) && $_POST['title'] != '') ? sanitize($_POST['title']) : $product['title']);
        $drand = ((isset($_POST['brand']) && !empty($_POST['brand'])) ? sanitize($_POST['brand']) : $product['brand']);
        $parent = ((isset($_POST['parent']) && !empty($_POST['parent'])) ? sanitize($_POST['parent']) : $product['parent']);
    }

    if ($_POST) {
        $title=sanitize($_POST['title']);
        $brand=sanitize($_POST['brand']);
        $categories = sanitize($_POST['child']);
        $price = sanitize($_POST['price']);
        $list_price = sanitize($_POST['list_price']);
        $sizes = sanitize($_POST['sizes']);
        $description = sanitize($_POST['description']);
        $dbpath = '';
        $errors = array();


        if (!empty($_POST['sizes'])) {
            $sizeString = sanitize($_POST['sizes']);
            $sizeString = rtrim($sizeString, ',');
            $sizeArray = explode(',', $sizeString);
            $sArray = array();
            $qArray = array();
            foreach ($sizeArray as $ss) {
                $s = explode(':', $ss);
                $sArray[] = $s[0];
                $qArray[] = $s[1];
            }
        } else {
            $sizeArray = array();
        }
        $required = array('title', 'brand', 'price', 'parent', 'child', 'sizes');
        foreach ($required as $field) {
            if ($_POST[$field] == '') {
                $errors[] = 'All Fields with and Astrisk are required.';
                break;
            }
        }
        if (!empty($_FILES)) {
           // var_dump($_FILES);
            $photo = $_FILES['photo'];
            $name = $photo['name'];
            $nameArray = explode('.', $name);
            $fileName = $nameArray[0];
            $fileExt = $nameArray[1];
            $mime = explode('/', $photo['type']);
            $mimeType = $mime[0];
            $mimeExt = $mime[1];
            $tmpLoc = $photo['tmp_name'];
            $fileSize = $photo['size'];
            $allowed = array('png', 'jpg', 'jpeg', 'gif');
            $uploadName = md5(microtime()) . '.' . $fileExt;
            $uploadPath = BASEURL . 'images/products/' . $uploadName;
            $dbpath = '/amnrStore2017/images/products/' . $uploadName;
            if ($mimeType != 'image') {
                $errors[] = 'The file extension must be a png, jpg, jpeg or gif';
            }
            if ($fileSize > 15000000) {
                $errors[] = 'The file siqe must under 15Mb';
            }
            if ($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg')) {
                $errors[] = 'The file extension doen not match the file.';
            }
        }
        if (!empty($errors)) {
            echo display_errors($errors);
        } else {
            //upload file and insert into database
            move_uploaded_file($tmpLoc, $uploadPath);
            $insertSql = "INSERT INTO products('title','price','list_price','brand','categories','sizes','image','description') ";
            $insertSql .= "VALUES ('$title','$price','$list_price','$brand','$categories','$sizes','$dbpath','$description')";
            $db->query($insertSql);
            header('Location : products.php');
        }
    }
    ?>
    <h2 class="text-center"><?= ((isset($_GET['edit'])) ? 'Edit' : 'Add new') ?> product</h2>
    <hr>
    <form action="products.php?<?= ((isset($_GET['edit'])) ? 'edit=' . $edit_id : 'add=1'); ?>"
          method="post" enctype="multipart/form-data">
        <div class="form-group col-md-3">
            <label for="title">Title*:</label>
            <input type="text" name="title" class="form-control" id="title"
                   value="<?= $title; ?>">
        </div>
        <div class="form-group col-md-3">
            <label for="brand">Brand*:</label>
            <select class="form-control" id="brand" name="brand">
                <option value="" <?= ((isset($_POST['brand']) && $_POST['brand'] == '') ? ' selected="selected"' : ''); ?></option>
                <?php while ($brand = mysqli_fetch_assoc($brandQuery)): ?>
                    <option
                            value="<?= $brand['id']; ?>" <?= ((isset($_POST['brand']) && $_POST['brand'] == $brand['id']) ? ' selected' : ''); ?> ><?= $brand['brand']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="parent">Parent category*:</label>
            <select class="form-control" id="parent" name="parent">
                <option
                        value="" <?= ((isset($_POST['parent']) && $_POST['parent'] == '') ? ' selected="selected"' : ''); ?> ></option>
                <?php while ($parent = mysqli_fetch_assoc($parentQuery)): ?>
                    <option
                            value="<?= $parent['id']; ?>" <?= ((isset($_POST['parent']) && $_POST['parent'] == $parent['id']) ? ' select' : ''); ?> ><?= $parent['category']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="child">Child category*:</label>
            <select class="form-control" id="child" name="child">
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="price">Price*:</label>
            <input type="text" class="form-control" id="price" name="price"
                   value="<?= ((isset($_POST['price'])) ? sanitize($_POST['price']) : ''); ?>">
            </input>
        </div>

        <div class="form-group col-md-3">
            <label for="price">list_price*:</label>
            <input type="text" class="form-control" id="list_price" name="list_price"
                   value="<?= ((isset($_POST['list_price'])) ? sanitize($_POST['list_price']) : ''); ?>">
            </input>
        </div>

        <div class="form-group col-md-3">
            <label>Quantity & sizes*:</label>
            <button class="btn btn-default form-control" onclick="jQuery('#sizeModal').modal('toggle');return false;">
                Quantity & sizes
            </button>
        </div>


        <div class="form-group col-md-3">
            <label for="sizes">Sizes & Qty Preview</label>
            <input type="text" class="form-control" id="sizes" name="sizes"
                   value="<?= ((isset($_POST['sizes'])) ? $_POST['sizes'] : ''); ?>" readonly>
            </input>
        </div>


        <div class="form-group col-md-6">
            <label for="photo">Product photo:</label>
            <input type="file" class="form-control" id="photo" name="photo">
            </input>
        </div>

        <div class="form-group col-md-6">
            <label for="description">Description:</label>
            <textarea id="description" name="description" class="form-control"
                      rows="6"><?= ((isset($_POST['description'])) ? sanitize($_POST['description']) : ''); ?></textarea>
        </div>
        <div class="form-group pull-right">
            <a href="products.php" class="btn btn-default">Cancel</a>
            <input type="submit" value="<?= ((isset($_GET['edit'])) ? 'Edit' : 'Add') ?> Product"
                   class="btn btn-success">
        </div>
        <div class="clearfix"></div>

    </form>
    <!-- show up sizes Modal -->
    <div class="modal fade " id="sizeModal" tabindex="-1" role="dialog" aria-labelledby="sizeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="sizeModalLabel">Size & Quantity</h4>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                            <div class="form-group col-md-4">
                                <label for="size<?= $i; ?>">Size:</label>
                                <input type="text" name="size<?= $i; ?>" id="size<?= $i; ?>"
                                       value="<?= ((!empty($sArray[$i - 1])) ? $sArray[$i - 1] : ''); ?>"
                                       class="form-control">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="qty<?= $i; ?>">Quantity:</label>
                                <input type="number" name="qty<?= $i; ?>" id="qty<?= $i; ?>"
                                       value="<?= ((!empty($qArray[$i - 1])) ? $qArray[$i - 1] : ''); ?>"
                                       min="0" class="form-control">
                            </div>

                        <?php endfor; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary"
                            onclick="updateSizes();jQuery('#sizeModal').modal('toggle');return false;">Save changes
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php } else {
    $sql = "SELECT * FROM products WHERE deleted = 0";
    $productResult = mysqli_query($db, $sql);
    if (isset($_GET['featured'])) {
        $id = (int)$_GET['id'];
        $featured = (int)$_GET['featured'];
        $featuredSql = "UPDATE products SET featured = '$featured' WHERE  id ='$id'";
        $db->query($featuredSql);
        header('Location: products.php');
    }

    ?>
    <h2 class="text-center">Products</h2>
    <a href="products.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add Product</a>
    <div class="clearfix"></div>
    <hr>
    <table class="table table-bordered table-condensed table-striped">
        <thead>
        <th></th>
        <th>Product</th>
        <th>Price</th>
        <th>Category</th>
        <th>Featured</th>
        <th>Sold</th>
        </thead>
        <tbody>
        <?php while ($product = mysqli_fetch_assoc($productResult)): ?>
            <?php
            $childID = $product['categories'];
            $catSql = "SELECT * FROM categories WHERE id = $childID";
            $result = $db->query($catSql);
            $child = mysqli_fetch_assoc($result);
            $parentID = $child['parent'];
            $pSql = "SELECT * FROM categories WHERE id= '$parentID'";
            $presult = $db->query($pSql);
            $parent = mysqli_fetch_assoc($presult);
            $category = $parent['category'] . '-' . $child['category'];
            ?>
            <tr>
                <td>
                    <a href="products.php?edit=<?= $product['id']; ?>" class="btn btn-xs btn-default"><span
                                class="glyphicon glyphicon-pencil"></span></a>
                    <a href="products.php?delete=<?= $product['id']; ?>" class="btn btn-xs btn-default"><span
                                class="glyphicon glyphicon-remove"></span></a>
                </td>
                <td><?= $product['title']; ?></td>
                <td><?= money($product['price']); ?></td>
                <td><?= $category; ?></td>
                <td>
                    <a href="products.php?featured=<?= (($product['featured'] == 0) ? '1' : '0'); ?>&id=<?= $product['id']; ?>"
                       class="btn btn-xs btn-default">
                        <span
                                class="glyphicon glyphicon-<?= (($product['featured'] == 1) ? 'minus' : 'plus'); ?>"></span>
                    </a>&nbsp <?= (($product['featured'] == 1) ? 'Featured Product' : '0'); ?></td>
                <td>0</td>

            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php }
include 'includes/footer.php'; ?>