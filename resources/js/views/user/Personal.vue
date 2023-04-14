<template>
    <div class="w-96 mx-auto mb-16">
        <div>
            <input v-model="title" type="text" placeholder="title" class="w-96 p-3 mb-2 border-2 rounded-lg border-sky-500"/>
            <div v-if="errors.title" class="bg-red-200 text-red-700 border border-red-700 rounded-lg p-2 mb-2">
                <p v-for="error in errors.title" :key="error">{{ error }}</p>
            </div>
        </div>
        <div>
            <textarea v-model="content" placeholder="content" class="w-96 p-3 border-2 rounded-lg border-sky-500"></textarea>
            <div v-if="errors.content" class="bg-red-200 text-red-700 border border-red-700 rounded-lg p-2 mb-2">
                <p v-for="error in errors.content" :key="error">{{ error }}</p>
            </div>
        </div>
        <div class="flex space-x-8">
            <div>
                <input @change="storeImage" ref="file" type="file" class="hidden">
                <a href="#" class="block w-44 p-3 mb-2 bg-sky-500 text-white rounded-lg hover:bg-sky-700 font-semibold text-center" @click.prevent="selectFile()">Image</a>
                <div v-if="errors.image" class="w-96 bg-red-200 text-red-700 border border-red-700 rounded-lg p-2 mb-2">
                    <p v-for="error in errors.image" :key="error">{{ error }}</p>
                </div>
            </div>
            <div v-if="image" @click="image = null" class="block w-44 p-3 mb-2 ml-2 bg-orange-500 text-white rounded-lg hover:bg-orange-700 font-semibold text-center">
                Cancel
            </div>
        </div>
        <div v-if="image" class="mb-2">
            <img :src="image.url"/>
        </div>
        <div class="flex justify-end">
            <input @click="storePost" type="button" value="Create post" class="block w-32 p-3 mb-2 border rounded-lg bg-sky-500 text-white hover:bg-sky-700 font-semibold"/>
        </div>
        
        <h2 class="text-3xl">Posts</h2>
        <Stat :userId="null"></Stat>
        <div v-if="posts">
            <Post v-for="post in posts" :key="post.id" :post="post"></Post>
        </div>
    </div>
</template>

<script>
import Post from '../../components/Post.vue';
import Stat from '../../components/Stat.vue';
export default {
    name: "Personal",
    components: {
        Post,
        Stat,
    },
    data() {
        return {
            title: '',
            content: '',
            image: null,
            posts: null,
            errors: [],
        }
    },
    mounted() {
        this.getPosts()
    },
    methods: {
        getPosts() {
            axios.get('/api/posts').then(res => {
                this.posts = res.data.data;
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
        selectFile() {
            this.fileInput = this.$refs.file;
            this.fileInput.click();
        },
        storeImage(e) {
            let file = e.target.files[0];
            const formData = new FormData();
            formData.append('image', file);
            
            axios.post(
                '/api/postImages', 
                formData, 
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(res => {
                    this.image = res.data.data;
                }).catch(error=>{
                    this.errors = error.response.data.errors;
                })
        },
        storePost() {
            const image_id = this.image? this.image.id: null;
            axios.post(
                '/api/posts',
                {
                    title: this.title,
                    content: this.content,
                    image_id: image_id
                }
            ).then(res => {
                this.title = '';
                this.content = '';
                this.image = null;

                this.getPosts();
            }).catch(error=>{
                this.errors = error.response.data.errors;
            })
        }
    }
}
</script>

<style scoped>

</style>
