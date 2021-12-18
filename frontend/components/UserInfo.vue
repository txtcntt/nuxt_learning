<template>
  <div class="modal show fade" id="popup_user_info" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" v-show="!editmode">Thêm User</h5>
          <h5 class="modal-title" v-show="editmode">Chỉnh sửa User</h5>
        </div>
        <form role="form" @submit.prevent="editmode ? updateUser() : createUser()">
          <div class="modal-body pl-5 pr-5">
            <div class="form-group text-danger mb-4 d-flex justify-content-center" v-if="errors.common">
              <span>{{ errors.common }}</span>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-sm-3">
                  <label class="control-label">Tên</label>
                </div>
                <div class="col-sm-9">
                  <input v-model="form.name" type="text" name="name" class="form-control" :class="{ 'is-invalid no-icon': errors.name }"/>
                  <span v-if="errors.name" class="error invalid-feedback d-inline-block">
                    {{ errors.name[0] }}
                  </span>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-sm-3">
                  <label class="control-label">Email</label>
                </div>
                <div class="col-sm-9">
                  <input v-model="form.email" type="text" name="email" class="form-control" :class="{ 'is-invalid no-icon': errors.email }"/>
                  <span v-if="errors.email" class="error invalid-feedback d-inline-block">
                    {{ errors.email[0] }}
                  </span>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-sm-3">
                  <label class="control-label">Mật khẩu</label>
                </div>
                <div class="col-sm-9">
                  <input v-model="form.password" type="password" name="password" class="form-control" :class="{ 'is-invalid no-icon': errors.password }"/>
                  <span v-if="errors.password" class="error invalid-feedback d-inline-block">
                    {{ errors.password[0] }}
                  </span>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-sm-3">
                  <label class="control-label">Xác nhận</label>
                </div>
                <div class="col-sm-9">
                  <input v-model="form.confirm" type="password" name="confirm" class="form-control" :class="{ 'is-invalid no-icon': errors.confirm }"/>
                  <span v-if="errors.confirm" class="error invalid-feedback d-inline-block">
                    {{ errors.confirm[0] }}
                  </span>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-sm-3">
                  <label class="control-label">Nhóm</label>
                </div>
                <div class="col-sm-9">
                  <select class="form-control" v-model="form.group" placeholder="Select one">
                    <option value="">Chọn nhóm</option>
                    <option v-for="(name, key) in groups" :value="name" :key="key">
                      {{ name }}
                    </option>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-sm-3">
                  <label class="control-label">Trạng thái</label>
                </div>
                <div class="col-sm-9">
                  <div class="icheck-primary">
                    <input type="checkbox" name="status" v-model="form.status"/>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-width" data-dismiss="modal" @click.prevent="closeModal">Hủy</button>
            <button v-show="editmode" type="submit" class="btn btn-primary btn-width">Lưu</button>
            <button v-show="!editmode" type="submit" class="btn btn-primary btn-width">Thêm mới</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["editmode", "groups", "status", "form"],
  data() {
    return {
    };
  },
  methods: {
    createUser() {
        this.$axios.$post("user/store", this.form)
        .then(({ data }) => {
            this.$emit("update", data.message);
        })
        .catch(function (error) {
        
        });
    },
    updateUser() {  
        this.$axios.$post("user/save", this.form)
        .then(({ data }) => {
          this.$emit("update", data.message);
        })
        .catch(function (error) {          
        });
    },
    closeModal(){
    },
  },
};
</script>