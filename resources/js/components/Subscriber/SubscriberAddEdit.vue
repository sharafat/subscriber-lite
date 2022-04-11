<template>
    <div class="max-w-md">
        <div class="shadow rounded-md overflow-hidden">
            <div class="px-4 py-5 bg-white space-y-6 sm:p-6">

                <div class="input-container">
                    <label for="name" class="input-label">
                        Name
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="name" class="input"
                           v-bind:class="{ 'input-error': errors.name }"
                           v-model="subscriber.name"
                           required="required"/>
                    <div class="error-msg">{{ errors.name }}</div>
                </div>

                <div class="input-container">
                    <label for="email" class="input-label">
                        Email
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="email" class="input"
                           v-bind:class="{ 'input-error': errors.email }"
                           v-model="subscriber.email"
                           required="required"/>
                    <div class="error-msg">{{ errors.email }}</div>
                </div>

                <div class="input-container" v-if="subscriber.state">
                    <label for="state" class="input-label">
                        State
                        <span class="text-red-500">*</span>
                    </label>
                    <select id="state" class="select"
                            v-bind:class="{ 'input-error': errors.state }"
                            v-model="subscriber.state">
                        <option value="active">Active</option>
                        <option value="unsubscribed">Unsubscribed</option>
                        <option value="junk">Junk</option>
                        <option value="bounced">Bounced</option>
                        <option value="unconfirmed">Unconfirmed</option>
                    </select>
                    <div class="error-msg">{{ errors.state }}</div>
                </div>

                <div v-for="(field, index) in subscriber.fields"
                     class="input-container">
                    <label :for="`field-${index}`" class="input-label">
                        <input v-if="subscriber.fields[index].type === 'boolean'"
                               :id="`field-${index}`" class="mr-2"
                               type="checkbox"
                               v-bind:class="{ 'input-error': errors.fields[index] ?? false }"
                               v-model="subscriber.fields[index].value"
                               true-value="1"
                               false-value="0"/>
                        {{ field.title }}
                    </label>
                    <input v-if="subscriber.fields[index].type !== 'boolean'"
                           :id="`field-${index}`" class="input"
                           :type="getInputType(subscriber.fields[index].type)"
                           v-bind:class="{ 'input-error': errors.fields[index] ?? false }"
                           v-model="subscriber.fields[index].value"/>
                    <div class="error-msg">{{ errors.fields[index] ?? '' }}</div>
                </div>

            </div>

            <div class="px-4 py-3 bg-gray-50 sm:px-6">
                <button type="submit" class="ladda-button btn btn-primary" data-style="expand-left"
                        v-text="subscriber.id !== null ? 'Update' : 'Add'"
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
        subscriberListPageUrl: {
            type: String,
            required: true,
        },
        subscriberCreateApi: {
            type: String,
            required: true,
        },
        subscriberUpdateApi: {
            type: String,
            required: true,
        },
        customFields: {
            type: Array,
            required: true,
        },
        subscriber: {
            type: Object,
            required: false,
            default: {
                id: null,
                name: null,
                email: null,
                state: null,
                fields: []
            }
        }
    },
    setup(props) {
        onMounted(() => {
            Ladda.bind(document.querySelector('.ladda-button'))
        })

        const subscriber = ref(props.subscriber)
        const errors = ref({
            name: null,
            email: null,
            state: null,
            fields: Array(subscriber.value.fields.length).fill(null)
        })

        // Populate subscriber fields with default values for missing custom fields
        const subscriberFields = subscriber.value.fields
        subscriber.value.fields = subscriberFields.concat(
            props.customFields.filter(
                customField => !subscriberFields.filter(subscriberField => subscriberField.id === customField.id).length
            )
        )

        const submit = () => {
            // Clear errors
            errors.value = {
                name: null,
                email: null,
                state: null,
                fields: errors.value.fields.fill(null)
            }

            const subscriberData = subscriber.value
            axios({
                method: subscriberData.id ? 'put' : 'post',
                url: subscriberData.id ? props.subscriberUpdateApi : props.subscriberCreateApi,
                data: subscriberData
            }).then(async () => {
                await window.Swal.fire(subscriberData.id ? 'Subscriber Updated' : 'Subscriber Added', '', 'success')
                window.location = props.subscriberListPageUrl
            }).catch(error => {
                window.Ladda.stopAll()

                if (error.response) {
                    const {status, data} = error.response

                    if (status === 422) {
                        processValidationErrors(data.errors);
                        window.Swal.fire('', 'Please correct the errors specified on the form.', 'error')

                        return
                    }
                }

                window.Swal.fire('Submission Failed', 'Unknown server error.', 'error')
            })
        }

        const processValidationErrors = errorData => {
            const errorMessages = Object.keys(errorData)
                .filter(key => key.includes('fields'))
                .map(key => key.split('.')[1])
                .map(index => {
                    return {
                        index,
                        msg: errorData[`fields.${index}.value`][0]
                    }
                });

            errors.value = {
                ...errors.value,
                name: 'name' in errorData ? errorData.name[0] : null,
                email: 'email' in errorData ? errorData.email[0] : null,
                state: 'state' in errorData ? errorData.state[0] : null,
            }

            for (let i = 0; i < errorMessages.length; i++) {
                errors.value.fields[errorMessages[i].index] = errorMessages[i].msg
            }
        }

        const getInputType = fieldType => {
            switch (fieldType) {
                case 'number':
                    return 'number';
                case 'date':
                    return 'date';
                case 'boolean':
                    return 'checkbox';
                default:
                    return 'text';
            }
        }

        return {
            subscriber,
            errors,
            submit,
            getInputType,
        }
    },
}
</script>

<style scoped>

</style>
