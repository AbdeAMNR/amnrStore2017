<?php
/**
 * Created by PhpStorm.
 * User: AbdeAMNR
 * Date: 09/02/2017
 * Time: 07:54
 */
?>
</div><br><br>
<!-- footer --->
<div class="col-md-12 text-center"> &copy; Copyright 2013-2017 E-Commerce site PFE 2017</div>


<script>
    jQuery(window).scroll(function () {
        var vscroll = jQuery(this).scrollTop();
        jQuery('#logotext').css({
            "transform": "translate(0px, " + vscroll / 2 + "px)"
        });

        jQuery('#back-flower').css({
            "transform": "translate(" + 0 + vscroll / 5 + "px, -" + vscroll / 12 + "px)"
        });

        jQuery('#fore-flower').css({
            "transform": "translate(0px, -" + vscroll / 2 + "px)"
        });
    });

    function detailsmodal(IQ) {


        $.ajax({
            type: "POST",
            url: '/amnrStore2017/includes/detailsmodal.php',
            data: 'id=' + IQ,
            success: function (data) {
                $('body').append(data);
                $('#details-modal').modal('show', {backdrop: 'static'});
            },
            dataType: 'html',
            error: function () {
                alert("something whent wrong!");
            }
        });

    }
</script>
</body>
</html>

