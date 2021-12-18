<template>
    <div>
        <div class="container">
            <div class="card mb-4 card-border-danger">
                <div class="card-body">
                    <form role="form">
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="search_name">Tên</label>
                            <input v-model="condition.name" @keyup.enter="search" type="text" id="search_name" class="form-control" placeholder="Nhập tên"/>
                        </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="search_email">Email</label>
                            <input v-model="condition.email" @keyup.enter="search" type="email" id="search_email" class="form-control" placeholder="Nhập email"/>
                        </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>Nhóm</label>
                            <select class="form-control" v-model="condition.group">
                            <option value="">Chọn nhóm</option>
                            <option v-for="(name, key) in groups" :value="name" :key="key">
                                {{ name }}
                            </option>
                            </select>
                        </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>Trạng thái</label>
                            <select class="form-control" v-model="condition.status">
                            <option value="">Chọn trạng thái</option>
                            <option v-for="(name, key) in status" :value="key" :key="key">
                                {{ name }}
                            </option>
                            </select>
                        </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="d-flex justify-content-start">
                            <button type="button" class="btn btn-primary" @click="newModal">
                            <span class="btn-label"><i class="fas fa-user-plus"></i></span>
                            Thêm mới
                            </button>
                        </div>
                        </div>
                        <div class="col-lg-8 col-md-6 col-sm-12">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary mr-4" @click="search">
                            <span class="btn-label"><i class="fas fa-search"></i></span>
                            Tìm kiếm
                            </button>
                            <button type="button" class="btn btn-success" @click="clearSearch">
                            <span class="btn-label"><i class="fas fa-cut"></i></span>
                            Xóa tìm
                            </button>
                        </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div v-if="users.total == 0" class="text-center">
                        <p class="text-danger font-weight-bolder">Không có dữ liệu tương ứng với điều kiện tìm kiếm</p>
                    </div>
                    <div class="row" v-if="users.total > 0">
                        <div class="col-sm-12 d-flex" >
                            <div class="col-sm-5 dataTables_info pl-0">Hiển thị từ {{users.from}} ~ {{users.to}} trong tổng số {{users.total}} user</div>
                            <div class="col-sm-7 pr-0">
                                <div style="float:right">
                                <!-- <pagination :data="users" @pagination-change-page="pagination"></pagination> -->
                                </div>
                            </div>
                        </div>
                    <div class="col-sm-12 table-responsive">
                        <table id="tblUsers" class="table table-bordered table-hover" role="grid">
                            <thead class="table-active">
                                <tr role="row" class="table-header-center">
                                <th tabindex="0" rowspan="1" colspan="1">#</th>
                                <th tabindex="1" rowspan="1" colspan="1">Họ tên</th>
                                <th tabindex="2" rowspan="1" colspan="1">Email</th>
                                <th tabindex="3" rowspan="1" colspan="1">Nhóm</th>
                                <th tabindex="4" rowspan="1" colspan="1">Trạng Thái</th>
                                <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr role="row" class="odd" v-for="(user, index) in users.data" :key="user.id">
                                <td>{{ getIndex(index) }}</td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.group_role }}</td>
                                <td>
                                    {{ user.is_active | statusName() }}
                                </td>
                                <td class="text-center">
                                    <div>
                                    <a href="javascript:void(0);" @click="editModal(user)">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="javascript:void(0);"  class="text-danger ml-1 mr-1" @click="deleteUser(user)" >
                                        <i class="fa fa-trash"></i>
                                    </a>
                                    <a href="javascript:void(0);" class="text-dark"  @click="lockUser(user)">
                                        <i class="fa fa-user-lock"></i>
                                    </a>
                                    </div>
                                </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-12 d-flex" >
                        <div class="col-sm-5 dataTables_info pl-0">Hiển thị từ {{users.from}} ~ {{users.to}} trong tổng số {{users.total}} user</div>
                        <div class="col-sm-7 pr-0">
                            <div style="float:right">
                            <!-- <pagination :data="users" @pagination-change-page="pagination"></pagination> -->
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <UserInfo v-bind:editmode="editmode" v-bind:form="form" v-bind:groups="groups" v-bind:status="status" v-on:update="updatePape"></UserInfo>
    </div>
</template>
<script>
import  UserInfo from '@/components/UserInfo'
export default {
    components: {UserInfo},
	middleware: ["auth"],
    data() {
        return {
            editmode: false,
            users: [],
            condition: this.initSearchCondition(),
            form: this.initForm(),
            groups: {admin:'Admin',editor:'Editor',reviewer:'Reviewer'},
            status: {0: 'Tạm ngừng', 1: 'Đang hoạt động'},
            perpage: 10,
        };
    },
    methods: {
        initSearchCondition() {
            return {
                name: "",
                email: "",
                group: "",
                status: "",
                page: 1,
            };
        },
        initForm() {
            return {
                id: "",
                name: "",
                email: "",
                password: "",
                confirm: "",
                group: "",
                status: 1,
            };
        },
        updatePape(e){
            this.pagination(this.condition.page);
            this.form = this.initForm();
            this.modalShow(false); 
        },
        search() {
            this.pagination(1);
        },
        clearSearch() {
            this.condition = this.initSearchCondition();
            this.pagination(1);
        },
        newModal() {
        },
        async pagination(page = 1) {
            this.condition.page = page;
            let {data} = await this.$axios.$post("user/search", this.condition)
            this.users = data
        },
        getIndex(itemIndex) {
            return itemIndex + 1 + (this.condition.page - 1) * this.perpage;
        },
        async deleteUser(user) {            
            if(confirm('Bạn có muốn xóa thành viên ' + user.name + ' không?')){
                let {data} = await this.$axios.$post("user/delete", { id: user.id })
                this.pagination(1);
            }
        },

        async lockUser(user) {
            var confirmMessage = 'Bạn có muốn '+ (user.is_active == 1 ? "khóa" : "mở hóa") +' thành viên ' + user.name + ' không?';
            if(confirm(confirmMessage)){
                var params = {
                    id: user.id,
                    status: user.is_active == 1 ? 0 : 1,
                }
                let {data} = await this.$axios.$post("user/lock", params)
                user.is_active = user.is_active == 1 ? 0 : 1;
            }            
        },

        editModal(user) {
            this.editmode = true;
            this.resetForm();
            this.modalShow(true);
            this.fillForm(user);
        },
        newModal() {
            this.editmode = false;
            this.resetForm();
            this.modalShow(true);
        },
        resetForm() {
            this.form = this.initForm();
        },
        fillForm(user) {
            this.form.id = user.id;
            this.form.name = user.name;
            this.form.email = user.email;
            this.form.group = user.group_role;
            this.form.status = user.is_active;
            this.form.password = '';
            this.form.confirm = '';
        },
        modalShow(isShow) {
        if (isShow) {
            $("#popup_user_info").modal('show');
        } else {
            $("#popup_user_info").modal("hide");
        }
        },
    },
    filters: {
        statusName: function (value) {
            return value == 1? 'Đang hoạt động' : 'Tạm ngưng';
        },
    }
};
</script>