<template>
    <div class="w-96 mx-auto mb-16">
        <h2 class="text-3xl">Feed</h2>
        <div v-if="posts">
            <Post v-for="post in posts" :key="post.id" :post="post"></Post>
        </div>
    </div>
</template>

<script>
import Post from '../../components/Post.vue';
export default {
    name: "Feed",
    components: {
        Post,
    },
    data() {
        return {
            posts: null,
        }
    },
    mounted() {
        this.getFollowingPosts()
    },
    methods: {
        getFollowingPosts() {
            axios.get('/api/users/following/posts').then(res => {
                this.posts = res.data.data;
            }).catch(error=>{
                if (error.response.status == 401) {
                    // //неавторизован (костыль + дублирование)
                    // axios.post('/logout')
                    //     .then( res => {
                    //         localStorage.removeItem('x_xsrf_token');
                    //         this.$router.push({name: 'user.login'});
                    //     })
                    console.log(error);
                } else {
                    console.log(error);
                }
            })
        }
    }
}
</script>

<style scoped>

</style>
