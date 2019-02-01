<?php
require_once '../core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';
//get brands from db
$reqt = "SELECT * FROM brand";
$sqlResult = mysqli_query($db, $reqt);
$errors = array();
/*--------------modify Brand--------------*/
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_id = sanitize($_GET['edit']);
    $sql2 = "SELECT *FROM brand WHERE id=$edit_id";
    $edit_result = $db->query($sql2);
    $eBrand = mysqli_fetch_assoc($edit_result);
}
/*----------------------------------------*/
/*--------------Delete Brand--------------*/
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    $delete_id = sanitize($delete_id);
    $requtte = "delete from brand WHERE id= $delete_id";
    mysqli_query($db, $requtte);
    header('Location: brands.php');
}
/*----------------------------------------*/

//if add form is submitted
if (isset($_POST['add_submit'])) {
    $brand = sanitize($_POST['brand']);
    //check if brand is blank
    if ($_POST['brand'] == '') {
        $errors[] .= 'You must enter a brand';
    }
    //check if brand exist in db
    $selectSQL = "SELECT * FROM brand WHERE brand ='$brand'";
    if (isset($_GET['edit'])) {
        $selectSQL = "SELECT * FROM brand WHERE brand ='$brand' AND id != $edit_id";
    }
    $result = mysqli_query($db, $selectSQL);
    $count = mysqli_num_rows($result);
    if ($count > 0) {
        $errors[] .= $brand . ' already exists. Please choose another brand name';
    }
    //display errors
    if (!empty($errors)) {
        echo display_errors($errors);
    } else {
        //add brand to database

        if (isset($_GET['edit'])) {
            $sql = "UPDATE brand SET brand ='$brand' WHERE id ='$edit_id'";
            $db->query($sql);
            // header('location: brands.php');
            ?>
            <script>
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "preventDuplicates": true
                }
                toastr.success('La modification a été effectuer correctement.', 'Fait avec succès', {timeOut: 5000});
            </script>

            <?php
        } else {
            $sql = "INSERT INTO brand (brand) VALUE ('$brand')";
            $db->query($sql);
            //     header('location: brands.php');
            ?>
            <script>
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "preventDuplicates": true
                }
                toastr.success('Une nouvelle marque a été ajoutée avec succès.', 'Fait avec succès', {timeOut: 5000});
            </script>

            <?php
        }
    }
}
?>
    <h2 class="text-center">Brands</h2>
    <!-- Brand form -->
    <div class="text-center">
        <form class="form-inline" action="brands.php<?= ((isset($_GET['edit'])) ? '?edit=' . $edit_id : ''); ?>"
              method="post">
            <div class="form-group">
                <?php
                $brand_value = '';
                if (isset($_GET['edit'])) {
                    $brand_value = $eBrand['brand'];
                } else {
                    if (isset($_POST['brand'])) {
                        $brand_value = sanitize($_POST['brand']);
                    }
                }
                ?>
                <label for="brand"><?= ((isset($_GET['edit'])) ? 'Edit' : 'Add a'); ?> brand:</label>
                <input type="text" name="brand" id="brand" class="form-control" value="<?= $brand_value; ?>">
                <!-- Create cancel button to cancel modify brand  -->
                <?php if (isset($_GET['edit'])): ?>
                    <a href="brands.php" class="btn btn-default">Anuller</a>
                <?php endif; ?>

                <input type="submit" name="add_submit" value="<?= ((isset($_GET['edit'])) ? 'Edit' : 'Add'); ?> Brand"
                       class="btn btn-success">
            </div>
        </form>
    </div>
    <hr>
    <script>
        function refrechPage() {
            window.location.assign("http://localhost/amnrStore2017/admin/brands.php")
        }
    </script>
    <div style="text-align: center;">
        <input value="Actualiser" class="btn btn-info " onclick="refrechPage();"/>
    </div>
    <br>

    <table class="table table-bordered table-striped table-fit ">
        <thead>
        <th>Edit</th>
        <th>Brands</th>
        <th>Delete</th>
        </thead>
        <tbody>
        <?php while ($items = mysqli_fetch_assoc($sqlResult)):; ?>
            <tr>
                <td><a href="brands.php?edit=<?= $items['id']; ?>" class="btn btn-xs btn-default"><span
                            class="glyphicon glyphicon-pencil"></span></a></td>
                <td><?= $items['brand']; ?></td>
                <td><a href="brands.php?delete=<?= $items['id']; ?>" class="btn btn-xs btn-default"><span
                            class="glyphicon glyphicon-remove-sign"></span></a></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php include 'includes/footer.php'; ?>