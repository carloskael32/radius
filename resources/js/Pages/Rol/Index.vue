<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast';


//datatable primevue
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
//para el filter
const FilterMatchMode = {
    CONTAINS: 'contains',
};


//para el boton de filtrado de datos
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS } // CORREGIDO
});

//para exportar la informacion
// Función para exportar a CSV
const dt = ref();
const toast = useToast();

const exportCSV = () => {
    if (dt.value) {
        dt.value.exportCSV({
            selectionOnly: false, // Exportar todos los datos
            fileName: 'usuarios.csv'
        });
    }
};

//resepcion de datos desde el controlador RolController
const props = defineProps({
    roles: { type: Array, default: () => [] },
    permisos: { type: Array, default: () => [] },
});

//Variables para gestionar seleciones de permisos
const selectPermisos = ref([]);



// para manejar el estado de los campos del formulario en Vue usando inertia
const form = useForm({
    name: '',
    description: '',
    permisosSelect: '',

});

const eform = ref({
    id: '',
    name: '',
    description: '',

})
//const mn = defineProps(['success']);

const operation = ref(1);

//controlador de modal de formulario para crear o editar
const showModalForm = ref(false);
const showModalDel = ref(false);
const title = ref('');

const openModalForm = (op, r) => {
    showModalForm.value = true;
    operation.value = op;
    if (op == 1) {
        title.value = 'Nuevo Rol';
        form.reset();
        selectPermisos.value = [];
    } else {
        title.value = 'Actualizar Rol';
        form.name = r.name;
        form.description = r.description;
        eform.value.id = r.id;
        // Cargar los permisos del rol en los checkboxes
        selectPermisos.value = r.permissions.map(p => p.id);
    }
}

// envio de datos al controlador insert update
const save = () => {
    //para enviar los permisos seleccionados
    const selectPermiIds = [...selectPermisos.value];
    if (selectPermiIds.length === 0) {
        //error('Debes seleccionar al menos un permiso');
        toast.add({ severity: 'error', summary: 'Debes seleccionar al menos un permiso', life: 3000 });
        closeModalForm();
        return;
    }

    if (operation.value == 1) {
        form.permisosSelect = selectPermiIds;
        form.post(route('rol.store'), {
            onSuccess: () => {
                ok('Rol guardado exitosamente'); // Mostrar mensaje de éxito
                closeModalForm(); // Cerrar el modal y resetear el formulario
            },
        });
    } else {
        form.permisosSelect = selectPermiIds;
        form.put(route('rol.update', eform.value.id), {
            onSuccess: () => {
                ok('Rol actualizado')
                closeModalForm();

            },
        });
    }
}
const ok = (m) => {
    form.reset();
    toast.add({ severity: 'success', summary: 'Éxito', detail: m, life: 3000 });
}



const closeModalForm = () => {
    showModalForm.value = false;
    form.reset();
    selectPermisos.value = [];
}
//controlador de modal para eliminar
const openModalDel = (r) => {
    showModalDel.value = true;
    eform.value.id = r.id;
    eform.value.name = r.name;

}
const closeModalDel = () => {
    showModalDel.value = false;
}

// funcion para eliminar registros
const deleteUser = () => {
    form.delete(route('rol.destroy', eform.value.id), {
        onSuccess: () => {
            ok('Rol eliminado');

            closeModalDel();

        }
    });

}
// para Validaciones de entrada
// Mapeo de nombres de campos a etiquetas legibles
const fieldLabels = {
    name: 'Nombre de rol',
    description: 'Descripción',
    permisosSelect: 'Permisos',
};

//Funcion para limpiar errores cuando se cambia un campo
const clearError = (field) => {
    form.clearErrors(field);
}
//funcion para obtener mensaje de error de un input
const getErrorMessage = (field) => {
    if (!form.errors[field]) return '';
    let message = form.errors[field];
    // Reemplazar el nombre del campo por la etiqueta legible
    const fieldLabel = fieldLabels[field] || field;
    message = message.replace(field, fieldLabel);
    return message;
}
//Funcion para validar si un campo tiene un error
const hasError = (field) => {
    return !!form.errors[field];
}





// pestañas para filtrar permisos 
const activeTab = ref('todos');

// Función genérica para filtrar permisos por keyword
const filterPermissionsByKeyword = (list, keyword) => {
    if (keyword === 'todos') return list; // Mostrar todos
    return list.filter(p => p.name.toLowerCase().includes(keyword.toLowerCase()));
};

// Configuración de pestañas con keywords de filtro
const pestana = [
    { id: 'todos', label: 'Todos', icon: '🔧', keyword: 'todos' },
    { id: 'usuarios', label: 'Usuarios', icon: '🪪', keyword: 'usuarios' },
    { id: 'roles', label: 'Roles', icon: '🔐', keyword: 'roles' },
    { id: 'nas', label: 'Mikrotik - NAS', icon: '📡', keyword: 'nas' },
    { id: 'clientes', label: 'Clientes', icon: '👥', keyword: 'clientes' },
    { id: 'plan_servicio', label: 'Planes de Servicio', icon: '📋', keyword: 'plan' },
    { id: 'reportes', label: 'Reportes', icon: '🗃️', keyword: 'reportes' },
    { id: 'auditoria', label: 'Auditoria', icon: '🖥️', keyword: 'auditoria' },
];

// Computed para obtener permisos filtrados por pestaña activa
const filteredPermissions = computed(() => {
    const activeTabData = pestana.find(p => p.id === activeTab.value);
    if (!activeTabData) return [];
    return filterPermissionsByKeyword(props.permisos, activeTabData.keyword);
});

//seccion de permisos
const page = usePage();

const canAdd = computed(() =>
    page.props.auth.user.permissions.includes('crear roles')
);
const canDelete = computed(() =>
    page.props.auth.user.permissions.includes('eliminar roles')
);

// Roles protegidos que no se pueden eliminar
const rolesProtegidos = ['Administrador', 'Operador', 'Usuario de Consulta'];

// Función para verificar si un rol puede ser eliminado
const canDeleteRole = (rolName) => {
    return !rolesProtegidos.includes(rolName);
};
const canEdit = computed(() =>
    page.props.auth.user.permissions.includes('modificar roles')
);




</script>


<template>

    <Head title="Roles" />

    <AuthenticatedLayout>
        <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-100">
            <div class="flex items-start justify-between pb-4">
                <div>
                    <h1 class="mb-1 text-2xl font-semibold text-gray-900">Gestión de Roles</h1>
                    <p class="text-sm text-gray-500">Numero de Roles: <span class="font-medium text-gray-700">{{
                        Object.keys(roles).length }}</span></p>
                </div>
                <div v-if="canAdd" class="flex items-center gap-3">
                    <PrimaryButton @click="openModalForm(1)" class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-11.25a.75.75 0 0 0-1.5 0v2.5h-2.5a.75.75 0 0 0 0 1.5h2.5v2.5a.75.75 0 0 0 1.5 0v-2.5h2.5a.75.75 0 0 0 0-1.5h-2.5v-2.5Z"
                                clip-rule="evenodd" />
                        </svg>
                        Agregar Rol
                    </PrimaryButton>
                </div>
            </div>


            <!-- CUERPO -->
            <div class="w-full overflow-hidden ">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

                    <DataTable :value="roles" v-model:filters="filters" ref="dt" selectionMode="single"
                        :globalFilterFields="['name', 'description']" paginator :rows="5"
                        :rowsPerPageOptions="[5, 10, 20, 50]"
                        paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
                        currentPageReportTemplate="{first} a {last} de {totalRecords}">

                        <template #header>
                            <!-- Filtro de búsqueda -->
                            <div
                                class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                                <div class="w-full md:w-auto">
                                    <IconField iconPosition="left" class="w-full md:w-64">
                                        <InputIcon>
                                            <i class="pi pi-search" />
                                        </InputIcon>
                                        <InputText v-model="filters['global'].value" placeholder="Buscar rol..."
                                            class="w-full pl-8 rounded-lg" />
                                    </IconField>
                                </div>

                                <!-- Botones de exportación -->
                                <button type="button" @click="exportCSV"
                                    class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 text-sm">📊
                                    Excel</button>
                            </div>
                        </template>


                        <Column field="name" sortable header="nombre de rol"
                            headerClass="border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider"
                            bodyClass="border border-gray-300">
                        </Column>
                        <Column field="description" sortable header="descripcion"
                            headerClass="border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider"
                            bodyClass="border border-gray-300">
                        </Column>

                        <Column v-if="canEdit || canDelete" header="acciones" #body="slotProps"
                            bodyClass="border border-gray-300"
                            headerClass="border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider">

                            <!-- BOTONES PARA EDITAR Y BORRAR -->
                            <div class="flex gap-2 items-center justify-center ">
                                <!-- BOTON DE EDITAR -->
                                <button v-if="canEdit && canDeleteRole(slotProps.data.name)"
                                    @click="openModalForm(2, slotProps.data)"
                                    class="inline-flex p-2 rounded-md hover:bg-blue-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-blue-600">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                    </svg>
                                </button>
                                <div v-else-if="rolesProtegidos.includes(slotProps.data.name)"
                                    class="inline-flex p-2 rounded-md opacity-40 cursor-not-allowed"
                                    title="Este rol no se puede modificar">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-gray-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                    </svg>
                                </div>
                                <!-- BOTON DE ELIMINAR -->
                                <button v-if="canDelete && canDeleteRole(slotProps.data.name)"
                                    @click="openModalDel(slotProps.data)"
                                    class="inline-flex p-2 rounded-md hover:bg-red-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-red-600">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                                <div v-else-if="rolesProtegidos.includes(slotProps.data.name)"
                                    class="inline-flex p-2 rounded-md opacity-40 cursor-not-allowed"
                                    title="Este rol no se puede eliminar">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-gray-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </div>
                            </div>
                        </Column>

                    </DataTable>
                </div>
            </div>
        </div>





        <!-- MODAL PARA FORMULARIO DE REGISTRO -->
        <Modal :show="showModalForm" @close="closeModalForm" maxWidth="xxxl">
            <div class="p-5">
                <div class="flex justify-between items-center pb-4">
                    <h2 class="text-lg font-medium text-gray-900">{{ title }}</h2>
                    <button @click="closeModalForm" class="text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form @submit.prevent="save">
                    <div class="grid gap-4 pb-4">
                        <div>
                            <label for="name" class="block mb-1.5 text-sm font-medium text-gray-900">Nombre de
                                Rol</label>
                            <input type="text" id="name" v-model="form.name"
                                class="block w-full px-2.5 py-2 text-sm text-gray-900 border border-gray-300 rounded-md shadow-xs focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Operador 2" />
                            <p v-if="hasError('name')" class="mt-1 text-sm text-red-600">
                                {{ getErrorMessage('name') }}
                            </p>
                        </div>

                    </div>
                    <div class="pb-4">
                        <div>
                            <label for="description"
                                class="block mb-1.5 text-sm font-medium text-gray-900">Descripcion</label>
                            <textarea id="description" v-model="form.description"
                                class="block w-full px-2.5 py-2 text-sm text-gray-900 border border-gray-300 rounded-md shadow-xs focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="descripcion del nuevo rol" />
                            <p v-if="hasError('description')" class="mt-1 text-sm text-red-600">
                                {{ getErrorMessage('description') }}

                            </p>
                        </div>

                    </div>

                    <!-- botones para ver los permisos -->
                    <div class="pb-2 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                        <button type="button" v-for="tab in pestana" :key="tab.id" @click="activeTab = tab.id" :class="[
                            'p-2 rounded-lg border-2 transition-all text-center hover:shadow-md',
                            activeTab === tab.id
                                ? 'border-blue-500 bg-blue-50'
                                : 'border-gray-200 hover:border-gray-300 bg-white'
                        ]">
                            <div class="text-2xl mb-2">{{ tab.icon }}</div>
                            <p :class="[
                                'font-medium text-xs',
                                activeTab === tab.id ? 'text-blue-900' : 'text-gray-700'
                            ]">
                                {{ tab.label }}
                            </p>
                        </button>
                    </div>

                    <!-- SECCIONES DE PERMISOS (Generado dinámicamente) -->
                    <div v-if="filteredPermissions.length > 0" class="space-y-4">
                        <div class="bg-white p-6 rounded-lg shadow-lg" :class="{
                            'border-gray-300': activeTab === 'todos',
                            'border-gray-100': activeTab !== 'todos'
                        }">
                            <div class="flex flex-col gap-4 lg:flex-row lg:justify-between lg:items-center mb-6">
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900">
                                        {{pestana.find(p => p.id === activeTab)?.label}}
                                    </h2>
                                </div>
                            </div>

                            <!-- Listar permisos para asignarlos al rol -->
                            <div class="space-y-3">
                                <div class="text-sm font-medium text-gray-700 mb-1">
                                    Total permisos: <span class="text-blue-600 font-semibold">{{
                                        filteredPermissions.length
                                        }}</span>
                                </div>
                                <div class="max-h-96 overflow-y-auto border border-gray-200 rounded-lg">
                                    <div v-for="perm in filteredPermissions" :key="perm.id"
                                        class="flex items-center justify-between p-3 border-b border-gray-100 hover:bg-gray-50">
                                        <div class="flex items-center gap-3">
                                            <input type="checkbox" :id="perm.id" :value="perm.id"
                                                v-model="selectPermisos" class="rounded" />
                                            <div>
                                                <label class="font-medium text-gray-900 cursor-pointer">
                                                    {{ perm.name }}
                                                </label>
                                                <p class="text-xs text-gray-500">
                                                    {{ perm.description || 'Sin descripción' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="pt-4 grid grid-cols-2 gap-4">
                        <div>
                            <SecondaryButton class="w-full" @click="closeModalForm">Cancelar</SecondaryButton>
                        </div>
                        <div>
                            <PrimaryButton class="w-full">Guardar</PrimaryButton>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- MODAL PARA ELIMINAR DATOS -->
        <Modal :show="showModalDel" @close="closeModalDel" maxWidth="md">
            <div class="p-5">
                <div class="flex justify-between items-center pb-6">
                    <h2 class="text-lg font-medium text-gray-900">Confirmar eliminación</h2>
                    <button @click="closeModalDel" class="text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex flex-col gap-4 pb-6">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 pt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-20 text-red-600">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 leading-relaxed">
                                Está por eliminar el siguiente rol:
                            </p>
                            <p class="mt-2 text-base font-semibold text-gray-900">
                                {{ eform.name }}
                            </p>
                            <p class="mt-3 text-sm text-red-600">
                                Esta acción no se puede deshacer.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <SecondaryButton class="w-full" @click="closeModalDel">Cancelar</SecondaryButton>
                    </div>
                    <div>
                        <PrimaryButton class="w-full bg-red-600 hover:bg-red-700" @click="deleteUser">Eliminar
                        </PrimaryButton>
                    </div>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
