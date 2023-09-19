@extends("layout.layout")

@section('style')
<style>
    .select2 {
        width:100%!important;
    }

    
</style>
@endsection

@section('content')
<div class="card">
            <div class="card-header">
                <h2>List Plant</h2>
            </div>
            <div class="card-body">
                <div class="row" style="margin-bottom:10px">
                    <div class="col-lg-2">
                        <button type="button" class="btn btn-primary" data-toggle="modal" id="btn-mdl-plant" data-target="#modal-add-plant">
                            Add Plant
                        </button>   
                    </div>
                    <div class="col">
                        <button type="button" class="btn btn-primary" data-toggle="modal" id="btn-mdl-product" data-target="#modal-add-product">
                            Add Product
                        </button>   
                    </div>
                </div>
                <table id="plant-table" class="table table-responsive table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Plant</th>  
                            <th scope="col" class="text-center">Products</th>
                        </tr>
                    </thead>
                    <tbody id="body-plant">
                        @foreach($listPlant as $row)
                            <tr >
                                <td>{{$row->plant}}</td>
                                <td id="{{$row->plant}}" style ="word-break:break-all;">{{$row->product}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    
                </table>
            </div>
        </div>

            <!-- modal add plant -->
    <div class="modal fade" id="modal-add-plant" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Plant</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-body">
                    <div class="form-group">
                        <label style="font-weight: bold; color: #444444">Kode</label>
                        <input type="text" class="form-control" id="kode" name="kode" required>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: bold; color: #444444">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-save-plant">Save</button>
            </div>
            </div>
        </div>
    </div>
    <!-- modal add plant end -->

    <!-- modal add product -->
    <div class="modal fade" id="modal-add-product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-body">
                    <div class="form-group">
                        <label style="font-weight: bold; color: #444444">Plant</label>
                        <select name="" id="select-plant" class="form-control">
                            <option value="">Select Plant</option>
                            @foreach($plants as $row)
                                <option value="{{$row->id}}">{{$row->kode}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: bold; color: #444444">Name</label>
                        <input type="text" class="form-control" id="product-name" name="productName" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-save-product">Save</button>
            </div>
            </div>
        </div>
    </div>
    <!-- modal add product end -->
@endsection

@section('ajaxquery')
    <script>
        $(document).ready(function(){
            // table = $("#plant-table").DataTable({
            //     order: [[0, 'desc']]
            // });
            $('#select-plant').select2({
                placeholder: 'Select an option',
            });
        })

        $('#btn-mdl-plant').on('click', function(){
            $('#kode').val('');
            $('#name').val('');
            $('#modal-add-plant').modal('toggle');
        })
        $('#btn-mdl-product').on('click', function(){
            $('#select-plant').val('').change();
            $('#product-name').val('');
            $('#modal-add-product').modal('toggle');
            $('#myModal').removeClass('show');
        })

        $('#btn-save-plant').on('click', function(){
            if($('#kode').val() == '' || $('#name').val() == ""){
                swal.fire("Error", 'kode or name must be fill', "error");
                return
            }
            $('#modal-add-plant').modal('toggle');
            $('.modal-backdrop').remove();
            swal.fire({
                    title: "",
                    text: 'Are you sure to add this plant',
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    closeOnConfirm: false,
                    closeOnCancel: false
                }).then(function(isConfirm){
                    if (isConfirm.value) {
                        
                        let kode = $('#kode').val();
                        $.ajax({
                        url: '{{route("addPlant")}}',
                        type: 'POST',
                        data: {
                            '_token': '<?php echo csrf_token() ?>',
                            'kode': kode,
                            'name': $('#name').val(),
                        },
                        success: function (data) {
                            if (data.status=="success"){
                                swal.fire({
                                    title: "Success",
                                    allowEscapeKey:false,
                                    text: "success add plant.",
                                    icon: "success",
                                    confirmButtonText: "OK",
                                    closeOnConfirm: false}).then(function () {
                                        $('#body-plant').append(`
                                        <tr >
                                            <td>`+kode+`</td>
                                            <td id="`+kode+`" style ="word-break:break-all;">-</td>
                                        </tr>
                                        `);
                                        $('#select-plant').append(`<option value="`+data.id+`">`+kode+`</option>`)
                                    });
                            }else{
                                swal.fire("Error", data.msg, "error");
                            }

                        }
                    });
                    }
                    else {
                    // swal.fire("Cancelled", "Your imaginary file is safe :)", "error");
                        swal.close();
                    }
                });
        })

        $('#btn-save-product').on('click', function(){
            if($('#select-plant').val() == '' || $('#product-name').val() == ""){
                swal.fire("Error", 'plant or name must be fill', "error");
                return
            }
            $('#modal-add-product').modal('toggle');
            swal.fire({
                    title: "",
                    text: 'Are you sure to add this product',
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    closeOnConfirm: false,
                    closeOnCancel: false
                }).then(function(isConfirm){
                    if (isConfirm.value) {
                        
                        let kode = $('#select-plant option:selected').text();
                        let name = $('#product-name').val();
                        
                        $.ajax({
                        url: '{{route("addProduct")}}',
                        type: 'POST',
                        data: {
                            '_token': '<?php echo csrf_token() ?>',
                            'id_plant': $('#select-plant').val(),
                            'name': name,
                        },
                        success: function (data) {
                            if (data.status=="success"){
                                swal.fire({
                                    title: "Success",
                                    allowEscapeKey:false,
                                    text: "success add product.",
                                    icon: "success",
                                    confirmButtonText: "OK",
                                    closeOnConfirm: false}).then(function () {
                                        let productList = $('#'+kode).text()
                                        let newProductList = ''
                                        if(productList=='-'){
                                            newProductList = name
                                        }
                                        else{
                                            newProductList = productList+','+name;
                                        }
                                        $('#'+kode).text(newProductList)
                                    });
                            }else{
                                swal.fire("Error", data.msg, "error");
                            }

                        }
                    });
                    }
                    else {
                    // swal.fire("Cancelled", "Your imaginary file is safe :)", "error");
                        swal.close();
                    }
                });
        })
    </script>
@endsection