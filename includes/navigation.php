<?php
$sqlQuery = "SELECT * FROM categories WHERE parent=0";
$resutSet = mysqli_query($db, $sqlQuery)
?>

<!-- Top Nav Bar --->
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <a href="/amnrStore2017/index.php" class="navbar-brand">e-commerce site PFE 2017</a>
        <ul class="nav navbar-nav">
            <?php while ($parent = mysqli_fetch_assoc($resutSet)):; ?>
                <?php
                $parentID = $parent['id'];
                $sqlQuery2 = "SELECT * FROM categories WHERE parent={$parentID}";
                $resutSetItems = mysqli_query($db, $sqlQuery2);
                ?>
                <!-- dropdown buttons --->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $parent['category'] ?><span
                            class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <?php while ($subItem = mysqli_fetch_assoc($resutSetItems)):; ?>
                            <li><a href="#"><?php echo $subItem['category'] ?></a></li>
                        <?php endwhile; ?>
                    </ul>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</nav>