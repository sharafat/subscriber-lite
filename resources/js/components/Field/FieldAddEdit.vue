<template>
    <div class="max-w-md">
        <div class="shadow rounded-md overflow-hidden">
            <div class="px-4 py-5 bg-white space-y-6 sm:p-6">

                <div class="input-container">
                    <label for="title" class="input-label">
                        Title
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="title" class="input"
                           v-bind:class="{ 'input-error': errors.title }"
                           v-model="field.title"
                           required="required"/>
                    <div class="error-msg">{{ errors.title }}</div>
                </div>

                <div class="input-container">
                    <label for="type" class="input-label">
                        Type
                        <span class="text-red-500">*</span>
                    </label>
                    <select id="type" class="select"
                            v-bind:class="{ 'input-error': errors.type }"
                            v-model="field.type"
                            required="required">
                        <option value="string">String</option>
                        <option value="number">Number</option>
                        <option value="date">Date</option>
                        <option value="boolean">Boolean</option>
                    </select>
                    <div class="error-msg">{{ errors.type }}</div>
                </div>

            </div>

            <div class="px-4 py-3 bg-gray-50 sm:px-6">
                <button type="submit" class="ladda-button btn btn-primary" data-style="expand-left"
                        v-text="field.id !== null ? 'Update' : 'Create'"
                        @click="submit"/>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios"
import {onMounted, ref} from "vue"

export default {
    props: {
        fieldListPageUrl: {
            type: String,
            required: true,
        },
        fieldCreateApi: {
            type: String,
            required: true,
        },
        fieldUpdateApi: {
            type: String,
            required: true,
        },
        field: {
            type: Object,
            required: false,
            default: {
                id: null,
                title: null,
                type: 'string',
            }
        }
    },
    setup(props) {
        onMounted(() => {
            Ladda.bind(document.querySelector('.ladda-button'))
        })

        const field = ref(props.field)
        const errors = ref({
            title: null,
            type: null,
        })

        const submit = () => {
            const fieldData = field.value
            axios({
                method: fieldData.id ? 'put' : 'post',
                url: fieldData.id ? props.fieldUpdateApi : props.fieldCreateApi,
                data: fieldData
            }).then(async () => {
                await window.Swal.fire(fieldData.id ? 'Field Updated' : 'Field Created', '', 'success')
                window.location = props.fieldListPageUrl
            }).catch(error => {
                window.Ladda.stopAll()

                if (error.response) {
                    const {status, data} = error.response

                    if (status === 422) {
                        const {errors: errorData} = data
                        errors.value = {
                            title: 'title' in errorData ? errorData.title[0] : null,
                            type: 'type' in errorData ? errorData.type[0] : null,
                        }

                        window.Swal.fire('', 'Please correct the errors specified on the form.', 'error')
                    }
                }

                window.Swal.fire('Submission Failed', 'Unknown server error.', 'error')
            })
        }

        return {
            field,
            errors,
            submit,
        }
    },
}
</script>

<style scoped>

</style>
