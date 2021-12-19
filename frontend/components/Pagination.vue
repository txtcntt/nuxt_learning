<template>
	<nav aria-label="..." v-if="total>1">
		<ul class="pagination justify-content-center">
			<li class="page-item disabled" v-if="current == 1">
				<span class="page-link">&laquo;</span>
			</li>
            <li class="page-item" v-if="current > 1">
				<a class="page-link" href="javascript:void(0);" @click="previousPage()">&laquo;</a>
			</li>
			<li class="page-item" v-for="page in total" :key="page">
                <a class="page-link" href="javascript:void(0);" @click="selectPage(page)" v-if="page != current">{{page}}</a>
                <a class="page-link" v-if="page == current">{{page}} <span class="sr-only">(current)</span></a>
            </li>
			
            <li class="page-item disabled" v-if="current == total">
				<span class="page-link">&raquo;</span>
			</li>
			<li class="page-item"  v-if="current < total">
				<a class="page-link" href="javascript:void(0);" @click="nextPage()">&raquo;</a>
			</li>
		</ul>
	</nav>
</template>

<script>
export default {
    props: ["total", "current"],
    data(){
        return {}
    },
    methods: {
        /**
         * Select page
         */
        selectPage (page) {
            this.$emit("paging", page);
        },
        /**
         * previous page
         */
        previousPage () {
            this.$emit("paging", this.current - 1);
        },
        /**
         * Next page
         */
        nextPage () {
            this.$emit("paging", this.current + 1);
        }
    }
}
</script>