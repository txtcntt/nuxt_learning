<template>
	<div class="container col-md-3 mt-5">
		<div class="d-flex justify-content-center mt-5 mb-5">
			<img
				src="~/assets/images/rcvn_name_lg.png"
				alt="RiverCrane"
				class="img-reponsive"
			/>
		</div>
		<form @submit.prevent="submit">
            <div class="input-group text-danger mb-4 d-flex justify-content-center" v-if="errors.common">
                <span>{{ errors.common[0] }}</span>
            </div>
			<div class="form-group mb-4">
				<label class="input-group-icon"><i class="fas fa-user"></i></label>
				<input
					v-model.trim="form.email"
					type="email"
					class="form-control custom-input-group"
					placeholder="Enter email"
					autofocus
				/>
				<small class="form-text text-danger" v-if="errors.email" >{{errors.email[0]}}</small>
			</div>
			<div class="form-group mb-4">
				<label class="input-group-icon"><i class="fas fa-lock"></i></label>
				<input
					v-model.trim="form.password"
					type="password"
					class="form-control custom-input-group"
					placeholder="Password"
				/>
				<small class="form-text text-danger" v-if="errors.password" >{{errors.password[0]}}</small>
			</div>

			<button type="submit" class="btn btn-primary" style="float: right">
				Login
			</button>
		</form>
	</div>
</template>

<script>
export default {
	layout: "login",
	middleware: "guest",
	data() {
		return {
			form: {
				email: "",
				password: "",
			},
		};
	},
	methods: {
		async submit() {
            try{
                this.$nuxt.$loading.start()
                await this.$auth.loginWith("local", {
                    data: this.form,
                });			
            }catch(error){
                
            }
            this.$nuxt.$loading.finish()
		},
	},
};
</script>
