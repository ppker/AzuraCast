<template>
    <section
        class="card"
        role="region"
        aria-labelledby="hdr_edit_profile"
    >
        <div class="card-header text-bg-primary">
            <h3
                id="hdr_edit_profile"
                class="card-title"
            >
                {{ $gettext('Edit Station Profile') }}
            </h3>
        </div>

        <error-card v-if="error != null">
            <p class="card-text">
                {{ $gettext('An error occurred while loading the station profile:') }}
            </p>

            <p class="card-text">
                {{ error }}
            </p>

            <p class="card-text">
                {{ $gettext('Click the button below to retry loading the page.') }}
            </p>

            <button
                type="button"
                class="btn btn-light"
                @click="retry"
            >
                <icon :icon="IconRefresh" />
                <span>
                    {{ $gettext('Reload') }}
                </span>
            </button>
        </error-card>
        <div
            v-else
            class="card-body"
        >
            <admin-stations-form
                v-bind="props"
                ref="$form"
                is-edit-mode
                :edit-url="editUrl"
                @submitted="onSubmitted"
                @error="onError"
            />
        </div>
    </section>
</template>

<script setup lang="ts">
import AdminStationsForm, {StationFormParentProps} from "~/components/Admin/Stations/StationForm.vue";
import {nextTick, onMounted, ref, useTemplateRef} from "vue";
import Icon from "~/components/InlinePlayer.vue";
import ErrorCard from "~/components/Common/ErrorCard.vue";
import {getStationApiUrl} from "~/router";
import {useRouter} from "vue-router";
import {IconRefresh} from "~/components/Common/icons";

const props = defineProps<StationFormParentProps>();

const editUrl = getStationApiUrl('/profile/edit');

const $form = useTemplateRef('$form');

onMounted(() => {
    $form.value?.reset();
});

const error = ref<string | null>(null);

const retry = () => {
    error.value = null;

    void nextTick(() => {
        $form.value?.reset();
    });
}

const onError = (err: string | null) => {
    error.value = err;
}

const router = useRouter();

const onSubmitted = async () => {
    await router.push({
        name: 'stations:index'
    });
    
    window.location.reload();
}
</script>
