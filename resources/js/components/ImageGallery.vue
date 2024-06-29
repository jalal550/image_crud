<template>
    <div>
        <h1>Image Gallery</h1>
        <div v-if="loading">Loading...</div>
        <div v-else class="image-gallery">
            <div v-for="image in images" :key="image.id" class="image-item">
                <img :src="`${image.image_path}`" :alt="image.id" class="img-thumbnail" />
                <button @click="editImage(image.id)">Edit</button>
                <button @click="deleteImage(image.id)">Delete</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                images: [],
                loading: true,
            };
        },
        mounted() {
            this.loadImages();
        },
        methods: {
            loadImages() {
                axios.get('/api/images')
                    .then(response => {
                        this.images = response.data;
                        this.loading = false;
                    })
                    .catch(error => {
                        console.error('Error fetching images:', error);
                        this.loading = false;
                    });
            },
            editImage(id) {
                // Implement edit functionality
                console.log('Edit image with ID:', id);
            },
            deleteImage(id) {
                if (confirm('Are you sure you want to delete this image?')) {
                    axios.delete(`/api/images/${id}`)
                        .then(response => {
                            alert('Image deleted successfully.');
                            this.loadImages();
                        })
                        .catch(error => {
                            console.error('Error deleting image:', error);
                        });
                }
            }
        }
    };
</script>

<style scoped>
    .image-gallery {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    .image-item {
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 4px;
        text-align: center;
    }
    .image-item img {
        max-width: 100%;
        height: auto;
    }
</style>
