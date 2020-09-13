<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Profile</div>

                    <div class="card-body">
                        <form class="form-horizontal">
                           
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
                                <label>Photo</label>
                                <input @change="updateProfile" type="file" name="photo" placeholder="Select Photo"  :class="{ 'is-invalid': form.errors.has('photo') }">
                                <has-error :form="form" field="photo"></has-error>
                                <img class="img-circle" :src="getProfilePhoto()" alt='User Avatar'>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input v-model="form.password" type="password" name="password" id="password" placeholder="Enter your password" class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                <has-error :form="form" field="password"></has-error>
                            </div>
                            
                            <div class="form-group ">
                                <div class="offset-sm-2 text-right">
                                <button type="submit" @click.prevent="updateInfo" class="btn btn-success">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                form:new Form({
                    id:'',
                    name:'',
                    email:'',
                    password:'',
                    mobile:'',
                    photo:''
                })
            }
        },
        methods:{
            updateProfile(e){
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
            updateInfo(){
                this.$Progress.start();
                this.form.put('api/profile/')
                
                .then(()=>{
                    Fire.$emit('AfterCrate');
                    swal.fire(
                        'Updated!',
                        'Profile updated successfully.',
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
            },
            getProfilePhoto(){
                let photo = (this.form.photo.length > 200) ? this.form.photo : 'img/profile/'+this.form.photo;
                return photo;
            }
        },
        created() {
            this.$Progress.start();
            axios.get('api/profile').then(({data}) => (this.form.fill(data)));
            this.$Progress.finish();
        }
    }
</script>
