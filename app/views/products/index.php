<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="row m-3">
        <div class="col-md-6">
            <h1>Products</h1>
        </div>
    
        <div class="col-md-6">
            <div class="float-end">
                <a href="<?php echo URLROOT; ?>products/add" class="btn btn-primary">
                    <i class="fas fa-pencil-alt"></i> Add Product
                </a>
                <button id="delete" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>

    <div class="row">
        <?php foreach ($data['products'] as $products) : ?>
            <div class="col-4 align-items-stretch d-flex <?php echo $products->SKU; ?>">
                <div class="card card-body mb-3 text-center ">
                    <input class="form-check-input" type="checkbox" name="checkboxName" value="<?php echo $products->SKU; ?>" id="flexCheckDefault">
                    <h4 class="card-title"><?php echo $products->SKU; ?> - <?php echo $products->Name; ?></h4>
                    <p class="fw-bold">Price: <?php echo $products->Price; ?>$</p>
                    <?php $attributes =  json_decode($products->TypeAttributes); ?>
                    <?php foreach ($attributes as  $key=>$val) : ?>
                        <?php foreach($val as $k=>$v): ?>
                            <p><?php  echo $k." : ".$v;  ?></p>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
<?php require APPROOT . '/views/inc/footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
$(document).ready(function() {
    var SKUs = [];
     $(document).on('change','#flexCheckDefault',function() {
        SKUs = [];
        $('input:checked').each(function() {
            SKUs.push($(this).attr("value"));
        });

    });
    $("#delete").click(function(event) {
        if (SKUs.length !== 0){
            var dataList = {}
            dataList['skus'] = SKUs
            $.ajax({
                type: "POST",
                url: "/products/delete",
                data: dataList,
                dataType: "json",
                encode: true,
            }).done(function(data) {
                console.log(data);
                $.each(dataList['skus'], function( i,l ){
                    $("."+l).remove();
                 });
                
            }).fail(function(data) {
                console.log(data.responseText);
            });
            event.preventDefault();
        }
    });
});
 </script>