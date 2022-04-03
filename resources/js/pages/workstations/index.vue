<template>
<div>
    <v-row>
        <v-col sm="5" md="5" lg="5" xl="5">
            <router-view />
        </v-col>
        <v-col></v-col>
        <v-col sm="6" md="6" lg="6" xl="6">
            <TerminalTable />
        </v-col>
    </v-row>
</div>
</template>
<script>
import TerminalTable from '../../components/TerminalTable'
export default {
    components: {
        TerminalTable
    }
}
</script>
<style scoped>

</style>