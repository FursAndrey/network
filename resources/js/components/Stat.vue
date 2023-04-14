<template>
    <div class="mt-4 pt-4 flex justify-between text-sm">
        <div>Posts {{ this.stat.posts_count }}</div>
        <div>Likes {{ this.stat.likes_count }}</div>
        <div>Comments {{ this.stat.comments_count }}</div>
        <div>Followings {{ this.stat.followings_count }}</div>
        <div>Subscribers {{ this.stat.subscribers_count }}</div>
    </div>
</template>

<script>

export default {
    name: "Stat",

    props: [
        'userId',
    ],

    data() {
        return {
            stat: [],
        }
    },
    mounted() {
        this.getStat()
    },
    
    methods: {
        getStat() {
            axios.get(`/api/users/stat/${this.userId}`).then(res => {
                this.stat = res.data.data
            }).catch(error=>{
                if (error.response.status == 401) {
                    // //неавторизован (костыль + дублирование)
                    // axios.post('/logout')
                    //     .then( res => {
                    //         localStorage.removeItem('x_xsrf_token')
                    //         this.$router.push({name: 'user.login'})
                    //     })
                    
                    // console.log(error);
                } else {
                    // console.log(error);
                }
            })
        },
    }
}
</script>

<style scoped>

</style>