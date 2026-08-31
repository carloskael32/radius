<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
		type: String,
    },
});

const form = useForm();

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <Head title="Email Verification" />

    <GuestLayout>
        <Link href="/" class="mb-4 flex items-center justify-center">
            <ApplicationLogo class="h-20 w-20 fill-current text-slate-500 dark:text-slate-400" />
        </Link>

        <div class="mb-4 text-sm text-slate-600 dark:text-slate-400">
            Thanks for signing up! Before getting started, could you verify your email address by clicking on the link
            we just emailed to you? If you didn't receive the email, we will gladly send you another.
        </div>

        <div class="mb-4 text-sm font-medium text-emerald-600 dark:text-emerald-400" v-if="verificationLinkSent">
            A new verification link has been sent to the email address you provided during registration.
        </div>

        <form @submit.prevent="submit">
            <div class="mt-4 flex items-center justify-between gap-3">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Resend Verification Email
                </PrimaryButton>

                <Link :href="route('logout')" method="post" as="button" class="text-sm text-slate-600 underline hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200">
                    Log Out
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
