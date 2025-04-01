<script setup>
import { usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

const { ticket } = usePage().props;  // Obtén los datos del ticket

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
</script>

<template>

    <Head title="Detalle del Ticket - {{ ticket.id }}" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Ticket #{{ ticket.id }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <!-- Contenedor de detalles del ticket -->
                <div class="bg-white shadow sm:rounded-lg p-6 space-y-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700">Detalles del Ticket</h3>
                            <p class="text-sm text-gray-500">Información detallada sobre el ticket seleccionado.</p>
                        </div>
                        <Link :href="route('tickets.index')" class="text-blue-600 hover:text-blue-800 transition">Volver
                        a la
                        lista</Link>
                    </div>

                    <div class="space-y-4">
                        <!-- Asunto -->
                        <div class="flex items-center space-x-4">
                            <span class="font-semibold text-gray-700 w-1/4">Asunto:</span>
                            <p class="text-gray-600">{{ ticket.issue }}</p>
                        </div>

                        <!-- Sistema -->
                        <div class="flex items-center space-x-4">
                            <span class="font-semibold text-gray-700 w-1/4">Sistema:</span>
                            <p class="text-gray-600">{{ ticket.system.name }}</p>
                        </div>

                        <!-- Cliente -->
                        <div class="flex items-center space-x-4">
                            <span class="font-semibold text-gray-700 w-1/4">Cliente:</span>
                            <p class="text-gray-600">{{ ticket.client.name }}</p>
                        </div>

                        <!-- Estado -->
                        <div class="flex items-center space-x-4">
                            <span class="font-semibold text-gray-700 w-1/4">Estado:</span>
                            <span
                                :class="`px-2 py-1 text-white text-xs font-semibold rounded ${statusColors[ticket.status]}`">
                                {{ ticket.status }}
                            </span>
                        </div>

                        <!-- Prioridad -->
                        <div class="flex items-center space-x-4">
                            <span class="font-semibold text-gray-700 w-1/4">Prioridad:</span>
                            <span
                                :class="`px-2 py-1 text-white text-xs font-semibold rounded ${priorityColors[ticket.priority]}`">
                                {{ ticket.priority }}
                            </span>
                        </div>

                        <!-- Categoría -->
                        <div class="flex items-center space-x-4">
                            <span class="font-semibold text-gray-700 w-1/4">Categoría:</span>
                            <p class="text-gray-600">{{ ticket.category }}</p>
                        </div>

                        <!-- Asignado a -->
                        <div class="flex items-center space-x-4">
                            <span class="font-semibold text-gray-700 w-1/4">Asignado a:</span>
                            <p class="text-gray-600">{{ ticket.assigned_to?.name || "No asignado" }}</p>
                        </div>

                        <!-- Descripción -->
                        <div class="space-y-2">
                            <span class="font-semibold text-gray-700">Descripción:</span>
                            <p class="text-gray-600">{{ ticket.description }}</p>
                        </div>

                        <!-- Resolución -->
                        <div class="space-y-2">
                            <span class="font-semibold text-gray-700">Resolución:</span>
                            <p class="text-gray-600">{{ ticket.resolution || "Sin resolución por el momento." }}</p>
                        </div>

                        <!-- Comentarios -->
                        <div class="space-y-2">
                            <span class="font-semibold text-gray-700">Comentarios:</span>
                            <p class="text-gray-600">{{ ticket.comments }}</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
