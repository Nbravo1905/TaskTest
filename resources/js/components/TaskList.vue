<template>
  <div class="container mt-5">
    <h2 class="mb-4">Gestión de Tareas</h2>

    <!-- Formulario para crear nueva tarea -->
    <div class="card mb-4">
      <div class="card-body">
        <h5 class="card-title">Crear Nueva Tarea</h5>
        <form @submit.prevent="createTask">
          <div class="mb-3">
            <label for="title" class="form-label">Título</label>
            <input type="text" class="form-control" id="title" v-model="newTask.title" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Palabras Clave</label>
            <!-- Lista de checkboxes para seleccionar keywords -->
            <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
              <div v-for="keyword in keywords" :key="keyword.id" class="form-check form-check-inline me-3 mb-2">
                <input 
                  class="form-check-input" 
                  type="checkbox" 
                  :id="`kw-${keyword.id}`" 
                  :value="keyword.id" 
                  v-model="newTask.keyword_ids"
                >
                <label class="form-check-label" :for="`kw-${keyword.id}`">
                  {{ keyword.name }}
                </label>
              </div>
            </div>
            <!-- Badges de seleccionadas, removibles como ingredientes -->
            <div class="mt-2">
              <span 
                v-for="id in newTask.keyword_ids" 
                :key="id" 
                class="badge bg-primary me-2 mb-1 d-inline-flex align-items-center"
                style="cursor: pointer;"
                @click="removeKeyword(id)"
              >
                {{ getKeywordName(id) }}
                <i class="ms-1 bi bi-x" style="font-size: 0.8em;"></i>
              </span>
            </div>
          </div>
          <button type="submit" class="btn btn-primary">Crear Tarea</button>
        </form>
      </div>
    </div>

    <!-- Lista de tareas -->
    <div class="list-group">
      <div v-for="task in tasks" :key="task.id" class="list-group-item d-flex justify-content-between align-items-center">
        <div>
          <h5 :class="{ 'text-decoration-line-through': task.is_done }">{{ task.title }}</h5>
          <span class="badge bg-secondary me-2" v-for="keyword in task.keywords" :key="keyword.id">{{ keyword.name }}</span>
          <span class="badge" :class="task.is_done ? 'bg-success' : 'bg-warning'">{{ task.is_done ? 'Completada' : 'Pendiente' }}</span>
        </div>
        <button class="btn btn-sm" :class="task.is_done ? 'btn-outline-success' : 'btn-outline-warning'" @click="toggleTask(task.id)">
          {{ task.is_done ? 'Marcar como Pendiente' : 'Marcar como Completada' }}
        </button>
      </div>
    </div>

    <!-- Manejo de errores -->
    <div v-if="error" class="alert alert-danger mt-3">{{ error }}</div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';

const tasks = ref([]);
const keywords = ref([]);
const newTask = ref({ title: '', keyword_ids: [] });
const error = ref(null);

const apiUrl = '/api'; // Laravel API base URL

const fetchTasks = async () => {
  try {
    const response = await axios.get(`${apiUrl}/tasks`);
    tasks.value = response.data;
  } catch (err) {
    error.value = 'Error al cargar tareas: ' + err.message;
  }
};

const fetchKeywords = async () => {
  try {
    const response = await axios.get(`${apiUrl}/keywords`);
    keywords.value = response.data;
  } catch (err) {
    error.value = 'Error al cargar palabras clave: ' + err.message;
  }
};

const createTask = async () => {
  try {
    const response = await axios.post(`${apiUrl}/tasks`, newTask.value);
    tasks.value.push(response.data);
    newTask.value = { title: '', keyword_ids: [] };
  } catch (err) {
    error.value = 'Error al crear tarea: ' + (err.response?.data?.message || err.message);
  }
};

const toggleTask = async (id) => {
  try {
    const response = await axios.post(`${apiUrl}/tasks/${id}/toggle`);
    const index = tasks.value.findIndex(t => t.id === id);
    if (index !== -1) tasks.value[index] = response.data;
  } catch (err) {
    error.value = 'Error al alternar estado: ' + err.message;
  }
};

const getKeywordName = (id) => {
  const keyword = keywords.value.find(k => k.id === parseInt(id));
  return keyword ? keyword.name : '';
};

const removeKeyword = (id) => {
  newTask.value.keyword_ids = newTask.value.keyword_ids.filter(k => k !== id);
};

onMounted(() => {
  fetchTasks();
  fetchKeywords();
});
</script>

<style>
/* Estilos personalizados si es necesario */
.form-check-inline .form-check-label {
  cursor: pointer;
  user-select: none;
}

.badge:hover {
  opacity: 0.8;
}
</style>