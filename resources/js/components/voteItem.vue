<template>
    <div class="v--vote-item">
        <div class="v--vote-item-title"> {{ title }} </div>
        <div v-if="voted" class="v--vote-item-countOfTotalVote">
            {{ count }} رای
        </div>
        <div class="v--vote-item-choices">
            <vote-item-choice v-for="choice in choices" :data="choice" :key="choice.id" :voted="voted"></vote-item-choice>
        </div>
    </div>
</template>

<script>
    export default {
        name: "voteItem",
        props: ["data"],
        data() {
            return {}
        },
        computed: {
            choices() {
                return this.data.options;
            },
            title() {
                return this.data.title();
            },
            count(){
                return this.data.count();
            },
            voted(){
                return this.data.hasUserVoted;
            }
        },
        methods: {}
    }
</script>

<style scoped>

</style>
