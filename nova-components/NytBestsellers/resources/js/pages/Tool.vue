<template>
    <div>

        <Head title="Nyt Bestsellers" />

        <Heading class="mb-6">New York Times Bestsellers</Heading>

        <Card v-for="category in data.items" class="mb-6 px-2 md:px-4 py-2">
            <h2 class="text-xl font-bold mb-6">{{ category.display_name }}</h2>

            <ul class="flex space-x-4 overflow-x-auto flex-nowrap pb-2">
                <li v-for="book in category.books">
                    <img :src="book.book_image" alt="" class="w-32 aspect-[1/1.5]">
                    <h3 class="mt-2 font-bold truncate w-32">{{ book.title }}</h3>
                    <span class="block w-32 truncate">{{ book.author }}</span>
                    <div class="mt-2">
                        <a v-if="book.buy_links?.[0]?.url" :href="book.buy_links[0].url" target="_blank"
                            rel="noopener noreferrer"
                            class="shrink-0 h-9 px-4 focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring text-white dark:text-gray-800 inline-flex items-center font-bold shadow rounded focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring bg-primary-500 hover:bg-primary-400 active:bg-primary-600 text-white dark:text-gray-800 inline-flex items-center font-bold px-4 h-9 text-sm">
                            Buy
                        </a>
                    </div>
                </li>
            </ul>
        </Card>
    </div>
</template>

<script setup>
import { onMounted, reactive } from "vue";

const data = reactive({
    items: [],
});

onMounted(() => {
    Nova.request().get('/nova-vendor/nyt-bestsellers/')
        .then(response => {
            data.items = response.data;
        });
});
</script>

<style>
ul::-webkit-scrollbar {
    height: 8px;
}

ul::-webkit-scrollbar-track {
    background: transparent;
}

ul::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 9999px;
}

ul::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
