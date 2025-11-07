<template>
  <ion-page>
    <ion-header>
      <navbar></navbar>
    </ion-header>
    <ion-content :fullscreen="true" class="profile-content">
      <!-- User Profile Section -->
      <ion-card class="profile-card">
        <ion-card-header>
          <ion-card-title class="profile-title">Profile Information</ion-card-title>
        </ion-card-header>
        <ion-card-content>
          <ion-list lines="none">
            <ion-item class="profile-item">
              <ion-label>
                <h3>Username</h3>
                <p>{{ userProfile.username }}</p>
              </ion-label>
            </ion-item>
            <ion-item class="profile-item">
              <ion-label>
                <h3>Email</h3>
                <p>{{ userProfile.email }}</p>
              </ion-label>
            </ion-item>
          </ion-list>
          
          <ion-button 
            expand="block" 
            fill="outline" 
            @click="openEditProfile"
            class="edit-btn"
          >
            <ion-icon :icon="pencil" slot="start"></ion-icon>
            Edit Profile
          </ion-button>
          
          <ion-button 
            expand="block" 
            fill="outline" 
            color="medium"
            @click="openChangePassword"
            class="password-btn"
          >
            <ion-icon :icon="lockClosed" slot="start"></ion-icon>
            Change Password
          </ion-button>
        </ion-card-content>
      </ion-card>

      <!-- User Posts Section -->
      <ion-card class="posts-card">
        <ion-card-header>
          <ion-card-title class="posts-title">My Posts</ion-card-title>
        </ion-card-header>
        <ion-card-content>
          <ion-list v-if="userPosts.length > 0">
            <ion-item-sliding v-for="post in userPosts" :key="post.id">
              <ion-item class="post-item">
                <ion-label>
                  <h3>{{ post.title }}</h3>
                  <p>{{ post.content.substring(0, 100) }}...</p>
                  <ion-note>{{ formatDate(post.created_at) }}</ion-note>
                </ion-label>
              </ion-item>
              
              <ion-item-options side="end">
                <ion-item-option @click="editPost(post)" color="primary">
                  <ion-icon :icon="pencil"></ion-icon>
                  Edit
                </ion-item-option>
                <ion-item-option @click="deletePost(post.id)" color="danger">
                  <ion-icon :icon="trash"></ion-icon>
                  Delete
                </ion-item-option>
              </ion-item-options>
            </ion-item-sliding>
          </ion-list>
          <div v-else class="no-posts">
            <ion-icon :icon="documentText" size="large"></ion-icon>
            <p>No posts yet</p>
          </div>
        </ion-card-content>
      </ion-card>

      <!-- Edit Profile Modal -->
      <ion-modal :is-open="isEditProfileOpen" @did-dismiss="isEditProfileOpen = false">
        <ion-header>
          <ion-toolbar>
            <ion-title>Edit Profile</ion-title>
            <ion-buttons slot="end">
              <ion-button @click="isEditProfileOpen = false">Close</ion-button>
            </ion-buttons>
          </ion-toolbar>
        </ion-header>
        <ion-content class="ion-padding">
          <ion-item>
            <ion-input
              v-model="editForm.username"
              label="Username"
              label-placement="stacked"
              placeholder="Enter username"
            ></ion-input>
          </ion-item>
          <ion-item>
            <ion-input
              v-model="editForm.email"
              label="Email"
              label-placement="stacked"
              type="email"
              placeholder="Enter email"
            ></ion-input>
          </ion-item>
          <ion-button expand="block" @click="updateProfile" class="save-btn">
            Save Changes
          </ion-button>
        </ion-content>
      </ion-modal>

      <!-- Change Password Modal -->
      <ion-modal :is-open="isChangePasswordOpen" @did-dismiss="isChangePasswordOpen = false">
        <ion-header>
          <ion-toolbar>
            <ion-title>Change Password</ion-title>
            <ion-buttons slot="end">
              <ion-button @click="isChangePasswordOpen = false">Close</ion-button>
            </ion-buttons>
          </ion-toolbar>
        </ion-header>
        <ion-content class="ion-padding">
          <ion-item>
            <ion-input
              v-model="passwordForm.currentPassword"
              label="Current Password"
              label-placement="stacked"
              type="password"
              placeholder="Enter current password"
            ></ion-input>
          </ion-item>
          <ion-item>
            <ion-input
              v-model="passwordForm.newPassword"
              label="New Password"
              label-placement="stacked"
              type="password"
              placeholder="Enter new password"
            ></ion-input>
          </ion-item>
          <ion-item>
            <ion-input
              v-model="passwordForm.confirmPassword"
              label="Confirm Password"
              label-placement="stacked"
              type="password"
              placeholder="Confirm new password"
            ></ion-input>
          </ion-item>
          <ion-button expand="block" @click="changePassword" class="save-btn">
            Update Password
          </ion-button>
        </ion-content>
      </ion-modal>

      <!-- Edit Post Modal -->
      <ion-modal :is-open="isEditPostOpen" @did-dismiss="isEditPostOpen = false">
        <ion-header>
          <ion-toolbar>
            <ion-title>Edit Post</ion-title>
            <ion-buttons slot="end">
              <ion-button @click="isEditPostOpen = false">Close</ion-button>
            </ion-buttons>
          </ion-toolbar>
        </ion-header>
        <ion-content class="ion-padding">
          <ion-item>
            <ion-input
              v-model="editPostForm.title"
              label="Title"
              label-placement="stacked"
              placeholder="Enter post title"
            ></ion-input>
          </ion-item>
          <ion-item>
            <ion-textarea
              v-model="editPostForm.content"
              label="Content"
              label-placement="stacked"
              placeholder="Enter post content"
              :rows="6"
            ></ion-textarea>
          </ion-item>
          <ion-button expand="block" @click="updatePost" class="save-btn">
            Update Post
          </ion-button>
        </ion-content>
      </ion-modal>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { 
  IonPage, IonHeader, IonContent, IonCard, IonCardHeader, IonCardContent, 
  IonCardTitle, IonList, IonItem, IonLabel, IonButton, IonIcon, IonModal,
  IonToolbar, IonTitle, IonButtons, IonInput, IonTextarea, IonItemSliding,
  IonItemOptions, IonItemOption, IonNote, alertController, toastController
} from '@ionic/vue';
import { pencil, lockClosed, trash, documentText } from 'ionicons/icons';
import axios from 'axios';
import { ref, onMounted } from 'vue';
import navbar from '@/components/navbar.vue';

interface UserProfile {
  username: string;
  email: string;
}

interface Post {
  id: number;
  title: string;
  content: string;
  created_at: string;
}

const userProfile = ref<UserProfile>({ username: '', email: '' });
const userPosts = ref<Post[]>([]);

const isEditProfileOpen = ref(false);
const isChangePasswordOpen = ref(false);
const isEditPostOpen = ref(false);

const editForm = ref<UserProfile>({ username: '', email: '' });
const passwordForm = ref({
  currentPassword: '',
  newPassword: '',
  confirmPassword: ''
});
const editPostForm = ref<Post>({ id: 0, title: '', content: '', created_at: '' });

const profileApi = 'http://localhost/cloneX/server_side/profile.php';
const postsApi = 'http://localhost/cloneX/server_side/user_posts.php';

onMounted(async () => {
  await loadUserProfile();
  await loadUserPosts();
});

const loadUserProfile = async () => {
  try {
    const username = localStorage.getItem('username') || '';
    const response = await axios.get(`${profileApi}?username=${username}`, {
      headers: { 'X-API-Key': '12345' }
    });
    userProfile.value = response.data;
  } catch (error) {
    console.error('Error loading profile:', error);
  }
};

const loadUserPosts = async () => {
  try {
    const username = localStorage.getItem('username') || '';
    const response = await axios.get(`${postsApi}?username=${username}`, {
      headers: { 'X-API-Key': '12345' }
    });
    userPosts.value = response.data;
  } catch (error) {
    console.error('Error loading posts:', error);
  }
};

const openEditProfile = () => {
  editForm.value = { ...userProfile.value };
  isEditProfileOpen.value = true;
};

const openChangePassword = () => {
  passwordForm.value = { currentPassword: '', newPassword: '', confirmPassword: '' };
  isChangePasswordOpen.value = true;
};

const updateProfile = async () => {
  try {
    const response = await axios.put(profileApi, editForm.value, {
      headers: { 'X-API-Key': '12345' }
    });
    
    if (response.data.success) {
      userProfile.value = { ...editForm.value };
      isEditProfileOpen.value = false;
      
      const toast = await toastController.create({
        message: 'Profile updated successfully',
        duration: 2000,
        color: 'success'
      });
      toast.present();
    }
  } catch (error) {
    console.error('Error updating profile:', error);
  }
};

const changePassword = async () => {
  if (passwordForm.value.newPassword !== passwordForm.value.confirmPassword) {
    const toast = await toastController.create({
      message: 'Passwords do not match',
      duration: 2000,
      color: 'danger'
    });
    toast.present();
    return;
  }

  try {
    const response = await axios.put(`${profileApi}/password`, {
      username: userProfile.value.username,
      currentPassword: passwordForm.value.currentPassword,
      newPassword: passwordForm.value.newPassword
    }, {
      headers: { 'X-API-Key': '12345' }
    });
    
    if (response.data.success) {
      isChangePasswordOpen.value = false;
      const toast = await toastController.create({
        message: 'Password updated successfully',
        duration: 2000,
        color: 'success'
      });
      toast.present();
    }
  } catch (error) {
    console.error('Error changing password:', error);
  }
};

const editPost = (post: Post) => {
  editPostForm.value = { ...post };
  isEditPostOpen.value = true;
};

const updatePost = async () => {
  try {
    const response = await axios.put(`${postsApi}/${editPostForm.value.id}`, editPostForm.value, {
      headers: { 'X-API-Key': '12345' }
    });
    
    if (response.data.success) {
      await loadUserPosts();
      isEditPostOpen.value = false;
      
      const toast = await toastController.create({
        message: 'Post updated successfully',
        duration: 2000,
        color: 'success'
      });
      toast.present();
    }
  } catch (error) {
    console.error('Error updating post:', error);
  }
};

const deletePost = async (postId: number) => {
  const alert = await alertController.create({
    header: 'Delete Post',
    message: 'Are you sure you want to delete this post?',
    buttons: [
      { text: 'Cancel', role: 'cancel' },
      {
        text: 'Delete',
        role: 'destructive',
        handler: async () => {
          try {
            const response = await axios.delete(`${postsApi}/${postId}`, {
              headers: { 'X-API-Key': '12345' }
            });
            
            if (response.data.success) {
              await loadUserPosts();
              const toast = await toastController.create({
                message: 'Post deleted successfully',
                duration: 2000,
                color: 'success'
              });
              toast.present();
            }
          } catch (error) {
            console.error('Error deleting post:', error);
          }
        }
      }
    ]
  });
  
  await alert.present();
};

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString();
};
</script>

<style scoped>
.profile-content {
  --background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0b1324 100%);
}

.profile-card, .posts-card {
  margin: 1rem;
  border-radius: 16px;
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(10px);
}

.profile-title, .posts-title {
  color: #ffffff;
  font-weight: 600;
}

.profile-item, .post-item {
  --background: rgba(255, 255, 255, 0.03);
  --border-radius: 12px;
  margin: 0.5rem 0;
}

.edit-btn, .password-btn, .save-btn {
  margin-top: 1rem;
  --border-radius: 12px;
}

.no-posts {
  text-align: center;
  padding: 2rem;
  color: rgba(255, 255, 255, 0.6);
}

.no-posts ion-icon {
  opacity: 0.5;
}
</style>
