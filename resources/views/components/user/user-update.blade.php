<div class="modal animated zoomIn" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update User</h5>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="firstNameUpdate">

                                <label class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="lastNameUpdate">

                                <label class="form-label">User Email *</label>
                                <input type="text" class="form-control" id="userEmailUpdate">

                                <label class="form-label">User Mobile *</label>
                                <input type="text" class="form-control" id="userMobileUpdate">

                                <label class="form-label">User Password *</label>
                                <input type="password" class="form-control" id="userPasswordUpdate">

                                <label class="form-label">User Type *</label>
                                <select name="" id="userTypeUpdate" class="form-control">
                                    <option value="admin">admin</option>
                                    <option value="manager">manager</option>
                                    <option value="editor">editor</option>
                                </select>

                                <input type="text" class="d-none" id="updateID">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="update-modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal"
                    aria-label="Close">Close</button>
                <button onclick="Update()" id="update-btn" class="btn bg-gradient-success">Update</button>
            </div>
        </div>
    </div>
</div>


<script>
    async function FillUpUpdateForm(id) {
        document.getElementById('updateID').value = id;
        showLoader();
        let res = await axios.post("/user-by-id", {
            id: id
        })
        hideLoader();
        document.getElementById('firstNameUpdate').value = res.data['firstName'];
        document.getElementById('lastNameUpdate').value = res.data['lastName'];
        document.getElementById('userEmailUpdate').value = res.data['email'];
        document.getElementById('userMobileUpdate').value = res.data['mobile'];
        document.getElementById('userPasswordUpdate').value = res.data['user_type'];
        document.getElementById('userTypeUpdate').value = res.data['user_type'];
    }


    async function Update() {

        let firstName = document.getElementById('firstNameUpdate').value;
        let lastName = document.getElementById('lastNameUpdate').value;
        let userEmail = document.getElementById('userEmailUpdate').value;
        let userMobile = document.getElementById('userMobileUpdate').value;
        let userPassword = document.getElementById('userPasswordUpdate').value;
        let userType = document.getElementById('userTypeUpdate').value;
        let updateID = document.getElementById('updateID').value;


        if (firstName.length === 0) {
            errorToast("User First Name Required !")
        }
        else if(lastName.length===0){
            errorToast("User Last Name Required !")
        }
        else if(userEmail.length===0){
            errorToast("User Email Required !")
        }
        else if(userMobile.length===0){
            errorToast("User Mobile Required !")
        }
        else if(userPassword.length===0){
            errorToast("User Passuser Required !")
        }
        else {
            document.getElementById('update-modal-close').click();
            showLoader();
            let res = await axios.post("/user-edit-data", {
                id: updateID,
                firstName: firstName,
                lastName: lastName,
                email: userEmail,
                mobile: userMobile,
                password: userPassword,
                user_type: userType,
            })

            hideLoader();
            if(res.status === 200 && res.data === 1) {
                successToast('Request completed');
                document.getElementById("update-form").reset();
                await getList();
            } else {
                errorToast("Request fail !")
            }
        }
    }
</script>
