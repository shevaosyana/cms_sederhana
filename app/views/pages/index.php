<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card card-body bg-light mt-5">
                    <h2><?php echo $data['title']; ?></h2>
                    <p class="lead"><?php echo $data['description']; ?></p>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <a href="<?php echo URLROOT; ?>/pages/about" class="btn btn-primary">About Us</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?> 