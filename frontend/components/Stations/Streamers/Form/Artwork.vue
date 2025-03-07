<template>
    <tab :label="$gettext('Artwork')">
        <div class="row">
            <div class="col-md-8">
                <form-group id="edit_form_art">
                    <template #label>
                        {{ $gettext('Select PNG/JPG artwork file') }}
                    </template>
                    <template #description>
                        {{
                            $gettext('This image will be used as the default album art when this streamer is live.')
                        }}
                    </template>
                    <template #default="{id}">
                        <form-file
                            :id="id"
                            accept="image/jpeg, image/png"
                            @uploaded="uploadFile"
                        />
                    </template>
                </form-group>
            </div>
            <div
                v-if="src && src !== ''"
                class="col-md-4"
            >
                <img
                    :src="src"
                    :alt="$gettext('Artwork')"
                    class="rounded img-fluid"
                >

                <div class="block-buttons pt-3">
                    <button
                        type="button"
                        class="btn btn-block btn-danger"
                        @click="deleteArt"
                    >
                        {{ $gettext('Clear Artwork') }}
                    </button>
                </div>
            </div>
        </div>
    </tab>
</template>

<script setup lang="ts">
import {computed, ref, toRef} from "vue";
import {useAxios} from "~/vendor/axios";
import FormGroup from "~/components/Form/FormGroup.vue";
import FormFile from "~/components/Form/FormFile.vue";
import Tab from "~/components/Common/Tab.vue";
import {UploadResponseBody} from "~/components/Common/FlowUpload.vue";

const props = defineProps<{
    artworkSrc: string,
    editArtUrl: string,
    newArtUrl: string,
}>();

const model = defineModel<UploadResponseBody | null>();

const artworkSrc = toRef(props, 'artworkSrc');
const localSrc = ref(null);

const src = computed(() => {
    return localSrc.value ?? artworkSrc.value;
});

const {axios} = useAxios();

const uploadFile = (file: File | null) => {
    if (null === file) {
        return;
    }

    const fileReader = new FileReader();
    fileReader.addEventListener('load', () => {
        localSrc.value = fileReader.result;
    }, false);
    fileReader.readAsDataURL(file);

    const url = (props.editArtUrl) ? props.editArtUrl : props.newArtUrl;
    const formData = new FormData();
    formData.append('art', file);

    void axios.post(url, formData).then((resp) => {
        model.value = resp.data;
    });
};

const deleteArt = () => {
    if (props.editArtUrl) {
        void axios.delete(props.editArtUrl).then(() => {
            localSrc.value = null;
        });
    } else {
        localSrc.value = null;
    }
}
</script>
