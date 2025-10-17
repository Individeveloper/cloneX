<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-title>Post Artikel</ion-title>
      </ion-toolbar>
    </ion-header>
    <ion-content :fullscreen="true" class="post-content">
      <ion-card class="post-card">
        <ion-card-header>
          <ion-card-title class="title">Tulis Artikel</ion-card-title>
          <p class="subtitle">Bagikan wawasan Anda</p>
        </ion-card-header>

        <ion-card-content class="form">
          <ion-list lines="none">
            <ion-item class="form-item">
              <ion-input
                label="Title"
                label-placement="floating"
                placeholder="Judul artikel"
                clear-input
                type="text"
                v-model="title"
              ></ion-input>
            </ion-item>

            <ion-item class="form-item">
              <ion-textarea
                label="Content"
                label-placement="floating"
                placeholder="Tulis isi artikel"
                auto-grow
                v-model="content"
              ></ion-textarea>
            </ion-item>
          </ion-list>

          <p v-if="errorMessage" class="error-text">{{ errorMessage }}</p>

          <ion-button
            expand="block"
            class="login-btn"
            @click="handlePost"
            :disabled="isPosting || content.length === 0 || title.length === 0"
            size="large"
          >
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

<style scoped>
.post-content {
  --background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0b1324 100%);
  display: flex;
  align-items: flex-start;
  justify-content: center;
  padding-top: 24px;
  padding-bottom: 24px;
}

.post-card {
  width: 100%;
  max-width: 720px;
  border-radius: 18px;
  padding-bottom: 10px;
  box-shadow: 0 10px 30px rgba(2, 8, 23, 0.35);
  backdrop-filter: blur(6px);
  margin: 4rem auto;
}

.title {
  font-size: 26px;
  font-weight: 800;
  text-align: left;
  color: #ffffff;
  letter-spacing: 0.2px;
}

.subtitle {
  margin: 6px 0 4px;
  color: rgba(255,255,255,0.7);
  font-size: 14px;
}

.form {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.form-item {
  --background: rgba(255,255,255,0.06);
  --border-radius: 14px;
  --highlight-color-focused: var(--ion-color-primary);
  border-radius: 14px;
  margin: 6px 0;
  backdrop-filter: blur(4px);
}

.form-item ion-input,
.form-item ion-textarea {
  --padding-start: 14px;
  --padding-end: 14px;
  --padding-top: 12px;
  --padding-bottom: 12px;
  color: #e5e7eb;
}

.error-text {
  margin: 6px 2px 2px;
  text-align: center;
  color: #ef4444;
  background: rgba(239, 68, 68, 0.1);
  border: 1px solid rgba(239, 68, 68, 0.25);
  padding: 8px 10px;
  border-radius: 10px;
  font-size: 13px;
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