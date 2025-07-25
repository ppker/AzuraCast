<template>
    <modal
        id="import_modal"
        ref="$modal"
        :title="$gettext('Import Configuration')"
        size="lg"
        @hidden="onHidden"
    >
        <form
            class="form"
            @submit.prevent="doSubmit"
        >
            <form-group id="import_modal_file">
                <template #label>
                    {{ $gettext('Select File to Import') }}
                </template>
                <template #description>
                    {{
                        $gettext('Import a file that was previously exported by this page, and AzuraCast will update any custom code sections with the custom code contained in your export file. Note that this will remove any existing code you have in these sections.')
                    }}
                </template>

                <template #default="{id}">
                    <form-file
                        :id="id"
                        @uploaded="uploaded"
                    />
                </template>
            </form-group>

            <invisible-submit-button/>
        </form>
        <template #modal-footer>
            <button
                type="button"
                class="btn btn-secondary"
                @click="hide"
            >
                {{ $gettext('Close') }}
            </button>
            <button
                class="btn btn-primary"
                type="submit"
                @click="doSubmit"
            >
                {{ $gettext('Import Configuration') }}
            </button>
        </template>
    </modal>
</template>

<script setup lang="ts">
import InvisibleSubmitButton from "~/components/Common/InvisibleSubmitButton.vue";
import {ref, useTemplateRef} from "vue";
import {useNotify} from "~/functions/useNotify";
import {useAxios} from "~/vendor/axios";
import FormGroup from "~/components/Form/FormGroup.vue";
import Modal from "~/components/Common/Modal.vue";
import FormFile from "~/components/Form/FormFile.vue";
import {useHasModal} from "~/functions/useHasModal.ts";
import {HasRelistEmit} from "~/functions/useBaseEditModal.ts";

const props = defineProps<{
    importUrl: string
}>();

const emit = defineEmits<HasRelistEmit>();

const configFile = ref(null);

const uploaded = (file) => {
    configFile.value = file;
}

const $modal = useTemplateRef('$modal');
const {show: open, hide} = useHasModal($modal);

const {notifySuccess, notifyError} = useNotify();
const {axios} = useAxios();

const doSubmit = () => {
    const formData = new FormData();
    formData.append('file', configFile.value);

    void axios.post(props.importUrl, formData).then((resp) => {
        if (resp.data.success) {
            notifySuccess(resp.data.message);
        } else {
            notifyError(resp.data.message);
        }

        hide();
    });
};

const onHidden = () => {
    emit('relist');
    configFile.value = null;
};

defineExpose({
    open
});
</script>
