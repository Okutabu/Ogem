<?php

include "connect.php";
include "control.php";
?>
        <div class="row">
            <div class="col-1">
                <h2>La montre aux super-pouvoirs:</h2>
                <h3>la Rolex GMT-Master Batman</h3>
            </div>
            <div class="col-2">
                    <img src="./images/batman2.png" class="batman">
                    <div class="color-box"></div>
            </div>
        </div>
    </div>

    <div class="container-fluid watch_display_container">
        <div class="row">
        <?php
        display_watch();


        ?>
        </div>
    </div>
 

</body>
