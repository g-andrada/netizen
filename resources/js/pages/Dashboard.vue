<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { onMounted, ref } from 'vue';
import axios from 'axios';
import { useEchoPublic, useEcho, useEchoPresence, useEchoModel } from '@laravel/echo-vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

const page = usePage();
const user = page.props.auth.user;

// API test
onMounted(async () => {
    try {
        const response = await axios.get('/api/user');
        console.log(response);
    } catch (error) {
        console.log(error)
    }
});

// Public event test dispatch
onMounted(async () => {
    try {
        const response = await axios.get('/api/broadcast/public-event');
        console.log(response);
    } catch (error) {
        console.log(error)
    }
});

// Public Channel
onMounted(() => {
    useEchoPublic(
        'channel',
        'TestPublicEvent',
        (response) => {
            console.log('Public Test Event: ', response)
        })
        .listen();
});

// Database event test
onMounted(() => {
    useEchoModel(
        'App.Models.User',
        user.id,
        ['UserCreated'],
        (response) => {
            console.log('yes');
            const data = response
            console.log(data);
        }
    );
});

// Customized broadcast name should lead a '.' character to avoind namespace conflict with Echo
onMounted(() => {
    useEcho(
        'users',
        '.user.created',
        (response) => {
            console.log('Private Model Event Test: ', response);
        }
    ).listen();
})

// Private Channel
onMounted(() => {
    useEcho(
        `channel-${user.id}`,
        'TestPrivateEvent',
        (response) => {
            console.log('Private Event Test: ', response);
        }
    )
    .listen();
});

// Presence Channel
onMounted(() => {
    // Indicate which channel id you wish to join
    // i.e presence-channel-{roomId}
    useEchoPresence(`presence-channel-2`, 'TestPresenceEvent', (response) => {
        console.log(response);
    })
    .channel()
    .here((response) => {
        console.log('Presence Channel - Here: ', response);
    })
    .joining((response) => {
        console.log('Presence Channel - Joining: ', response);
    })
    .listen("TestPresenceEvent", (response) => {
        console.log('Presence Channel - Listening: ', response);
    })
    .leaving((response) => {
        console.log('Presence Channel - Leaving: ', response);
    });
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
                >
                    <PlaceholderPattern />
                </div>
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
                >
                    <PlaceholderPattern />
                </div>
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
                >
                    <PlaceholderPattern />
                </div>
            </div>
            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border"
            >
                <PlaceholderPattern />
            </div>
        </div>
    </AppLayout>
</template>
