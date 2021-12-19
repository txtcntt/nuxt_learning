<template>
    <div>
        <div class="container">
            <div class="card mb-4 card-border-navy">
                <div class="card-body">
                    <form role="form">
                        <div class="row mb-4">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="search_name">Tên</label>
                                <input v-model.trim="conditions.name" @keyup.enter="search" type="text" id="search_name" 
                                class="form-control" placeholder="Nhập tên" tabindex="1"/>
                            </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Trạng thái</label>
                                <select class="form-control" v-model="conditions.status"  tabindex="2">
                                <option value="">Chọn trạng thái</option>
                                <option v-for="(name, key) in status" :value="key" :key="key">
                                    {{ name }}
                                </option>
                                </select>
                            </div>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12">
                            <div class="form-group d-inline-block">
                                <label for="search_price_from">Giá bán từ</label>
                                <input v-model.trim="conditions.fprice" @keyup.enter="search" type="number" id="search_price_from" 
                                class="form-control" tabindex="3"/>
                            </div>
                            <div class="form-group d-inline-block"><span>~</span></div>
                            <div class="form-group d-inline-block">
                                <label for="search_price_to">Giá bán đến</label>
                                <input v-model.trim="conditions.tprice" @keyup.enter="search" type="number" id="search_price_to" 
                                class="form-control" tabindex="4"/>
                            </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="d-flex justify-content-start">
                                <button type="button" class="btn btn-primary" tabindex="7" @click="openProductInfo(null)">
                                <span class="btn-label"><i class="fas fa-user-plus"></i></span>
                                Thêm mới
                                </button>                
                            </div>
                            </div>            
                            <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-primary mr-4" tabindex="5" @click="search">
                                <span class="btn-label"><i class="fas fa-search"></i></span>
                                Tìm kiếm
                                </button>
                                <button type="button" class="btn btn-success" tabindex="6" @click="clearSearch">
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
                    <div v-if="products.total == 0" class="text-center">
                    <p class="text-danger font-weight-bolder">Không có dữ liệu tương ứng với điều kiện tìm kiếm</p>
                    </div>
                    
                    <div class="row" v-if="products.total > 0"> 
                        <div class="col-sm-12 d-flex" >
                            <div class="col-sm-5 dataTables_info pl-0">Hiển thị từ {{products.from}} ~ {{products.to}} trong tổng số {{products.total}} sản phẩm</div>
                            <div class="col-sm-7 pr-0">
                                <div style="float:right">
                                <Pagination :total="products.last_page" :current="products.current_page" v-on:paging="pagination"></Pagination>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 table-responsive">
                            <table id="tblProducts" class="table table-bordered table-hover" role="grid">
                                <thead class="table-active">
                                    <tr role="row" class="table-header-center">
                                    <th rowspan="1" colspan="1" style="width:8%">#</th>
                                    <th rowspan="1" colspan="1" style="width:12%">Mã sản phẩm</th>
                                    <th rowspan="1" colspan="1" style="width:20%">Tên sản phẩm</th>
                                    <th rowspan="1" colspan="1" style="width:30%">Mô tả</th>
                                    <th rowspan="1" colspan="1" style="width:8%">Giá</th>
                                    <th rowspan="1" colspan="1" style="width:12%">Tình trạng</th>
                                    <th style="width:10%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr role="row" class="odd" v-for="(product, index) in products.data" :key="index">
                                    <td>{{ getIndex(index) }}</td>
                                    <td>
                                        <a href="javascript:void(0);" class="product-hover">
                                        {{ product.product_id }}
                                        <div class="template-image">
                                            <div class="image-tooltip">
                                            <img :src="product.product_image| getImagePath(product.product_id, image_path)" onerror="this.src='/default.png'" />
                                            </div>
                                        </div>
                                        </a>
                                    </td>
                                    <td>
                                        {{ product.product_name }}
                                    </td>
                                    <td>
                                        {{ product.description }}
                                    </td>
                                    <td class="text-right">
                                        ${{ product.product_price }}
                                    </td>
                                    <td>
                                        <span :class="{'text-danger': product.is_sales==0}"> {{ product.is_sales | statusName(status) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div>
                                        <a href="javascript:void(0);" @click="openProductInfo(product)">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="text-danger ml-1" @click="deleteProduct(product)">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                        </div>
                                    </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-12 d-flex" >
                            <div class="col-sm-5 dataTables_info pl-0">Hiển thị từ {{products.from}} ~ {{products.to}} trong tổng số {{products.total}} sản phẩm</div>
                            <div class="col-sm-7 pr-0">
                                <div style="float:right">
                                <Pagination :total="products.last_page" :current="products.current_page" v-on:paging="pagination"></Pagination>
                                </div>
                            </div>
                        </div>                   
                    </div>
                </div>
            </div> 
        </div>
        <!-- Modal -->
        <ProductInfo v-bind:form="form" v-bind:editmode="editmode" v-on:update="updatePape"></ProductInfo>
    </div>
</template>
<script>
import ProductInfo from '@/components/ProductInfo'
import Pagination from '@/components/Pagination'
export default {
    components: {Pagination, ProductInfo},
	middleware: "auth",
    data() {
        return {
            editmode: false,
            products: {},
            form: {},
            status: {0: 'Ngừng bán', 1: 'Đang bán', 2: 'Hết hàng'},
            conditions: this.initSearchCondition(),
            baseUrl: '/products',
            searchUrl: '',
            image_path: '/public/products/'
        };
    },
    methods: {
        /**
         * Search product
         */
        search() {
            this.conditions.page = 1;
            window.location.href = this.generateSearchUrl();
        },
        /**
         * Clear search condition
         */
        clearSearch() {
            this.conditions = this.initSearchCondition();
            window.location.href = this.generateSearchUrl();
        },
        /**
         * Init search condition
         */
        initSearchCondition() {
            return {
                name: '',
                fprice: '',
                tprice:'',
                status: '',
                page: 1,
            };
        },
        /**
         * Get item index
         */
        getIndex(itemIndex) {
            return itemIndex + 1 + (this.conditions.page - 1) * this.products.per_page;
        },
        /**
         * Get products with search condition and paging
         */
        async pagination(pageNumber) {
            this.conditions.page = pageNumber;
            let {data} = await this.$axios.$post("products/search", this.conditions);
            this.products = data;
        },
        /**
         * Reload data after add/edit/delete product
         * and show message
         */
        updatePape(e){
            this.form = {};
            if(e){
                this.pagination(this.conditions.page);
                this.modalShow(false); 
                alert(e);
            }
        },
        /**
         * Create search url from search condition
         */
        generateSearchUrl(){
            var rqParams = [];
            if(this.conditions.name != ''){
                rqParams.push('name='+this.conditions.name);
            }           
            if(['0','1','2'].indexOf(this.conditions.status) != -1){
                rqParams.push('status='+this.conditions.status);
            }
            if(this.conditions.fprice != ''){
                rqParams.push('fprice='+this.conditions.fprice);
            }
            if(this.conditions.tprice != ''){
                rqParams.push('tprice='+this.conditions.tprice);
            }            
            if(rqParams.length > 0){                
                return encodeURI(this.baseUrl + '?' + rqParams.join('&'));
            }else{
                return this.baseUrl;
            }
        },
        /**
         * Delete product info
         */
        async deleteProduct(product) {
            if(confirm('Bạn có muốn xóa sản phẩm ' + product.product_name + ' không?')){
                let {data} = await this.$axios.$post("products/delete", { id: product.product_id })
                this.pagination(1);
            }            
        },
        /**
         * Open dialog add/edit product info
         */
        openProductInfo(product) {
            this.form = this.initForm();
            this.editmode = product != null;
            if(this.editmode){
                this.fillForm(product);
            }
            this.modalShow(true);
        },
        modalShow(isShow) {
            if (isShow) {
                $("#popup_product_info").modal("show");
            } else {
                $("#popup_product_info").modal("hide");
            }
        },
        /**
         * Init product form data. Using for add product
         */
        initForm() {
            return {
                id: '',
                name: '',
                description: '',
                price: '',
                status: '1',
                image_name: '',
                image_url: ''
            };
        },
        /**
         * Fill product info from object to form
         */
        fillForm(product) {
            this.form.id = product.product_id;
            this.form.name = product.product_name;
            this.form.description = product.description;
            this.form.price = product.product_price;
            this.form.status = product.is_sales;
            this.form.image_name = product.product_image;
            this.form.image_url = product.product_image;
        },
    },
    created() {
        let requestParams = this.$route.query;        
        this.conditions.status = requestParams.status ? requestParams.status: '';
        this.conditions.page = requestParams.page ? requestParams.page: 1;
        this.conditions.name = requestParams.name ? requestParams.name: '';
        this.conditions.fprice = requestParams.fprice ? requestParams.fprice: '';
        this.conditions.tprice = requestParams.tprice ? requestParams.tprice: '';        
    },
    async fetch() {
        let conditions= this.conditions;
        let {data} = await this.$axios.$post("products/search", conditions)
        this.products = data
    },
    filters: {
        statusName: function (value, status) {
            if(status[value]){
                return status[value];
            }
            return '';
        },
        getImagePath: function (value, id, path) {
            if(!value || value == null || value == 'null' || value == ''){
                return '/default.png';
            }
            return path + id + '/' + encodeURI(value);
        },
    }
};
</script>