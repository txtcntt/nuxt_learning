<template>
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
            
        </div>
        </div>
  </div>
</template>
<script>
export default {
	middleware: ["auth"],
    data() {
        return {
        editmode: false,
        users: {},
        condition: this.initSearchCondition(),
        form: this.initForm(),
        groups: {admin:'Admin',editor:'Editor',reviewer:'Reviewer'},
        status: {0: 'Tạm ngừng', 1: 'Đang hoạt động'},
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
        search() {
        },
        clearSearch() {
        },
        newModal() {
        }
    }
};
</script>