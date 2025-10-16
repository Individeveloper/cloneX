<template>
  <ion-page>
    <navbar></navbar>
    <ion-content>
      <ion-item v-for="user in userlist">
        <ion-label>
          <h2>Username: {{ user.username }}</h2>
          <p>Password: {{ user.password }}</p>
        </ion-label>
      </ion-item>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent } from '@ionic/vue';
import axios from 'axios';
import { ref, onMounted } from 'vue';
import navbar from '@/components/navbar.vue';

interface User {
  username: string;
  password: string;
}

const userlist = ref<User[]>([]);

const api = 'http://localhost/server_side/api.php';

onMounted(async () => {
  try {
    const response = await axios.get(api);
    userlist.value = response.data;
  } catch (error) {
    console.error('Error fetching user data:', error);
  }
});

</script>
