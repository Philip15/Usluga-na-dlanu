<!doctype html>
<html lang="sr" class="h-100">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!--Bootstrap CSS-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
        
        <!--Custom CSS-->
        <link rel="stylesheet" type="text/css"  href="<?=base_url('css/style.css')?>"/>

        <!--Location picker dependencies-->
        <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src='http://maps.google.com/maps/api/js?sensor=false&libraries=places'></script>
        <script src="<?=base_url('js/locationpicker.jquery.min.js')?>"></script>

        <!--Custom JS-->
        <script src="<?=base_url('js/script.js')?>"></script>

        <link rel="icon" type="image/x-icon" href="<?=base_url('favicon.ico')?>"/>
        <title><?php if(isset($title) && !empty($title)){echo esc($title).' - ';} echo lang('App.title');?></title>

        <!--Additional head tags-->
        <?= $this->renderSection('additionalhead') ?>

</head>
<body class="d-flex flex-column h-100 bg-dark">
        
        <!--Header-->
        <?= $this->include('components/header') ?>

        <!--Content-->
        <?= $this->renderSection('content') ?>

        <!--Footer-->
        <?= $this->include('components/footer') ?>
        
        <!--Custom JS Init-->
        <?php if(isset($jsinit)){echo '<script>'.esc($jsinit).'_Init();</script>';}?>
        
        <!--Bootstrap JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>