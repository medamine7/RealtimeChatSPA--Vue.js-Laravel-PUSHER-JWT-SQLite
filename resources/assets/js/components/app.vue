<template>
	<transition
	    name="custom-classes-transition"
	    enter-active-class="animated fadeIn"
	    leave-active-class="animated fadeOut"
	  >
    	<router-view/>
    </transition>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
