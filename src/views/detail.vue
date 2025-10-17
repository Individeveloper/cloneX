<template>
    <ion-page>
        <ion-header>
            <navbar /> 
        </ion-header>
        <ion-content class="ion-padding">

            <div v-if="isLoading" class="ion-text-center ion-padding">
                <ion-spinner name="crescent"></ion-spinner>
                <p>Memuat detail artikel...</p>
            </div>
            <div v-else-if="post">
                <ion-card>
                    <ion-card-header>
                        <ion-card-title>{{ post.title }}</ion-card-title>
                        <ion-card-subtitle>Post ID: {{ post.id }}</ion-card-subtitle> 
                    </ion-card-header>
                    
                    <ion-card-content>
                        <p style="white-space: pre-wrap;">{{ post.content }}</p> 
                        <hr>
                        <p class="ion-text-end">
                            <small>Dibuat pada: {{ new Date(post.created_at).toLocaleDateString() }}</small>
                            </p>
                    </ion-card-content>
                </ion-card>
            </div>

            <div v-else class="ion-text-center ion-padding">
                <ion-icon :icon="alertCircleOutline" size="large" color="danger"></ion-icon>
                <h1>404 Not Found</h1>
                <p>Artikel dengan ID tersebut tidak ditemukan.</p>
                <ion-button router-link="/tabs/tab1">Kembali ke Beranda</ion-button>
            </div>
            
        </ion-content>
    </ion-page>
</template>

<script setup lang="ts">
import { IonPage, IonHeader, IonContent, IonSpinner, IonButton, IonCard, IonCardHeader, IonCardTitle, IonCardSubtitle, IonCardContent, IonIcon } from '@ionic/vue';
import { alertCircleOutline } from 'ionicons/icons';
import axios from 'axios';
import { ref, onMounted, watch } from 'vue';
import navbar from '@/components/navbar.vue';
import { useRoute } from 'vue-router';

interface Post {
    id: number;
    title: string;
    content: string;
    created_at: string; 
}

const post = ref<Post | null>(null);
const isLoading = ref(true); 
const api = 'http://localhost/server_side/detail.php'; 
const route = useRoute();

async function fetchPost(id: number | string) {
    isLoading.value = true;
    post.value = null;

    try {
        const response = await axios.get(`${api}?id=${id}`);
        
        if (response.data && response.data.record) {
            post.value = response.data.record as Post;
        } else if (response.data && response.data.length > 0) {
            post.value = response.data[0] as Post; 
        } else {
            post.value = null; 
        }
    } catch (error) {
        console.error('Error fetching post detail:', error);
        post.value = null; 
    } finally {
        isLoading.value = false;
    }
}

onMounted(() => {
    const id = route.params.id as string;
    if (id) fetchPost(id);
});

watch(
    () => route.params.id,
    (newId) => {
        if (newId) fetchPost(newId as string);
    }
);
</script>