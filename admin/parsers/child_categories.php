<?php
/**
 * Created by PhpStorm.
 * User: AbdeAMNR
 * Date: 06/03/2017
 * Time: 02:26
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/amnrStore2017/core/init.php';
$parentID =(int)$_POST['parentID'];
$childQuery = $db->query("SELECT * FROM categories WHERE parent=$parentID ORDER BY  category");

ob_start();
?>
<option value=""></option>
<?php while ($child = mysqli_fetch_assoc($childQuery)): ?>
    <option value="<?= $child['id'];?>"><?= $child['category'] ;?></option>
<?php endwhile;?>
<?php echo ob_get_clean();?>

