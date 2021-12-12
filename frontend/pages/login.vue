<template>
	<div class="container col-md-3 mt-5">
		<h2>Login</h2>
		<br />
		<form @submit.prevent="submit">
			<div class="form-group">
				<label>Email address</label>
				<input
					v-model.trim="form.email"
					type="email"
					class="form-control"
					placeholder="Enter email"
					autofocus
				/>
				<small class="form-text text-danger"> </small>
			</div>
			<div class="form-group">
				<label>Password</label>
				<input
					v-model.trim="form.password"
					type="password"
					class="form-control"
					placeholder="Password"
				/>
				<small class="form-text text-danger"> </small>
			</div>
			<button type="submit" class="btn btn-primary">Login</button>
		</form>
	</div>
</template>

<script>
export default {
    layout:'login',
    middleware:'guest',
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
			await this.$auth.loginWith("local", {
				data: this.form,
			});
            // this.$router.push('/users');
		},
	},
};
</script>
