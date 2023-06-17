<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import ButtonSend from '@/Components/ButtonSend.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    messages: {
        type: Object,
    }
});

const form = useForm({
    message: '',
    room_id: '1'
});

const submit = () => {
    form.post(route('chats.store'), {
        onFinish: () => form.reset('message'),
    });
};

const data = ref([
    {
        'user_id': 1,
        'name': 'Sinthon Seng',
        'message': 'Hello guy!'
    },
    {
        'user_id': 2,
        'name': 'Robot User',
        'message': 'Hello'
    },
    {
        'user_id': 2,
        'name': 'Robot User',
        'message': 'May I help you?'
    },
]);
</script>

<template>
    <Head title="Chat" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="px-3 first-letter:font-semibold my-3 text-xl text-gray-800 leading-tight">Chat</h2>
        </template>

        <div class="h-full bg-white flex flex-col justify-between">

            <div class="flex-none text-xl font-bold text-start p-3 border">
                General
            </div>

            <div class="container-snap flex flex-col-reverse grow p-3 h-14 bg-gray-100 overflow-y-scroll">
                <div class="grid justify-items-stretch" v-for="message in messages">
                    <div class="border rounded px-2 py-1 my-1 bg-blue-200"
                        :class="{ 'justify-self-end': true, 'justify-self-start': false }">
                        {{ message.message }}
                    </div>
                </div>
            </div>

            <div class="flex-none p-3 h-auto">
                <form @submit.prevent="submit">
                    <div class="w-full relative">
                        <TextInput id="message" type="text" class="mt-1 block w-full" v-model="form.message" required
                            autofocus autocomplete="message" aria-placeholder="Test"/>
                        <InputError class="mt-2" :message="form.errors.message" />
                        <ButtonSend class="absolute h-full place-self-center right-0 top-0 px-3">Send</ButtonSend>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


<style scoped>
/* Hide scrollbar for Chrome, Safari and Opera */
.container-snap::-webkit-scrollbar {
    display: none;
}

/* Hide scrollbar for IE, Edge and Firefox */
.container-snap {
    -ms-overflow-style: none;
    /* IE and Edge */
    scrollbar-width: none;
    /* Firefox */
}
</style>