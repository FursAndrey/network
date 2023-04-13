<template>
    <div class="w-96 mx-auto mb-16">
        <h2 class="text-3xl">Users</h2>
        <div v-if="users">
            <div v-for="user in users" :key="user.id" class="mt-4 pt-4 border-t border-sky-500 flex justify-between">
                <router-link :to="{ name: 'user.post', params: {id: user.id} }">
                    <h3 class="text-xl mb-4">{{ user.name }}</h3>
                    <p>{{ user.email }}</p>
                </router-link>
                <div>
                    <span @click="followingToggle(user)" 
                        :class="['block w-32 p-2 rounded-lg font-semibold text-center', user.is_followed? 'bg-orange-500 text-white hover:bg-orange-700' : 'bg-sky-500 text-white hover:bg-sky-700']"
                        >
                        {{ user.is_followed? 'Unfollow' : 'Follow' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "Users",
    data() {
        return {
            users: [],
        }
    },
    mounted() {
        this.getUsers()
    },
    methods: {
        getUsers() {
            axios.get('/api/users').then(res => {
                // console.log(res);
                this.users = res.data.data
            }).catch(error=>{
                if (error.response.status == 401) {
                    // //неавторизован (костыль + дублирование)
                    // axios.post('/logout')
                    //     .then( res => {
                    //         localStorage.removeItem('x_xsrf_token')
                    //         this.$router.push({name: 'user.login'})
                    //     })
                    console.log(error);
                } else {
                    console.log(error);
                }
            })
        },
        followingToggle(user) {
            axios.get('/api/users/'+user.id+'/following').then(res => {
                user.is_followed = res.data.is_followed;
            }).catch(error=>{
                console.log(error);
            });
        }
    }
}
</script>

<style scoped>

</style>
