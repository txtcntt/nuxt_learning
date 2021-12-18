<template >
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <nuxt-link to="/users" class="navbar-brand logo">
            <img src="~/assets/images/rcvn_lg.png" alt="Rivercrane Logo" class="brand-image img-circle elevation-3" style="opacity: .8; border-radius: 50%; height: 30px">
            <span class="brand-text font-weight-bold">RiverCrane Vietnam</span>
        </nuxt-link>
        <button
            class="navbar-toggler"
            type="button"
            data-toggle="collapse"
            data-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <nuxt-link to="/users" active-class="active" class="nav-link">User</nuxt-link>
                </li>
                <li class="nav-item">
                    <nuxt-link to="/products" active-class="active" class="nav-link">Product</nuxt-link>
                </li>
            </ul>
            <template v-if="authenticated">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link">Hi {{ user.name }}</a>
                    </li>
                    <li class="nav-item">
                        <a @click.prevent="logout" class="nav-link" href="#">Logout</a>
                    </li>
                </ul>
            </template>
        </div>
	</nav>
</template>
<script>
export default {
    methods: {
        logout(){
            this.$auth.logout();
            // this.$router.push('/login');
        }
    }
};
</script>
