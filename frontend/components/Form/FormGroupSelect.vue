<template>
    <form-group
        v-bind="$attrs"
        :id="id"
    >
        <template
            v-if="label || slots.label"
            #label="slotProps"
        >
            <form-label
                :is-required="isRequired"
                :advanced="props.advanced"
                :high-cpu="props.highCpu"
            >
                <slot
                    name="label"
                    v-bind="slotProps"
                >
                    {{ label }}
                </slot>
            </form-label>
        </template>

        <template #default>
            <slot
                name="default"
                v-bind="{ id, field, model, options, multiple, class: fieldClass }"
            >
                <form-multi-select
                    v-if="multiple"
                    :id="id"
                    v-model="model"
                    :class="fieldClass"
                    :options="options"
                />
                <form-select
                    v-else
                    :id="id"
                    v-model="model"
                    :class="fieldClass"
                    :options="options"
                />
            </slot>

            <vuelidate-error
                v-if="isVuelidateField"
                :field="field"
            />
        </template>

        <template
            v-if="description || slots.description"
            #description="slotProps"
        >
            <slot
                v-bind="slotProps"
                name="description"
            >
                {{ description }}
            </slot>
        </template>
    </form-group>
</template>

<script setup lang="ts" generic="T = ModelFormField">
import VuelidateError from "~/components/Form/VuelidateError.vue";
import FormLabel, {FormLabelParentProps} from "~/components/Form/FormLabel.vue";
import FormGroup from "~/components/Form/FormGroup.vue";
import {FormFieldEmits, FormFieldProps, ModelFormField, useFormField} from "~/components/Form/useFormField";
import {useSlots} from "vue";
import {NestedFormOptionInput} from "~/functions/objectToFormOptions.ts";
import FormSelect from "~/components/Form/FormSelect.vue";
import FormMultiSelect from "~/components/Form/FormMultiSelect.vue";

interface FormGroupSelectProps extends FormFieldProps<T>, FormLabelParentProps {
    id: string,
    name?: string,
    label?: string,
    description?: string,
    options: NestedFormOptionInput,
    multiple?: boolean,
}

const props = withDefaults(
    defineProps<FormGroupSelectProps>(),
    {
        name: null,
        label: null,
        description: null,
        multiple: false
    }
);

const slots = useSlots();

const emit = defineEmits<FormFieldEmits<T>>();

const {model, isVuelidateField, fieldClass, isRequired} = useFormField<T>(props, emit);
</script>
