<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";

const form = useForm({
    id: "",
    issue: "",
    description: "",
    system_id: "",
    client_id: "",
    assigned_to: "",
    status: "",
    priority: "",
    category: "",
});

const props = defineProps({
    systems: Array,
    clients: Array,
    users: Array,
    ticket: Object, 
});

// Estado para errores de validación
const errors = ref({});

const statusColors = {
    open: "bg-red-500",
    in_progress: "bg-yellow-500",
    resolved: "bg-green-500",
    closed: "bg-gray-500",
    pending: "bg-blue-500",
};

const priorityColors = {
    low: "bg-green-400",
    medium: "bg-yellow-400",
    high: "bg-red-500",
};

// Función para validar el formulario
const validateForm = () => {
    errors.value = {}; // Reiniciar errores

    if (!form.issue.trim()) {
        errors.value.issue = "El asunto es obligatorio.";
    }

    if (!form.description.trim()) {
        errors.value.description = "La descripción es obligatoria.";
    }

    if (!form.system_id) {
        errors.value.system_id = "Debes seleccionar un sistema.";
    }

    if (!form.client_id) {
        errors.value.client_id = "Debes seleccionar un cliente.";
    }

    if (!form.category) {
        errors.value.category = "Debes seleccionar una categoría.";
    }

    if (!form.status) {
        errors.value.status = "Debes seleccionar un estado.";
    }

    if (!form.priority) {
        errors.value.priority = "Debes seleccionar una prioridad.";
    }


    // Retorna `true` si no hay errores
    return Object.keys(errors.value).length === 0;
};

// Función para enviar el formulario
const submit = () => {
    if (validateForm()) {
        form.put(route("tickets.update", form.id), {
            onSuccess: () => form.reset(),
        });
    }
};

// Rellenar el formulario con los datos del ticket actual
onMounted(() => {
    console.log(props.ticket);
    form.id = props.ticket.id;
    form.issue = props.ticket.issue;
    form.description = props.ticket.description;
    form.system_id = props.ticket.system_id;
    form.client_id = props.ticket.client_id;
    form.assigned_to = props.ticket.assigned_to?.id || "";
    form.status = props.ticket.status;
    form.priority = props.ticket.priority;
    form.category = props.ticket.category;
});
</script>

<template>
    <Head title="Editar Ticket" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Editar Ticket</h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto bg-white shadow-lg sm:rounded-lg p-8">
                <form @submit.prevent="submit">
                    <!-- Asunto -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Asunto</label>
                        <input v-model="form.issue" type="text" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <p v-if="errors.issue" class="text-red-500 text-sm mt-1">{{ errors.issue }}</p>
                    </div>

                    <!-- Descripción -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea v-model="form.description" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        <p v-if="errors.description" class="text-red-500 text-sm mt-1">{{ errors.description }}</p>
                    </div>

                    <!-- Sistema -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Sistema</label>
                        <select v-model="form.system_id" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Selecciona un sistema</option>
                            <option v-for="system in systems" :key="system.id" :value="system.id">{{ system.name }}</option>
                        </select>
                        <p v-if="errors.system_id" class="text-red-500 text-sm mt-1">{{ errors.system_id }}</p>
                    </div>

                    <!-- Categoría -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Categoría</label>
                        <select v-model="form.category" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Selecciona una categoría</option>
                            <option value="bug">Error</option>
                            <option value="feature_request">Funcionalidad</option>
                            <option value="question">Pregunta</option>
                            <option value="other">Otro</option>
                        </select>
                        <p v-if="errors.category" class="text-red-500 text-sm mt-1">{{ errors.category }}</p>
                    </div>

                    <!-- Cliente -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Cliente</label>
                        <select v-model="form.client_id" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Selecciona un cliente</option>
                            <option v-for="client in clients" :key="client.id" :value="client.id">{{ client.name }}</option>
                        </select>
                        <p v-if="errors.client_id" class="text-red-500 text-sm mt-1">{{ errors.client_id }}</p>
                    </div>

                    <!-- Estado -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Estado</label>
                        <select v-model="form.status" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Selecciona un estado</option>
                            <option value="open" :class="statusColors.open">Abierto</option>
                            <option value="in_progress" :class="statusColors.in_progress">En Progreso</option>
                            <option value="resolved" :class="statusColors.resolved">Resuelto</option>
                            <option value="closed" :class="statusColors.closed">Cerrado</option>
                            <option value="pending" :class="statusColors.pending">Pendiente</option>
                        </select>
                        <p v-if="errors.status" class="text-red-500 text-sm mt-1">{{ errors.status }}</p>
                    </div>

                    <!-- Prioridad -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Prioridad</label>
                        <select v-model="form.priority" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Selecciona una prioridad</option>
                            <option value="low" :class="priorityColors.low">Baja</option>
                            <option value="medium" :class="priorityColors.medium">Media</option>
                            <option value="high" :class="priorityColors.high">Alta</option>
                        </select>
                        <p v-if="errors.priority" class="text-red-500 text-sm mt-1">{{ errors.priority }}</p>
                    </div>

                    <!-- Asignado a -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Asignado a</label>
                        <select v-model="form.assigned_to" class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">No asignar</option>
                            <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                        </select>
                    </div>

                    <!-- Botón de envío -->
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
