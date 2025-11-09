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

          <ion-button expand="block" fill="outline" @click="openEditProfile" class="edit-btn">
            <ion-icon :icon="pencil" slot="start"></ion-icon>
            Edit Profile
          </ion-button>

          <ion-button expand="block" fill="outline" color="medium" @click="openChangePassword" class="password-btn">
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
            <div class="post-row" v-for="post in userPosts" :key="post.id">
              <ion-item class="post-item">
                <ion-label class="post-label">
                  <h3>{{ post.title }}</h3>
                  <p>{{ post.content.substring(0, 100) }}...</p>
                  <ion-note>{{ formatDate(post.created_at) }}</ion-note>
                  <div class="post-actions-bottom">
                    <ion-button size="small" fill="clear" @click.stop="editPost(post)">
                      <ion-icon :icon="pencil"></ion-icon>
                    </ion-button>
                    <ion-button size="small" fill="clear" color="danger" @click.stop="deletePost(post.id)">
                      <ion-icon :icon="trash"></ion-icon>
                    </ion-button>
                  </div>
                </ion-label>

                <!-- Actions moved to bottom of the post card -->

              </ion-item>
            </div>
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
            <ion-input v-model="editForm.username" label="Username" label-placement="stacked"
              placeholder="Enter username"></ion-input>
          </ion-item>
          <ion-item>
            <ion-input v-model="editForm.email" label="Email" label-placement="stacked" type="email"
              placeholder="Enter email"></ion-input>
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
            <ion-input v-model="passwordForm.currentPassword" label="Current Password" label-placement="stacked"
              type="password" placeholder="Enter current password"></ion-input>
          </ion-item>
          <ion-item>
            <ion-input v-model="passwordForm.newPassword" label="New Password" label-placement="stacked" type="password"
              placeholder="Enter new password"></ion-input>
          </ion-item>
          <ion-item>
            <ion-input v-model="passwordForm.confirmPassword" label="Confirm Password" label-placement="stacked"
              type="password" placeholder="Confirm new password"></ion-input>
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
            <ion-input v-model="editPostForm.title" label="Title" label-placement="stacked"
              placeholder="Enter post title"></ion-input>
          </ion-item>
          <ion-item>
            <ion-textarea v-model="editPostForm.content" label="Content" label-placement="stacked"
              placeholder="Enter post content" :rows="6"></ion-textarea>
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

// Use the profile endpoint for profile ops, and the dedicated user_posts endpoint for post mutations
const profileApi = 'http://localhost/cloneX/server_side/profile.php';
const postsApi = 'http://localhost/cloneX/server_side/user_posts.php';

onMounted(async () => {
  await loadUserProfile();
  await loadUserPosts();
});

const loadUserProfile = async () => {
  try {
    const username = localStorage.getItem('username') || '';
    if (!username) {
      userProfile.value = { username: '', email: '' };
      return;
    }

    // profile.php expects ?username=... and returns a single user object
    const response = await axios.get(`${profileApi}?username=${encodeURIComponent(username)}`, {
      headers: { 'X-API-Key': '12345' }
    });
    const user = response.data;
    userProfile.value = user && user.username ? { username: user.username, email: user.email || '' } : { username, email: '' };
  } catch (error) {
    console.error('Error loading profile:', error);
  }
};

const loadUserPosts = async () => {
  try {
    const username = localStorage.getItem('username') || '';
    if (!username) {
      userPosts.value = [];
      return;
    }

    // user_posts.php supports ?username=... and returns posts for a given username
    const response = await axios.get(`${postsApi}?username=${encodeURIComponent(username)}`, {
      headers: { 'X-API-Key': '12345' }
    });
    const data = Array.isArray(response.data) ? response.data : [];

    // Normalize date field naming so the UI uses `created_at` consistently
    userPosts.value = data.map((p: any) => ({
      id: p.id,
      title: p.title,
      content: p.content,
      created_at: p.createdAt ?? p.created_at ?? ''
    }));
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
    // Server expects current username in `username` and optional new username in `new_username`.
    // Use the current userProfile.username as the "current" username, and send editForm.username as new_username.
    const payload = {
      username: userProfile.value.username,
      email: editForm.value.email,
      new_username: editForm.value.username
    };

    const response = await axios.put(profileApi, payload, {
      headers: { 'X-API-Key': '12345' }
    });

    if (response.data.success) {
      // If username changed, update local storage so subsequent calls use the new username
      if (editForm.value.username && editForm.value.username !== userProfile.value.username) {
        localStorage.setItem('username', editForm.value.username);
      }

      // Refresh profile and posts to reflect changes
      await loadUserProfile();
      await loadUserPosts();

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
    const username = localStorage.getItem('username') || '';
    const response = await axios.put(`${postsApi}/${editPostForm.value.id}`, editPostForm.value, {
      headers: { 'X-API-Key': '12345', 'X-Username': username }
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
            const username = localStorage.getItem('username') || '';
            const response = await axios.delete(`${postsApi}/${postId}`, {
              headers: { 'X-API-Key': '12345', 'X-Username': username }
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

.profile-card,
.posts-card {
  margin: 1rem;
  border-radius: 16px;
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(10px);
}

.profile-title,
.posts-title {
  color: #ffffff;
  font-weight: 600;
}

.profile-item,
.post-item {
  --background: rgba(255, 255, 255, 0.03);
  --border-radius: 12px;
  margin: 0.5rem 0;
}

/* Make profile items look like post cards */
.profile-card ion-list {
  background: rgba(0, 0, 0, 0.22);
  padding: 12px;
  border-radius: 12px;
}

.profile-item {
  display: flex;
  flex-direction: column;
  align-items: stretch;
  border-radius: 10px;
  margin: 0.6rem 0;
}

.profile-item h3 {
  color: #ffffff;
  font-weight: 700;
  margin: 0 0 6px 0;
  font-size: 0.95rem;
}

.profile-item p {
  color: rgba(255,255,255,0.85);
  margin: 0;
  font-size: 0.92rem;
}

.edit-btn,
.password-btn,
.save-btn {
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

.post-actions {
  display: inline-flex;
  gap: 0.5rem;
  margin-top: 0;
  align-items: center;
}

.post-item {
  --padding-start: 12px;
  --padding-end: 12px;
  display: flex;
  flex-direction: column;
  align-items: stretch;
  border-radius: 10px;
  margin: 0.4rem 0;
}

.post-label {
  flex: 1 1 auto;
  min-width: 0;
}

.post-actions[slot="end"] {
  margin-top: 0;
  margin-left: 0.5rem;
}

/* Contrast the ion-list inside posts-card to make post items pop */
.posts-card ion-list {
  background: rgba(0, 0, 0, 0.22);
  padding: 12px;
  border-radius: 12px;
}

.post-actions-bottom {
  display: flex;
  justify-content: flex-start;
  gap: 0.5rem;
  margin-top: 12px;
}

/* Tweak buttons in bottom bar */
.post-actions-bottom ion-button {
  --padding-start: 8px;
  --padding-end: 8px;
  height: 36px;
}
</style>
