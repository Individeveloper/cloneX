<template>
    <ion-page>
        <navbar></navbar>
        <ion-content :fullscreen="true" class="login-content">
            <ion-card class="login-card">
                <ion-card-header>
                    <ion-card-title class="title">Welcome Back</ion-card-title>
                    <p class="subtitle">Silakan masuk untuk melanjutkan</p>
                </ion-card-header>

                <ion-card-content class="form">
                    <ion-list lines="none">
                        <ion-item class="form-item">
                            <ion-input
                                label="Username"
                                label-placement="floating"
                                placeholder="Masukkan username"
                                clear-input
                                type="text"
                                v-model="username"
                            ></ion-input>
                        </ion-item>

                        <ion-item class="form-item">
                            <ion-input
                                label="Password"
                                label-placement="floating"
                                placeholder="Masukkan password"
                                clear-input
                                type="password"
                                v-model="password"
                            ></ion-input>
                        </ion-item>
                        <ion-item>
                            <p>Belum punya akun? <router-link to="/tabs/register">Daftar</router-link></p>
                        </ion-item>
                    </ion-list>

                    <p v-if="errorMessage" class="error-text">{{ errorMessage }}</p>

                    <ion-button
                        expand="block"
                        class="login-btn"
                        @click="handleLogin"
                        :disabled="isLoggingIn"
                        size="large"
                    >
                        <ion-spinner v-if="isLoggingIn" name="crescent"></ion-spinner>
                        {{ isLoggingIn ? 'Logging in...' : 'Login' }}
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
import { 
  IonPage, IonHeader, IonToolbar, IonTitle, IonContent, 
  IonTextarea, IonItem, IonButton, IonSpinner, IonInput, 
  IonCard, IonCardHeader, IonCardTitle, IonCardContent, IonList,
  alertController 
} from '@ionic/vue';
import navbar from '@/components/navbar.vue';

const router = useRouter();
const username = ref('');
const password = ref('');
const errorMessage = ref('');
const isLoggingIn = ref(false);
const api = 'http://localhost/cloneX/server_side/login.php';

const handleLogin = async () => {
    errorMessage.value = '';
    isLoggingIn.value = true;

    if (!username.value || !password.value){
        errorMessage.value = 'Please enter both username and password.';
        isLoggingIn.value = false;
        return;
    }

    try {
        const response = await axios.post(api,{
            username: username.value,
            password: password.value
        }, {
            headers: {
                'X-API-Key': '12345',
                'Content-Type': 'application/json'
            }
        });
        
        if (response.data.success) {
            localStorage.setItem('user_token', response.data.token || 'dummy_token');
            localStorage.setItem('username', response.data.username);  // Tambahkan ini
            localStorage.setItem('isLoggedIn', 'true');
            router.push('/tabs/tab1');
        } else {
            errorMessage.value = response.data.message || 'Login failed. Please try again.';
        }
    } catch (error) {
        errorMessage.value = 'An error occurred. Please try again later.';
    } finally {
        isLoggingIn.value = false;
    }
}

</script>

<style scoped>
.login-content {
  --background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0b1324 100%);
  display: flex;
  align-items: center;
  justify-content: center;
}

.login-card {
  width: 100%;
  max-width: 420px;
  border-radius: 18px;
  padding: 8px 6px 18px;
  box-shadow: 0 10px 30px rgba(2, 8, 23, 0.35);
  backdrop-filter: blur(6px);
  margin: 4rem auto;
}

.title {
  font-size: 28px;
  font-weight: 800;
  text-align: center;
  color: #ffffff;
  letter-spacing: 0.2px;
}

.subtitle {
  margin: 6px 0 4px;
  text-align: center;
  color: rgba(255,255,255,0.7);
  font-size: 14px;
}

.form {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin: 0 auto;
}

.form-item {
  --background: rgba(255,255,255,0.06);
  --border-radius: 14px;
  --highlight-color-focused: var(--ion-color-primary);
  border-radius: 14px;
  margin: 6px 0;
  backdrop-filter: blur(4px);
}

.form-item ion-input {
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