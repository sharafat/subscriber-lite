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

            queries.value = { ...query }

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
                const icon = field.value === 'true' ? 'fa-circle-check text-green-400' : 'fa-circle-xmark text-red-400'

                return `<i class="fa-solid ${icon}"></i>`
            }

            return field.value
        }

        return {
            tableData,
            pagination,
            queries,
            customFieldNames,
            isLoading,
            loadData,
            badgeColor,
            renderCustomField
        }
    }
}
</script>

<style scoped>
    .state::first-letter {
        text-transform: capitalize;
    }
</style>
