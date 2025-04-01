<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const { tickets } = usePage().props;

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

    <Head title="Gestión de Tickets" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Gestión de Tickets
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white shadow sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Lista de Tickets</h3>
                        <Link :href="route('tickets.create')"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        + Nuevo Ticket
                        </Link>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="p-3 border">ID</th>
                                    <th class="p-3 border">Asunto</th>
                                    <th class="p-3 border">Sistema</th>
                                    <th class="p-3 border">Cliente</th>
                                    <th class="p-3 border">Estado</th>
                                    <th class="p-3 border">Prioridad</th>
                                    <th class="p-3 border">Categoría</th>
                                    <th class="p-3 border">Asignado a</th>
                                    <th class="p-3 border">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="ticket in tickets" :key="ticket.id" class="text-center">
                                    <td class="p-3 border">{{ ticket.id }}</td>
                                    <td class="p-3 border">{{ ticket.issue }}</td>
                                    <td class="p-3 border">{{ ticket.system.name }}</td>
                                    <td class="p-3 border">{{ ticket.client.name }}</td>
                                    <td class="p-3 border">
                                        <span
                                            :class="`px-2 py-1 text-white text-xs font-semibold rounded ${statusColors[ticket.status]}`">
                                            {{ ticket.status }}
                                        </span>
                                    </td>
                                    <td class="p-3 border">
                                        <span
                                            :class="`px-2 py-1 text-white text-xs font-semibold rounded ${priorityColors[ticket.priority]}`">
                                            {{ ticket.priority }}
                                        </span>
                                    </td>
                                    <td class="p-3 border">{{ ticket.category }}</td>
                                    <td class="p-3 border">{{ ticket.assigned_to?.name || "No asignado" }}</td>
                                    <td class="p-3 border flex justify-center space-x-2">
                                        <Link :href="route('tickets.show', ticket.id)"
                                            class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition text-sm">
                                        Ver
                                        </Link>
                                        <Link :href="route('tickets.edit', ticket.id)"
                                            class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition text-sm">
                                        Editar
                                        </Link>
                                        <Link :href="route('tickets.destroy', ticket.id)" method="delete"
                                            class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition text-sm">
                                        Eliminar
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
