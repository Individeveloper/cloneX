<template>
    <ion-page>
        <navbar></navbar>
        <ion-content :fullscreen="true">
            <ion-card style="width: 70%; height: 50%; margin: 2rem auto; padding: 20px;">
                <ion-card-header>
                    <ion-card-title
                        style="font-size: 30px; font-weight: bold; text-align: center;">Login</ion-card-title>
                </ion-card-header>

                <ion-card-content style="display: flex; flex-direction: column; ">
                    <ion-item>
                        <ion-input label="Username" label-placement="stacked"
                            placeholder="Enter your username" v-model="username"></ion-input>
                    </ion-item>
                    <ion-item>
                        <ion-input label="Password" label-placement="stacked"
                            placeholder="Enter your password" v-model="password"></ion-input>
                    </ion-item>

                    <p v-if="errorMessage" style="margin-top: 10px; text-align: center; color: red"></p>

                    <ion-button expand="block" @click="handleLogin" style="margin-top: 50px; width: 100%;" :disabled="isLoggingIn">
                        <ion-spinner v-if="isLoggingIn" name="crescent"></ion-spinner>
                        {{ isLoggingIn ? 'Logging in...' : 'Login'}}
                    </ion-button>

                </ion-card-content>
            </ion-card>
        </ion-content>
    </ion-page>
</template>

<script setup lang="ts">
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonInput, IonItem, IonLabel, IonList, IonButton, IonSpinner, useIonRouter } from '@ionic/vue';
import navbar from '@/components/navbar.vue';
import axios from 'axios';
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const username = ref('');
const password = ref('');
const errorMessage = ref('');
const isLoggingIn = ref(false);
const api = 'http://localhost/server_side/login.php';

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
        });
        
        if (response.data.success) {
            localStorage.setItem('user_token', response.data.token)
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