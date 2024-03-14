@extends('layout.sidenav-layout')
@section('content')
{{-- ------ product list --------  --}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="card px-5 py-5">
                    <div class="row justify-content-between ">
                        <div class="align-items-center col">
                            <h4>Product</h4>
                        </div>
                        <div class="align-items-center col">
                            @if ($user->user_type == 'admin' || $user->user_type == 'manager')
                                <button data-bs-toggle="modal" data-bs-target="#create-modal"
                                    class="float-end btn m-0  bg-gradient-primary">Create</button>
                            @else
                            @endif
                        </div>
                    </div>
                    <hr class="bg-dark " />
                    <table class="table" id="tableData">
                        <thead>
                            <tr class="bg-light">
                                <th>Image</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Created_by</th>
                                <th>Updated_by</th>
                                <th>Stock</th>
                                <th>Action </th>
                            </tr>
                        </thead>
                        <tbody id="tableList">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        getList();


        async function getList() {


            showLoader();
            let res = await axios.get("/list-product");
            hideLoader();

            let tableList = $("#tableList");
            let tableData = $("#tableData");

            tableData.DataTable().destroy();
            tableList.empty();

            res.data.forEach(function(item, index) {
                let row = `<tr>
                    <td><img class="w-15 h-auto" alt="" src="${item['img_url']}"></td>
                    <td>${item['name']}</td>
                    <td>${item['price']}</td>
                    <td>${item['created_by']}</td>
                    <td>${item['updated_by']}</td>
                    <td>${item['stock']}</td>
                    <td>
                        @if ($user->user_type == 'admin')
                        <button data-path="${item['img_url']}" data-id="${item['id']}" class="btn editBtn btn-sm btn-outline-success">Edit</button>
                        <button data-path="${item['img_url']}" data-id="${item['id']}" class="btn deleteBtn btn-sm btn-outline-danger">Delete</button>
                        @elseif ($user->user_type == 'manager')
                        <button data-path="${item['img_url']}" data-id="${item['id']}" class="btn editBtn btn-sm btn-outline-success">Edit</button>
                        @endif
                    </td>
                 </tr>`
                tableList.append(row)
            })

            $('.editBtn').on('click', async function() {
                let id = $(this).data('id');
                let filePath = $(this).data('path');
                await FillUpUpdateForm(id, filePath)
                $("#update-modal").modal('show');
            })

            $('.deleteBtn').on('click', function() {
                let id = $(this).data('id');
                let path = $(this).data('path');

                $("#delete-modal").modal('show');
                $("#deleteID").val(id);
                $("#deleteFilePath").val(path)

            })

            new DataTable('#tableData', {
                order: [
                    [0, 'desc']
                ],
                lengthMenu: [5, 10, 15, 20, 30]
            });

        }
    </script>


    @include('components.product.product-delete')

    {{-- @include('components.product.product-create') --}}
    <div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Product</h5>
                </div>
                <div class="modal-body">
                    <form id="save-form">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 p-1">

                                    <label class="form-label">Category *</label>
                                    <select type="text" class="form-control form-select" id="productCategory">
                                        <option value="">Select Category</option>
                                    </select>

                                    <label class="form-label mt-2">Name *</label>
                                    <input type="text" class="form-control" id="productName">

                                    <label class="form-label mt-2">Price *</label>
                                    <input type="text" class="form-control" id="productPrice">

                                    <label class="form-label mt-2">Stock Quantity *</label>
                                    <input type="text" class="form-control" id="productUnit">

                                    <label class="form-label mt-2">Expire Date </label>
                                    <input type="date" class="form-control" id="expireDate">

                                    <input type="hidden" class="form-control" id="createdBy"
                                        value="{{ $user->firstName }}">

                                    <br />
                                    <img class="w-15" id="newImg" src="{{ asset('images/default.jpg') }}" />
                                    <br />

                                    <label class="form-label">Image</label>
                                    <input oninput="newImg.src=window.URL.createObjectURL(this.files[0])" type="file" class="form-control" id="productImg" value="{{asset('images/default.jpg')}}">

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="modal-close" class="btn bg-gradient-primary mx-2" data-bs-dismiss="modal"
                        aria-label="Close">Close</button>
                    <button onclick="Save()" id="save-btn" class="btn bg-gradient-success">Save</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        FillCategoryDropDown();

        async function FillCategoryDropDown() {
            let res = await axios.get("/list-category")
            res.data.forEach(function(item, i) {
                let option = `<option value="${item['id']}">${item['name']}</option>`
                $("#productCategory").append(option);
            })
        }


        async function Save() {

            let productCategory = document.getElementById('productCategory').value;
            let productName = document.getElementById('productName').value;
            let productPrice = document.getElementById('productPrice').value;
            let productUnit = document.getElementById('productUnit').value;
            let expireDate = document.getElementById('expireDate').value;
            let createdBy = document.getElementById('createdBy').value;
            let productImg = document.getElementById('productImg').files[0];

            if (productCategory.length === 0) {
                errorToast("Product Category Required !")
            } else if (productName.length === 0) {
                errorToast("Product Name Required !")
            } else if (productPrice.length === 0) {
                errorToast("Product Price Required !")
            } else if (productUnit.length === 0) {
                errorToast("Product Stock Required !")
            }
            // else if(!productImg){
            //     errorToast("Product Image Required !")
            // }
            else {

                document.getElementById('modal-close').click();

                let formData = new FormData();
                formData.append('img', productImg)
                formData.append('name', productName)
                formData.append('price', productPrice)
                formData.append('stock', productUnit)
                formData.append('expire_date', expireDate)
                formData.append('created_by', createdBy)
                formData.append('category_id', productCategory)

                const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }

                showLoader();
                let res = await axios.post("/create-product", formData, config)
                hideLoader();

                if (res.status === 201) {
                    successToast('Request completed');
                    document.getElementById("save-form").reset();
                    await getList();
                } else {
                    errorToast("Request fail !")
                }
            }
        }
    </script>


    {{-- @include('components.product.product-update') --}}
    <div class="modal animated zoomIn" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Product</h5>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">


                                <label class="form-label">Category</label>
                                <select type="text" class="form-control form-select" id="productCategoryUpdate">
                                    <option value="">Select Category</option>
                                </select>

                                <label class="form-label mt-2">Name</label>
                                <input type="text" class="form-control" id="productNameUpdate">

                                <label class="form-label mt-2">Price</label>
                                <input type="text" class="form-control" id="productPriceUpdate">

                                <label class="form-label mt-2">Stock Quantity</label>
                                <input type="text" class="form-control" id="productUnitUpdate">

                                <label class="form-label mt-2">Expire Date</label>
                                <input type="date" class="form-control" id="productExpireUpdate">

                                <input type="hidden" class="form-control" id="updatedBy" value="{{$user->firstName}}">

                                <br/>
                                <img class="w-15" id="oldImg" src="{{asset('images/default.jpg')}}"/>
                                <br/>
                                <label class="form-label mt-2">Image</label>
                                <input oninput="oldImg.src=window.URL.createObjectURL(this.files[0])"  type="file" class="form-control" id="productImgUpdate">

                                <input type="text" class="d-none" id="updateID">
                                <input type="text" class="d-none" id="filePath">


                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button id="update-modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                <button onclick="update()" id="update-btn" class="btn bg-gradient-success" >Update</button>
            </div>

        </div>
    </div>
</div>


<script>



    async function UpdateFillCategoryDropDown(){
        let res = await axios.get("/list-category")
        res.data.forEach(function (item,i) {
            let option=`<option value="${item['id']}">${item['name']}</option>`
            $("#productCategoryUpdate").append(option);
        })
    }


    async function FillUpUpdateForm(id,filePath){

        document.getElementById('updateID').value=id;
        document.getElementById('filePath').value=filePath;
        document.getElementById('oldImg').src=filePath;


        showLoader();
        await UpdateFillCategoryDropDown();

        let res=await axios.post("/product-by-id",{id:id})
        hideLoader();

        document.getElementById('productNameUpdate').value=res.data['name'];
        document.getElementById('productPriceUpdate').value=res.data['price'];
        document.getElementById('productUnitUpdate').value=res.data['stock'];
        document.getElementById('productExpireUpdate').value=res.data['expire_date'];
        // document.getElementById('updatedBy').value=res.data['updated_by'];
        document.getElementById('productCategoryUpdate').value=res.data['category_id'];

    }



    async function update() {

        let productCategoryUpdate=document.getElementById('productCategoryUpdate').value;
        let productNameUpdate = document.getElementById('productNameUpdate').value;
        let productPriceUpdate = document.getElementById('productPriceUpdate').value;
        let productUnitUpdate = document.getElementById('productUnitUpdate').value;
        let productExpireUpdate = document.getElementById('productExpireUpdate').value;
        let updatedBy = document.getElementById('updatedBy').value;
        let updateID=document.getElementById('updateID').value;
        let filePath=document.getElementById('filePath').value;
        let productImgUpdate = document.getElementById('productImgUpdate').files[0];


        if (productCategoryUpdate.length === 0) {
            errorToast("Product Category Required !")
        }
        else if(productNameUpdate.length===0){
            errorToast("Product Name Required !")
        }
        else if(productPriceUpdate.length===0){
            errorToast("Product Price Required !")
        }
        else if(productUnitUpdate.length===0){
            errorToast("Product Stock Required !")
        }

        else {

            document.getElementById('update-modal-close').click();

            let formData=new FormData();
            formData.append('img',productImgUpdate)
            formData.append('id',updateID)
            formData.append('name',productNameUpdate)
            formData.append('price',productPriceUpdate)
            formData.append('stock',productUnitUpdate)
            formData.append('expire_date',productExpireUpdate)
            formData.append('updated_by',updatedBy)
            formData.append('category_id',productCategoryUpdate)
            formData.append('file_path',filePath)

            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }

            showLoader();
            let res = await axios.post("/update-product",formData,config)
            hideLoader();

            if(res.status===200 && res.data===1){
                successToast('Request completed');
                document.getElementById("update-form").reset();
                await getList();
            }
            else{
                errorToast("Request fail !")
            }
        }
    }
</script>


@endsection
