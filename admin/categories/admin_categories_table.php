<?php

?>

<script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/hmac-sha1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>
<script src="https://cdn.jsdelivr.net/npm/oauth-1.0a@2.2.6/oauth-1.0a.js"></script>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

<article class="categories__crud">
    <header>
        <h1> CATEGORIAS CRUD </h1>
        <hr>
    </header>
    <content id="v-app-categories-crud">
        <div class="bsc__tabs">
            <div class="tabs__headers nav-tab-wrapper woo-nav-tab-wrapper">
                <div v-for="kTab of UI.TABS" :key="kTab" class="tabs_header nav-tab" :class="{ 'nav-tab-active' : kTab.isActive }" @click="setTab(kTab.slug)">
                    {{ kTab.slug }}
                </div>
            </div>

            <div class="tabs__contents" v-if=" UI.TABS">
                <template v-for="kTab of UI.TABS" :key="kTab">
                    <div class="tabs__content" v-if="kTab.isActive">
                        <ul class="content__column" v-for="kCol of UI.COLUMNS[kTab.slug]">
                            <template v-if="kCol">
                                <li class="column_header">
                                    {{ kCol }}
                                </li>
                                <li v-for="row of DATA[kCol]" :key="row" 
                                    class="content__row" :class="{'is_selected' : UI.SIDEBARS.SIDEBAR_EDIT.payload.slug === row.slug }" 
                                    @click="setCategorySelected(row)">
                                    {{ row.slug }}
                                </li>
                                <!--
                                      <li class="column_add">
                                    <input type="text" placeholder="" v-model="category_add[column].slug" @click="onClickAddCategory('group-skin-care', column)">
                                </li>-->
                            </template>
                        </ul>
                    </div>
                </template>
                <div class="tabs__content__sidebar">
                    <?php
                        // include_once plugin_dir_path(__FILE__) . 'form_sidebar_add.php';
                        // include_once plugin_dir_path(__FILE__) . 'form_sidebar_edit.php';
                    ?>
            </div>
            </div>

        </div>


        <div v-if="false" class="tabs__contents" v-if="tabs && categories && categories['group-skin-care']">
            <div class="tabs__content" v-if="tabs['sk']">
                <ul class="content__column" v-for="column of Object.keys(categories['group-skin-care'])" :key="column">
                    <li class="column_header">
                        {{ column }}
                    </li>
                    <li class="column_add">
                        <input type="text" placeholder="" v-model="category_add[column].slug" @click="onClickAddCategory('group-skin-care', column)">
                    </li>
                    <li class="content__row" :class="{'is_selected' : category_selected && category_selected.slug === row.slug }" v-for="row of categories['group-skin-care'][column]" :key="row" @click="setCategorySelected(row)">
                        {{ row.slug }}
                    </li>
                </ul>
            </div>
            <div class="tabs__content" v-if="tabs['hc']">
                <ul class="content__column" v-for="column of Object.keys(categories['group-hair-care'])" :key="column">
                    <li class="column_header">
                        {{ column }}
                    </li>
                    <li class="column_add">
                        <input type="text" placeholder="" v-model="category_add[column].slug" v-on:keyup.enter="onEnterAddCategory('group-hair-care', column)" @click="onClickAddCategory('group-hair-care', column)">
                    </li>
                    <li class="content__row" :class="{'is_selected' : category_selected && category_selected.slug === row.slug }" v-for="row of categories['group-hair-care'][column]" :key="row" @click="setCategorySelected(row)">
                        <span></span> <span></span> {{ row.slug }}
                    </li>
                </ul>
            </div>
            <div class="tabs__content" v-if="tabs['mk']">
                <ul class="content__column" v-for="column of Object.keys(categories['group-make-up'])" :key="column">
                    <li class="column_header">
                        {{ column }}
                    </li>
                    <li class="column_add">
                        <input type="text" placeholder="" @click="onClickAddCategory('group-make-up', column)">
                    </li>
                    <li class="content__row" :class="{'is_selected' : category_selected && category_selected.slug === row.slug }" v-for="row of categories['group-make-up'][column]" :key="row" @click="setCategorySelected(row)">
                        {{ row.slug }}
                    </li>
                </ul>
            </div>
            <div class="tabs__content__sidebar">
                <?php
                include_once plugin_dir_path(__FILE__) . 'form_sidebar_add.php';
                include_once plugin_dir_path(__FILE__) . 'form_sidebar_edit.php';
                ?>
            </div>
        </div>
        </div>
    </content>
</article>

<?php

include_once plugin_dir_path(__FILE__) . 'admin_categories_script.php';


?>