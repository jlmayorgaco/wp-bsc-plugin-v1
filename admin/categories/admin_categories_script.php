<?php include_once plugin_dir_path(__FILE__) . 'script_helpers.php'; ?>
<script>
    const {
        ref,
        createApp,
        computed,
        onMounted
    } = Vue
    const app = createApp({
        setup() {

            const UI = ref({
                TABS: CATEGORIES_TABS,
                SIDEBARS: CATEGORIES_SIDEBARS,
                COLUMNS: CATEGORIES_COLUMNS
            })

            const DATA = ref(
                Object.values(CATEGORIES_SLUG)
                .map(slug => ({
                    columnSlug: slug,
                    columnChilds: []
                }))
                .reduce((prev, curr) => {
                    return {
                        ...prev,
                        [curr.columnSlug]: curr.columnChilds
                    };
                }, {})
            );

            setTab = (tabCode) => {
                const _UI = UI.value;
                _UI.TABS.forEach(tab => {
                    tab.isActive = (tab.slug === tabCode)
                })
                UI.value = _UI;
                setCategorySelected(null);
            }

            doGetCategoriesAPI = (page) => {
                return new Promise((resolve, reject) => {

                    const url = 'http://bsc2.local/wp-json/wc/v3/products/categories?per_page=100&page=' + page;
                    const consumerKey = 'ck_fa6e5ec7bd507dc45d2fbaadf40e767ecebe35b1';
                    const consumerSecret = 'cs_99498b696c38786b9988da2fd3103c7d6c735f2e';

                    // Initialize
                    const oauth = OAuth({
                        consumer: {
                            key: consumerKey,
                            secret: consumerSecret,
                        },
                        signature_method: 'HMAC-SHA1',
                        hash_function(base_string, key) {
                            return CryptoJS.HmacSHA1(base_string, key).toString(CryptoJS.enc.Base64)
                        },
                    })

                    const request_data = {
                        url: url,
                        method: 'GET',
                    }


                    fetch(request_data.url, {
                            method: request_data.method,
                            headers: {
                                'Content-Type': 'application/json',
                                'Access-Control-Expose-Headers': 'X-WP-Total',
                                ...oauth.toHeader(oauth.authorize(request_data)),
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }

                            const pages = response.headers.get('X-WP-TotalPages');
                            const total = response.headers.get('X-WP-Total');

                            const data = response.json().then(_data => {
                                resolve({
                                    total: total,
                                    pages: pages,
                                    data: _data
                                })
                            })
                        })
                })
            }

            doLoopCategoriesAPI = async () => {
                let page = 1;
                let responses = [];
                while (true) {
                    const response = await this.doGetCategoriesAPI(page);
                    responses = [...responses, ...response.data];

                    if (page <= response.pages) {
                        page = page + 1;
                    } else {
                        break;
                    }
                }
                return responses;
            }



            setCategorySelected = (category) => {

                if (!category) {
                    const _UI = UI.value;
                    _UI.SIDEBARS.SIDEBAR_EDIT.isVisible = false;
                    _UI.SIDEBARS.SIDEBAR_ADD.isVisible = false;
                    _UI.SIDEBARS.SIDEBAR_EDIT.payload = {};
                    UI.value = _UI;
                    return false;
                }

                const _UI = UI.value;
                if (_UI.SIDEBARS.SIDEBAR_EDIT.payload.slug === category.slug) {
                    _UI.SIDEBARS.SIDEBAR_EDIT.isVisible = false;
                    _UI.SIDEBARS.SIDEBAR_ADD.isVisible = false;
                    _UI.SIDEBARS.SIDEBAR_EDIT.payload = {};
                } else {
                    _UI.SIDEBARS.SIDEBAR_EDIT.isVisible = true;
                    _UI.SIDEBARS.SIDEBAR_ADD.isVisible = false;
                    _UI.SIDEBARS.SIDEBAR_EDIT.payload.id = category.id;
                    _UI.SIDEBARS.SIDEBAR_EDIT.payload.slug = category.slug;
                    _UI.SIDEBARS.SIDEBAR_EDIT.payload.label = category.name;
                    _UI.SIDEBARS.SIDEBAR_EDIT.payload.parent_id = category.parent;
                    _UI.SIDEBARS.SIDEBAR_EDIT.payload.description = category.description;
                    _UI.SIDEBARS.SIDEBAR_EDIT.payload.bsc__text_como_usar = category.description;
                    _UI.SIDEBARS.SIDEBAR_EDIT.payload.bsc__text_paso_rutina = category.description;
                    _UI.SIDEBARS.SIDEBAR_EDIT.payload.bsc__text_tipo_piel = category.description;
                }
                UI.value = _UI;
            }

            isTipoPiel = (slug) => {
                return slug.startsWith('sk-tipo-piel-');
            }
            isRutina = (slug) => {
                return slug.startsWith('sk-rutina-') || slug.startsWith('hc-rutina-');
            }

            saveCategorySelected = (id) => {
                return new Promise((resolve, reject) => {
                    const url = 'http://bsc2.local/wp-json/wc/v3/products/categories/'+id;
                    const consumerKey = 'ck_fa6e5ec7bd507dc45d2fbaadf40e767ecebe35b1';
                    const consumerSecret = 'cs_99498b696c38786b9988da2fd3103c7d6c735f2e';
                    // Initialize
                    const oauth = OAuth({
                        consumer: {
                            key: consumerKey,
                            secret: consumerSecret,
                        },
                        signature_method: 'HMAC-SHA1',
                        hash_function(base_string, key) {
                            return CryptoJS.HmacSHA1(base_string, key).toString(CryptoJS.enc.Base64)
                        },
                    })

                    const body = { 
                        ...UI.value.SIDEBARS.SIDEBAR_EDIT.payload , 
                        name: UI.value.SIDEBARS.SIDEBAR_EDIT.payload.name,
                        description: JSON.stringify({
                            bsc__text_como_usar: UI.value.SIDEBARS.SIDEBAR_EDIT.payload.bsc__text_como_usar,
                            bsc__text_paso_rutina: UI.value.SIDEBARS.SIDEBAR_EDIT.payload.bsc__text_paso_rutina,
                            bsc__text_tipo_piel: UI.value.SIDEBARS.SIDEBAR_EDIT.payload.bsc__text_tipo_piel,
                        })
                    };
                    console.log({ body })
                    const request_data = {
                        url: url,
                        body: JSON.stringify(body),
                        method: 'PATCH',
                    }
                    fetch(request_data.url, {
                            method: request_data.method,
                            body: request_data.body,
                            headers: {
                                'Content-Type': 'application/json',
                                ...oauth.toHeader(oauth.authorize(request_data)),
                            }
                        })
                        .then(response => {
                            return response.json()
                        }).then( response => {
                            console.log('')
                            console.log(' ::::::saveCategorySelected () => ')
                            console.log(response)
                            console.log('')
                            console.log('')
                        })
                    })


            }
            removeCategorySelected = () => {
                if (window.confirm('SEGURO DESEA BORRAR ESTA CATEGORIA')) {
                    console.log(' DELETEED ')

                    onStart()
                    setCategorySelected(null)

                }
            }

            onClickAddCategory = (groupSlug, columnSlug) => {

                category_add.value = {
                    'sk-tipo-piel': defaultCategoryAdd('sk-tipo-piel'),
                    'sk-necesidades': defaultCategoryAdd('sk-necesidades'),
                    'sk-rutina': defaultCategoryAdd('sk-rutina'),
                    'sk-ingredientes': defaultCategoryAdd('sk-ingredientes'),
                    'sk-marcas': defaultCategoryAdd('sk-marcas'),
                    'hc-necesidades': defaultCategoryAdd('hc-necesidades'),
                    'hc-rutina': defaultCategoryAdd('hc-rutina'),
                    'hc-marca': defaultCategoryAdd('hc-marca'),
                    'mk-productos': defaultCategoryAdd('mk-productos'),
                    'mk-marcas': defaultCategoryAdd('mk-marcas'),
                }
                category_selected.value = null;
                category_add_active.value = columnSlug;
            }

            onStart = async () => {
                /*
                const LIST = (await doLoopCategoriesAPI())
                const categoriesList = (await doLoopCategoriesAPI())
                    .map(item => ({
                        ...item,
                        parent_slug: LIST.find(parent => parent.id === item.parent)?.slug
                    }))
                    .filter(item =>
                        CATEGORIES_SLUG.includes(item.parent_slug)
                    );
                const _DATA = DATA.value;
                Object.keys(_DATA).forEach(key => {
                    _DATA[key] = categoriesList.filter(item => item.parent_slug === key)
                });
                DATA.value = _DATA;
                */
            }

            onMounted(async () => {
                console.log(`the component is now mounted.`)
                onStart()
            })
            return {
                UI,
                DATA,
                setTab,
                isRutina,
                saveCategorySelected,
                removeCategorySelected,
                isTipoPiel,
                onClickAddCategory,
                setCategorySelected,
            }
        }
    });
    <?php include_once plugin_dir_path(__FILE__) . 'script_components.php'; ?>
    app.mount('#v-app-categories-crud')
</script>