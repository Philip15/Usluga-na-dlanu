<!--
  * @author Lazar Premović  2019/0091
-->

<?= $this->extend('layouts/defaultLayout') ?>

<?= $this->section('content') ?>

<?= $this->include('components/requestInfo') ?>
<?= $this->include('components/newReservedSlot') ?>

<div class="m-5 d-flex justify-content-center">
    <div class="rounded bg-light p-4">
        <?= $this->include('components/calendar') ?>
    </div>
</div>

<?= $this->endSection() ?>
