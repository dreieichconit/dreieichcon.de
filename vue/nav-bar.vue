<template>
    <nav class="navbar navbar-expand-lg nav-bar gradient-border d1">
        <div class="collapse navbar-collapse h100" id="navbarNav">
            <ul class="navbar-nav h100">
                <li
                    v-for="element in nav.collection"
                    v-bind:key="element"
                    class="nav-element"
                >
                    <div class="dropdown">
                        <div
                            class="toplevel-element"
                            v-bind:style="isPointer(element)"
                            @click="navigate(element)"
                        >
                            <div class="dropdown-title">
                                {{ element.get("title", $language) }}
                            </div>
                        </div>
                        <div class="dropdown-content d2">
                            <div
                                v-for="option in element.options"
                                :key="option"
                                v-bind:style="isPointer(option)"
                                class="dropdown-item"
                                @click="navigate(option)"
                            >
                                {{ option.get("title", $language) }}
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script>
module.exports = {
    name: "nav-bar",
    data: function () {
        return {};
    },
    created() {},
    props: ["nav"],
    mounted() {},
    methods: {
        navigate(item) {
            if (item.href != null) {
                this.emitter.emit("navigate", { href: item.href });
                return;
            }

            this.emitter.emit("navigate", { id: item.page_id });
        },
        isPointer(item) {
            if (item.href != null || item.page_id != null)
                return { cursor: "pointer" };
            return "";
        },
    },
    components: {},
};
</script>

<style>
@import "css/components/nav-bar.css";
</style>