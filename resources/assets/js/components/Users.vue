<template>
    <div class="row">
            <div class="col-md-12" v-if="$gate.isAdmin()">
                <div class="card">
              <div class="card-header">
                <h3 class="card-title">Users Management</h3>
                <div class="card-tools">
                  <button class="btn btn-success" @click="newModal()"><i class="fas fa-user-plus"></i> Add New</button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>User</th>
                      <th>Email</th>
                      <th>Mobile</th>
                      <th>Date</th>
                      <th>Type</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="user in users.data" :key="user.id">
                      <td>{{user.id}}</td>
                      <td>{{user.name | capitalize}}</td>
                      <td>{{user.email}}</td>
                      <td>{{user.mobile}}</td>
                      <td>{{user.created_at | dateformat}}</td>
                      <td>{{user.type | capitalize}}</td>
                      <td>
                          <a href="#"  @click="editModal(user)"><i class="fa fa-edit text-blue"></i></a>&nbsp;/ &nbsp;
                          <a href="#" @click="deleteUser(user.id)"><i class="fa fa-trash text-red"></i></a>
                      </td>
                    </tr>
                    
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="users" @pagination-change-page="getresults"></pagination>
              </div>    
            </div>
            </div>

            <div class="col-md-12"  v-if="!$gate.isAdmin()">
                <notfound></notfound>
            </div>
            

            <!-- Modal -->
            <div class="modal fade" id="adduserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" v-show="!editMode">Add New User</h5>
                    <h5 class="modal-title" id="exampleModalLabel" v-show="editMode">Update  User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editMode ? UpdateUser() : createUser()">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input v-model="form.name" type="text" name="name" id="name" placeholder="Enter name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                        <has-error :form="form" field="name"></has-error>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input v-model="form.email" type="text" name="email" placeholder="Email" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                        <has-error :form="form" field="email"></has-error>
                    </div>
                    <div class="form-group">
                        <label>Mobile no.:</label>
                        <input v-model="form.mobile" type="text" name="mobile" placeholder="Mobile number"  class="form-control" :class="{ 'is-invalid': form.errors.has('mobile') }">
                        <has-error :form="form" field="mobile"></has-error>
                    </div>
                    <div class="form-group">
                        <label>Type</label>
                        <select name="type" v-model="form.type" id='type' placeholder="Select Type" class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">    
                            <option value="admin">Admin</option>
                        </select>
                        <has-error :form="form" field="type"></has-error>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input v-model="form.password" type="password" name="password" id="password" placeholder="Enter your password" class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                        <has-error :form="form" field="password"></has-error>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" v-show="!editMode">Create</button>
                    <button type="submit" class="btn btn-success" v-show="editMode">Update</button>
                </div>
                </form>
                </div>
            </div>
            </div>
        </div>
</template>

<script>
    export default {
        data(){
            return {
                editMode:false,
                users:{},
                form:new Form({
                    id:'',
                    name:'',
                    email:'',
                    password:'',
                    mobile:'',
                    type:''
                })
            }
        },
        methods:{
            createUser(){
                this.$Progress.start();
                this.form.post('api/user')               
                .then(()=>{
                    $('#adduserModal').modal('hide');
                    toast.fire({
                        icon: 'success',
                        title: 'User created successfully'
                    })
                    Fire.$emit('AfterCrate');
                    this.$Progress.finish();    
                })
                .catch(()=>{
                    this.$Progress.fail();  
                });
            },
            loadUsers(){
                if(this.$gate.isAdmin()){
                    this.$Progress.start();
                    axios.get('api/user').then(({data}) => (this.users = data));
                    this.$Progress.finish();
                }
            },
            deleteUser(id){
                swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if(result.value){
                            //send delete request
                            this.form.delete('api/user/'+id).then(()=>{
                                Fire.$emit('AfterCrate');
                                swal.fire(
                                    'Deleted!',
                                    'Your file has been deleted.',
                                    'success'
                                )
                            })
                            .catch(()=>{
                                swal.fire(
                                    'Failed!',
                                    'There is something wrong.',
                                    'warning'
                                )
                                this.$Progress.fail(); 
                            });
                        }
                });
            },
            UpdateUser(){
                this.$Progress.start();
                this.form.put('api/user/'+this.form.id)
                .then(()=>{
                    $('#adduserModal').modal('hide');
                    Fire.$emit('AfterCrate');
                    swal.fire(
                        'Updated!',
                        'User updated successfully.',
                        'success'
                    )
                    this.$Progress.finish();  
                })
                .catch(()=>{
                    swal.fire(
                        'Failed!',
                        'There is something wrong.',
                        'warning'
                    )
                    this.$Progress.fail(); 
                });
                console.log('Call update');
            },
            newModal(){
                this.editMode = false,
                this.form.reset();
                this.form.clear();
                $('#adduserModal').modal('show');
            },
            editModal(user){
                this.editMode = true,
                this.form.reset();
                this.form.fill(user);
                $('#adduserModal').modal('show');
            },
            getresults(page = 1){
                this.$Progress.start();
                axios.get('api/user?page='+page).then(({data}) => (this.users = data));
                this.$Progress.finish();
            }
        },
        created() {
            this.loadUsers();
            Fire.$on('AfterCrate',()=>{this.loadUsers()});
        }
    }
</script>
