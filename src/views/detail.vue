<template>
    <ion-page>
        <ion-header>
            <navbar></navbar> 
        </ion-header>
        <ion-content class="detail-content">
            <div v-if="isLoading" class="state-wrap">
                <ion-spinner name="crescent"></ion-spinner>
                <p class="state-text">Memuat detail artikel...</p>
            </div>

            <div v-else-if="post" class="card-wrap">
                <ion-card class="detail-card">
                    <ion-card-header>
                        <ion-card-title class="title">{{ post.title }}</ion-card-title>
                    </ion-card-header>
                    <ion-card-content>
                        <hr class="divider" />
                        <p class="content">{{ post.content }}</p>
                        <hr class="divider" />
                        <p class="meta">
                            <ion-card-subtitle class="subtitle">Post ID: {{ post.id }}</ion-card-subtitle>
                            <small>Dibuat pada: {{ new Date(post.createdAt).toLocaleDateString() }}</small>
                        </p>
                    </ion-card-content>
                </ion-card>
            </div>

            <div v-else class="state-wrap">
                <ion-icon :icon="alertCircleOutline" size="large" color="danger"></ion-icon>
                <h1 class="title" style="margin-top: 10px;">404 Not Found</h1>
                <p class="state-text">Artikel dengan ID tersebut tidak ditemukan.</p>
                <ion-button router-link="/tabs/tab1" class="login-btn" size="default">Kembali ke Beranda</ion-button>
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
    createdAt: string; 
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

<style scoped>
.detail-content {
  --background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0b1324 100%);
  display: flex;
  align-items: flex-start;
  justify-content: center;
  padding-top: 24px;
  padding-bottom: 24px;
}

.card-wrap {
  width: 100%;
  max-width: 1000px;
  padding: 0 16px;
  margin: 4rem auto;
}

.detail-card {
  width: 100%;
  border-radius: 18px;
  box-shadow: 0 10px 30px rgba(2, 8, 23, 0.35);
  backdrop-filter: blur(6px);
  padding: 7px 15px;
}

.title {
  font-size: 26px;
  font-weight: 800;
  text-align: center;
  color: #ffffff;
  letter-spacing: 0.2px;
}

.subtitle {
  margin-top: 4px;
  color: rgba(255,255,255,0.7);
  font-size: 14px;
}

.content {
  white-space: pre-wrap;
  color: #e5e7eb;
  line-height: 1.65;
  font-size: 15px;
  margin: 20px 0;
}

.meta {
    display: flex;
    justify-content: space-between;
  margin-top: 10px;
  text-align: right;
  color: rgba(255,255,255,0.6);
}

.divider {
  border: none;
  border-top: 1px solid rgba(255,255,255,0.08);
  margin: 12px 0;
}

.state-wrap {
  width: 100%;
  max-width: 820px;
  padding: 40px 16px;
  margin: 0 auto;
  text-align: center;
  color: #e5e7eb;
}

.state-text {
  margin-top: 10px;
  color: rgba(255,255,255,0.7);
}

.login-btn {
  margin-top: 12px;
  --border-radius: 14px;
  --background: linear-gradient(135deg, var(--ion-color-primary) 0%, #6c5ce7 100%);
  --box-shadow: 0 10px 18px rgba(108, 92, 231, 0.35);
  font-weight: 700;
  letter-spacing: 0.3px;
}
</style>