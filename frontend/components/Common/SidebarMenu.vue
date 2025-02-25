<template>
    <ul class="navdrawer-nav">
        <li
            v-for="category in menu.categories"
            :key="category.key"
            class="nav-item"
        >
            <router-link
                v-if="isRouteLink(category.url)"
                :class="getLinkClass(category)"
                :to="category.url"
                class="nav-link"
            >
                <icon
                    class="navdrawer-nav-icon"
                    :icon="category.icon"
                />
                <span class="might-overflow">{{ category.label }}</span>
            </router-link>
            <a
                v-else
                v-bind="getCategoryLink(category)"
                class="nav-link"
                :class="getLinkClass(category)"
            >
                <icon
                    class="navdrawer-nav-icon"
                    :icon="category.icon"
                />
                <span class="might-overflow">{{ category.label }}</span>
                <icon
                    v-if="category.external"
                    class="sm ms-2"
                    :icon="IconOpenInNew"
                    :aria-label="$gettext('External')"
                />
            </a>

            <div
                v-if="category.items"
                :id="'sidebar-submenu-'+category.key"
                class="collapse pb-2"
                :class="(isActiveItem(category)) ? 'show' : ''"
            >
                <ul class="navdrawer-nav">
                    <li
                        v-for="item in category.items"
                        :key="item.key"
                        class="nav-item"
                    >
                        <router-link
                            v-if="isRouteLink(item.url)"
                            :to="item.url"
                            class="nav-link ps-4 py-2"
                            :class="getLinkClass(item)"
                            :title="item.title"
                        >
                            <span class="might-overflow">{{ item.label }}</span>
                        </router-link>
                        <a
                            v-else
                            class="nav-link ps-4 py-2"
                            :class="item.class"
                            :href="item.url"
                            :target="(item.external) ? '_blank' : ''"
                            :title="item.title"
                        >
                            <span class="might-overflow">{{ item.label }}</span>
                            <icon
                                v-if="item.external"
                                class="sm ms-2"
                                :icon="IconOpenInNew"
                                :aria-label="$gettext('External')"
                            />
                        </a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</template>

<script setup lang="ts">
import Icon from "~/components/Common/Icon.vue";
import {useRoute} from "vue-router";
import {some} from "lodash";
import {IconOpenInNew} from "~/components/Common/icons.ts";
import {MenuCategory, MenuRouteBasedUrl, MenuRouteUrl, MenuSubCategory, ReactiveMenu} from "~/functions/filterMenu.ts";

defineProps<{
    menu: ReactiveMenu
}>();

const currentRoute = useRoute();

const isRouteLink = (url: MenuRouteUrl): url is MenuRouteBasedUrl => {
    return (typeof (url) !== 'undefined')
        && (typeof (url) !== 'string');
};

const isCategory = (item: MenuCategory | MenuSubCategory): item is MenuCategory => {
    return 'items' in item;
}

const isActiveItem = (item: MenuCategory | MenuSubCategory) => {
    if (isCategory(item) && some(item.items ?? [], isActiveItem)) {
        return true;
    }

    return isRouteLink(item.url) && !('params' in item.url) && item.url.name === currentRoute.name;
};

const getLinkClass = (item: MenuSubCategory) => {
    return [
        item.class ?? null,
        isActiveItem(item) ? 'active' : ''
    ];
}

const getCategoryLink = (item: MenuSubCategory) => {
    const linkAttrs: {
        [key: string]: any
    } = {};

    if ('items' in item) {
        linkAttrs['data-bs-toggle'] = 'collapse';
        linkAttrs.href = '#sidebar-submenu-' + item.key;
    } else {
        linkAttrs.href = item.url;
    }

    if (item.external) {
        linkAttrs.target = '_blank';
    }
    if (item.title) {
        linkAttrs.title = item.title;
    }

    return linkAttrs;
}
</script>

<style lang="scss">
@import "~/scss/_mixins.scss";

.might-overflow {
    @include might-overflow();
}
</style>
