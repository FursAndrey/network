<template>
    <div class="w-96 mx-auto mb-16">
        <h2 class="text-3xl">Posts</h2>
        <Stat :userId="this.userId"></Stat>
        <div v-if="posts">
            <Post v-for="post in posts" :key="post.id" :post="post"></Post>
        </div>
    </div>
</template>

<script>
import Post from '../../components/Post.vue';
import Stat from '../../components/Stat.vue';
export default {
    name: "UserPost",
    components: {
        Post,
        Stat,
    },
    data() {
        return {
            userId: this.$route.params.id,
            posts: null,
        }
    },
    mounted() {
        this.getPosts()
    },
    methods: {
        getPosts() {
            axios.get('/api/users/'+this.userId).then(res => {
                this.posts = res.data.data
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
        }
    }
}
</script>

<style scoped>

</style>
