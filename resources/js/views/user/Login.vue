<template>
    <div class="w-96 mx-auto mb-16">
        <div v-if="errorMessage" class="w-96 p-2 mb-2 border border-red-600 rounded-lg text-red-600 bg-red-100">
            {{ errorMessage }}
        </div>
        <div>
            <input v-model="email" type="email" placeholder="email" class="w-96 p-2 mb-2 border border-inherit rounded-lg">
        </div>
        <div>
            <input v-model="password" type="password" placeholder="password" class="w-96 p-2 mb-2 border border-inherit rounded-lg">
        </div>
        <div>
            <input @click.prevent="login" type="submit" value="login" class="block float-right mx-auto w-32 p-2 bg-sky-400 text-white rounded-lg">
        </div>
    </div>
</template>

<script>
export default {
    name: "Login",

    data() {
        return {
            errorMessage: null,
            email: null,
            password: null,
        }
    },

    methods: {
        login() {
            axios.get('/sanctum/csrf-cookie')
                .then(response => {
                    axios.post('/login', {email: this.email, password: this.password})
                        .then(r => {
                            localStorage.setItem('x_xsrf_token', r.config.headers['X-XSRF-TOKEN'])
                            this.$router.push({name: 'user.personal'})
                        })
                        .catch(err => {
                            this.errorMessage = err.response.data.message;
                            // console.log(err);
                        })
                })
        }
    }
}
</script>

<style scoped>

</style>
