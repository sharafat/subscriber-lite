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
            <table-head sortable="id"
                        :sort="sort"
                        @sorting="sorting">
                ID
            </table-head>
            <table-head sortable="title"
                        :sort="sort"
                        @sorting="sorting">
                Title
            </table-head>
            <table-head sortable="type"
                        :sort="sort"
                        @sorting="sorting">
                Type
            </table-head>
            <table-head>
                Actions
            </table-head>
        </template>

        <template #tbody="{row}">
            <table-body v-text="row.id"/>
            <table-body v-text="row.title"/>
            <table-body v-text="row.type" class="type"/>
            <table-body>
                <button class="btn-icon btn-icon-primary"
                        @click="navigateToEditPage(row.id)">
                    <i class="fa-solid fa-edit"></i>
                </button>
                <button class="btn-icon btn-icon-danger ml-3"
                        @click="destroy(row.id, row.title)">
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
        fieldListApi: {
            type: String,
            required: true,
        },
        fieldDeleteApi: {
            type: String,
            required: true,
        },
        fieldEditPageUrl: {
            type: String,
            required: true,
        },
    },
    setup(props) {
        const tableData = ref([])
        const pagination = ref({})
        const isLoading = ref(false)
        const queries = ref({
            sort: "id:asc",
        })

        const loadData = async (query) => {
            isLoading.value = true

            queries.value = {...query}

            const {data: {data, total}} = await axios.get(props.fieldListApi, {
                params: {
                    page: query.page,
                    size: query.per_page,
                    sort: query.sort.replace(':', ' '),
                },
            })

            tableData.value = data
            pagination.value = {...pagination.value, page: query.page, total}

            isLoading.value = false
        }

        const navigateToEditPage = id => {
            window.location = props.fieldEditPageUrl.replace('0', id)
        }

        const destroy = (id, title) => {
            Swal.fire(
                {
                    title: "Delete field?",
                    text: `Are you sure you want to delete the field "${title}"? This action cannot be undone.`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#f2353c",
                    confirmButtonText: "Delete",
                    showLoaderOnConfirm: true,
                    backdrop: true,
                    allowOutsideClick: () => !Swal.isLoading(),
                    preConfirm: () =>
                        axios.delete(props.fieldDeleteApi.replace('0', id))
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
            isLoading,
            loadData,
            navigateToEditPage,
            destroy,
        }
    }
}
</script>

<style scoped>
.type::first-letter {
    text-transform: capitalize;
}
</style>
