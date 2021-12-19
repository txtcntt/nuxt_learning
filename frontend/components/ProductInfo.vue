<template>
  <div class="modal show fade" id="popup_product_info" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" v-if="editmode">Chỉnh sửa sản phẩm</h5>
          <h5 class="modal-title" v-else>Thêm mới sản phẩm</h5>
        </div>
        <form role="form" @submit.prevent="createProduct()" method="post" enctype="multipart/form-data" id="frmProduct">
          <div class="modal-body pl-3 pr-3">
            <div class="form-group text-danger mb-4 d-flex justify-content-center" v-if="errors.common">
              <span>{{ errors.common }}</span>
            </div>
            <div class="row col-12">
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <label class="control-label">Tên sản phẩm</label>
                            </div>
                            <div class="col-sm-9">
                                <input v-model.trim="form.name" type="text" name="name" class="form-control" :class="{ 'is-invalid no-icon': errors.name }"/>
                                <span v-if="errors.name" class="error invalid-feedback d-inline-block">
                                    {{ errors.name[0] }}
                                </span>
                            </div>
                        </div>
                    </div>            
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <label class="control-label">Giá bán</label>
                            </div>
                            <div class="col-sm-9">
                                <input v-model.trim="form.price" type="number" pattern="[0-9]" name="price" class="form-control" :class="{ 'is-invalid no-icon': errors.price }"/>
                                <span v-if="errors.price" class="error invalid-feedback d-inline-block">
                                    {{ errors.price[0] }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <label class="control-label">Mô tả</label>
                            </div>
                            <div class="col-sm-9">
                                <textarea v-model.trim="form.description" name="description" class="form-control" rows="5"></textarea>
                            </div>
                        </div>
                    </div>            
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <label class="control-label">Trạng thái</label>
                            </div>
                            <div class="col-sm-9">
                                <select class="form-control" v-model="form.status">
                                    <option v-for="(name, key) in status" :value="key" :key="key">
                                    {{ name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="control-label">Hình ảnh</label>                        
                    </div>
                    <div class="form-group">
                        <img v-bind:src="form.image_url| getImagePath(form.id)" class="product-image" v-if="form.image_url" />
                        <img src="/default.png" class="product-image" v-else />
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-primary btn-width btn-sm" @click.prevent="openFileUpload">Upload</button>
                        <button type="button" class="btn btn-primary btn-width btn-sm" @click.prevent="deleteImage(form.id)">Xóa file</button>
                        <input type="text" v-model.trim="form.image_name" class="d-inline form-group" disabled :class="{ 'is-invalid no-icon': errors.image }"/>
                        <span v-if="errors.image" class="error invalid-feedback d-inline-block">
                            {{errors.image[0]}}
                        </span>
                        <input type="file" id="img_file" multiple="" ref="file" name="image" accept="fileTypes" class="d-none" @change.prevent="checkImageUpload">                                         
                    </div>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-width" data-dismiss="modal" @click.prevent="closeModal">Hủy</button>
            <button type="button" class="btn btn-primary btn-width" v-if="editmode" @click.prevent="updateProduct">Lưu</button>
            <button type="button" class="btn btn-primary btn-width" v-else @click.prevent="createProduct">Lưu</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
    props: ['form','editmode'],
    data() {
        return {
            file:'',
            fileTypes: '.png, .jpg, .jpeg',
            fileSize: 2, //2Mb
            fileWidth: 1024, //1024px
            status: {0: 'Ngừng bán', 1: 'Đang bán', 2: 'Hết hàng'},
        };
    },
    methods: {
        /**
         * Create new product info
         */
        async createProduct() {
            await this.$axios.$post("products/store", this.createFormData(),{
                headers: {
                  'Content-Type': 'multipart/form-data'
                }
            })
            .then((data) => {
                this.clearFileUpload();
                this.$emit("update", data.message);                
            })
            .catch(function (error) {
            });      
        },
        /**
         * Update product info
         */
        async updateProduct() {
            await this.$axios.$post("products/save", this.createFormData(),{
                headers: {
                  'Content-Type': 'multipart/form-data'
                }
            })
            .then((data) => {
                this.clearFileUpload();
                this.$emit("update", data.message);
            })
            .catch(function (error) {          
            });      
        },
        /**
         * Close dialog add/edit product
         */
        closeModal(){
            this.clearFileUpload();
            this.$emit("update", '');
        },
        /**
         * Open file upload
         */
        openFileUpload(){
            $('#img_file').click();
        },
        /**
         * Delete product image
         */
        async deleteImage(id){
            if(!this.form.image_url || id == ''){
                this.clearFileUpload();
                return;
            }
            await this.$axios.$post("products/image/delete", {id:id})
                .then(({ data }) => {
                    this.clearFileUpload();
                })
                .catch(function (error) {          
                }
            );
        },
        /**
         * Clear file upload input
         */
        clearFileUpload(){
            $('#img_file').val('');
            this.form.image_url = '';
            this.form.image_name = '';
            this.file = '';
        },
        /**
         * Check image upload: file type, size, 
         */
        checkImageUpload(){
            if (!$('#img_file')[0].files.length){
                this.clearFileUpload();
                return ;
            }
            let vm = this;
            vm.file = this.$refs.file.files[0]; 
            let fileName = vm.file.name;
            let arrFileName = fileName.split('.');
            let extension = arrFileName[arrFileName.length - 1].toLowerCase();
            if(vm.file.type.indexOf('image') == -1 || vm.fileTypes.indexOf(extension) == -1){
                vm.clearFileUpload();
                alert('Chỉ cho phép các file hình có định dạng: ' + vm.fileTypes);
                return ;
            }
            let fileSize = vm.file.size/(1024 * 1024);
            if(fileSize > vm.fileSize){
                alert('Dung lượng file phải nhỏ hơn '+ vm.fileSize+'Mb');
                vm.clearFileUpload();
                return ;
            }

            var img = new Image();
            img.onload = function() {
                if(this.width > vm.fileWidth || this.height > vm.fileWidth){
                    alert('Kích thước hình phải nhỏ hơn '+vm.fileWidth+'px');
                    vm.clearFileUpload();
                }else{
                    vm.form.image_name = fileName;
                    vm.form.image_url = img.src;
                }
            };
            img.onerror = function() {
                alert('Chỉ cho phép các file hình có định dạng: ' + vm.fileTypes);
                vm.clearFileUpload();
            };
            var _URL = window.URL || window.webkitURL;
            img.src = _URL.createObjectURL(vm.file);
        },
        /**
         * Create submit data from form data
         */
        createFormData(){
            let formData = new FormData();
            formData.append('id', this.form.id);
            formData.append('name', this.form.name);
            formData.append('price', this.form.price);
            formData.append('description', this.form.description);
            formData.append('image_name', this.form.image_name);
            formData.append('status', this.form.status);
            formData.append('image', this.file);
            return formData;
        }
    },
    filters: {
        getImagePath: function (value, id) {
            if(value == null || value == 'null' || value == ''){
                return '/default.png';
            }
            if(value.indexOf('blob:') == -1){
                return '/public/products/'+ id + '/' + encodeURI(value);
            }
            return value;
        }
    },  
};
</script>
<style>
    .product-image{
        width: 300px;
        height: 200px;
    }
</style>