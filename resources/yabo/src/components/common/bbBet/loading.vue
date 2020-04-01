<template>
    <div class="loading" :class="{loadingActive:!loaded}">
        <i class="fas fa-spinner fa-pulse"></i>
    </div>
</template>

<script>
export default {
    props: {
        loaded: {
            type: Boolean,
            default: false,

        },
    }
}
</script>
