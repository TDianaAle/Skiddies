<template>
    <div class="carousel-container">
    <button @click="prev" :disabled="startIndex === 0">←</button>

    <div class="video-scroll">
        <div
            v-for="video in currentVideos"
            :key="video.id"
            class="video-card"
        >
            <video width="300" controls :src="`http://localhost:8000/${video.file_path}`"></video>
            <h4>{{ video.title }}</h4>
            <p>{{ video.category }}</p>
        </div>
        </div>

        <button @click="next" :disabled="startIndex + 3 >= videos.length">→</button>
    </div>
</template>

<script>
export default {
    name: 'TutorVideoCarousel',
    data() {
        return {
        videos: [],
        startIndex: 0
        };
    },
    computed: {
        currentVideos() {
        return this.videos.slice(this.startIndex, this.startIndex + 3);
        }
    },
    methods: {
        async fetchVideos() {
        const res = await fetch('http://localhost:8000/api/get_tutor_videos.php', {
            credentials: 'include'
        });
        const data = await res.json();
        if (data.success) {
            this.videos = data.videos;
        } else {
            console.error(data.error);
        }
        },
        next() {
        if (this.startIndex + 3 < this.videos.length) {
            this.startIndex += 3;
        }
        },
        prev() {
        if (this.startIndex > 0) {
            this.startIndex -= 3;
        }
        }
    },
    mounted() {
        this.fetchVideos();
    }
};
</script>

<style scoped>
.carousel-container {
    display: flex;
    align-items: center;
    gap: 10px;
}
.video-scroll {
    display: flex;
    gap: 10px;
    overflow: hidden;
}
.video-card {
    border: 1px solid #ccc;
    padding: 10px;
    width: 300px;
    background: #fafafa;
    border-radius: 8px;
}
button:disabled {
    opacity: 0.5;
}
</style>
