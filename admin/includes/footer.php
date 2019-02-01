<?php
/**
 * Created by PhpStorm.
 * User: AbdeAMNR
 * Date: 19/02/2017
 * Time: 10:32
 */
?>
</div><br><br>
<!-- footer --->
<div class="col-md-12 text-center"> &copy; Copyright 2013-2017 E-Commerce site PFE 2017</div>

<script>
    function updateSizes() {
        var sizeString = '';
        for (var i = 1; i <= 12; i++) {
            if (jQuery('#size' + i).val() != '') {
                sizeString += jQuery('#size' + i).val() + ':' + jQuery('#qty' + i).val() + ',';
            }
        }
        jQuery('#sizes').val(sizeString);
    }

    function get_child_options() {
        var parentID = jQuery('#parent').val();
        jQuery.ajax({
            url: '/amnrStore2017/admin/parsers/child_categories.php',
            type: 'POST',
            data: {parentID: parentID},
            success: function (data) {
                jQuery('#child').html(data);
            },
            error: function () {
                alert("Something when wrong with the child option.");
            },

        });
    }
    jQuery('select[name="parent"]').change(get_child_options);
</script>
</body>
</html>

