<template>
  <ion-page>
    <navbar></navbar>
    <ion-content class="login-content">
      <section class="hero">
        <h1 class="hero-title">Artikel</h1>
        <p class="hero-subtitle">Ikuti perkembangan terkini seputar pasar saham</p>
      </section>

      <section class="list-wrap">
        <ion-buttons style="display: flex; justify-content: flex-end; margin-bottom: 10px;">
          <ion-button class="post-btn" @click="router.push('/tabs/tab2')">Post Artikel</ion-button>
        </ion-buttons>
        
        <div v-if="loading" class="loading-state">
          <ion-spinner></ion-spinner>
          <p>Loading posts...</p>
        </div>
        
        <div v-else-if="posts.length === 0" class="empty-state">
          <ion-icon :icon="documentText" size="large"></ion-icon>
          <p>No posts available</p>
        </div>
        
        <ion-card v-else v-for="post in posts" :key="post.id" class="post-card">
          <ion-card-header>
            <div class="post-header">
              <div class="author-info">
                <ion-icon :icon="person" class="author-icon"></ion-icon>
                <span class="author-name">{{ post.author_name || post.username }}</span>
              </div>
              <span class="post-date">{{ formatDate(post.createdAt) }}</span>
            </div>
            <ion-card-title class="post-title">{{ post.title }}</ion-card-title>
          </ion-card-header>

          <ion-card-content>
            <div class="truncate">{{ post.content }}</div>
          </ion-card-content>

          <ion-button fill="clear" class="read-btn" @click="router.push(`/tabs/detail/${post.id}`)">
            Baca Artikel
          </ion-button>
        </ion-card>
      </section>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { IonPage, IonContent, IonSpinner, IonIcon } from '@ionic/vue';
import { person, documentText } from 'ionicons/icons';
import axios from 'axios';
import { ref, onMounted } from 'vue';
import navbar from '@/components/navbar.vue';
import router from '@/router';

interface Post {
  id: number;
  username: string;
  title: string;
  content: string;
  createdAt: string;
  author_name?: string;
  author_email?: string;
}

const posts = ref<Post[]>([]);
const loading = ref(false);

const api = 'http://localhost/cloneX/server_side/account.php?endpoint=posts';

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

onMounted(async () => {
  loading.value = true;
  try {
    const response = await axios.get(api, {
      headers: {
        'X-API-Key': '12345'
      }
    });
    posts.value = response.data;
  } catch (error) {
    console.error('Error fetching posts data:', error);
    // Optionally show toast error message
  } finally {
    loading.value = false;
  }
});
</script>

<style scoped>
.login-content {
  --background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0b1324 100%);
  padding-bottom: 24px;
}

.hero {
  text-align: center;
  padding: 36px 16px 8px;
  color: #e5e7eb;
}

.hero-title {
  font-size: 34px;
  font-weight: 800;
  margin: 0;
}

.hero-subtitle {
  margin-top: 6px;
  color: rgba(255,255,255,0.7);
}

.list-wrap {
  max-width: 980px;
  margin: 10px auto 0;
  padding: 0 20px;
}

.post-card {
  margin: 16px 0;
  border-radius: 16px;
  box-shadow: 0 8px 26px rgba(2, 8, 23, 0.28);
  backdrop-filter: blur(6px);
  padding: 15px;
  background: rgba(255, 255, 255, 0.05);
}

.post-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 8px;
}

.author-info {
  display: flex;
  align-items: center;
  gap: 6px;
}

.author-icon {
  color: var(--ion-color-primary);
  font-size: 16px;
}

.author-name {
  color: rgba(255,255,255,0.8);
  font-size: 14px;
  font-weight: 500;
}

.post-date {
  color: rgba(255,255,255,0.6);
  font-size: 12px;
}

.post-title {
  font-size: 20px;
  font-weight: 800;
  color: #fff;
}

.post-meta {
  margin-top: 10px;
  font-weight: 600;
  color: rgba(255,255,255,0.6);
}

.read-btn {
  --color: var(--ion-color-primary);
  font-weight: 700;
}

.post-btn {
  --background: linear-gradient(135deg, var(--ion-color-primary) 0%, #6c5ce7 100%);
  --box-shadow: 0 10px 18px rgba(108, 92, 231, 0.35);
  font-weight: 700;
  letter-spacing: 0.3px;
}

.truncate {
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 2;
  overflow: hidden;
  text-overflow: ellipsis;
  color: #e5e7eb;
  line-height: 1.6;
}

.loading-state, .empty-state {
  text-align: center;
  padding: 40px 20px;
  color: rgba(255,255,255,0.7);
}

.loading-state ion-spinner {
  --color: var(--ion-color-primary);
  margin-bottom: 10px;
}

.empty-state ion-icon {
  font-size: 48px;
  opacity: 0.5;
  margin-bottom: 10px;
}
</style>
