<template>
  <ion-page>
    <navbar></navbar>
    <ion-content>
      <div style="text-align: center; margin-top: 3rem;">
        <h1 style="font-size: 40px;">Artikel</h1>
        <p>Ikuti perkembangan terkini seputar pasar saham</p>
      </div>
      <ion-buttons slot="end">
        <ion-button style="margin-top: 2rem;">Post Artikel</ion-button>
      </ion-buttons>
      <ion-card v-for="post in posts" :key="post.id" style="margin: 2rem; padding: 1rem;">
        <ion-card-header>
          <ion-card-title style="font-size: 1.7rem; font-weight: bold;">{{ post.title }}</ion-card-title>
        </ion-card-header>

        <ion-card-content>
          <div class="truncate">{{ post.content }}</div>
          <p style="margin-top: 10px; font-weight: bold;">Created At: {{ post.createdAt }}</p>
        </ion-card-content>

        <ion-button fill="clear" @click="router.push(`/tabs/detail/${post.id}`)">
          Baca Artikel
        </ion-button>

      </ion-card>

    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { IonPage, IonContent } from '@ionic/vue';
import axios from 'axios';
import { ref, onMounted } from 'vue';
import navbar from '@/components/navbar.vue';
import router from '@/router';

interface Post {
  id: number;
  title: string;
  content: string;
  createdAt: string;
}

const posts = ref<Post[]>([]);

const api = 'http://localhost/server_side/api.php';

onMounted(async () => {
  try {
    const response = await axios.get(api);
    posts.value = response.data;
  } catch (error) {
    console.error('Error fetching posts data:', error);
  }
});

</script>

<style scoped>
.truncate {
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 2;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>
