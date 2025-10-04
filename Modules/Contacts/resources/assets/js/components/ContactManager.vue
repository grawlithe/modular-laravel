<template>
  <div class="contact-manager">
    <h2>Contact Manager</h2>

    <form @submit.prevent="saveContact" class="mb-4">
        <input v-model="form.name" placeholder="Name" required class="p-2 border" />
        <input v-model="form.email" type="email" placeholder="Email" required class="p-2 border" />
        <button type="submit" class="bg-blue-500 text-white p-2">{{ isEditing ? 'Update' : 'Add' }} Contact</button>
    </form>

    <ul class="list-disc ml-5">
      <li v-for="contact in contacts" :key="contact.id" class="mb-2 p-2 border-b flex justify-between items-center">
        <span>{{ contact.name }} ({{ contact.email }})</span>
        <div>
            <button @click="editContact(contact)" class="text-yellow-600 mr-2">Edit</button>
            <button @click="deleteContact(contact.id)" class="text-red-600">Delete</button>
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
const form = ref({ id: null, name: '', email: '' });
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
    try {
        if (isEditing.value) {
            // Update
            const response = await axios.put(`${API_URL}/${form.value.id}`, form.value);
            // Replace the old contact in the list with the updated one
            const index = contacts.value.findIndex(c => c.id === form.value.id);
            if (index !== -1) {
                contacts.value[index] = response.data;
            }
        } else {
            // Add New
            const response = await axios.post(API_URL, form.value);
            contacts.value.unshift(response.data); // Add new contact to the top
        }

        resetForm();
    } catch (error) {
        console.error("Error saving contact:", error);
        // Handle validation errors (error.response.data.errors)
    }
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
    form.value = { id: null, name: '', email: '' };
    isEditing.value = false;
};

// --- Lifecycle Hook ---
onMounted(fetchContacts);
</script>
