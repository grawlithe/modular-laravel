<template>
  <div class="contact-manager max-w-4xl mx-auto p-4 sm:p-6 lg:p-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Contact Manager</h2>

    <!-- Form Card -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
        <form @submit.prevent="saveContact" class="space-y-4" enctype="multipart/form-data">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input id="name" v-model="form.name" placeholder="John Doe" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" v-model="form.email" type="email" placeholder="john.doe@example.com" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
            </div>
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">Profile Picture</label>
                <input id="image" type="file" @change="handleImageUpload" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"/>
            </div>
            <div class="text-right">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    {{ isEditing ? 'Update' : 'Add' }} Contact
                </button>
            </div>
        </form>
    </div>

    <!-- Contacts List -->
    <ul class="space-y-3">
      <li v-for="contact in contacts" :key="contact.id" class="bg-white shadow-sm rounded-md p-4 flex justify-between items-center border border-gray-200">
        <div class="flex items-center">
            <img :src="getImageUrl(contact.image)" alt="Contact Image" class="h-10 w-10 rounded-full object-cover">
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-900">{{ contact.name }}</p>
                <p class="text-sm text-gray-500">{{ contact.email }}</p>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <button @click="editContact(contact)" class="text-gray-400 hover:text-indigo-600 p-2 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <!-- Heroicon: pencil -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                </svg>
            </button>
            <button @click="deleteContact(contact.id)" class="text-gray-400 hover:text-red-600 p-2 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                 <!-- Heroicon: trash -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios'; // Use axios for API calls

// --- State ---
const contacts = ref([]);
const form = ref({ id: null, name: '', email: '', image: null });
const isEditing = ref(false);

// --- API Base URL ---
// Adjust this to your actual module API URL!
const API_URL = '/api/contacts/contacts';

// --- Methods ---

// 1. Fetch All Contacts
const fetchContacts = async () => {
    try {
        const response = await axios.get(API_URL);
        contacts.value = response.data;
    } catch (error) {
        console.error("Error fetching contacts:", error);
    }
};

// 2. Save (Add or Update) Contact
const saveContact = async () => {
    const formData = new FormData();
    formData.append('name', form.value.name);
    formData.append('email', form.value.email);
    if (form.value.image) {
        formData.append('image', form.value.image);
    }

    try {
        if (isEditing.value) {
            // For updates, we need to use POST with a _method field for multipart/form-data
            formData.append('_method', 'PUT');
            const response = await axios.post(`${API_URL}/${form.value.id}`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });
            const index = contacts.value.findIndex(c => c.id === form.value.id);
            if (index !== -1) {
                contacts.value[index] = response.data;
            }
        } else {
            // Add New
            const response = await axios.post(API_URL, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });
            contacts.value.unshift(response.data); // Add new contact to the top
        }

        resetForm();
    } catch (error) {
        console.error("Error saving contact:", error);
        // Handle validation errors (error.response.data.errors)
    }
};

// 2.5 Handle Image Upload
const handleImageUpload = (event) => {
    form.value.image = event.target.files[0];
};

// 3. Set up Edit
const editContact = (contact) => {
    form.value = { ...contact }; // Copy contact data to the form
    isEditing.value = true;
};

// 4. Delete Contact
const deleteContact = async (id) => {
    if (!confirm('Are you sure you want to delete this contact?')) return;
    try {
        await axios.delete(`${API_URL}/${id}`);
        contacts.value = contacts.value.filter(contact => contact.id !== id);
    } catch (error) {
        console.error("Error deleting contact:", error);
    }
};

// 5. Reset Form
const resetForm = () => {
    form.value = { id: null, name: '', email: '', image: null };
    isEditing.value = false;
    // Also reset the file input if you have a ref to it
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.value = '';
    }
};

// 6. Get Image URL
const getImageUrl = (imagePath) => {
    if (imagePath) {
        console.log(imagePath);
        // Assuming 'storage' is linked and accessible from the public directory
        return `/storage/${imagePath}`;
    }
    // Return a default placeholder image if no image is set
    return 'https://via.placeholder.com/150';
};

// --- Lifecycle Hook ---
onMounted(fetchContacts);
</script>
