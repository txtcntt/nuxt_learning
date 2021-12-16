<template></template>

<script>
export default {
	layout: "login",
	middleware: "auth",
	mounted() {
		if (this.authenticated) {
			this.$router.push("/users");
		} else {
			this.$router.push("/login");
		}
	},
};
</script>
