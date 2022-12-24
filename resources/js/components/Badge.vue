<template>
    <q-badge
        v-if="changeColumn?.includes(keyColumn)"
        align="middle"
        color='red' style="cursor: pointer;">
        {{ newValue ?? 'Chưa cập nhật' }}
        <q-tooltip  class="bg-red text-body2 " :offset="[10, 10]">
            {{ "Dữ liệu cũ: "+ (oldValue || 'Chưa cập nhật') }}
        </q-tooltip>
    </q-badge>
<span v-else>
    {{ newValue ?? 'Chưa cập nhật' }}
</span>

</template>

<script>

export default ({
    name: "Badge",
    props: {
        changeColumn: {
            type: Array,
        },
        keyColumn: {
            type: String,
        },
        newValue: {
            type: String,
        },
        oldValue: {
            type: String,
        }
    },
    setup(props) {
        console.log(props);
        return {
            ...props
            // change_column, key_column, new_value, old_value
        }
    },
})
</script>
