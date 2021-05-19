<?php require APPROOT . '/views/inc/header.php'; ?>
    

    <div class="card card-body bg-light mt-5">
        <h2>Add Product</h2>
        <p>Create a product with this form</p>
        <form name="userForm" id="userForm" method="POST">
        <div id="sku-group" class="mb-3">
          <label class="form-label" for="sku">SKU</label>
          <input
            type="text"
            class="form-control"
            id="sku"
            name="sku"
            placeholder="Unique ID for each product"
          />
        </div>

        <div id="name-group" class="mb-3">
          <label class="form-label" for="name">Name</label>
          <input
            type="text"
            class="form-control"
            id="name"
            name="name"
            placeholder="Product Name"
          />
        </div>
        <div id="price-group" class="mb-3">
          <label class="form-label" for="price">Price </label>
          <input
            type="text"
            class="form-control"
            id="price"
            name="price"
            placeholder="$"
          />
        </div>
        <div id="typeName-group" class="mb-3">
          <label class="form-label" for="typeName">Type Name</label>

          <select class="form-select" id="typeName" name="typeName">
            <option selected value="">Open this select menu</option>
          </select>
        </div>
        <div id="attributes" class="mb-3">
        </div>
        <button type="submit" class="btn btn-success">
          Save
        </button>
        <a href="<?php echo URLROOT; ?>products" class="btn btn-secondary"><i class="fa fa-backward"></i> Cancel</a>
      </form>
    </div>
    
    
<?php require APPROOT . '/views/inc/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
$(document).ready(function() {
    var type = <?php echo json_encode($data['productsType']); ?> ;
    $.each(type, function(i,x) {
      $("#typeName").append("<option value='"+x.Name+"'>"+x.Name+"</option>")
    });
    $('select').on('change', function() {
      $("#attributes").text("")
      var att = (type.find(x => x.Name === this.value)) ? JSON.parse(type.find(x => x.Name === this.value).Attributes) :  [];
      $.each(att, function(i,x) {
        $("#attributes").append("<div id='"+x.name+"-group' class='mb-3'> <label class='form-label' for='"+x.name+"'>"+x.name+"</label> <input type='text' class='form-control' id='"+x.name+"' name='"+x.name+"' placeholder='"+x.description+"'/></div>")
      })
    });
    $("form").submit(function(event) {
        $(".help-block").remove();
        var att = (type.find(x => x.Name === $("#typeName").val())) ? JSON.parse(type.find(x => x.Name === $("#typeName").val()).Attributes) : [];
        var formData = {
            sku: $("#sku").val(),
            name: $("#name").val(),
            price: $("#price").val(),
            typeName: $("#typeName").val(),
        };
        $.each(att, function(i,x) {
          formData[x.name] = $("#"+x.name).val()
        });
        $.ajax({
            type: "POST",
            url: "/products/addProd",
            data: formData,
            dataType: "json",
            encode: true,
        }).done(function(data) {
            console.log(data);
            if (!data.success) {
              $.each(data.errors, function(i,x){
                    $("#"+i+"-group").append('<div class="help-block text-danger">' + x + "</div>");              
              })    
            } else {
                window.location.href = "add.php";
            }
        }).fail(function(data) {
            console.log(data.responseText);
        });
        event.preventDefault();
    });
});
</script>