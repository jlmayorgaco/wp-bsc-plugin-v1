<?php

    //
    function doRenderAdminCategories(){

            //GROUPS

            // CATEGORIES

            ?>
            <form>
               <tab name="Skin Care">
                    <input type="search">
                    <ul class="collapsed" name="Rutina" *ngFor="">
                        <li> <input type="checkbox"> <label> S </label> </li>
                    </ul>
                    (+) <input type="text" > <submit>
                </tab>
               <tab name="Hair Care"></tab>
               <tab name="Make Up"></tab>
            </form>
            <?php
    }


?>