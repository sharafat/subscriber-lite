<template>
    <data-table :rows="tableData"
                :pagination="pagination"
                :query="queries"
                striped
                sn
                sortable
                topPagination
                :loading="isLoading"
                @loadData="loadData">
        <template #thead="{sorting, sort}">
            <table-head sortable="state"
                        :sort="sort"
                        @sorting="sorting">
                State
            </table-head>
            <table-head sortable="id"
                        :sort="sort"
                        @sorting="sorting">
                ID
            </table-head>
            <table-head sortable="name"
                        :sort="sort"
                        @sorting="sorting">
                Name
            </table-head>
            <table-head sortable="email"
                        :sort="sort"
                        @sorting="sorting">
                Email
            </table-head>
            <table-head v-for="customField in customFieldNames">{{ customField }}</table-head>
            <table-head>
                Actions
            </table-head>
        </template>

        <template #tbody="{row}">
            <table-body>
                <div :class="`state bg-${badgeColor(row.state)}-100 text-${badgeColor(row.state)}-800
                               text-center text-xs font-semibold mr-2 px-2.5 py-0.5 rounded w-24`"
                     v-text="row.state"/>
            </table-body>
            <table-body v-text="row.id"/>
            <table-body v-text="row.name"/>
            <table-body v-text="row.email"/>
            <table-body v-for="customFieldName in customFieldNames"
                        v-html="renderCustomField(row, customFieldName)"/>
            <table-body>
                <button class="btn-icon btn-icon-primary"
                        @click="navigateToEditPage(row.id)">
                    <i class="fa-solid fa-edit"></i>
                </button>
                <button class="btn-icon btn-icon-danger ml-3"
                        @click="destroy(row.id, row.name)">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </table-body>
        </template>
    </data-table>
</template>

<script>
import axios from "axios"
import {ref} from "vue"
import {DataTable, TableBody, TableHead} from "@jobinsjp/vue3-datatable"

export default {
    components: {
        DataTable,
        TableBody,
        TableHead
    },
    props: {
        subscriberListApi: {
            type: String,
            required: true,
        },
        subscriberDeleteApi: {
            type: String,
            required: true,
        },
        subscriberEditPageUrl: {
            type: String,
            required: true,
        },
    },
    setup(props) {
        const tableData = ref([])
        const pagination = ref({})
        const isLoading = ref(false)
        const queries = ref({
            sort: "id:desc",
        })
        const customFieldNames = ref([])

        const loadData = async (query) => {
            isLoading.value = true

            queries.value = {...query}

            const {data: {data, total}} = await axios.get(props.subscriberListApi, {
                params: {
                    page: query.page,
                    size: query.per_page,
                    sort: query.sort.replace(':', ' '),
                },
            })

            tableData.value = data
            pagination.value = {...pagination.value, page: query.page, total}

            customFieldNames.value = Array.from(new Set(
                data.map(subscriber => subscriber.fields.map(field => field.title)).flat())
            ).sort()

            isLoading.value = false
        }

        const badgeColor = (state) => {
            switch (state) {
                case 'active':
                    return 'green';
                case 'unsubscribed':
                    return 'red';
                case 'junk':
                    return 'purple';
                case 'bounced':
                    return 'yellow';
                case 'unconfirmed':
                    return 'gray';
                default:
                    return 'pink';
            }
        }

        const renderCustomField = (row, fieldTitle) => {
            const field = row.fields.filter(field => field.title === fieldTitle)[0] || null
            if (!field) {
                return ''
            }

            if (field.type === 'boolean') {
                const icon = [true, 'true', 1, '1'].includes(field.value)
                    ? 'fa-circle-check text-green-400'
                    : 'fa-circle-xmark text-red-400'

                return `<i class="fa-solid fa-xl ${icon}"></i>`
            }

            return field.value
        }

        const navigateToEditPage = id => {
            window.location = props.subscriberEditPageUrl.replace('0', id)
        }

        const destroy = (id, name) => {
            Swal.fire(
                {
                    title: "Delete subscriber?",
                    text: `Are you sure you want to delete the subscriber "${name}"? This action cannot be undone.`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#f2353c",
                    confirmButtonText: "Delete",
                    showLoaderOnConfirm: true,
                    backdrop: true,
                    allowOutsideClick: () => !Swal.isLoading(),
                    preConfirm: () =>
                        axios.delete(props.subscriberDeleteApi.replace('0', id))
                            .catch(() => window.Swal.fire('Submission Failed', 'Unknown server error.', 'error'))
                }
            ).then(async result => {
                if (result.value) {
                    await Swal.fire('Deleted!', '', 'success');
                    window.location.reload()
                }
            });
        }

        return {
            tableData,
            pagination,
            queries,
            customFieldNames,
            isLoading,
            loadData,
            badgeColor,
            renderCustomField,
            navigateToEditPage,
            destroy,
        }
    }
}
</script>

<style scoped>
.state::first-letter {
    text-transform: capitalize;
}
</style>
