<?= $this->extend('layouts/defaultLayout') ?>

<?= $this->section('additionalhead') ?>
<!--Location picker dependencies-->
<script type="text/javascript" src='http://maps.google.com/maps/api/js?sensor=false&libraries=places'></script>
<script src="<?=base_url('js/locationpicker.jquery.min.js')?>"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="p-0 bg-white">
    <img src="<?=base_url('res/placeholder-banner.jpg')?>" class="img-fluid mx-auto d-block"/>
</section>

<div class="d-flex">
    <aside class="d-flex flex-column flex-shrink-0 p-3 border-end text-light border-light w-280px">
        <h5><?=lang('App.serviceCategories')?>:</h5>
        <ul class="flex-column nav" id="categories">
            <li class="nav-item">
                <button type="button" class="btn nav-link text-light bg-dark w-100" value="-1" onclick="onClick_Category(this)"><?=lang('App.all')?></button>
            </li>
            <?php
                foreach ($categories as $category) 
                {
                    echo '<li class="nav-item">';
                    echo '<button type="button" class="btn nav-link text-light bg-dark w-100" value="'.esc($category->idKategorije).'" onclick="onClick_Category(this)">'.esc(ucfirst($category->naziv)).'</button>';
                    echo '</li>';
                }
            ?>
        </ul>
        <hr>
        <form class="py-2">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="dateFilter" onchange="onChange_DateFilter()">
                <label class="form-check-label" for="flexCheckDefault"><?=lang('App.filterByAvailability')?></label>
            </div>
            <div class="container dnone" id="timerSelector">
                <div class="row mt-1 gy-1">
                    <label class="col-1 col-form-label form-label px-0" for="timeFrom"><?=lang('App.from')?>:</label>
                    <div class="col-5 px-2">
                        <input class="form-control px-04rem" type="time" id="timeFrom">
                    </div>
                    <label class="col-1 col-form-label form-label px-0" for="timeTo"><?=lang('App.to')?>:</label>
                    <div class="col-5 px-2">
                        <input class="form-control px-04rem" type="time" id="timeTo">
                    </div>
                    <label class="col-1 col-form-label form-label px-0" for="dateFrom"><?=lang('App.from')?>:</label>
                    <div class="col-11 px-2">
                        <input class="form-control px-04rem" type="date" id="dateFrom">
                    </div>
                    <label class="col-1 col-form-label form-label px-0" for="dateTo"><?=lang('App.to')?>:</label>
                    <div class="col-11 px-2">
                        <input class="form-control px-04rem" type="date" id="dateTo">
                    </div>
                    <div class="col-12 ps-0 pe-2 mt-2">
                        <button class="btn btn-secondary w-100" type="button" onclick="onClick_DateFilter()"><?=lang('App.search')?></button>
                    </div>
                    
                </div>  
            </div>
        </form>
        <hr>
        <form class="py-2">
            <div class="container mb-3" id="sortSelector">
                <div class="row gy-1">
                    <label class="col-4 col-form-label form-label px-0" for="sortSelect"><?=lang('App.sortBy')?>:</label>
                    <div class="col-8 p-0">
                        <select class="form-select" id="sortSelect" onchange="onChange_SortSelect()">
                            <option value="0" selected><?=lang('App.rating')?></option>
                            <option value="1"><?=lang('App.distance')?></option>
                        </select>
                    </div>
                </div>
            </div>
            <div id="locationPicker" class="dnone" style="width: 100%; height: 198px;"></div>
        </form>
    </aside>

    <main class="w-100 mx-4 my-4" >
        <div class="row" id="cardContainer">
            
        </div>    
    </main>

</div>

<?= $this->endSection() ?>
