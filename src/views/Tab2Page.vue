<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-title>Tab 2</ion-title>
      </ion-toolbar>
    </ion-header>
    <ion-content :fullscreen="true">
      <ion-card style="width: 70%; height: 50%; margin: 2rem auto; padding: 20px;">
        <ion-card-header>
          <ion-card-title style="font-size: 30px; font-weight: bold; text-align: center;">Post Artikel</ion-card-title>
        </ion-card-header>

        <ion-card-content style="display: flex; flex-direction: column; ">
          <ion-item>
            <ion-input label="Title" label-placement="stacked" placeholder="Title" v-model="title"></ion-input>
          </ion-item>
          <ion-item>
            <ion-textarea label="Content" label-placement="stacked" placeholder="Content"
              v-model="content"></ion-textarea>
          </ion-item>

          <p v-if="errorMessage" style="margin-top: 10px; text-align: center; color: red">{{ errorMessage }}</p>

          <ion-button expand="block" @click="handlePost" style="margin-top: 50px; width: 100%;"
            :disabled="isPosting || content.length === 0 || title.length === 0">
            <ion-spinner v-if="isPosting" name="crescent"></ion-spinner>
            {{ isPosting ? 'Posting...' : 'Post' }}
          </ion-button>

        </ion-card-content>
      </ion-card>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonTextarea, IonItem, IonButton, IonSpinner, IonInput, alertController } from '@ionic/vue';

const router = useRouter();

// State untuk formulir
const title = ref('');
const content = ref('');
const errorMessage = ref('');
const isPosting = ref(false);

const insertApiUrl = 'http://localhost/server_side/post.php'; // Ganti URL API Anda

const showAlert = async (header: string, message: string) => {
  const alert = await alertController.create({
    header,
    message,
    buttons: ['OK'],
  });
  await alert.present();
};

const handlePost = async () => {
  errorMessage.value = '';
  isPosting.value = true;

  if (title.value.trim().length === 0) {
    errorMessage.value = 'Judul artikel tidak boleh kosong.';
    isPosting.value = false;
    return;
  }

  if (content.value.trim().length === 0) {
    errorMessage.value = 'Isi artikel tidak boleh kosong.';
    isPosting.value = false;
    return;
  }

  try {
    const response = await axios.post(insertApiUrl, {
      title: title.value,
      content: content.value,
    });

    if (response.data.success) {
      await showAlert("Sukses", "Artikel berhasil diposting!");
      title.value = '';
      content.value = '';
      router.replace('/tabs/tab1');
    } else {
      errorMessage.value = response.data.message || 'Gagal menyimpan artikel.';
    }

  } catch (error) {
    if (axios.isAxiosError(error) && error.response) {
      errorMessage.value = error.response.data.message || 'Terjadi kesalahan pada server.';
    } else {
      errorMessage.value = 'Gagal terhubung ke server. Periksa koneksi Anda.';
    }
  } finally {
    isPosting.value = false;
  }
};
</script>