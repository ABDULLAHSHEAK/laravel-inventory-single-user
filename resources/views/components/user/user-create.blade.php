<div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Customer</h5>
                </div>
                <div class="modal-body">
                    <form id="save-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="firstName">

                                <label class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="lastName">

                                <label class="form-label">User Email *</label>
                                <input type="text" class="form-control" id="userEmail">

                                <label class="form-label">User Mobile *</label>
                                <input type="text" class="form-control" id="userMobile">

                                <label class="form-label">User Password *</label>
                                <input type="password" class="form-control" id="userPassword">

                                <label class="form-label">User Type *</label>
                                <select name="" id="userType" class="form-control">
                                    <option value="admin">admin</option>
                                    <option value="manager">manager</option>
                                    <option value="editor">editor</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    <button onclick="Save()" id="save-btn" class="btn bg-gradient-success" >Save</button>
                </div>
            </div>
    </div>
</div>


<script>

    async function Save() {

        let firstName = document.getElementById('firstName').value;
        let lastName = document.getElementById('lastName').value;
        let userEmail = document.getElementById('userEmail').value;
        let userMobile = document.getElementById('userMobile').value;
        let userPassword = document.getElementById('userPassword').value;
        let userType = document.getElementById('userType').value;

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

            document.getElementById('modal-close').click();

            showLoader();
            let res = await axios.post("/create-user",{firstName:firstName,lastName:lastName ,email:userEmail,mobile:userMobile,password:userPassword,userType:userType})
            hideLoader();
            // console.log(res);
            if(res.status===200){

                successToast('Request completed');

                document.getElementById("save-form").reset();

                await getList();
            }
            else{
                errorToast("Request fail !")
            }
        }
    }

</script>
