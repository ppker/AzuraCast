<template>
    <setup-step :step="2" />

    <section
        class="card"
        role="region"
        aria-labelledby="hdr_new_station"
    >
        <div class="card-header text-bg-primary">
            <h3
                id="hdr_new_station"
                class="card-title"
            >
                {{ $gettext('Create a New Radio Station') }}
            </h3>
        </div>

        <info-card>
            {{
                $gettext('Continue the setup process by creating your first radio station below. You can edit any of these details later.')
            }}
        </info-card>

        <div class="card-body">
            <admin-stations-form
                v-bind="props"
                ref="$adminForm"
                :is-edit-mode="false"
                :create-url="createUrl"
                @submitted="onSubmitted"
            >
                <template #submitButtonText>
                    {{ $gettext('Create and Continue') }}
                </template>
            </admin-stations-form>
        </div>
    </section>
</template>

<script setup lang="ts">
import AdminStationsForm, {StationFormParentProps} from "~/components/Admin/Stations/StationForm.vue";
import SetupStep from "~/components/Setup/SetupStep.vue";
import InfoCard from "~/components/Common/InfoCard.vue";
import {onMounted, useTemplateRef} from "vue";

interface SetupStationProps extends StationFormParentProps {
    createUrl: string,
    continueUrl: string,
}

const props = defineProps<SetupStationProps>();

const $adminForm = useTemplateRef('$adminForm');

onMounted(() => {
    $adminForm.value?.reset();
});

const onSubmitted = () => {
    window.location.href = props.continueUrl;
}
</script>
