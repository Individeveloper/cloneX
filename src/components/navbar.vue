<template>
  <ion-header>
    <ion-toolbar>
      <ion-title style="font-weight: bold;">Berita Bursa</ion-title>
      <ion-buttons slot="end">
        <ion-button @click="onLogout" title="Logout">
          <ion-icon :icon="logOut" />
        </ion-button>
      </ion-buttons>
    </ion-toolbar>
  </ion-header>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router';
import { IonButtons, IonButton, IonIcon } from '@ionic/vue';
import { logOut } from 'ionicons/icons';

const router = useRouter();

const onLogout = () => {
  // confirm with the user
  if (!confirm('Are you sure you want to logout?')) return;

  // remove session keys used in the app
  localStorage.removeItem('user_token');
  localStorage.removeItem('username');
  localStorage.removeItem('isLoggedIn');

  // optionally clear other keys if present
  // localStorage.clear();

  // redirect to login (replace to avoid back navigation)
  router.replace('/tabs/login');
}
</script>