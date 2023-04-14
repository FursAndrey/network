<template>
    <div class="mt-4 pt-4 border-t border-sky-500">
        <h3 class="text-xl mb-4">{{ post.title }}</h3>
        <router-link :to="{name: 'user.post', params: {id: post.author.id}}" class="text-orange-400">{{ post.author.name }}</router-link>
        <img class="mb-3 mx-auto" v-if="post.image_url" :src="post.image_url" :alt="post.title"/>
        <p>{{ post.content }}</p>

        <div v-if="post.reposted_post" class="border border-sky-300 bg-sky-100 p-3 mt-2">
            <h3 class="text-xl mb-4">{{ post.reposted_post.title }}</h3>
            <router-link :to="{name: 'user.post', params: {id: post.reposted_post.author.id}}" class="text-orange-400">{{ post.reposted_post.author.name }}</router-link>
            <img class="mb-3 mx-auto" v-if="post.reposted_post.image_url" :src="post.reposted_post.image_url" :alt="post.reposted_post.title"/>
            <p>{{ post.reposted_post.content }}</p>
        </div>

        <div class="flex justify-between items-center mt-3">
            <div class="flex">
                <div class="flex">
                    <svg @click="likeToggle(post)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" 
                        :class="['stroke-sky-500 cursor-pointer hover:fill-sky-500 w-6 h-6', post.is_liked ? 'fill-sky-500': 'fill-white']">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                            d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" 
                        />
                    </svg>
                    <span class="ml-2">{{ post.likes_count }}</span>
                </div>
                <div class="flex ml-5">
                    <svg @click="toggleFormRepost()" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" 
                        :class="['stroke-sky-500 cursor-pointer hover:fill-sky-500 w-6 h-6', post.is_reposted ? 'fill-sky-500': 'fill-white']"
                        >
                        <path stroke-linecap="round" stroke-linejoin="round" 
                            d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z" 
                        />
                    </svg>
                    <span class="ml-2">{{ post.reposted_count }}</span>
                </div>
                <div class="flex ml-5">
                    <svg @click="commentFormToggle(post)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        class="stroke-sky-500 cursor-pointer hover:fill-sky-500 w-6 h-6 fill-white"
                        >
                        <path stroke-linecap="round" stroke-linejoin="round" 
                            d="M8.625 9.75a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 01.778-.332 48.294 48.294 0 005.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" 
                            />
                    </svg>
                    <span class="ml-2">{{ post.comments_count }}</span>
                </div>
            </div>

            <p class="text-right text-sm text-gray-400">{{ post.published }}</p>
        </div>
        <div v-if="is_open_comments" class="mt-3">
            <div v-if="parent_id">
                <span class="mr-6">Answer for {{ answer_for }}</span>
                <span @click="resetAnswer()" class="cursor-pointer text-sm text-sky-500">Cancel</span>
            </div>
            <div>
                <textarea v-model="comment" placeholder="body of comment" class="w-96 p-3 border-2 rounded-lg border-sky-500"></textarea>
                <div v-if="errors.body" class="bg-red-200 text-red-700 border border-red-700 rounded-lg p-2 mb-2">
                    <p v-for="error in errors.body" :key="error">{{ error }}</p>
                </div>
            </div>
            <div class="flex justify-end">
                <input @click="storeComment(post)" type="button" value="Create comment" class="block w-48 p-3 mb-2 border rounded-lg bg-sky-500 text-white hover:bg-sky-700 font-semibold"/>
            </div>
            <div class="border border-sky-500 p-3 bg-sky-50" v-if="post.comments_count > 0">
                <div v-for="comment in this.comments" :key="comment.id" class="pb-2 border-b border-sky-500">
                    <p class="flex justify-between">
                        <span class="text-sm text-gray-700">{{ comment.user }}</span>
                        <span @click="setAnswer(comment)" class="text-sm text-sky-500 cursor-pointer">Answer</span>
                        <span class="text-sm text-gray-400">{{ comment.published }}</span>
                    </p>
                    <p>
                        <span v-if="comment.answer_for_user" class="text-red-400">{{ comment.answer_for_user }},</span>
                        {{ comment.body }}
                    </p>
                </div>
            </div>
        </div>
        <div v-if="is_repost" class="mt-3">
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
            <div class="flex justify-end">
                <input @click="repost(post)" type="button" value="Create post" class="block w-32 p-3 mb-2 border rounded-lg bg-sky-500 text-white hover:bg-sky-700 font-semibold"/>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "Post",

    props: [
        'post',
    ],

    data() {
        return {
            is_repost: false,
            reposted_id: null,
            is_open_comments: false,
            title: '',
            content: '',
            comment: '',
            errors: [],
            comments: [],
            parent_id: null,
            answer_for: null,
        }
    },
    
    methods: {
        likeToggle(post) {
            axios.post(`/api/posts/${post.id}/like`).then(res => {
                post.is_liked = res.data.is_liked;
                post.likes_count = res.data.likes_count;
            }).catch(error=>{
                // console.log(error);
            });
        },
        toggleFormRepost() {
            if (this.isPersonal()) {
                return;
            }
            this.is_repost = !this.is_repost;
        },
        repost(post) {
            if (this.isPersonal()) {
                return;
            }

            axios.post(
                `/api/posts/${post.id}/repost`,
                {
                    title: this.title,
                    content: this.content,
                }
            ).then(res => {
                this.title = '';
                this.content = '';
                this.toggleFormRepost();
            }).catch(error=>{
                this.errors = error.response.data.errors;
            })
        },

        isPersonal() {
            return this.$route.name == 'user.personal';
        },

        commentFormToggle(post) {
            this.is_open_comments = !this.is_open_comments;

            if (this.is_open_comments && this.comments.length == 0) {
                this.getComments(post);
            }
        },

        getComments(post) {
            axios.get(
                    `/api/posts/${post.id}/getComments`,
                ).then(res => {
                    this.comments = res.data.data;
                    this.post.comments_count = this.comments.length;
                }).catch(error=>{
                    // console.log(error);
                });
        },
        
        storeComment(post) {
            axios.post(
                `/api/posts/${post.id}/storeComment`,
                {
                    body: this.comment,
                    parent_id: this.parent_id,
                }
            ).then(res => {
                this.comment = '';
                this.parent_id = null;
                this.getComments(post);
            }).catch(error=>{
                this.errors = error.response.data.errors;
            })
        },

        setAnswer(comment) {
            this.parent_id = comment.id;
            this.answer_for = comment.user;
        },

        resetAnswer() {
            this.parent_id = null;
            this.answer_for = null;
        }
    }
}
</script>

<style scoped>

</style>