<template>
    <div class="row">
        <form-group-multi-check
            id="edit_form_playlists"
            class="col-md-12"
            :field="form.playlists"
            :options="options"
            stacked
            :label="$gettext('Playlists')"
        />
    </div>
</template>

<script setup lang="ts">
import {map} from "lodash";
import {computed} from "vue";
import FormGroupMultiCheck from "~/components/Form/FormGroupMultiCheck.vue";
import {VuelidateObject} from "~/functions/useVuelidateOnForm.ts";

const props = defineProps<{
    form: VuelidateObject,
    playlists: Array<any>, // TODO
}>();

const options = computed(() => {
    return map(props.playlists, function (row) {
        return {
            text: row.name,
            value: row.id
        };
    });
});
</script>
