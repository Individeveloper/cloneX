<template>
  <ion-page>
    <ion-header>
      <navbar></navbar>
    </ion-header>
    <ion-content :fullscreen="true">
      <ion-list v-for="item in items" :key="item.username" style="margin: 2rem; padding: 1rem;">
        <ion-item>
            <h2>{{ item.username }}</h2>
            <p>{{ item.password }}</p>
        </ion-item>
      </ion-list>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent } from '@ionic/vue';
import axios from 'axios';
import { ref, onMounted } from 'vue';
import navbar from '@/components/navbar.vue';

interface Item {
  username: string;
  password: string;
}

const items = ref<Item[]>([]);

const api = 'http://localhost/server_side/account.php';

onMounted(async () => {
  try {
    const response = await axios.get(api);
    items.value = response.data;
  } catch (error) {
    console.error('Error fetching posts data:', error);
  }
});

</script>
