@extends('layout.sidenav-layout')
@section('content')
    @if ($user->user_type == 'admin')
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="card px-5 py-5">
                        <div class="row justify-content-between ">
                            <div class="align-items-center col">
                                <h4>Users</h4>
                            </div>
                            <div class="align-items-center col">
                                <button data-bs-toggle="modal" data-bs-target="#create-modal"
                                    class="float-end btn m-0 bg-gradient-primary">Create</button>
                            </div>
                        </div>
                        <hr class="bg-dark " />
                        <table class="table" id="tableData">
                            <thead>
                                <tr class="bg-light">
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>User-Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tableList">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="container">
            <div class="row p-5 text-center">
                <h3 class="text-dark">This Section Only For Admin</h3>
                <p>Editor & Manager Can`t Access this page </p>
            </div>
        </div>
        <script>
            setTimeout(function() {
                window.history.back();
            }, 3000); // 3000 milliseconds = 3 seconds
        </script>
    @endif
    <script>
        getList();


        async function getList() {
            showLoader();
            let res = await axios.get("/list-user");
            hideLoader();

            let tableList = $("#tableList");
            let tableData = $("#tableData");

            tableData.DataTable().destroy();
            tableList.empty();

            res.data.forEach(function(item, index) {
                let row = `<tr>
                    <td>${index+1}</td>
                    <td>${item['firstName']} | ${item['lastName']}</td>
                    <td>${item['email']}</td>
                    <td>${item['mobile']}</td>
                    <td>${item['user_type']}</td>
                    <td>
                        <button data-id="${item['id']}" class="btn editBtn btn-sm btn-outline-success">Edit</button>
                        <button data-id="${item['id']}" class="btn deleteBtn btn-sm btn-outline-danger">Delete</button>
                    </td>
                 </tr>`
                tableList.append(row)
            })

            $('.editBtn').on('click', async function() {
                let id = $(this).data('id');
                await FillUpUpdateForm(id)
                $("#update-modal").modal('show');
            })

            $('.deleteBtn').on('click', function() {
                let id = $(this).data('id');
                $("#delete-modal").modal('show');
                $("#deleteID").val(id);
            })

            new DataTable('#tableData', {
                order: [
                    [0, 'desc']
                ],
                lengthMenu: [5, 10, 15, 20, 30]
            });

        }
    </script>


    @include('components.user.user-create')
    @include('components.user.user-update')
    @include('components.user.user-delete')
@endsection
