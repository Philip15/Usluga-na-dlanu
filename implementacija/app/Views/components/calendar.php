<!--
  * @author Lazar Premović  2019/0091
-->

<div class="row">
    <div class="col-auto p-3 bg-light">
        <div class="">
            <table class="table table-bordered bg-white m-0" id="calendarMonth">
                <?php
                    echo '<thead><tr><th class="p-4"><i class="bi bi-caret-left-fill"></i></th><th class="p-4 text-center" colspan=5>PLACEHOLDER</th><th class="p-4"><i class="bi bi-caret-right-fill"></i></th></tr></thead>';
                    echo '<tbody>';
                    for ($i=0; $i < 6; $i++) { 
                        echo '<tr>';
                        for ($j=0; $j < 7; $j++) { 
                            echo '<td class="p-4 text-center align-middle">XX</td>';
                        }
                        echo '</tr>';
                    }
                    echo '</tbody>';
                ?>
            </table>
        </div>
    </div>
    <div class="<?=($calendaranon??'false')=='true'?'col-auto':'w-500px'?> p-3 bg-light">
        <table class="table table-bordered bg-white m-0" id="calendarDayHeader">
            <thead><tr><th class="p-4 text-center" colspan=2>PLACEHOLDER</th></tr></thead>
        </table>
        <div class="h-438 scroll-y">
            <table class="table table-bordered bg-white m-0" id="calendarDay">
                <?php
                    
                    echo '<tbody>';
                    for ($i=0; $i < 24; $i++) { 
                        echo $i%2==0 ? '<tr class="bbd">' : '<tr class="btd">';
                            echo '<td class="fs-6 py-0 px-1 fw-lighter">'.str_pad(intval($i/2)+8,2,'0',STR_PAD_LEFT).':'.(3*($i%2)).'0</td>';
                            echo '<td class="fs-6 py-0 px-5 fw-lighter" index="'.esc($i).'"></td>';
                        echo '</tr>';
                    }
                    echo '</tbody>';
                ?>
            </table>
        </div>
    </div>
</div>