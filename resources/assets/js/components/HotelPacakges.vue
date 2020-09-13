<template>
    <div class="row">
            <div class="col-md-12" v-if="$gate.isAdmin()">
                <div class="card">
              <div class="card-header">
                <h3 class="card-title">Hotel Packages</h3>
                <div class="card-tools">
                  <button class="btn btn-success" @click="newModal()"><i class="fas fa-plus"></i> Add New</button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Price</th>
                      <th>Duration</th>
                      <th>Date</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="packge in packages.data" :key="packge.id">
                      <td>{{packge.id}}</td>
                      <td>{{packge.hotel_name | capitalize}}</td>
                      <td>RM {{packge.price}}</td>
                      <td>{{packge.duration}}</td>
                      <td>{{packge.created_at | dateformat}}</td>
                      <td>
                          <a href="#"  @click="editModal(packge)"><i class="fa fa-edit text-blue"></i></a>&nbsp;/ &nbsp;
                          <a href="#" @click="deletePackage(packge.id)"><i class="fa fa-trash text-red"></i></a>
                      </td>
                    </tr>
                    
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="packages" @pagination-change-page="getresults"></pagination>
              </div>    
            </div>
            </div>

            <div class="col-md-12"  v-if="!$gate.isAdmin()">
                <notfound></notfound>
            </div>
            

            <!-- Modal -->
            <div class="modal fade" id="addpackageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" v-show="!editMode">Add New</h5>
                    <h5 class="modal-title" id="exampleModalLabel" v-show="editMode">Update</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editMode ? UpdatePackage() : createPackage()">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Hotel Name</label>
                        <input v-model="form.hotel_name" type="text" name="hotel_name" id="hotel_name" placeholder="Enter hotel name" class="form-control" :class="{ 'is-invalid': form.errors.has('hotel_name') }">
                        <has-error :form="form" field="hotel_name"></has-error>
                    </div>
                    <div class="form-group">
                        <label>Price</label>
                        <input v-model="form.price" type="text" name="price" id="price" placeholder="Enter price" class="form-control" :class="{ 'is-invalid': form.errors.has('price') }">
                        <has-error :form="form" field="price"></has-error>
                    </div>
                    <div class="form-group">
                        <label>The validity duration of the package</label>
                        <input v-model="form.package_validity" type="text" name="package_validity" id="package_validity" placeholder="Enter package validity" class="form-control" :class="{ 'is-invalid': form.errors.has('package_validity') }">
                        <has-error :form="form" field="package_validity"></has-error>
                    </div>
                    <div class="form-group">
                        <label>Duration</label>
                        <input v-model="form.duration" type="text" name="duration" id="duration" placeholder="Enter Duration" class="form-control" :class="{ 'is-invalid': form.errors.has('duration') }">
                        <has-error :form="form" field="duration"></has-error>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea v-model="form.description" name="description" id="description" placeholder="Enter description" class="form-control" :class="{ 'is-invalid': form.errors.has('description') }"></textarea>
                        <has-error :form="form" field="description"></has-error>
                    </div>
                    <div class="form-group">
                        <label>Photo</label>
                        <input @change="updatePhoto" type="file" name="photo" placeholder="Select Photo"  :class="{ 'is-invalid': form.errors.has('photo') }">
                        <has-error :form="form" field="photo"></has-error>
                        <img class="img-fluid" :src="getPhoto()" alt='Photo' width="100%">
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
                packages:{},
                form:new Form({
                    id:'',
                    hotel_name:'',
                    price:'',
                    package_validity:'',
                    duration:'',
                    description:'',
                    photo:''
                })
            }
        },
        methods:{
            updatePhoto(e){
                this.$Progress.start();
                let file = e.target.files[0];
                let reader = new FileReader();
                
                if(file['size'] < 211175){
                    reader.onloadend = (file) => {
                        this.form.photo = reader.result;
                    }
                    reader.readAsDataURL(file);
                } else {
                    swal.fire(
                        'Error!',
                        'You are uploading large file.',
                        'warning'
                    )
                    this.$Progress.fail(); 
                }
            },
            getPhoto(){
                let photo = (this.form.photo.length > 200) ? this.form.photo : 'img/hotel/'+this.form.photo;
                return photo;
            },
            createPackage(){
                this.$Progress.start();
                this.form.post('api/hotelpackage')               
                .then(()=>{
                    $('#addpackageModal').modal('hide');
                    toast.fire({
                        icon: 'success',
                        title: 'Package created successfully'
                    })
                    Fire.$emit('AfterCrate');
                    this.$Progress.finish();    
                })
                .catch(()=>{
                    this.$Progress.fail();  
                });
            },
            loadPackages(){
                if(this.$gate.isAdmin()){
                    this.$Progress.start();
                    axios.get('api/hotelpackage').then(({data}) => (this.packages = data));
                    this.$Progress.finish();
                }
            },
            deletePackage(id){
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
                            this.form.delete('api/hotelpackage/'+id).then(()=>{
                                Fire.$emit('AfterCrate');
                                swal.fire(
                                    'Deleted!',
                                    'Your package has been deleted.',
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
            UpdatePackage(){
                this.$Progress.start();
                this.form.put('api/hotelpackage/'+this.form.id)
                .then(()=>{
                    $('#addpackageModal').modal('hide');
                    Fire.$emit('AfterCrate');
                    swal.fire(
                        'Updated!',
                        'Package updated successfully.',
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
                $('#addpackageModal').modal('show');
            },
            editModal(packge){
                this.editMode = true,
                this.form.reset();
                this.form.fill(packge);
                $('#addpackageModal').modal('show');
            },
            getresults(page = 1){
                this.$Progress.start();
                axios.get('api/hotelpackage?page='+page).then(({data}) => (this.packages = data));
                this.$Progress.finish();
            }
        },
        created() {
            this.loadPackages();
            Fire.$on('AfterCrate',()=>{this.loadPackages()});
        }
    }
</script>
